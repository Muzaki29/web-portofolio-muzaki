# QA — bukti dan batas verifikasi

30 Oktober 2026. Server lokal `http://127.0.0.1:8000`, Chrome/Playwright pada Windows. Tidak ada request pengiriman email valid atau provider chatbot live dari browser; success email diuji dengan Mail fake dan chatbot browser memakai response mock.

## Pemeriksaan yang dijalankan

| Pemeriksaan | Hasil |
|---|---|
| Baseline `npm run build` / `php artisan test` | Lolos; 2 test sebelum perubahan |
| Build final `npm run build` | Lolos, Vite 7.3.7 |
| `php artisan test` | 12 test, 53 assertion, lolos |
| `vendor/bin/pint --test tests/Feature/PortfolioRegressionTest.php` | Lolos |
| `php artisan route:list --except-vendor` | 4 route, homepage/locale/contact/chatbot tetap tersedia |
| `node --check` public scripts dan module motion/browser suite | Lolos |
| `git diff --check` | Lolos; warning CRLF normalisasi saja |
| `npm audit` / `npm audit --omit=dev` | 0 kerentanan terlapor; sebelumnya 11 |
| Browser regression | 27/27 lolos; detail `browser-results.json` |
| Supplemental visual final bundle | Lolos; CTA explore mobile berakhir x=310, chatbot mulai x=322, tidak bertumpuk |
| Axe 4.14.0 WCAG2/2.1/2.2 A/AA tagged rules | 0 violation terdeteksi pada kedua theme; 33 rule pass/theme, 1 rule incomplete/theme |

## Regresi browser

- 390, 768, 1024, 1440, 1920px: tidak ada overflow horizontal; teks hero diperiksa dengan Range bounds agar overflow clipping tidak menyembunyikan huruf.
- Screenshot proyek dimuat/decode sebelum capture. Screenshot seluruh section menyembunyikan fixed navigation/skip link/cursor/floating controls **hanya saat capture** agar galeri terbaca; tidak mengubah perilaku aplikasi.
- Tema tersimpan setelah reload, ID/EN, keyboard skip link, skills Arrow/Home/End/ARIA, mobile menu, focus trap dan focus restore kedua dialog.
- Reduced motion dinamis dan restore; static typewriter/marquee; review tambahan membuktikan tween tab langsung berhenti saat preferensi berubah.
- CV PDF 96,792 bytes; generated PDF 4,386 bytes dengan signature `%PDF`; PPTX 92,027 bytes dengan signature ZIP `PK`.
- CAPTCHA invalid mengembalikan 422 tanpa email. HTTP regression juga membuktikan success fake mail, CAPTCHA satu kali, expired/missing/wrong answer, validation dan invalid chatbot input.
- Mocked chatbot membuktikan HTML berbahaya dirender sebagai teks dan link protokol berbahaya ditolak.
- JavaScript disabled: nama/profesi, about, kartu, navigasi mobile tetap terbaca.
- Tidak ada uncaught page error atau unexpected runtime resource error sebelum scanner. Console mentah mencatat **422 yang disengaja** dan CSP warnings dari upaya axe mengambil stylesheet eksternal, bukan kegagalan stylesheet halaman. CSP tidak dilonggarkan.

## Aksesibilitas

Temuan awal sudah diperbaiki: judul tersembunyi legacy CSS, teks hero mobile terpotong, ARIA name yang tidak sesuai visible label, ARIA yang dilarang pada paragraf, nested interactive certificate controls, hidden dialog focus, dan teks kurang kontras. Sertifikat sekarang memiliki tombol native sendiri, modal tertutup inert dan kontrol input berlabel.

Incomplete: rule `color-contrast` masih meminta review manual terhadap 88 node/theme (misalnya background kompleks/ikon/gambar). Nol violation otomatis **bukan** sertifikasi WCAG penuh. Screen reader manual, perangkat touch fisik, dan browser selain Chromium belum diuji. Screenshot visual diperiksa untuk hero mobile/desktop dan gallery; contrast issue yang terdeteksi scanner telah diperbaiki.

## Performa

Build motion sekitar **117.18 kB / 46.35 kB gzip JS**, CSS sekitar **7.03 kB / 2.32 kB gzip**. App entry lama tetap tersedia tetapi tidak dimuat tambahan oleh portfolio. Hero WebP 27,920 bytes; 9 gambar proyek WebP sekitar 19–49 kB/gambar, lazy-loaded, dengan dimensi eksplisit. Export vendor baru dimuat saat diperlukan.

Satu observasi Chromium lokal unthrottled 1440×1000: LCP **~727ms**, CLS **0.0146**. Cache/network tidak dikontrol; ini **lab lokal**, bukan hasil Lighthouse mobile, data lapangan, atau jaminan target Core Web Vitals. INP belum diukur. Raw lab mencatat bundle yang diuji; perbaikan padding mobile terakhir diverifikasi terpisah tanpa mengubah algoritme motion.

## Menjalankan ulang

```powershell
npm ci --include=dev
npm run build
php artisan test
php artisan serve --host=127.0.0.1 --port=8000
```

Terminal lain, memakai runtime Playwright terpasang (tanpa menambah dependency aplikasi):

```powershell
$env:HEADLESS = '1'
$env:BASE_URL = 'http://127.0.0.1:8000'
$env:PLAYWRIGHT_MODULE = 'C:/Users/muzak/.agents/skills/playwright-skill/node_modules/playwright'
# AXE_MODULE menunjuk axe-core/axe.min.js pada instalasi QA terpisah.
$env:AXE_MODULE = Join-Path $env:TEMP 'muzaki-motion-axe/node_modules/axe-core/axe.min.js'
node tests/browser/portfolio.cjs
```

`PLAYWRIGHT_MODULE`, `AXE_MODULE`, `BROWSER_CHANNEL`, dan `BASE_URL` dapat disesuaikan. Browser suite menyimpan hasil aktual termasuk kegagalan bila dependency QA belum tersedia; tidak menganggap download dependency sebagai keberhasilan test.

Suite 27 pemeriksaan memakai bundle `index-pdZ_R49U`; smoke visual tambahan dan screenshot mobile/galeri terakhir memakai `index-DII7vVQy` setelah perubahan padding mobile saja. Raw JSON memisahkan keduanya melalui `lab.motionBundle` dan `finalVisualVerification`. Screenshot galeri final memakai reduced motion sementara untuk memastikan seluruh gambar dan metadata terlihat setelah transition; fixed overlays disembunyikan hanya untuk capture.

## Readiness rilis

Siap untuk review lokal dan staging. Tidak ada commit/push/merge/deploy. Perlu verifikasi Node/npm server dan artefak Vite, email/provider chatbot nyata, serta smoke test staging sebelum production. Pipeline telah mendapat langkah build dan fail-fast, tetapi deployment tetap memakai prosedur existing dan belum diuji di server.
