# Arsitektur motion yang diimplementasikan

## Pemilik dan entrypoint

| File | Tanggung jawab |
|---|---|
| `resources/views/portfolio.blade.php` | HTML semantik, real project config, favicon, satu entry Vite, script interaksi existing |
| `public/css/portfolio.css` | Token, layout dan style existing; aturan legacy yang menyembunyikan hero-name dihapus |
| `resources/css/motion.css` | Layer editorial/responsif, hover, reduced motion dan aksesibilitas |
| `resources/js/motion/index.js` | Register ScrollTrigger, init idempotent, matchMedia, refresh setelah layout berubah, visibility dan cleanup |
| `resources/js/motion/config.js` | Timing, easing, threshold, responsive/reduced-motion queries |
| `resources/js/motion/hero.js` | Timeline hero, desktop parallax dan atmosfer |
| `resources/js/motion/scroll-reveal.js` | Reveal section/cards/images dan progress timeline |
| `public/js/portfolio.js` | Menu, theme, skills, form, chatbot, modal, canvas, typewriter, cursor; reveal lama dihapus |
| `public/js/portfolio-export.js` | PDF/PPT text export existing; library dimuat hanya saat klik, timeout/retry |

## Lifecycle

Entry Vite dimuat sebagai module setelah parsing HTML. `gsap.matchMedia` mencatat scene dan mengembalikan inline style saat breakpoint atau preferensi berubah. Kondisi `all` memastikan mobile juga diinisialisasi. `pagehide` melakukan revert dan membatalkan listener; `pageshow` menginisialisasi kembali untuk bfcache. HMR memakai cleanup yang sama.

Refresh ScrollTrigger terjadi setelah font, gambar, load dan perubahan skills panel. Resize bawaan ScrollTrigger dipakai tanpa listener resize tambahan. Reveal hanya mulai ketika elemen memasuki viewport (`immediateRender:false`) sehingga elemen di luar layar tidak dibiarkan transparan. `clearProps` mengembalikan kepemilikan hover ke CSS setelah entrance.

Legacy script tetap IIFE sekali per dokumen. Canvas dan typewriter memiliki satu loop/timer aktif dengan sinkronisasi visibility dan reduced motion. Cursor hanya fine-pointer dan hover, memakai requestAnimationFrame/translate. Native pointer tidak disembunyikan. Menu/modal memakai kontrol native dan event keyboard; dialog tertutup `inert`.

## Kontrak fitur

Tidak ada controller, schema, migration, captcha, throttle, kredensial atau API provider yang diubah. Project config, konten pengalaman, locale dan export data tetap sumber existing. Chatbot kini merender input sebagai textContent; markdown response di-escape dan URL dibatasi http/https/mailto. Contact mendapatkan label dan method/action POST agar data pribadi tidak masuk query URL jika JS mati.

## Build dan rilis

`npm ci --include=dev` lalu `npm run build` menghasilkan `public/build/manifest.json` dan hashed motion CSS/JS. Blade menggunakan `@vite('resources/js/motion/index.js')`; manifest wajib tersedia. Public CSS dan legacy script tetap satu kali dimuat. PDF/PPT CDN tidak dimuat dua kali dan tidak mengganggu initial load.

Workflow deploy disiapkan untuk menjalankan kedua perintah npm sebelum cache Laravel, tetapi **tidak dijalankan**. Server memerlukan Node yang kompatibel Vite 7, npm dan akses registry. Bila runtime Node tidak tersedia di server, bangun artefak di CI dan kirim `public/build` sebagai rencana rilis terpisah. Deployment production belum diverifikasi.
