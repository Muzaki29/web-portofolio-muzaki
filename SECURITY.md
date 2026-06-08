# 🔐 SECURITY.md — web-portofolio-muzaki

> Dokumen ini mencatat semua implementasi keamanan yang sudah diterapkan,
> temuan yang masih terbuka, dan roadmap hardening ke depannya.
> **Update dokumen ini setiap kali ada perubahan terkait keamanan.**

---

## 📋 Informasi Proyek

| Item | Detail |
|------|--------|
| **Project** | web-portofolio-muzaki |
| **Stack** | Laravel 11, Blade, SQLite, SMTP Gmail |
| **Repo** | https://github.com/Muzaki29/web-portofolio-muzaki |
| **Last Security Review** | 2026-06-08 |
| **Status** | 🟡 Partially Hardened |

---

## ✅ Sudah Diimplementasikan

### Sprint 1 — 2026-06-08

#### 1. `SecurityHeaders` Middleware
**File**: [`app/Http/Middleware/SecurityHeaders.php`](app/Http/Middleware/SecurityHeaders.php)
**Didaftarkan di**: [`bootstrap/app.php`](bootstrap/app.php)

Header yang aktif pada setiap response:

| Header | Nilai | Fungsi |
|--------|-------|--------|
| `X-Frame-Options` | `SAMEORIGIN` | Cegah clickjacking via iframe |
| `X-Content-Type-Options` | `nosniff` | Cegah MIME-type sniffing |
| `X-XSS-Protection` | `1; mode=block` | Filter XSS di browser lama |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Kontrol data referrer ke pihak ketiga |
| `Permissions-Policy` | `camera=(), microphone=(), geolocation=(), payment=()` | Matikan fitur browser berbahaya |
| `Strict-Transport-Security` | `max-age=31536000; includeSubDomains; preload` | Paksa HTTPS (hanya di production) |

> ⚠️ **Catatan**: `Server` dan `X-Powered-By` header juga dihapus dari response untuk menyembunyikan tech stack.

---

#### 2. Rate Limiting pada `/contact`
**File**: [`routes/web.php`](routes/web.php) — baris 54-57

```php
Route::post('/contact', [ContactController::class, 'sendEmail'])
    ->name('contact.send')
    ->middleware('throttle:5,1'); // Maks 5 request per menit per IP
```

- Melindungi dari **email flooding** dan **spam bot**
- Jika melebihi batas: Laravel mengembalikan `HTTP 429 Too Many Requests`

---

#### 3. Captcha Session Expiry
**File**: [`routes/web.php`](routes/web.php) — saat generate captcha
**File**: [`app/Http/Controllers/ContactController.php`](app/Http/Controllers/ContactController.php)

- Captcha token disimpan bersama timestamp `captcha_generated_at`
- Token **expired setelah 5 menit** — mencegah replay attack
- Setelah berhasil kirim, **kedua session key dihapus** sekaligus

---

#### 4. Email Target dari Config (bukan hardcoded)
**File**: [`app/Http/Controllers/ContactController.php`](app/Http/Controllers/ContactController.php)

```php
// Sebelum (buruk):
Mail::to('abdullahmuzaki2912@gmail.com')->send(...);

// Sesudah (aman):
Mail::to(config('mail.from.address'))->send(...);
```

Email target diambil dari `MAIL_FROM_ADDRESS` di `.env` — tidak terikat ke kode.

---

## 🔒 Kontrol Keamanan Bawaan Laravel (Sudah Aktif Sejak Awal)

| Kontrol | Status | Keterangan |
|---------|--------|------------|
| CSRF Token | ✅ Aktif | Semua form POST dilindungi otomatis |
| Input Validation | ✅ Aktif | Semua field divalidasi di `ContactController` |
| Query Builder | ✅ Aman | Menggunakan Eloquent/Query Builder (parameterized) |
| Password Hashing | ✅ N/A | Tidak ada auth user di portfolio ini |
| Session Encryption | ✅ Config | `SESSION_ENCRYPT=false` — pertimbangkan aktifkan |

---

## 🟡 Masih Perlu Ditingkatkan

### Sprint 2 (Target: Bulan Depan)

| # | Item | Prioritas | Deskripsi |
|---|------|-----------|-----------|
| 1 | **`APP_DEBUG=false` di production** | 🔴 Tinggi | Saat ini `APP_DEBUG=true` di `.env` lokal — **pastikan `false` di server produksi** |
| 2 | **Aktifkan `SESSION_ENCRYPT=true`** | 🟡 Sedang | Enkripsi session cookie untuk data sensitif |
| 3 | **Bot Protection lebih kuat** | 🟡 Sedang | Ganti math captcha dengan Cloudflare Turnstile atau hCaptcha |
| 4 | **Content-Security-Policy header** | 🟡 Sedang | Tambahkan CSP ke `SecurityHeaders` middleware |
| 5 | **Log monitoring** | 🟡 Sedang | Alert jika terjadi banyak error 500 atau rate limit hit berturut-turut |

### Sprint 3 (Target: 2 Bulan ke Depan)

| # | Item | Prioritas | Deskripsi |
|---|------|-----------|-----------|
| 6 | **Dependency audit rutin** | 🟡 Sedang | Jalankan `composer audit` tiap bulan |
| 7 | **HTTPS enforcement** | 🟡 Sedang | Konfigurasi redirect HTTP → HTTPS di web server (Nginx/Apache) |
| 8 | **Backup otomatis** | 🔵 Rendah | Jadwalkan backup storage & database mingguan |
| 9 | **Error page kustom** | 🔵 Rendah | Halaman 404/500 kustom agar tidak bocorkan info Laravel |

---

## 📁 File-File Kritis yang Harus Dijaga

```
⚠️  JANGAN COMMIT FILE INI KE GITHUB:
    .env                    ← credentials, APP_KEY, mail password
    storage/logs/           ← log server (bisa bocor info sensitif)
    .env.backup             ← backup env (jika ada)

✅  Pastikan ada di .gitignore:
    /vendor
    .env
    storage/app/private
    storage/logs
```

---

## 🧪 Cara Verifikasi Security Headers

Setelah deploy, cek header dengan tools berikut:

```bash
# Via curl (terminal)
curl -I https://muzakiabdullahirsyad.my.id

# Via browser
# Buka DevTools → Network → pilih request → lihat tab Headers → Response Headers
```

Atau kunjungi: **https://securityheaders.com/?q=muzakiabdullahirsyad.my.id**

---

## 🚨 Prosedur Jika Terjadi Insiden

1. **Segera set** `APP_DEBUG=false` dan restart server
2. **Cek log** di `storage/logs/laravel.log` untuk trace serangan
3. **Rotasi** `APP_KEY` dengan `php artisan key:generate`
4. **Ganti** `MAIL_PASSWORD` (Gmail App Password) jika dicurigai bocor
5. **Review** git log untuk perubahan mencurigakan: `git log --oneline -20`

---

## 📅 Riwayat Perubahan Keamanan

| Tanggal | Sprint | Perubahan | Commit |
|---------|--------|-----------|--------|
| 2026-06-08 | Sprint 1 | Add SecurityHeaders middleware, rate limiting, captcha expiry, email dari config | `c6dd08e` |

---

*Dokumen ini dibuat menggunakan metodologi OWASP Top 10 dan STRIDE Analysis.*
*Selalu update tabel "Riwayat Perubahan" setiap kali ada perbaikan keamanan.*
