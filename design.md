# Design System — Muzaki Abdullah Irsyad Portfolio

Dokumen referensi visual dan teknis untuk website portfolio pribadi.  
Stack: Laravel (Blade) · CSS murni · JavaScript vanilla.

---

## 1. Visi & Referensi

Portfolio ini menampilkan profil profesional **Muzaki Abdullah Irsyad** — Full-Stack Web Developer, IT Support, dan lulusan Teknik Informatika.

**Referensi layout:** [DevSync — Framer Template](https://devsync.framer.website/) (Grabui Library)  
**Referensi palet:** [Color Hunt — Maroon palette](https://colorhunt.co/palette/fef9e1e5d0aca31d1d6d2323)

Prinsip desain:
- Typography bold & editorial (nama besar di hero)
- Aksen maroon konsisten di kedua mode
- Glassmorphism ringan pada kartu
- Label section format DevSync: `// Section` + judul dengan suffix ` />`
- Marquee nama, stats row, dan hero portrait workspace

---

## 2. Palet Warna (Brand)

| Token | Hex | Peran |
|-------|-----|-------|
| `--cream` | `#fef9e1` | Teks utama, nav pill, highlight |
| `--blush` | `#e5d0ca` | Teks sekunder, subheading |
| `--maroon` | `#a31d1d` | Aksen utama, CTA, glow, giant text |
| `--maroon-dark` | `#6d2323` | Background light mode, depth |
| `--maroon-deep` | `#4a1515` | Gradient hero light mode |

---

## 3. Dual Theme

Toggle tema via tombol moon/sun di navbar. Preferensi disimpan di `localStorage.theme`.

### Dark Mode (Default) — **Hitam**

| Token | Nilai | Keterangan |
|-------|-------|------------|
| `--bg` | `#000000` | Background halaman |
| `--bg2` | `#0a0a0a` | Section alternatif |
| `--card` | `#111111` | Input, modal surface |
| `--glass` | `rgba(17,17,17,0.72)` | Kartu glassmorphism |
| `--navbg` | `rgba(0,0,0,0.92)` | Navbar saat scroll |
| `--txt` | `#fef9e1` | Body text |
| `--txt2` | `#e5d0ca` | Muted text |
| `--border` | `rgba(255,255,255,0.08)` | Border halus |

Hero: gradient hitam + glow maroon subtle.

### Light Mode — **Maroon**

| Token | Nilai | Keterangan |
|-------|-------|------------|
| `--bg` | `#6d2323` | Background halaman |
| `--bg2` | `#5a1c1c` | Section alternatif |
| `--card` | `rgba(74,21,21,0.92)` | Surface kartu |
| `--glass` | `rgba(109,35,35,0.55)` | Kartu glassmorphism |
| `--navbg` | `rgba(109,35,35,0.94)` | Navbar saat scroll |
| `--txt` | `#fef9e1` | Body text (cream on maroon) |
| `--txt2` | `#e5d0ca` | Muted text |

Hero: gradient maroon penuh + glow accent.

> **Catatan:** Variabel legacy `--blue` / `--blue2` dipetakan ke maroon/cream agar kompatibel dengan kode existing.

---

## 4. Tipografi

| Peran | Font | Weight | Contoh |
|-------|------|--------|--------|
| Display / Heading | **Syne** | 700–800 | Hero giant name, section titles |
| Body | **Inter** | 400–800 | Paragraf, kartu, form |
| Mono / Label | **Fira Code** | 400–500 | `// labels`, dates, captcha |

```html
<!-- Google Fonts -->
Syne:wght@700;800
Inter:wght@300;400;500;600;700;800;900
Fira+Code:wght@400;500
```

**Section title pattern:**
```html
<span class="sec-label">About</span>
<h2 class="section-title">Sedikit tentang saya</h2>
<!-- CSS menambahkan " />" setelah judul -->
```

---

## 5. Spacing & Radius

| Token | Nilai | Penggunaan |
|-------|-------|------------|
| `--r` | `8px` | Button, input |
| `--r2` | `14px` | Kartu kecil |
| `--r3` | `22px` | Modal, form split |
| Section padding | `100px 0` | Semua `<section>` |
| Container max-width | `1200px` | `.container` |

---

## 6. Komponen Utama

### Navbar
- Fixed top, transparan → solid saat scroll
- Brand: uppercase letter-spacing (`MUZAKI ABDULLAH IRSYAD`)
- Nav pill cream dengan link maroon
- Lang toggle ID/EN + theme toggle

### Hero (Full-bleed)
- Background image: `public/images/profil_hero.png` (crop upper-body, 1600×1091)
- Full viewport (`100svh`) dengan overlay gradient kiri→kanan
- Giant text `MUZAKI` di kanan (watermark)
- Intro + CTA overlay kiri
- Stats bar glass di bagian bawah hero
- Service cards dipindah ke strip setelah marquee

### Buttons
| Class | Style |
|-------|-------|
| `.btn-primary` | Gradient maroon → maroon-dark, text cream |
| `.btn-outline` | Transparent, border cream/maroon |

### Kartu
- `.project-grid-card`, `.exp-card`, `.sk-card`, `.cert-card`
- Glass background + border `--border`
- Hover: `--border2` + `--blue-glow` shadow

### Modal
- Project detail modal (`.project-modal`)
- Certificate lightbox (`.cert-lightbox`)

### Chatbot
- Floating bubble kanan bawah
- Avatar: `public/images/profil.png`

---

## 7. Aset Gambar

| File | Penggunaan |
|------|------------|
| `public/images/profil_hero.png` | Hero section — workspace portrait |
| `public/images/profil.png` | Chatbot avatar, fallback |
| `public/images/project_*.png` | Preview kartu proyek |
| `public/images/certificates/*` | Sertifikasi |
| `public/images/cta_invitation_banner.png` | Contact form visual |
| `public/cv/muzaki-abdullah-irsyad.pdf` | Download CV |

---

## 8. Struktur File Desain

```
resources/views/portfolio.blade.php   # HTML utama (single page)
public/css/portfolio.css              # Semua style + CSS variables
public/js/portfolio.js                # Interaksi (theme, typewriter, modal, chatbot)
config/portfolio.php                  # Data proyek (works_projects)
lang/id.json · lang/en.json           # i18n
design.md                             # Dokumen ini
```

---

## 9. i18n

Bahasa: **Indonesia (default)** · **English**  
Switch via `/locale/{locale}` — link ID/EN di navbar.

---

## 10. Checklist Implementasi Fitur Baru

Saat menambah section atau komponen:

1. Gunakan token CSS (`var(--maroon)`, `var(--cream)`, dll.) — jangan hardcode hex baru tanpa update dokumen ini
2. Uji di **dark (hitam)** dan **light (maroon)**
3. Label section pakai `.sec-label` + `.section-title`
4. Pastikan kontras teks memenuhi readability (cream on black / cream on maroon)
5. Bump cache query `?v=` di `portfolio.blade.php` setelah ubah CSS

---

## 11. Changelog Desain

| Versi | Tanggal | Perubahan |
|-------|---------|-----------|
| 3.0 | Jul 2026 | Redesign DevSync + palet maroon |
| 3.1 | Jul 2026 | Hero image `profil_hero.png` |
| 3.3 | Jul 2026 | Full-bleed hero + crop `profil_hero.png` |

---

*Maintainer: Muzaki Abdullah Irsyad · [muzakiabdullahirsyad.my.id](https://muzakiabdullahirsyad.my.id)*
