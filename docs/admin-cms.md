# Admin CMS — Panduan

Panel admin untuk mengelola isi landing page portofolio tanpa menyentuh kode.
Dibangun dengan Laravel + Inertia + React + shadcn/ui, di dalam aplikasi yang sama.

## Masuk ke admin

| | Lokal | Production |
|---|---|---|
| Halaman login | `http://127.0.0.1:8124/loginadmin` | `https://muzakiabdullahirsyad.my.id/loginadmin` |
| Dashboard | `/admin` | `/admin` |

- Tidak ada tautan ke `/loginadmin` dari landing page; buka lewat URL langsung.
- Login dibatasi 5 percobaan per menit per IP.
- Halaman admin memakai `noindex, nofollow`, jadi tidak diindeks mesin pencari.
- Semua halaman `/admin/*` mewajibkan login; tamu dialihkan ke `/loginadmin`.

### Membuat atau mengganti password akun admin

Jalankan di server (atau lokal), password diminta secara tersembunyi, minimal 10 karakter:

```bash
php artisan admin:create emailkamu@domain.com --name="Muzaki Abdullah Irsyad"
```

Perintah yang sama dipakai untuk reset password (email yang sudah ada akan diperbarui).
Akun tidak pernah dibuat lewat seeder.

Akun lokal untuk uji coba tersimpan di `storage/app/private/local-admin.txt` (di-ignore git, hanya untuk database lokal).

## Menu

| Menu | Mengatur | Tampil di landing |
|---|---|---|
| **Dashboard** | Grafik kunjungan 7/30/90 hari, pengunjung unik, perangkat, sumber kunjungan, jumlah konten | — |
| **Karya** | Proyek: judul, tagline, deskripsi, fitur, teknologi, gambar, tautan GitHub/live | Section *Karya Saya* |
| **Pengalaman** | Peran, perusahaan, periode, lokasi, deskripsi singkat, poin pencapaian, tautan, sertifikat terkait | Section *Pengalaman Kerja* |
| **Timeline** | Judul dan periode perjalanan karier/pendidikan | Section *Journey* |
| **Sertifikat** | Judul, label, penerbit, tanggal, satu atau beberapa halaman gambar (lebih dari satu = slider) | Section *Sertifikasi* + angka sertifikat |
| **Profil & Kontak** | Peran di hero, teks Tentang, lokasi, email (juga tujuan form kontak), LinkedIn, GitHub, Dribbble, Pinterest, Instagram, Threads — kosongkan untuk menyembunyikan | Hero, Tentang, Kontak (grup *Hubungi Saya* / *Karya & Konten*), footer |

Fitur yang sama di semua daftar:

- **Tab Semua / Tayang / Draft** dan pencarian.
- **Saklar Tayang** — matikan untuk menyembunyikan item tanpa menghapusnya.
- **Panah naik/turun** — mengubah urutan tampil di landing page (aktif saat tab *Semua* tanpa pencarian).
- **Hapus** — permanen, selalu dengan konfirmasi.

### Dua bahasa (ID / EN)

Field bertanda `ID`/`EN` mengikuti tab bahasa di form. Bahasa Indonesia wajib; bila English
dikosongkan, landing page versi Inggris memakai teks Indonesia.

### Tips menulis untuk rekruter & klien

- Deskripsi singkat: satu kalimat, fokus hasil.
- Poin pencapaian: maksimal 3 baris pendek, satu poin per baris.
- Pakai **Kunci sertifikat** (mis. `asdos_devops`) di Pengalaman/Timeline agar kartunya membuka sertifikat terkait.

## Statistik kunjungan

- Dicatat setiap kali landing page (`/`) dibuka.
- Tidak dihitung: bot/crawler, prefetch, dan admin yang sedang login.
- IP **tidak disimpan**; hanya hash harian yang di-salt dengan `APP_KEY` untuk menghitung pengunjung unik.
- Data di tabel `page_visits`.

## Menjalankan di lokal

```bash
php artisan migrate
php artisan db:seed            # isi awal dari konten lama (hanya bila tabel masih kosong)
php artisan storage:link       # agar gambar upload bisa diakses
npm run build                  # aset admin (React) dan landing
php artisan serve --port=8124
```

Setelah mengubah kode admin (`resources/js/admin`), jalankan `npm run build` lagi.

## Deploy

`scripts/deploy-production.sh` sudah menjalankan `migrate --force`, `db:seed --force`, dan
`storage:link`. Seeder **tidak menimpa** konten yang sudah diedit di admin — tiap section hanya
diisi bila masih kosong. Setelah deploy pertama, buat akun admin dengan `php artisan admin:create`.

## Struktur kode

| Bagian | Lokasi |
|---|---|
| Skema field Pengalaman/Timeline/Sertifikat/Profil | `config/cms.php` |
| Controller admin | `app/Http/Controllers/Admin/` |
| Model | `app/Models/Project.php`, `ContentItem.php`, `Setting.php`, `PageVisit.php` |
| Pencatat kunjungan | `app/Http/Middleware/TrackVisit.php` |
| Halaman React | `resources/js/admin/pages/` |
| Komponen shadcn | `resources/js/admin/components/ui/` |
| Logo MAI (SVG) | `public/images/mai-logo.svg`, `resources/js/admin/components/logo.tsx` |
| Tes | `tests/Feature/AdminCmsTest.php`, `tests/Feature/AdminContentTest.php` |

Menambah field baru ke Pengalaman/Timeline/Sertifikat cukup lewat `config/cms.php`, lalu
tampilkan di `resources/views/portfolio.blade.php`; form admin mengikuti otomatis.
