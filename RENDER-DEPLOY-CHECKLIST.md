# 🚀 Checklist Deploy ke Render (100% GRATIS!)

## ✅ Pre-Deployment Checklist

Semua sudah siap! ✅
- ✅ Repository GitHub: https://github.com/Muzaki29/web-portofolio-muzaki
- ✅ Laravel Framework 12.41.1
- ✅ File CV: `public/cv/muzaki-abdullah-irsyad.pdf`
- ✅ File Image: `public/images/profil.jpg.jpg`
- ✅ render.yaml sudah dikonfigurasi
- ✅ APP_KEY sudah di-generate

---

## 📋 Step-by-Step Deploy ke Render

### Step 1: Login ke Render
1. Buka https://render.com
2. Klik **"Get Started for Free"** atau **"Sign In"**
3. Pilih **"Sign Up with GitHub"** atau **"Sign In with GitHub"**
4. Authorize Render untuk akses GitHub kamu

### Step 2: Create New Web Service
1. Setelah login, klik **"New +"** di dashboard
2. Pilih **"Web Service"**
3. Connect GitHub:
   - Klik **"Connect account"** atau **"Connect repository"**
   - Pilih repository **`web-portofolio-muzaki`**
   - Klik **"Connect"**

### Step 3: Configure Settings

Isi form dengan settings berikut:

| Field | Value |
|-------|-------|
| **Name** | `web-portofolio-muzaki` (atau nama lain) |
| **Environment** | `PHP` |
| **Region** | `Singapore` (atau yang terdekat) |
| **Branch** | `main` |
| **Root Directory** | (kosongkan, biarkan default) |
| **Build Command** | `composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache` |
| **Start Command** | `php artisan serve --host=0.0.0.0 --port=$PORT` |
| **Instance Type** | **`Free`** ⭐ |

### Step 4: Add Environment Variables

Scroll ke bagian **"Environment Variables"**, lalu klik **"Add Environment Variable"** untuk setiap variable:

#### Application Variables:
```
Key: APP_NAME
Value: Portfolio Muzaki

Key: APP_ENV
Value: production

Key: APP_KEY
Value: base64:ase0B5i9KkeOxC6iMx/JwVmb4jMI9Ntckb8F9KbSM7g=

Key: APP_DEBUG
Value: false

Key: APP_URL
Value: https://web-portofolio-muzaki.onrender.com
```

**⚠️ Catatan APP_URL**: 
- Isi dulu dengan `https://web-portofolio-muzaki.onrender.com`
- Setelah deploy selesai, Render akan kasih URL pasti
- Update `APP_URL` dengan URL yang diberikan Render

#### Email Variables (untuk Contact Form):
```
Key: MAIL_MAILER
Value: smtp

Key: MAIL_HOST
Value: smtp.gmail.com

Key: MAIL_PORT
Value: 587

Key: MAIL_USERNAME
Value: abdullahmuzaki2912@gmail.com

Key: MAIL_PASSWORD
Value: bjpgwjjfoejrfsdb

Key: MAIL_ENCRYPTION
Value: tls

Key: MAIL_FROM_ADDRESS
Value: abdullahmuzaki2912@gmail.com

Key: MAIL_FROM_NAME
Value: Portfolio Muzaki
```

**📝 Cara tambah:**
- Klik **"Add Environment Variable"**
- Masukkan Key
- Masukkan Value
- Klik **"Add"**
- Ulangi untuk semua variable di atas

### Step 5: Create Web Service
1. Scroll ke bawah
2. Pastikan semua settings sudah benar
3. Klik **"Create Web Service"**
4. Render akan mulai build dan deploy (butuh waktu ~5-10 menit)
5. Kamu bisa lihat progress di dashboard Render

### Step 6: Dapatkan URL & Update APP_URL
1. Setelah deploy selesai (status berubah jadi "Live")
2. Render akan kasih URL gratis (contoh: `https://web-portofolio-muzaki.onrender.com`)
3. **PENTING**: Copy URL tersebut
4. Di dashboard Render:
   - Klik service kamu
   - Klik tab **"Environment"**
   - Cari variable `APP_URL`
   - Klik edit icon
   - Update value dengan URL yang diberikan Render
   - Klik **"Save Changes"**
5. Render akan auto-redeploy dengan APP_URL yang benar

### Step 7: Test Website
1. Buka URL yang Render berikan
2. Test semua fitur:
   - ✅ Portfolio website muncul
   - ✅ Dark/Light mode toggle
   - ✅ Contact form (test kirim email)
   - ✅ Download CV button berfungsi
   - ✅ Semua section (About, Skills, Projects, dll) terlihat

---

## 🎉 Selesai!

Portfolio kamu sekarang online dan bisa diakses publik! 🚀

URL kamu akan seperti: `https://web-portofolio-muzaki.onrender.com`

---

## ⚠️ Troubleshooting

### Error: "Build failed"
- Cek logs di Render dashboard
- Pastikan Build Command benar
- Pastikan semua environment variables sudah di-set

### Error: "Application Error"
- Cek logs di Render dashboard
- Pastikan APP_KEY sudah benar
- Pastikan APP_URL sudah di-update dengan URL Render

### Email tidak terkirim
- Pastikan semua MAIL_* variables sudah benar
- Pastikan Gmail App Password benar (bukan password Gmail biasa)
- Cek logs di Render dashboard untuk detail error

### Service selalu sleep
- Normal untuk Free tier
- Service akan sleep setelah 15 menit idle
- Request pertama setelah sleep akan lebih lambat (~30 detik)

---

## 💡 Tips

1. **Auto-Deploy**: Setiap push ke GitHub akan auto-deploy ke Render
2. **View Logs**: Bisa lihat logs real-time di Render dashboard
3. **Update Code**: Push ke GitHub, Render akan otomatis deploy versi baru
4. **Prevent Sleep**: Bisa pakai UptimeRobot (gratis) untuk ping website setiap 10-15 menit

---

**Selamat deploy! Semoga lancar! ✨**

