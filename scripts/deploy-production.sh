#!/usr/bin/env bash
set -euo pipefail

archive=${1:?Release archive is required}
revision=${2:?Release revision is required}
[[ "$revision" =~ ^[0-9a-f]{40}$ ]] || { echo 'Invalid release revision' >&2; exit 2; }
test -f "$archive"
archive=$(readlink -f "$archive")
entries=$(tar -tf "$archive")
if grep -E '(^/|(^|/)\.\.(/|$)|^\.env($|/)|\.(sqlite[0-9]*|db)(-|$))' <<< "$entries" >/dev/null; then
    echo 'Unsafe release archive path' >&2
    exit 2
fi

app=/var/www/web-portofolio-muzaki
test "$(readlink -f "$app")" = "$app"
grep -Fq "$app/public" /etc/nginx/sites-enabled/muzakiabdullahirsyad.my.id
test -f "$app/.env"
runtime_owner=$(stat -c '%u:%g' "$app/storage")
for command in php composer node npm curl tar flock systemctl; do command -v "$command" >/dev/null; done
systemctl is-active --quiet php8.3-fpm
node -e 'const [major,minor]=process.versions.node.split(".").map(Number); if (!(major===20&&minor>=19 || major===22&&minor>=12 || major>22)) process.exit(1)'

privileged() {
    if [ "$(id -u)" -eq 0 ]; then "$@"; else sudo -n "$@"; fi
}
if [ "$(id -u)" -ne 0 ]; then
    sudo -n -l "$(command -v systemctl)" reload php8.3-fpm >/dev/null
    sudo -n -l "$(command -v chown)" -R "$runtime_owner" "$app/storage" "$app/bootstrap/cache" >/dev/null
    sudo -n -l "$(command -v chgrp)" "${runtime_owner#*:}" "$app/.env" >/dev/null
fi

umask 077
state="$HOME/.local/state/muzaki-portfolio"
mkdir -p "$state/releases"
exec 9>"$state/deploy.lock"
flock -n 9 || { echo 'Another portfolio deployment is running' >&2; exit 3; }
backup="$state/releases/$(date -u +%Y%m%dT%H%M%SZ)-$revision"
mkdir "$backup"
cd "$app"
if [ -f .release-revision ]; then cp .release-revision "$backup/revision"; fi
tar --exclude=./.git --exclude=./storage --exclude=./node_modules --exclude=./.env --exclude='./database/*.sqlite*' --exclude='./database/*.db' -czf "$backup/release.tar.gz" .
tar -tzf "$backup/release.tar.gz" >/dev/null

reload_php() {
    privileged systemctl reload php8.3-fpm
}

rollback() {
    result=$?
    trap - EXIT
    set +e
    if [ "$result" -ne 0 ]; then
        echo 'Deployment failed; restoring previous release' >&2
        if [ -f "$backup/maintenance.php" ]; then
            cp "$backup/maintenance.php" storage/framework/maintenance.php
            chmod 644 storage/framework/maintenance.php
        fi
        if [ -f "$backup/revision" ]; then
            cp "$backup/revision" .release-revision
        else
            rm -f .release-revision
        fi
        if tar --no-overwrite-dir -xzf "$backup/release.tar.gz" -C "$app" &&
            php artisan view:clear && php artisan up &&
            rm -f storage/framework/maintenance.php && reload_php; then
            echo 'Previous release restored' >&2
        else
            if [ -f "$backup/maintenance.php" ]; then
                cp "$backup/maintenance.php" storage/framework/maintenance.php
                chmod 644 storage/framework/maintenance.php
            fi
            echo 'Rollback incomplete; operator review required' >&2
        fi
    fi
    exit "$result"
}
trap rollback EXIT
umask 022
php artisan down --render=errors::503 --retry=60
chmod 644 storage/framework/maintenance.php
cp storage/framework/maintenance.php "$backup/maintenance.php"
database_metadata=$(stat -c '%u:%g:%a' database database/database.sqlite)
tar --no-overwrite-dir -xf "$archive" -C "$app"
test "$(stat -c '%u:%g:%a' database database/database.sqlite)" = "$database_metadata"
COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
composer check-platform-reqs --no-dev
npm ci --include=dev
npm run build
test -s public/build/manifest.json
test ! -e public/hot
php artisan config:cache
php artisan route:cache
php artisan view:cache
privileged chown -R "$runtime_owner" "$app/storage" "$app/bootstrap/cache"
privileged chgrp "${runtime_owner#*:}" "$app/.env"
chmod 640 .env
chmod 750 bootstrap/cache
find bootstrap/cache -type f -exec chmod 640 {} +
printf '%s\n' "$revision" > .release-revision
php artisan up
rm -f storage/framework/maintenance.php
reload_php
curl --fail --silent --show-error --retry 2 --max-time 20 https://muzakiabdullahirsyad.my.id/ --output "$backup/health.html"
grep -q 'hero-cinematic' "$backup/health.html"
grep -q 'mai-social.png' "$backup/health.html"
trap - EXIT
echo "Release $revision verified"
