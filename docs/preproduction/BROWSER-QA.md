# Browser QA pra-staging

Review memakai browser Chromium nyata melalui Playwright dengan runtime existing di user skill. Semua request contact sukses dipalsukan atau ditolak CAPTCHA; tidak ada email production dikirim. Request chatbot di-mock untuk menguji respons/error tanpa provider live.

## Bukti dan cara menjalankan

`tests/browser/portfolio.cjs` mempertahankan default output lama, dengan `OUTPUT_DIR` dan `SKIP_BASELINE` untuk review ini:

```powershell
$env:HEADLESS = '1'
$env:BASE_URL = 'http://127.0.0.1:8000'
$env:OUTPUT_DIR = 'docs/preproduction'
$env:SKIP_BASELINE = '1'
$env:AXE_MODULE = Join-Path $env:TEMP 'muzaki-motion-axe/node_modules/axe-core/axe.min.js'
node tests/browser/portfolio.cjs
```

Output aktual ada di `browser-results.json`; hasil lama tidak ditimpa. Screenshot `final-page-390/768/1024/1440/1920.png` mencakup seluruh section yang dimuat dan didecode. Capture full-page memakai reduced motion agar frame screenshot tidak berhenti di tengah entrance; motion dinamis diperiksa tersendiri. Capture galeri menyembunyikan overlay fixed hanya pada screenshot untuk keterbacaan konten.

Run pada **31 Oktober 2026**, bundle `index-mfD_PXk7.js`: **36/36 pemeriksaan lolos**, `pageErrors` dan `runtimeConsoleErrors` kosong. Axe 4.14.0 mendeteksi 0 violation pada kedua tema; contrast masih meminta review manual untuk 88 node per tema. OG/Twitter menunjuk PNG MAI asli 1536×1024, diambil hanya saat tes metadata, bukan sebagai dependency initial render. PDF/PPT benar-benar terunduh (4,386 / 92,027 bytes).

Tambahan regresi: malformed chatbot success, 429/500/network error, HTTP200 contact yang bukan sukses valid, retry PDF setelah CDN gagal, touch menu/chatbot, protokol dan opener protection link proyek, serta canvas benar-benar berhenti redraw saat reduced motion lalu berjalan kembali.

## Lighthouse

Lighthouse **13.6.0** dipasang hanya di folder TEMP dengan `--ignore-scripts`; tidak menambah dependency aplikasi. Chrome existing dipakai headless. Laporan JSON/HTML desktop dan mobile disimpan di direktori ini.

Baseline sebelum perbaikan review: desktop Performance 67 / Accessibility 94 / Best Practices 100 / SEO 100, LCP 6.0s, TBT 10ms, CLS 0.02. Mobile Performance 53 / Accessibility 94 / Best Practices 100 / SEO 100, LCP 35.7s, TBT 390ms, CLS 0.005. Payload sekitar 9.6MB, terutama sertifikat eager dan gambar resume PNG. Laporan baseline dipertahankan sebagai `lighthouse-*-baseline.report.json`.

Temuan Lighthouse yang diteruskan untuk perbaikan: contrast badge AI putih/merah 3.76:1, urutan heading service h1→h3, landmark main belum tersedia, serta eager certificate image dan resume berukuran berlebih. Angka ini adalah simulasi lab localhost, bukan field CWV atau jaminan performa hosting. INP field tidak diukur.

Post-fix pertama pada 31 Oktober: desktop Performance 94 / Accessibility 100 / Best Practices 100 / SEO 100; LCP 1.2s, TBT 120ms, CLS 0.019. Mobile Performance 57 / Accessibility 100 / Best Practices 100 / SEO 100; LCP 6.1s, TBT 540ms, CLS 0. Payload kedua run turun menjadi 949KiB. Nilai mobile belum memenuhi target performa; laporan dan `performance-summary.json` menyimpan nilai aktual, tidak menyamakan skor accessibility otomatis dengan sertifikasi.

Run mobile final setelah portrait tidak lagi diawali clipping (bundle `index-CO5ywtT5.js`): **Performance 54 / Accessibility 100 / Best Practices 100 / SEO 100**, FCP 2.4s, LCP 6.1s, Speed Index 5.2s, TBT 680ms, CLS 0, payload 949KiB. Tidak ada runtime error atau run warning. Hasil ini tidak membuktikan perbaikan Lighthouse dari perubahan entrance tersebut; variasi antar satu run tidak boleh diperlakukan sebagai benchmark stabil. File final adalah `lighthouse-mobile.report.json/html`; hasil mobile sebelum perubahan entrance disimpan di `lighthouse-mobile-before-hero.report.json`.

Sumber LCP final tetap portrait hero. Pada run mobile pertama setelah optimasi gambar, breakdown observed menunjukkan TTFB sekitar 1.02s dan render delay sekitar 0.945s; main-thread didominasi style/layout dan module motion. Font/icon eksternal dan CSS publik juga berada dalam critical chain. Nilai simulasi ini belum memenuhi target LCP 2.5s dan TBT masih tinggi; verifikasi pada staging dengan kompresi/cache server nyata diperlukan. TBT adalah metric lab tersendiri, bukan pengganti INP field; tidak ada klaim bahwa target INP telah tercapai.

## Smoke perubahan terakhir

`hero-smoke-results.json` mencatat **5/5 viewport lolos** pada bundle `index-CO5ywtT5.js`, setelah guard typewriter terakhir: portrait mobile tetap paintable tanpa clipping awal, semua nama muat di viewport, tidak ada overflow horizontal, reduced motion menampilkan profesi statis dan menghentikan redraw canvas, kemudian heading tetap terlihat saat preferensi dipulihkan. Tidak ada page error. Screenshot `final-hero-390/768/1024/1440/1920.png` adalah capture terakhir untuk hero. CAPTCHA tidak lagi menampilkan token literal `{question}`.

Probe fresh-context sempat menemukan `matchMedia(...).matches` berubah tanpa sinkronisasi listener legacy yang teramati, sehingga teks typewriter membeku parsial. Tidak disimpulkan sebagai bug hardware/browser. Guard timer kini mengembalikan teks statis ketika reduced motion aktif; smoke final memeriksa hasil render dan canvas redraw, bukan hanya atribut `hidden` internal. Perangkat fisik dan peralihan preferensi OS nyata tetap belum diuji.

External project link GET tanpa autentikasi dicatat terpisah di `external-link-checks.json`: empat URL GitHub 404 (mungkin private), SyahduLab DNS gagal, portal Muspen 503; URL lain 200. Ini bukan bukti repository dihapus atau tautan harus diganti. Tidak ada URL pengganti yang dikarang dan tidak ada request yang mengubah layanan remote.

## Batas

Perangkat touch fisik, screen reader manual, layanan email/chatbot live, remote server/staging, dan traffic pengguna tidak diuji oleh suite ini. Periksa hasil final aktual sebelum keputusan rilis; zero violation otomatis tidak sama dengan sertifikasi WCAG.
