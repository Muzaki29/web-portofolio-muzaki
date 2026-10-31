# Staging dan rollback — prosedur, belum dieksekusi

Review 31 Oktober 2026, branch `codex/cinematic-portfolio`. Pengguna kemudian mengotorisasi push/deployment production setelah review selesai. Dokumen ini prosedur staging; catatan pelaksanaan publish berada terpisah di DEPLOYMENT-STATUS.md. Jangan menganggap checkout branch saja mencakup perubahan yang belum dikomit.

## Prasyarat rilis

- Pilih revisi/artifact yang sudah direview setelah commit mendapat otorisasi terpisah. Sertakan seluruh file baru, bukan hanya `git diff` tracked. `.omx/` adalah runtime existing yang dikecualikan dari rilis.
- PHP >=8.2 sesuai composer.json; versi dan extension aktual harus lolos `composer check-platform-reqs --no-dev` pada host target. TLS CA store harus valid.
- Node `^20.19.0 || >=22.12.0`, npm yang kompatibel lockfile. Build saat review memakai Node24.11.1, Vite7.3.7 dan GSAP3.15.0.
- Composer2 dan akses registry/GitHub. Gunakan lockfile; jangan menjalankan `composer update` di staging/production.
- Web root harus menunjuk ke `public`, tidak ke root repository. Hanya storage dan bootstrap/cache yang perlu writable oleh user aplikasi; jangan memberi permission777.
- HTTPS staging, hostname terpisah, kontrol akses staging, storage/session/cache/database terpisah dari production. Tidak menyalin data/kredensial production untuk pengujian.
- Simpan release sebelumnya dengan vendor dan build asset masing-masing. Jangan menghapus release lama saat aktivasi.

## Checklist konfigurasi environment

| Pengaturan | Staging yang aman |
|---|---|
| APP_ENV | `staging`; jangan memakai local |
| APP_DEBUG | `false` |
| APP_URL | origin HTTPS staging yang benar; berpengaruh pada URL asset/share metadata |
| APP_KEY | key unik staging dari secret manager, jangan dicetak atau dikomit; jangan regenerasi key existing saat update |
| ASSET_URL / public/hot | ASSET_URL hanya jika diperlukan CDN; `public/hot` tidak boleh masuk artifact |
| SESSION_DRIVER / CACHE_STORE | file untuk staging sederhana atau store terpisah yang sudah diprovisikan; prefix/domain jangan berbagi production |
| SESSION_SECURE_COOKIE / SESSION_HTTP_ONLY / SESSION_SAME_SITE | true / true / lax pada staging HTTPS |
| DB_* | database staging kosong/fixture non-sensitif; sesuaikan driver dan extension; tidak ada perubahan schema dalam task ini |
| MAIL_MAILER | `array` untuk smoke tanpa delivery atau SMTP sink/sandbox terpisah yang disetujui; jangan mengirim ke inbox production |
| MAIL_FROM_* | identitas staging; konfigurasi recipient controller mengikuti mail.from.address existing |
| GEMINI_API_KEY | kosong untuk smoke503 aman; secret staging terpisah hanya untuk provider smoke yang diotorisasi |
| GEMINI_MODEL | `gemini-3.6-flash` default baru; konfirmasi model tersedia pada project/key staging sebelum rilis |
| LOG_* | log non-debug dengan akses terbatas dan rotasi; patch tidak lagi mencatat raw transport exception |
| QUEUE_CONNECTION | `sync` bila tidak ada worker; worker diperlukan jika konfigurasi lain memang dipakai |

File `.env.example` bukan file production siap pakai. `.env` dan auth.json tetap tidak masuk Git. Jangan menaruh API key dalam VITE_* atau JavaScript. CSP existing memerlukan akses browser ke Google Fonts, Font Awesome dan CDN export; export pertama membutuhkan koneksi eksternal.

## Prosedur deployment staging

Semua perintah berikut **untuk release staging terpisah yang path-nya sudah diverifikasi**, bukan untuk server production atau direktori pengguna saat ini. Jangan mengeksekusi migrasi atau menimpa release aktif sebagai bagian review ini.

1. Buat direktori release baru di host staging dan ekstrak artifact/revisi yang disetujui. Konfirmasi identitas release dan lokasi public root.
2. Pasang konfigurasi staging dari secret store; link storage staging yang sudah diprovisikan. Jangan menyalin `.env` dari artifact atau merotasi APP_KEY existing.
3. Jalankan dengan fail-fast:

```bash
set -eu
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
composer check-platform-reqs --no-dev
npm ci --include=dev
npm run build
test -s public/build/manifest.json
test ! -e public/hot
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Build di CI lalu mengirim `public/build` bersama artifact juga valid; bila demikian host runtime tidak perlu Node/npm. Jangan menghapus build release sebelumnya. Perintah cache hanya menyentuh release staging baru; jangan menyimpan hasil config cache dalam Git atau membagikannya karena memuat secret runtime.

4. Task ini tidak mengubah migration. Bila staging baru memakai driver database untuk session/cache/queue, provisioning schema dilakukan melalui prosedur staging yang disetujui setelah backup; tidak menjalankan migrate production secara otomatis.
5. Arahkan staging ke release baru secara atomik sesuai deployment platform, lalu reload PHP-FPM/opcache bila dipakai. Health check `/up` dan `/` harus200; pastikan asset hashed, ikon, social image, CV dan script tidak404.
6. Smoke lima viewport, keyboard/touch, kedua theme/locale, modal, reduced motion, contact invalid CAPTCHA, PDF/PPT. Dengan mail array, success response hanya membuktikan aplikasi menerima submission, bukan delivery SMTP.
7. Jika provider smoke diotorisasi, gunakan key staging terpisah untuk satu pertanyaan portfolio, tanpa data pribadi; uji status/error, output dan quota. Pengujian mock lokal tidak membuktikan akses provider nyata.
8. Jalankan Lighthouse pada staging HTTPS dengan cold cache dan catat kondisi lab. Jangan menyamakan angka localhost dengan CWV pengguna lapangan.

Workflow `.github/workflows/deploy.yml` dipicu push ke main. Patch menambah preflight Node/npm/Composer/curl, backup kode/vendor/build sebelum reset, prerendered maintenance503 selama pemasangan, fail-fast, rollback trap dan HTTP health check. Layanan dibuka kembali sebelum health check dan setelah rollback. Automatic migration dihapus untuk release ini karena tidak ada perubahan schema. Ini tetap deployment in-place dengan maintenance sementara, bukan deployment staging atomik; kontrol concurrency mencegah dua deploy bersamaan. Keberhasilan workflow harus diperiksa setelah push, bukan diasumsikan dari run sebelumnya.

## Rollback

1. Jika health check, assets atau fitur kritis gagal, hentikan promosi release. Catat release gagal dan gejala tanpa secret.
2. Kembalikan pointer/vhost staging ke release terakhir yang sehat beserta vendor/build miliknya, lalu reload PHP-FPM/opcache bila diperlukan. Jangan menggabungkan manifest baru dengan asset lama.
3. Pertahankan APP_KEY, storage dan konfigurasi staging yang kompatibel; tidak ada migration baru sehingga rollback kode tidak memerlukan database downgrade.
4. Ulang `/up`, `/`, asset manifest, locale, dialog dan contact invalid CAPTCHA. Jangan menghapus release gagal sampai diagnosis selesai.
5. Jika konfigurasi model baru bermasalah, pilih model yang didukung dan terverifikasi pada key staging melalui GEMINI_MODEL; jangan kembali ke model retired tanpa verifikasi. Provider failure harus tetap503 dengan pesan aman.

Restore database, operasi destruktif, dan rollback production bukan bagian task ini. Jika dilakukan kemudian, verifikasi backup dan target eksplisit sebelum tindakan.
