---
name: muzaki-portfolio-motion
description: Implement or review cinematic GSAP motion in Muzaki's Laravel Blade portfolio while preserving branding, integrations, responsive layouts, and accessible static content.
---

# Muzaki Portfolio Motion

Read [the project contract](references/portfolio-contract.md) before editing; use [the skills guide](../../../CODEX-SKILLS-GUIDE.md) for installation paths and verification limits.

- Inspect current Blade, CSS, motion entrypoint, legacy interactions, export code, and relevant tests before choosing effects. Existing implementation and project conventions take precedence over recipes.
- Select only needed installed skills: `gsap-vanilla` for lifecycle, `gsap-core`/`gsap-timeline` for orchestration, `gsap-scrolltrigger` for scroll scenes, `gsap-performance` for rendering, and `animaxxing` for a specific effect. Read their `SKILL.md` under `C:/Users/muzak/.agents/skills/`; load relevant references only. Use `gsap-plugins` only for plugins actually needed.
- Keep one owner per animated element. Centralize timing in existing configuration, bundle with Vite, preserve static HTML, and clean up timelines, triggers, observers, listeners, and temporary styles. Handle preference changes, resize, hidden tabs, and bfcache restoration.
- Treat hero, About/Experience, projects, skills/certificates, and contact/footer as one visual rhythm. Keep full professional content and controls usable without animation; simplify mobile/touch motion and provide static reduced-motion alternatives.
- Use `ui-ux-pro-max` or `web-design-guidelines` for relevant design/review tasks. Use `spatie-laravel-php` for PHP/Blade with existing conventions. Never apply `style-animaxxing` unless its separate art direction is explicitly requested.
- Verify changed behavior through Laravel tests and browser checks. Run `npm run build`, `php artisan test`, and JS syntax checks. Inspect 390, 768, 1024, 1440, and 1920px, keyboard, reduced-motion changes, repeat scrolling, theme/locale, modals, and exports when affected. Use connected browser tools or `agent-browser` with an isolated named session. Save useful screenshots and report exact gaps.
- Document the actual implemented system in `docs/motion/`. Report files, checks, risks, and readiness. Do not push, merge, deploy, change production, or add unrelated packages as part of this workflow.
