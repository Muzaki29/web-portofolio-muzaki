# Muzaki — cinematic portfolio

Arah: **cinematic editorial**, hitam `#000000`, maroon `#A31D1D`, cream `#FEF9E1`. Nama bertingkat Syne, portrait studio existing, ritme section lapang dan showcase proyek nyata. DFII desain: impact 4 + context 5 + feasibility 5 + performance 4 − consistency risk 3 = 15. Font body, serif pengalaman, dan monospace mengikuti font existing.

## Sistem yang digunakan

- GSAP + ScrollTrigger dibundle Vite, tanpa React, WebGL, SplitText, smooth-scroll library, atau scroll hijacking.
- Hero: reveal portrait, masked line reveal H1 semantik, kicker/CTA/nav bertahap, atmosfer maroon. Parallax dan pergerakan atmosfer hanya desktop; atmosfer berhenti saat hero keluar layar.
- About/resume, pengalaman, timeline, skills, sertifikasi, contact dan footer memakai bahasa gerak yang sama.
- Proyek: gambar existing dari config, WebP, featured project pertama, stagger desktop, image entrance dan hover scale. Tidak menambah klaim hasil bisnis.
- Typewriter memiliki teks statis dan nama profesi tetap untuk pembaca layar. Particle canvas dibatasi 30fps dan maksimal 60 partikel; pause ketika tab tersembunyi atau reduced motion aktif.
- Keyboard: menu Escape, tab skills Arrow/Home/End, dialog focus trap/restore, tombol sertifikat nyata, skip link dan focus visible.
- Logo kiriman pengguna dikemas sebagai ICO/PNG favicon 16/32/192 dan Apple touch icon. Tidak memakai SVG lama agar browser tidak memilih ikon berbeda.

## Pengaturan

`resources/js/motion/config.js`: duration 0.8s, hero 1.15s, power3.out, stagger 0.09s, distance 32px, trigger top 92%, desktop >=1024px. Mobile memakai jarak 16px tanpa parallax/ambient continuous. CSS hover memakai easing bersama.

`prefers-reduced-motion` dipantau dinamis: konteks GSAP dikembalikan ke style awal, canvas/cursor/typewriter/marquee dihentikan. Seluruh teks tetap ada dalam HTML. Tanpa JS, navigasi, semua skill panel dan detail proyek terlihat; contact menggunakan POST native (response JSON existing), CV dan email tetap tersedia.

## Referensi

[Kemana](https://kemana.com/) dan [ValFX](https://valfx.framer.website/) dipakai sebagai referensi hierarki/ritme, tanpa mengambil aset atau layout. Implementasi mengikuti [GSAP matchMedia](https://gsap.com/docs/v3/GSAP/gsap.matchMedia()/) dan [ScrollTrigger](https://gsap.com/docs/v3/Plugins/ScrollTrigger/). Skill GSAP terpasang diperiksa sebelum dipakai; workflow proyek ada di `.agents/skills/muzaki-portfolio-motion`.

Lihat `MOTION-ARCHITECTURE.md`, `MOTION-QA.md`, dan `MOTION-IMPLEMENTATION-SUMMARY.md` untuk lifecycle, bukti pengujian, dan batas rilis.
