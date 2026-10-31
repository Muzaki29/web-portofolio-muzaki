#!/usr/bin/env bash
set -euo pipefail

archive=${1:?Release archive is required}
revision=${2:?Release revision is required}
[[ "$revision" =~ ^[0-9a-f]{40}$ ]] || { echo 'Invalid release revision' >&2; exit 2; }
test -f "$archive"
archive=$(readlink -f "$archive")
entries=$(tar -tf "$archive")
if grep -E '(^/|(^|/)\.\.(/|$)|^\.env($|/))' <<< "$entries" >/dev/null; then
    echo 'Unsafe release archive path' >&2
    exit 2
fi

app=/var/www/web-portofolio-muzaki
test "$(readlink -f "$app")" = "$app"
grep -Fq "$app/public" /etc/nginx/sites-enabled/muzakiabdullahirsyad.my.id
test -f "$app/.env"
for command in php composer node npm curl tar flock; do command -v "$command" >/dev/null; done
node -e 'const [major,minor]=process.versions.node.split(".").map(Number); if (!(major===20&&minor>=19 || major===22&&minor>=12 || major>22)) process.exit(1)'

umask 077
state="$HOME/.local/state/muzaki-portfolio"
mkdir -p "$state/releases"
exec 9>"$state/deploy.lock"
flock -n 9 || { echo 'Another portfolio deployment is running' >&2; exit 3; }
backup="$state/releases/$(date -u +%Y%m%dT%H%M%SZ)-$revision"
mkdir "$backup"
runtime_owner=$(stat -c '%u:%g' "$app/storage")
cd "$app"
tar --exclude=./.git --exclude=./storage --exclude=./node_modules --exclude=./.env -czf "$backup/release.tar.gz" .
tar -tzf "$backup/release.tar.gz" >/dev/null

reload_php() {
    if command -v systemctl >/dev/null && systemctl is-active --quiet php8.3-fpm; then
        systemctl reload php8.3-fpm
    fi
}

rollback() {
    result=$?
    trap - EXIT
    set +e
    if [ "$result" -ne 0 ]; then
        echo 'Deployment failed; restoring previous release' >&2
        if [ -f "$backup/maintenance.php" ]; then
            cp "$backup/maintenance.php" storage/framework/maintenance.php
        fi
        if tar -xzf "$backup/release.tar.gz" -C "$app" && php artisan view:clear; then
            php artisan up
            reload_php
            echo 'Previous release restored' >&2
        else
            echo 'Rollback requires operator review; retain maintenance mode' >&2
        fi
    fi
    exit "$result"
}
trap rollback EXIT
php artisan down --render=errors::503 --retry=60
cp storage/framework/maintenance.php "$backup/maintenance.php"
tar -xf "$archive" -C "$app"
COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
composer check-platform-reqs --no-dev
npm ci --include=dev
npm run build
test -s public/build/manifest.json
test ! -e public/hot
php artisan config:cache
php artisan route:cache
php artisan view:cache
if [ "$(id -u)" -eq 0 ]; then
    chown -R "$runtime_owner" storage bootstrap/cache
fi
chmod 640 .env
printf '%s\n' "$revision" > .release-revision
php artisan up
reload_php
curl --fail --silent --show-error --retry 2 --max-time 20 https://muzakiabdullahirsyad.my.id/ --output "$backup/health.html"
grep -q 'hero-cinematic' "$backup/health.html"
grep -q 'mai-social.png' "$backup/health.html"
trap - EXIT
echo "Release $revision verified"
