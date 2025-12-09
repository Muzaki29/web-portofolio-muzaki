# 🚀 Panduan Deploy Portfolio ke Hosting Gratis

**GitHub Profile**: [@Muzaki29](https://github.com/Muzaki29)

---

## 📋 Opsi Hosting Gratis untuk Laravel

### 1. **Render** ⭐⭐⭐ MOST RECOMMENDED (100% Gratis!)
- **Gratis**: ✅ Free tier PERMANEN (tidak perlu kartu kredit)
- **URL**: https://render.com
- **Keunggulan**: 
  - ✅ Auto-deploy dari GitHub
  - ✅ URL gratis dengan SSL
  - ✅ Mudah setup
  - ✅ Gratis selamanya
- **Limit**: 
  - ⚠️ Service sleep setelah 15 menit idle (free tier)
  - Request pertama setelah sleep akan lebih lambat (~30 detik cold start)
- **Verdict**: **PALING COCOK untuk portfolio!** Sleep tidak masalah karena portfolio jarang diakses terus-menerus.

### 2. **Fly.io** ⭐⭐
- **Gratis**: ✅ Free tier permanen (3 shared-cpu-1x VMs)
- **URL**: https://fly.io
- **Keunggulan**: 
  - ✅ Selalu online (tidak sleep)
  - ✅ Gratis selamanya
  - ✅ Global deployment
- **Limit**: 
  - 3 VMs gratis
  - Setup sedikit lebih kompleks (perlu CLI)
- **Verdict**: Bagus kalau mau website selalu online tanpa sleep.

### 3. **Railway** ⭐
- **Gratis**: ⚠️ Trial 30 hari ($5 credit), lalu $1/bulan untuk free tier
- **URL**: https://railway.app
- **Keunggulan**: 
  - ✅ Auto-deploy dari GitHub
  - ✅ Mudah setup
  - ✅ Tidak sleep
- **Limit**: 
  - ⚠️ Trial 30 hari pertama dengan $5 credit
  - Setelah itu, free tier hanya $1 credit/bulan
  - Maksimal 1 proyek, 0.5 GB RAM, 1 vCPU
  - Untuk portfolio sederhana mungkin cukup, tapi ada limit
- **Verdict**: Oke untuk trial, tapi setelah 30 hari ada limit ketat.

### 4. **InfinityFree / 000webhost**
- **Gratis**: ✅ 100% gratis (shared hosting)
- **URL**: https://infinityfree.net atau https://www.000webhost.com
- **Keunggulan**: 
  - ✅ Selalu online
  - ✅ Gratis selamanya
  - ✅ Tidak sleep
- **Limit**: 
  - ⚠️ Perlu setup manual (upload via FTP)
  - Tidak ada auto-deploy dari GitHub
  - Setup Laravel lebih kompleks
- **Verdict**: Bisa jadi alternatif, tapi setup lebih ribet.

---

## 🎯 Setup untuk Render (100% GRATIS - Recommended! ⭐)

### Step 1: Push ke GitHub

```bash
# Inisialisasi git (jika belum)
git init

# Tambahkan semua file
git add .

# Commit
git commit -m "Initial commit: Portfolio website"

# Buat repository baru di GitHub (atau pakai yang sudah ada)
# Nama repo: web-portofolio-muzaki atau portfolio (pilihan kamu)

# Tambahkan remote
git remote add origin https://github.com/Muzaki29/web-portofolio-muzaki.git

# Push ke GitHub
git branch -M main
git push -u origin main
```

**Note**: Jika repo sudah ada di GitHub, ganti URL remote sesuai repo kamu.

### Step 2: Generate APP_KEY

```bash
php artisan key:generate --show
```

Salin output-nya, akan dipakai nanti di Railway.

### Step 3: Deploy di Railway

1. Login ke https://railway.app
   - Pilih **"Login with GitHub"**
   - Authorize Railway untuk akses GitHub kamu

2. Create New Project
   - Klik **"New Project"**
   - Pilih **"Deploy from GitHub repo"**
   - Pilih repository `web-portofolio-muzaki` (atau nama repo kamu)

3. Railway akan auto-detect Laravel dan mulai build

### Step 4: Setup Environment Variables

Di Railway dashboard, klik service kamu → **Variables** tab → tambahkan:

```
APP_NAME=Portfolio Muzaki
APP_ENV=production
APP_KEY=paste-app-key-dari-step-2
APP_DEBUG=false
APP_URL=https://your-railway-url.up.railway.app

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=abdullahmuzaki2912@gmail.com
MAIL_PASSWORD=bjpgwjjfoejrfsdb
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=abdullahmuzaki2912@gmail.com
MAIL_FROM_NAME="Portfolio Muzaki"
```

**⚠️ Penting**: 
- Ganti `APP_URL` dengan URL yang Railway berikan setelah deploy
- `APP_KEY` harus diisi dengan key yang di-generate di step 2

### Step 5: Generate Domain

1. Di Railway dashboard, klik service → **Settings**
2. Scroll ke **"Generate Domain"**
3. Railway akan kasih URL gratis (contoh: `web-portofolio-muzaki-production.up.railway.app`)
4. Update `APP_URL` di Variables dengan URL baru ini

### Step 6: Verifikasi Deploy

- Railway akan auto-deploy setiap ada push ke GitHub
- Cek logs di Railway dashboard untuk melihat progress
- Buka URL domain yang Railway berikan

---

## 🎯 Setup untuk Render (100% GRATIS! ⭐⭐⭐)

### Step 1: Push ke GitHub ✅
**Sudah selesai!** Repo kamu sudah ada di: https://github.com/Muzaki29/web-portofolio-muzaki

### Step 2: Generate APP_KEY ✅
**Sudah di-generate!** APP_KEY kamu:
```
base64:ase0B5i9KkeOxC6iMx/JwVmb4jMI9Ntckb8F9KbSM7g=
```
**⚠️ Salin APP_KEY ini untuk dipakai di Step 4!**

### Step 3: Deploy di Render

1. **Login ke Render**
   - Buka https://render.com
   - Klik **"Get Started for Free"** → **"Sign Up with GitHub"**
   - Authorize Render untuk akses GitHub kamu

2. **Create New Web Service**
   - Klik **"New +"** di dashboard
   - Pilih **"Web Service"**
   - Connect GitHub repository:
     - Pilih **"Connect account"** atau **"Connect repository"**
     - Pilih repository `web-portofolio-muzaki`
     - Klik **"Connect"**

3. **Configure Web Service**
   - **Name**: `web-portofolio-muzaki` (atau nama lain)
   - **Environment**: Pilih **`PHP`**
   - **Region**: Pilih yang terdekat (misalnya `Singapore`)
   - **Branch**: `main` (atau `master`)
   - **Root Directory**: (kosongkan, biarkan default)
   - **Build Command**: 
     ```bash
     composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
     ```
   - **Start Command**: 
     ```bash
     php artisan serve --host=0.0.0.0 --port=$PORT
     ```
   - **Instance Type**: Pilih **`Free`** (yang paling bawah)

4. **Add Environment Variables** (PENTING!)
   
   Scroll ke bawah ke bagian **"Environment Variables"**, lalu klik **"Add Environment Variable"** untuk setiap variable berikut:
   
   ```
   APP_NAME = Portfolio Muzaki
   APP_ENV = production
   APP_KEY = base64:ase0B5i9KkeOxC6iMx/JwVmb4jMI9Ntckb8F9KbSM7g=
   APP_DEBUG = false
   APP_URL = https://web-portofolio-muzaki.onrender.com
   ```
   
   **⚠️ Note untuk APP_URL**: 
   - Isi dulu dengan `https://web-portofolio-muzaki.onrender.com` (atau URL yang Render berikan)
   - Setelah deploy selesai, Render akan kasih URL pasti, update `APP_URL` sesuai URL yang diberikan
   
   ```
   MAIL_MAILER = smtp
   MAIL_HOST = smtp.gmail.com
   MAIL_PORT = 587
   MAIL_USERNAME = abdullahmuzaki2912@gmail.com
   MAIL_PASSWORD = bjpgwjjfoejrfsdb
   MAIL_ENCRYPTION = tls
   MAIL_FROM_ADDRESS = abdullahmuzaki2912@gmail.com
   MAIL_FROM_NAME = Portfolio Muzaki
   ```
   
   **📝 Cara tambah variable:**
   - Klik **"Add Environment Variable"**
   - Masukkan **Key** (misalnya `APP_NAME`)
   - Masukkan **Value** (misalnya `Portfolio Muzaki`)
   - Klik **"Add"**
   - Ulangi untuk semua variable di atas

5. **Create Web Service**
   - Setelah semua settings benar, scroll ke bawah
   - Klik **"Create Web Service"**
   - Render akan mulai build dan deploy (butuh waktu ~5-10 menit)
   - Kamu bisa lihat progress di dashboard Render

6. **Dapatkan URL**
   - Setelah deploy selesai, Render akan kasih URL gratis
   - URL format: `https://web-portofolio-muzaki.onrender.com` (atau sesuai nama service)
   - **Update `APP_URL`** di Environment Variables dengan URL yang diberikan

### Step 4: Verifikasi Deploy ✅

- ✅ Cek status deploy di Render dashboard
- ✅ Klik URL yang Render berikan
- ✅ Test semua fitur:
  - ✅ Portfolio website muncul
  - ✅ Dark/Light mode toggle
  - ✅ Contact form (test kirim email)
  - ✅ Download CV button

**🎉 Selamat! Portfolio kamu sudah online dan bisa diakses publik!**

### 📝 Catatan Penting Render:

- ✅ **Gratis selamanya** - Tidak perlu kartu kredit
- ⚠️ **Service sleep**: Free tier akan sleep setelah 15 menit tidak ada traffic
- ⚠️ **Cold start**: Request pertama setelah sleep akan lebih lambat (~30 detik)
- ✅ **Auto-deploy**: Setiap push ke GitHub akan auto-deploy
- ✅ **SSL gratis**: URL sudah termasuk HTTPS

**💡 Tips**: 
- Untuk menghindari sleep, bisa pakai service seperti UptimeRobot (gratis) untuk ping website setiap 10-15 menit
- Atau terima saja sleep, karena portfolio biasanya tidak perlu selalu online

---

## 📝 Checklist Sebelum Deploy

### File yang Harus Ada:
- ✅ `railway.json` (untuk Railway)
- ✅ `Procfile` (untuk Render/Heroku)
- ✅ `.gitignore` (pastikan `.env` dan `vendor` di-ignore)
- ✅ `composer.json` (sudah ada)
- ✅ File CV di `public/cv/muzaki-abdullah-irsyad.pdf`
- ✅ File image di `public/images/profile.jpg`

### Environment Variables yang Wajib:
- `APP_KEY` (generate dengan `php artisan key:generate --show`)
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL` (akan otomatis dari hosting)
- Semua `MAIL_*` variables (untuk contact form)

---

## ⚙️ Setup untuk InfinityFree (Shared Hosting)

### Step 1: Upload Files

1. Upload semua file ke hosting via FTP/cPanel
2. **File yang TIDAK perlu di-upload**:
   - `vendor/` (akan di-generate via composer)
   - `node_modules/`
   - `.env` (buat baru di hosting)
   - `.git/`

3. Pastikan struktur folder:
   ```
   public_html/
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── public/  (ini yang jadi root web)
   ├── resources/
   ├── routes/
   ├── storage/
   └── ...
   ```

### Step 2: Setup .htaccess

Di root `public_html/`, buat `.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Step 3: Setup .env di Hosting

Buat file `.env` di root hosting (sama level dengan `public/`), isi seperti di Railway Step 4.

---

## 🐛 Troubleshooting

### Error: "No application encryption key"
- Generate `APP_KEY` dan set di environment variables
- Jalankan: `php artisan key:generate --show` di local, salin ke hosting

### Error: "Storage link not found"
- Setelah deploy, jalankan: `php artisan storage:link`
- Atau set di build command hosting

### Error: "Permission denied" untuk storage
- Pastikan folder `storage/` dan `bootstrap/cache/` writable
- Set permission: `chmod -R 775 storage bootstrap/cache`

### Email tidak terkirim
- Cek semua `MAIL_*` variables sudah benar
- Pastikan Gmail App Password sudah benar (bukan password Gmail biasa)
- Cek logs di hosting dashboard

### 404 Not Found setelah deploy
- Pastikan `APP_URL` sudah benar
- Cek apakah hosting support Laravel routing
- Untuk shared hosting, pastikan `.htaccess` sudah benar

---

## 🎉 Setelah Deploy Berhasil

1. **Test Website**
   - Buka URL yang hosting berikan
   - Test semua section (About, Skills, Projects, dll)
   - Test contact form (kirim test email)

2. **Test Download CV**
   - Klik tombol "Download CV"
   - Pastikan file PDF terdownload

3. **Test Dark/Light Mode**
   - Toggle dark/light mode
   - Pastikan preference tersimpan

4. **Auto-Deploy**
   - Setelah setup, setiap push ke GitHub akan auto-deploy
   - Cek logs di hosting dashboard jika ada error

---

## 📚 Referensi

- **Railway Docs**: https://docs.railway.app
- **Render Docs**: https://render.com/docs
- **Laravel Deployment**: https://laravel.com/docs/deployment
- **GitHub Profile**: https://github.com/Muzaki29

---

## ⭐ Rekomendasi

**Untuk Portfolio**: Pakai **Railway** atau **Render** karena:
- ✅ Auto-deploy dari GitHub
- ✅ Gratis untuk portfolio
- ✅ Mudah setup environment variables
- ✅ Support Laravel dengan baik
- ✅ URL gratis dengan SSL

**Untuk Production**: Pertimbangkan upgrade ke paid tier jika traffic tinggi.

---

**Selamat deploy! Semoga lancar! 🚀✨**

