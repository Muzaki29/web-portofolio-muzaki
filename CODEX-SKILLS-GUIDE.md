# Codex Skills Library — Muzaki

Audit/setup: **30 Oktober 2026**. Setup ini menambahkan tooling tanpa mengubah desain, Composer, MCP, atau konfigurasi Codex. Perubahan website dalam working tree berasal dari task portfolio sebelumnya.

## Inventory dan invocation

Root global: `C:/Users/muzak/.agents/skills/<nama>/SKILL.md`. Root project: `.agents/skills/muzaki-portfolio-motion/`. Existing `.codex/skills` tetap dipertahankan; tidak ada existing skill ditimpa/dihapus.

| Status | Invocation | Source | Scope/path |
|---|---|---|---|
| Installed | `$gsap-core` | [GreenSock](https://github.com/greensock/gsap-skills/tree/main/skills/gsap-core) | Global/gsap-core |
| Installed | `$gsap-timeline` | [GreenSock](https://github.com/greensock/gsap-skills/tree/main/skills/gsap-timeline) | Global/gsap-timeline |
| Installed | `$gsap-scrolltrigger` | [GreenSock](https://github.com/greensock/gsap-skills/tree/main/skills/gsap-scrolltrigger) | Global/gsap-scrolltrigger |
| Installed | `$gsap-plugins` | [GreenSock](https://github.com/greensock/gsap-skills/tree/main/skills/gsap-plugins) | Global/gsap-plugins |
| Installed | `$gsap-performance` | [GreenSock](https://github.com/greensock/gsap-skills/tree/main/skills/gsap-performance) | Global/gsap-performance |
| Installed | `$gsap-vanilla` | [Animaxxing](https://github.com/johnpolacek/animaxxing-skills/tree/main/skills/gsap-vanilla) | Global/gsap-vanilla |
| Installed | `$animaxxing` | [Animaxxing](https://github.com/johnpolacek/animaxxing-skills/tree/main/skills/animaxxing) | Global/animaxxing |
| Installed | `$spatie-laravel-php` | [Spatie](https://github.com/spatie/guidelines-skills/tree/main/resources/boost/skills/spatie-laravel-php) | Global/spatie-laravel-php |
| Installed | `$agent-browser` | [Vercel](https://github.com/vercel-labs/agent-browser/tree/main/skills/agent-browser) | Global/agent-browser |
| Created | `$muzaki-portfolio-motion` | [Project workflow](.agents/skills/muzaki-portfolio-motion/SKILL.md) | Project |
| Already present | `$ui-ux-pro-max` | [UI UX Pro Max](https://github.com/nextlevelbuilder/ui-ux-pro-max-skill) | Global `.agents/skills` dan `.codex/skills` |
| Already present | `$web-design-guidelines` | [Vercel Agent Skills](https://github.com/vercel-labs/agent-skills) | Global `.agents/skills` |
| Already present | `$laravel-expert`, `$laravel-security-audit`, `$browser-automation` | Existing local skills | Global `.agents/skills` |
| Already present | `$using-superpowers` | [Superpowers](https://github.com/obra/superpowers) | Global `.codex/skills` |

Anti Slop plugin sudah tersedia melalui catalog sesi: `antislop:antislop` dan code/copywriting/human/layoutmobile/ui. Existing `ai-slop-cleaner` tetap dipertahankan. Tidak diinstal ulang. Nama duplicate `ui-ux-pro-max` perlu path eksplisit bila beberapa pilihan tampil; jangan hapus sebelum memeriksa pemilik instalasi.

## Audit sumber

Semua sembilan repository dapat diakses dan tidak archived. Waktu push dan daftar path SKILL.md aktual tercatat di [source-audit.json](.agents/skills/muzaki-portfolio-motion/references/source-audit.json); aktivitas terbaru bukan jaminan keamanan. README, skill terpilih, lisensi, manifest, dan installer entrypoint relevan diperiksa sebagai data tidak dipercaya. Tidak ada remote shell installer dijalankan.

| Repository | Keputusan dan nama aktual | Lisensi/runtime |
|---|---|---|
| [greensock/gsap-skills](https://github.com/greensock/gsap-skills) | Lima prioritas dipasang. `gsap-utils`, `gsap-frameworks`, `gsap-react` tersedia tetapi tidak diperlukan | MIT untuk skill; GSAP aplikasi punya lisensi sendiri. Markdown tanpa executable |
| [johnpolacek/animaxxing-skills](https://github.com/johnpolacek/animaxxing-skills) | `gsap-vanilla`, `animaxxing` dipasang. `style-animaxxing`, `animaxxing-webgl`, dan framework lain dilewati | MIT; beberapa resep membutuhkan GSAP/plugin versi tertentu, tidak otomatis menambah dependency website |
| [nextlevelbuilder/ui-ux-pro-max-skill](https://github.com/nextlevelbuilder/ui-ux-pro-max-skill) | Existing `ui-ux-pro-max` dipertahankan. Upstream juga menyediakan design, brand, design-system, banner-design, slides, ui-styling | MIT; Python untuk search. CLI kini `ui-ux-pro-max-cli`/`uipro`, paket `uipro-cli` lama stale. Generator tidak dijalankan |
| [vercel-labs/agent-skills](https://github.com/vercel-labs/agent-skills) | Existing `web-design-guidelines` digunakan. React/composition/React Native/deployment/token skills dilewati | README menyatakan MIT; root LICENSE tidak ditemukan di snapshot. Tidak download ulang existing skill |
| [vercel-labs/agent-browser](https://github.com/vercel-labs/agent-browser) | Skill dan runtime npm **0.41.0** dipasang; skill kini stub yang memuat panduan melalui CLI | Apache-2.0. Windows x64 binary packaged bekerja; `npm install -g agent-browser@0.41.0 --ignore-scripts` |
| [laravel/boost](https://github.com/laravel/boost) | Evaluasi saja: package dev Composer + MCP, membutuhkan perubahan dependency/config aplikasi di luar setup ini | MIT; PHP ^8.2, Illuminate ^11.45.3 / ^12.41.1 / ^13, laravel/mcp dan roster. Perlu Composer solver terpisah; tidak upgrade Laravel |
| [spatie/guidelines-skills](https://github.com/spatie/guidelines-skills) | `spatie-laravel-php` dipasang tanpa Composer. spatie-javascript/security/version-control tersedia tetapi overlap | MIT; Markdown/reference tidak memerlukan Boost. Alternatif Composer mendukung Illuminate 10–13 |
| [obra/superpowers](https://github.com/obra/superpowers) | Bootstrap existing ditemukan. Tambahan systematic-debugging/verification/etc. dilewati karena overlap OMX/Ponytail | MIT; upstream kini merekomendasikan plugin marketplace Codex. Tidak menambah hooks/plugin atau mengganti AGENTS |
| [openai/skills](https://github.com/openai/skills) | README kini **deprecated**, mengarah ke [openai/plugins](https://github.com/openai/plugins). Bundled skill-installer/skill-creator tersedia | Lisensi per folder `LICENSE.txt`. Tidak menduplikasi browser/PDF/deployment skills |

Animaxxing, UI UX Pro Max, Spatie, dan Superpowers adalah sumber maintainer masing-masing; tidak semua merupakan official vendor GSAP/OpenAI.

## Environment dan instalasi

Terverifikasi: Codex CLI 0.166.0-alpha.5.1, Node 24.11.1, npm/npx 11.6.2, PHP 8.3.16, Composer 2.8.9, Git 2.49.0.windows.1, Python 3.13.0. Sebelum setup ditemukan 300 entrypoint skill `.agents/skills` dan 35 `.codex/skills`. Nama MCP dibaca tanpa mencetak token/argumen rahasia; Chrome DevTools dan node_repl sudah tersedia. Tidak mengubah MCP.

README GSAP/Animaxxing mendukung copy folder dan npx skills. Instalasi ini menggunakan bundled `skill-installer` dengan `--dest C:/Users/muzak/.agents/skills`; helper menolak destination existing. Source berasal dari `main` saat setup, hash lokal dicatat dalam inventory. Installer tidak menjalankan script dari source repository.

Alternatif resmi untuk mesin baru (cek help versi CLI sebelum menjalankan):

```powershell
npx skills add greensock/gsap-skills --agent codex --global --skill gsap-core gsap-timeline gsap-scrolltrigger gsap-plugins gsap-performance
npx skills add johnpolacek/animaxxing-skills --agent codex --global --skill gsap-vanilla animaxxing
npx skills add spatie/guidelines-skills --agent codex --global --skill spatie-laravel-php
```

Jangan menjalankan ulang bulk installer pada mesin ini tanpa memeriksa duplicate. Runtime agent-browser diinstal user scope npm `C:/Users/muzak/AppData/Roaming/npm`; postinstall dibaca tetapi **tidak dijalankan**. Binary packaged tersedia sehingga tidak perlu download tambahan/Rust/pnpm/admin. Build dari source baru membutuhkan Rust, pnpm 11+, Node 24+.

## Verifikasi dan batas discovery

- Sembilan folder global memiliki SKILL.md dan nama frontmatter sesuai; semua relative Markdown reference yang diperiksa tersedia. Folder GSAP/Animaxxing/Spatie terpilih tidak memiliki executable script.
- Custom skill lolos bundled `quick_validate.py` dan references tersedia.
- Read-only invocation `agent-browser skills get core` berhasil. Named session `muzaki-skills-smoke-20261030` membuka halaman `data:` lokal, snapshot menemukan heading yang diharapkan, kemudian browser ditutup. Tidak mengakses profile browser pengguna.
- Catalog turn aktif tidak bisa dipaksa refresh lewat copy file. Coba invocation pada **turn berikutnya**; bila belum muncul, buka chat/sesi baru atau reload Codex. Lokasi sudah mengikuti root yang dipakai environment ini, tetapi belum mengklaim picker UI telah menampilkan skill baru.
- Stub upstream `agent-browser` memiliki `hidden: true`; bisa tidak tampil pada picker. Path eksplisit tetap dapat dibaca. Validator bundled versi lama menolak metadata tambahan ini; metadata vendor tidak diubah hanya demi validator.

## Pemakaian dan contoh

Gunakan kombinasi yang relevan, jangan memuat semua skill untuk setiap task.

| Kebutuhan | Kombinasi |
|---|---|
| Premium website | ui-ux-pro-max; web-design-guidelines untuk review |
| Cinematic Blade | muzaki-portfolio-motion memilih GSAP/Animaxxing sesuai efek |
| Laravel/PHP | laravel-expert + spatie-laravel-php; security audit bila relevan |
| Accessibility | web-design-guidelines + keyboard/reduced-motion browser checks; existing fixing-accessibility tersedia |
| Browser QA | agent-browser atau connected Chrome DevTools, named session terisolasi |
| Refactoring | Ponytail + cleanup/debugging existing yang relevan + regression tests |

```text
$gsap-core periksa tween dan reduced-motion di resources/js/motion.
$gsap-timeline rapikan sequencing hero tanpa mengubah konten.
$gsap-scrolltrigger review trigger, resize, dan cleanup scroll scenes.
$gsap-plugins evaluasi apakah efek ini perlu plugin atau cukup CSS.
$gsap-performance audit layout thrashing dan continuous animation.
$gsap-vanilla periksa lifecycle Blade/Vite dan bfcache restoration.
$animaxxing tambahkan image reveal project card dengan brand existing.
$spatie-laravel-php review controller ini, utamakan konvensi repository.
$agent-browser lakukan read-only visual QA localhost dengan session terpisah.
$muzaki-portfolio-motion poles hero/project showcase; pertahankan form, chatbot, ekspor.
```

Contoh runtime PowerShell:

```powershell
$env:AGENT_BROWSER_SESSION = 'muzaki-qa-example'
agent-browser skills get core
agent-browser open http://127.0.0.1:8000
agent-browser snapshot -i
agent-browser close
```

System Chrome digunakan otomatis jika tersedia. `agent-browser install` hanya diperlukan bila tidak ada browser yang sesuai; smoke test sudah berhasil tanpa instalasi browser tambahan.

## Update, removal, troubleshooting

- **Update:** catat source/hash/version sekarang; download candidate ke staging, baca diff README/SKILL/references/scripts, validasi, lalu ganti hanya skill terpilih. Jangan overwrite seluruh `.agents` atau custom workflow. Helper bundled menolak existing target; gunakan `--dest` staging. Review versi runtime sebelum `npm install -g agent-browser@<versi> --ignore-scripts` dan ulang smoke.
- **Removal:** verifikasi path absolut berada tepat pada `C:/Users/muzak/.agents/skills/<nama-terpilih>`, lalu hapus folder itu melalui Explorer atau `Remove-Item -LiteralPath '<path-yang-sudah-diverifikasi>' -Recurse`. Custom workflow hanya di project `.agents/skills/muzaki-portfolio-motion`. `npm uninstall -g agent-browser` menghapus runtime, tidak menghapus Chrome/data pengguna. Tidak ada removal dijalankan saat setup.
- **Tidak muncul:** cek SKILL.md/name/description, root, duplicate, setting disabled, lalu turn/sesi baru. Jangan menampilkan config/token mentah. Path eksplisit dapat dipakai sementara.
- **Command tidak ditemukan:** terminal baru untuk npm user PATH, atau `C:/Users/muzak/AppData/Roaming/npm/agent-browser.cmd`. Tidak perlu mengubah machine PATH/admin.
- **Browser gagal:** baca core guide, named session, cek executable Chrome. Close hanya session sendiri. Jangan attach profile browser pengguna tanpa kebutuhan/otorisasi.
- **Konflik instruksi:** user/repository lebih tinggi prioritasnya. style-animaxxing tidak dipasang; resep contoh tidak boleh mengganti warna/font portfolio.

Status akhir: 9 skill global baru + 1 custom project workflow; existing dipertahankan; tidak ada instalasi gagal, push, atau deploy. Manual follow-up hanya pembuktian picker sesi baru dan keputusan Boost sebagai perubahan aplikasi terpisah.
