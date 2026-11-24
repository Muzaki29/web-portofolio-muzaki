# 🔐 SECURITY.md — web-portofolio-muzaki

> Dokumen ini mencatat semua implementasi keamanan yang sudah diterapkan,
> temuan yang masih terbuka, dan roadmap hardening ke depannya.
> **Update dokumen ini setiap kali ada perubahan terkait keamanan.**

---

## 📋 Informasi Proyek

| Item | Detail |
|------|--------|
| **Project** | web-portofolio-muzaki |
| **Stack** | Laravel 12, Blade, Inertia + React (admin), SQLite, SMTP |
| **Repo** | https://github.com/Muzaki29/web-portofolio-muzaki |
| **Last Security Review** | 2026-11-23 |
| **Status** | 🟡 Partially Hardened |

---

## ✅ Sudah Diimplementasikan

### Sprint 1 — 2026-06-08 (diperbarui 2026-11-23)

#### 1. `SecurityHeaders` Middleware
**File**: [`app/Http/Middleware/SecurityHeaders.php`](app/Http/Middleware/SecurityHeaders.php)
**Didaftarkan di**: [`bootstrap/app.php`](bootstrap/app.php)

| Header | Nilai | Fungsi |
|--------|-------|--------|
| `X-Frame-Options` | `DENY` | Cegah clickjacking via iframe |
| `X-Content-Type-Options` | `nosniff` | Cegah MIME-type sniffing |
| `X-XSS-Protection` | `1; mode=block` | Filter XSS di browser lama |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Kontrol data referrer ke pihak ketiga |
| `Permissions-Policy` | kamera, mikrofon, geolokasi, pembayaran, USB, sensor, dll. dimatikan | Matikan fitur browser yang tidak dipakai |
| `Content-Security-Policy` | `default-src 'self'`, `object-src 'none'`, `frame-ancestors 'none'`, `base-uri 'self'`, `form-action 'self'`; `upgrade-insecure-requests` hanya di HTTPS | Pertahanan utama XSS (lihat catatan `unsafe-inline` di bawah) |
| `Cross-Origin-Opener/Resource-Policy`, `Cross-Origin-Embedder-Policy` | `same-origin`, `same-origin`, `credentialless` | Isolasi dari origin lain |
| `Strict-Transport-Security` | `max-age=63072000; includeSubDomains; preload` | Paksa HTTPS (hanya di production) |

> `Server` dan `X-Powered-By` dihapus dari response. Response `POST` diberi `Cache-Control: no-store`.

#### 2. Rate Limiting pada `/contact`
**File**: [`routes/web.php`](routes/web.php), limiter `contact` di [`AppServiceProvider`](app/Providers/AppServiceProvider.php)

- `throttle:contact` = maks **3 request per menit per IP** (melindungi dari email flooding dan spam bot).
- Melebihi batas → `HTTP 429`.

#### 3. Captcha Session Expiry
**File**: [`Captcha`](app/Services/Captcha.php), [`ContactController`](app/Http/Controllers/ContactController.php)

- Captcha dibuat dan dicek di satu modul (`app/Services/Captcha.php`); disimpan bersama `captcha_generated_at`, **kedaluwarsa 5 menit**, dan kedua session key dihapus setelah berhasil kirim.

#### 4. Tujuan Email Tidak Hardcoded
**File**: [`ContactController`](app/Http/Controllers/ContactController.php)

- Penerima form kontak diambil dari pengaturan admin `contact_email` (Admin → Profil & Kontak), cadangan `MAIL_FROM_ADDRESS`.

### Sprint 2 — 2026-11-23 (Admin CMS)

#### 5. Akses Admin
**File**: [`routes/web.php`](routes/web.php), [`AuthController`](app/Http/Controllers/Admin/AuthController.php)

- Login hanya lewat URL `/loginadmin` (tidak ada tautan dari landing page). `POST` dibatasi **5 per menit**; sesi diregenerasi setelah login.
- Semua `/admin/*` wajib login; tamu dialihkan. Halaman admin memakai `noindex, nofollow`.
- Akun hanya dibuat lewat `php artisan admin:create` (tidak ada registrasi, tidak ada seeder akun). Password di-hash (`hashed` cast).
- **`robots.txt` tidak menyebut `/loginadmin`** (tes: `test_robots_txt_does_not_reveal_the_admin_login_url`).

#### 6. Login Google (opsional)
- Aktif hanya bila `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` terisi; kalau tidak, rutenya `404`.
- Hanya email Google **terverifikasi** yang **sudah terdaftar sebagai admin** yang bisa masuk. Akun lain ditolak. Rute dibatasi 10 per menit.

#### 7. Perlindungan XSS untuk Konten Admin
- Blade meng-escape semua keluaran secara default. Satu-satunya HTML mentah dari konten admin:
  - Poin fitur case study lewat `Project::inlineHtml()`: **escape dulu, lalu pulihkan hanya `<strong> <em> <b> <i>` tanpa atribut**, jadi `onmouseover=…` dan `<script>` tidak pernah lolos.
  - JSON-LD memakai `JSON_HEX_TAG`, jadi teks berisi `</script>` tidak bisa keluar dari tag.
- Tes: `test_case_study_bullets_never_render_attributes_or_scripts`, `test_json_ld_cannot_break_out_of_its_script_tag`.

#### 8. Hostinger Mail di Admin
**File**: [`HostingerMail`](app/Services/HostingerMail.php), [`MailController`](app/Http/Controllers/Admin/MailController.php)

- Token `HOSTINGER_MAIL_TOKEN` hanya di `.env`, dipakai di sisi server, tidak pernah dikirim ke browser.
- Mailbox diverifikasi terhadap daftar milik token; `uid` dibatasi angka; rute dibatasi (baca/bintang 60, pindah/lampiran 30, kirim 10 per menit).
- **Tidak ada hapus permanen**: pesan hanya dipindah ke folder yang benar-benar ada. Isi email HTML diubah jadi teks; lampiran selalu diunduh (`attachment` + `nosniff`, nama file disanitasi).

#### 9. Lain-lain
- Upload gambar divalidasi (jpg/png/webp, batas ukuran) dan disimpan di disk `public`.
- Statistik kunjungan menyimpan IP hanya sebagai **hash harian bergaram**; bot dan admin tidak dihitung.
- Chatbot **mati secara default** (`CHATBOT_ENABLED=false`); `/api/chatbot` mengembalikan `404` saat mati.

---

## 🔒 Kontrol Keamanan Bawaan Laravel

| Kontrol | Status | Keterangan |
|---------|--------|------------|
| CSRF Token | ✅ Aktif | Semua form POST dilindungi otomatis |
| Input Validation | ✅ Aktif | Form kontak, admin (proyek, konten, pengaturan, mail), dan login divalidasi di server |
| Query Builder | ✅ Aman | Eloquent/Query Builder (parameterized) |
| Password Hashing | ✅ Aktif | Akun admin di-hash lewat cast `hashed` |
| Session Encryption | ⚠️ Config | `SESSION_ENCRYPT=false` — pertimbangkan aktifkan |

---

## 🟡 Masih Perlu Ditingkatkan

| # | Item | Prioritas | Deskripsi |
|---|------|-----------|-----------|
| 1 | **`APP_DEBUG=false` di production** | 🔴 Tinggi | Pastikan `false` di server produksi |
| 2 | **`/admin` mengalihkan tamu ke `/loginadmin`** | 🟡 Sedang | Ini membuka URL login ke siapa pun yang menebak `/admin`. Ganti jadi `404` untuk tamu |
| 3 | **CSP masih memakai `'unsafe-inline'`** | 🟡 Sedang | Pindahkan skrip dan gaya inline ke nonce agar CSP benar-benar menahan XSS |
| 4 | **`GET /loginadmin` tanpa throttle** | 🔵 Rendah | Hanya `POST` yang dibatasi |
| 5 | **Login Google selalu "ingat saya"** | 🔵 Rendah | `Auth::login($user, true)`; pertimbangkan sesi biasa |
| 6 | **`Content-Type` lampiran dari upstream** | 🔵 Rendah | Paksa `application/octet-stream` |
| 7 | **Aktifkan `SESSION_ENCRYPT=true`** | 🟡 Sedang | Enkripsi session cookie |
| 8 | **Bot protection lebih kuat** | 🟡 Sedang | Ganti math captcha dengan Turnstile/hCaptcha |
| 9 | **Dependency audit rutin** | 🟡 Sedang | `composer audit` dan `npm audit` tiap bulan |
| 10 | **Log monitoring dan backup otomatis** | 🔵 Rendah | Alert error 500/rate limit; backup DB dan storage mingguan |

---

## 📁 File-File Kritis yang Harus Dijaga

```
⚠️  JANGAN COMMIT FILE INI KE GITHUB:
    .env                    ← APP_KEY, mail password, GOOGLE_CLIENT_SECRET, HOSTINGER_MAIL_TOKEN
    storage/logs/           ← log server
    storage/app/private/    ← berisi akun uji lokal (di-ignore)
    .env.backup             ← backup env (jika ada)
```

---

## 🧪 Cara Verifikasi Security Headers

```bash
curl -I https://muzakiabdullahirsyad.my.id
```

Atau kunjungi: **https://securityheaders.com/?q=muzakiabdullahirsyad.my.id**

---

## 🚨 Prosedur Jika Terjadi Insiden

1. **Segera set** `APP_DEBUG=false` dan restart server
2. **Cek log** di `storage/logs/laravel.log`
3. **Rotasi** `APP_KEY` dengan `php artisan key:generate` (semua sesi akan keluar)
4. **Ganti** `MAIL_PASSWORD`, **buat ulang** `GOOGLE_CLIENT_SECRET` (Google Cloud → Clients), dan **cabut + buat ulang** token Hostinger Mail jika dicurigai bocor
5. **Reset password admin**: `php artisan admin:create email@domain --name="..."`
6. **Review** git log: `git log --oneline -20`

---

## 📅 Riwayat Perubahan Keamanan

| Tanggal | Sprint | Perubahan | Commit |
|---------|--------|-----------|--------|
| 2026-06-08 | Sprint 1 | SecurityHeaders middleware, rate limiting, captcha expiry, email dari config | `c6dd08e` |
| 2026-11-23 | Sprint 2 | Admin CMS (login throttled, Google login, Hostinger Mail), escape-first untuk konten admin, JSON-LD `JSON_HEX_TAG`, `robots.txt` tidak lagi menyebut `/loginadmin`, dokumen disesuaikan dengan kondisi sebenarnya | — |

---

*Dokumen ini dibuat menggunakan metodologi OWASP Top 10 dan STRIDE Analysis.*
*Selalu update tabel "Riwayat Perubahan" setiap kali ada perbaikan keamanan.*
