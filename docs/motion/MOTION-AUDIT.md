# Audit dan rencana konsolidasi

Baseline 30 Oktober 2026: Laravel 12, PHP >=8.2, Blade, vanilla JS, Tailwind 4, Vite 7. `npm run build`, `php artisan test` (2 tes), sintaks JS, dan 4 route berhasil sebelum perubahan.

## Temuan dan cakupan

- Blade memuat CSS/JS publik langsung; entry Vite lama tidak digunakan di halaman portfolio.
- Canvas lama memiliki loop tanpa jeda, 120 partikel; cursor menulis left/top pada setiap mousemove.
- Typewriter dimulai dari teks kosong. Reveal IntersectionObserver terpisah dari animasi CSS.
- Konfigurasi proyek sudah memiliki gambar nyata; kartu hanya menampilkan teks.
- Review visual menemukan nama file tiga gambar museum/presensi/koleksi tidak cocok dengan isinya. Pemetaan config dikoreksi berdasarkan gambar yang diperiksa, tanpa mengubah informasi proyek.
- Contact/captcha, chatbot, locale, theme, CV dan export adalah kontrak yang dipertahankan. Tidak ada perubahan schema/database.
- Pipeline deploy existing belum membangun Vite. Motion memerlukan manifest dan assets hasil build.

## Rencana sebelum edit

1. Kunci perilaku HTTP dengan regresi untuk halaman, locale, contact/captcha, chatbot validation.
2. Pertahankan script interaksi existing; hapus hanya reveal lama setelah pengganti GSAP terpasang.
3. Satu entry motion melalui Vite, satu konfigurasi, hero dan reveal bersama. Tidak menambah scroll hijacking atau framework.
4. Pakai gambar dan informasi dari config; pertahankan tombol/modal yang sama.
5. Pause rendering tersembunyi, responsif, reduced-motion dinamis, dan fallback tanpa JS.
6. Periksa build, regresi, browser multi-viewport, keyboard, export, dan degradasi sebelum laporan.

Perubahan menjangkau Blade, CSS, legacy JS, entry/config Vite, aset favicon/proyek, locale, tests dan build pipeline. Dokumentasi QA mencatat bukti akhir dan batas verifikasi.
