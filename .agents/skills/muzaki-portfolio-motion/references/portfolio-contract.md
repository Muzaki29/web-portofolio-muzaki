# Portfolio contract

## Identity and references

- Preserve black `#000000`, maroon `#A31D1D`, cream `#FEF9E1`, supporting tokens, installed fonts, and real portrait/project assets.
- Direction: premium editorial typography, restrained cinematic reveals, readable professional content, generous hierarchy. Avoid scroll hijacking, long preloaders, excessive pinning, heavy mobile rendering, and effects that obscure content.
- Study [Kemana](https://kemana.com/) for rhythm and [ValFX](https://valfx.framer.website/) for composition. These are inspiration, not assets or layouts to copy. Do not invent metrics, project outcomes, or career claims.

## Existing application

Laravel 12, PHP 8.2+, Blade, vanilla JS, Tailwind 4, Vite 7. Retain this stack. Inspect current versions rather than assuming this snapshot remains current.

Relevant files: `resources/views/portfolio.blade.php`, `public/css/portfolio.css`, `public/js/portfolio.js`, `public/js/portfolio-export.js`, `resources/js/motion/`, `resources/css/motion.css`, `vite.config.js`, `config/portfolio.php`, `routes/web.php`, and `tests/Feature/PortfolioRegressionTest.php`.

Preserve contact validation/CAPTCHA/throttling, chatbot, locale/theme switching, mobile navigation, skill tabs, project/certificate dialogs, CV download, PDF/PPT exports, external links, and factual descriptions. Avoid route/controller/schema changes unless the request requires them.

## Motion and verification

- Coordinated hero timeline; word/line masks with semantic heading intact; portrait entrance and limited desktop parallax.
- Consistent heading/card reveals; experience/timeline progress; project image entrances with restrained pointer hover and vertical mobile layout.
- Keep focus visible, dialogs keyboard usable, no-JS content readable, reduced motion static. Pause continuous canvas/marquee effects when hidden; avoid duplicate RAF loops.
- Performance goals: LCP <=2.5s, CLS <=0.1, INP <=200ms. Separate lab observations from field metrics; do not claim WCAG AA or production readiness from a screenshot/build alone.
- Record failures, unavailable integrations, and untested cases accurately. Use fake mail and blocked external HTTP in automated tests.
