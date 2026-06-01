/**
 * Portfolio JS — Muzaki Abdullah Irsyad
 * Premium Interactive Edition 2025
 */

'use strict';

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ═══════════════════════════════════════
   1. PARTICLE CANVAS BACKGROUND
═══════════════════════════════════════ */
function initParticles() {
    const canvas = document.getElementById('bg-canvas');
    if (!canvas || prefersReducedMotion) return;

    const ctx = canvas.getContext('2d');
    let particles = [];
    let animFrame;

    function resize() {
        canvas.width  = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    function createParticles() {
        particles = [];
        const count = Math.floor((canvas.width * canvas.height) / 18000);
        for (let i = 0; i < count; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                r: Math.random() * 1.5 + 0.3,
                dx: (Math.random() - 0.5) * 0.3,
                dy: (Math.random() - 0.5) * 0.3,
                opacity: Math.random() * 0.5 + 0.15,
            });
        }
    }

    function getAccentColor() {
        const theme = document.documentElement.getAttribute('data-theme');
        return theme === 'light' ? '37,99,235' : '59,130,246';
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        const color = getAccentColor();

        // Draw particles
        particles.forEach(p => {
            p.x += p.dx;
            p.y += p.dy;

            if (p.x < 0) p.x = canvas.width;
            if (p.x > canvas.width) p.x = 0;
            if (p.y < 0) p.y = canvas.height;
            if (p.y > canvas.height) p.y = 0;

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(${color},${p.opacity})`;
            ctx.fill();
        });

        // Draw lines between nearby particles
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 120) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = `rgba(${color},${0.12 * (1 - dist / 120)})`;
                    ctx.lineWidth = 0.7;
                    ctx.stroke();
                }
            }
        }

        animFrame = requestAnimationFrame(draw);
    }

    resize();
    createParticles();
    draw();

    window.addEventListener('resize', () => {
        cancelAnimationFrame(animFrame);
        resize();
        createParticles();
        draw();
    });
}

/* ═══════════════════════════════════════
   2. TYPEWRITER EFFECT
═══════════════════════════════════════ */
function initTypewriter() {
    const textEl   = document.getElementById('typewriter-text');
    const prefixEl = document.getElementById('typewriter-prefix');
    if (!textEl) return;

    if (prefixEl) prefixEl.textContent = 'I am a ';

    const phrases = [
        'Full-Stack Web Developer',
        'Laravel & PHP Engineer',
        'IT Support Specialist',
        'Informatics Educator',
        'Open Source Enthusiast',
    ];

    let phraseIdx = 0;
    let charIdx   = 0;
    let deleting  = false;
    let pauseTimer;

    function type() {
        const current = phrases[phraseIdx];

        if (!deleting) {
            textEl.textContent = current.slice(0, charIdx + 1);
            charIdx++;
            if (charIdx === current.length) {
                deleting = true;
                pauseTimer = setTimeout(type, 1800);
                return;
            }
        } else {
            textEl.textContent = current.slice(0, charIdx - 1);
            charIdx--;
            if (charIdx === 0) {
                deleting  = false;
                phraseIdx = (phraseIdx + 1) % phrases.length;
            }
        }

        const speed = deleting ? 45 : 90;
        pauseTimer  = setTimeout(type, speed);
    }

    type();
}

/* ═══════════════════════════════════════
   3. SCROLL REVEAL — INTERSECTION OBSERVER
═══════════════════════════════════════ */
function initScrollReveal() {
    const selector = '.reveal, .reveal-left, .reveal-scale';
    const elements = document.querySelectorAll(selector);

    if (!elements.length) return;

    const obs = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                const delay = entry.target.style.transitionDelay || '0s';
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, parseFloat(delay) * 1000 + i * 60);
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    elements.forEach(el => obs.observe(el));
}

/* ── Timeline items ── */
function initTimelineReveal() {
    const items = document.querySelectorAll('.timeline-item');
    const obs = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), i * 80);
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    items.forEach(el => obs.observe(el));
}

/* ═══════════════════════════════════════
   4. COUNTER ANIMATION
═══════════════════════════════════════ */
function animateCounter(el, target, duration = 1800) {
    const isDecimal = String(target).includes('.');
    const start = performance.now();

    function update(now) {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const value = target * eased;

        el.textContent = isDecimal ? value.toFixed(2) : Math.floor(value).toLocaleString();

        if (progress < 1) requestAnimationFrame(update);
        else el.textContent = isDecimal ? target.toFixed(2) : target.toLocaleString();
    }

    requestAnimationFrame(update);
}

function initCounters() {
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.counted) {
                entry.target.dataset.counted = '1';
                const val = parseFloat(entry.target.textContent.replace(/[^\d.]/g, ''));
                if (!isNaN(val)) animateCounter(entry.target, val);
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.counter-number').forEach(el => obs.observe(el));
}

/* ═══════════════════════════════════════
   5. NAVBAR — SCROLL & ACTIVE LINK
═══════════════════════════════════════ */
function initNavbar() {
    const navbar = document.getElementById('navbar');
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('section[id]');
    const scrollProg = document.getElementById('scrollProgress');

    // Scroll behaviour
    let lastY = 0;
    window.addEventListener('scroll', () => {
        const y = window.scrollY;

        // Progress bar
        if (scrollProg) {
            const total = document.documentElement.scrollHeight - window.innerHeight;
            scrollProg.style.transform = `scaleX(${y / total})`;
        }

        // Navbar glass on scroll
        if (navbar) {
            if (y > 60) navbar.classList.add('scrolled');
            else        navbar.classList.remove('scrolled');
        }

        // Active nav link
        sections.forEach(section => {
            const top = section.offsetTop - 100;
            const bot = top + section.offsetHeight;
            if (y >= top && y < bot) {
                navLinks.forEach(l => l.classList.remove('active'));
                const active = document.querySelector(`.nav-link[href="#${section.id}"]`);
                if (active) active.classList.add('active');
            }
        });

        lastY = y;
    }, { passive: true });
}

/* ═══════════════════════════════════════
   6. MOBILE MENU
═══════════════════════════════════════ */
function initMobileMenu() {
    const hamburger = document.getElementById('hamburger');
    const navMenu   = document.getElementById('navMenu');
    if (!hamburger || !navMenu) return;

    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
    });

    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
        });
    });
}

/* ═══════════════════════════════════════
   7. SMOOTH SCROLL
═══════════════════════════════════════ */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            window.scrollTo({
                top: target.offsetTop - 75,
                behavior: prefersReducedMotion ? 'auto' : 'smooth',
            });
        }
    });
});

window.scrollToSection = function(id) {
    const el = document.getElementById(id);
    if (el) window.scrollTo({ top: el.offsetTop - 75, behavior: 'smooth' });
};

/* ═══════════════════════════════════════
   8. BACK TO TOP
═══════════════════════════════════════ */
function initBackToTop() {
    const btn = document.createElement('button');
    btn.className    = 'back-to-top';
    btn.innerHTML    = '<i class="fas fa-arrow-up"></i>';
    btn.setAttribute('aria-label', 'Back to top');
    document.body.appendChild(btn);

    window.addEventListener('scroll', () => {
        btn.classList.toggle('visible', window.scrollY > 400);
    }, { passive: true });

    btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

/* ═══════════════════════════════════════
   9. SKILLS TABS
═══════════════════════════════════════ */
function initSkillsTabs() {
    const tabs   = document.querySelectorAll('.skills-tab-btn');
    const panels = document.querySelectorAll('.skills-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.dataset.tab;

            tabs.forEach(t  => t.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));

            tab.classList.add('active');
            const panel = document.getElementById(`panel-${target}`);
            if (panel) {
                panel.classList.add('active');
                // re-trigger reveal for newly shown items
                panel.querySelectorAll('.skill-item').forEach((el, i) => {
                    el.style.opacity   = '0';
                    el.style.transform = 'translateY(16px)';
                    setTimeout(() => {
                        el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                        el.style.opacity    = '1';
                        el.style.transform  = 'translateY(0)';
                    }, i * 40);
                });
            }
        });
    });
}

/* ═══════════════════════════════════════
   10. GITHUB REPOSITORIES — LIVE API
═══════════════════════════════════════ */
const GITHUB_USER = 'Muzaki29';
const CACHE_KEY   = 'gh_repos_muzaki29';
const CACHE_TTL   = 30 * 60 * 1000; // 30 minutes

// Language → CSS class & display name mapping
const LANG_MAP = {
    'Blade':      { cls: 'lang-blade',      label: 'Blade/PHP' },
    'PHP':        { cls: 'lang-php',        label: 'PHP'       },
    'Python':     { cls: 'lang-python',     label: 'Python'    },
    'JavaScript': { cls: 'lang-javascript', label: 'JavaScript'},
    'TypeScript': { cls: 'lang-typescript', label: 'TypeScript'},
    'HTML':       { cls: 'lang-html',       label: 'HTML'      },
    'CSS':        { cls: 'lang-css',        label: 'CSS'       },
};

function getLangInfo(lang) {
    if (!lang) return { cls: 'lang-other', label: 'Other' };
    if (LANG_MAP[lang]) return LANG_MAP[lang];
    return { cls: 'lang-other', label: lang };
}

// Skip repos that aren't interesting for a portfolio
const SKIP_REPOS = ['HBD-GF'];

function repoDescription(repo) {
    if (repo.description) return repo.description;
    const n = repo.name.toLowerCase();
    if (n.includes('portofolio') || n.includes('portfolio')) return 'Personal portfolio website built with Laravel & Blade.';
    if (n.includes('parthaistic'))  return 'Company profile website built with Laravel.';
    if (n.includes('kenangan'))     return 'Internship memories website — HTML/CSS static site.';
    if (n.includes('mes'))          return 'Website for MES (Majelis Ekonomi & Sosial) Depok organization.';
    if (n.includes('properti'))     return 'Real estate listing website built with Laravel Blade.';
    if (n.includes('streamlit'))    return 'Python Streamlit data analysis application.';
    if (n.includes('ramadan'))      return 'Ramadan tracker web app built with PHP/Laravel.';
    if (n.includes('merchband'))    return 'Merchandise & band website with e-commerce features.';
    if (n.includes('machine-learning') || n.includes('dicoding')) return 'Machine learning course assignments — Dicoding Academy.';
    if (n.includes('web'))          return 'Web application project.';
    return 'Software development project.';
}

function timeAgo(dateStr) {
    const diff = Date.now() - new Date(dateStr).getTime();
    const d = Math.floor(diff / 86400000);
    if (d === 0) return 'today';
    if (d === 1) return '1 day ago';
    if (d < 30)  return `${d} days ago`;
    const m = Math.floor(d / 30);
    if (m === 1) return '1 month ago';
    if (m < 12)  return `${m} months ago`;
    const y = Math.floor(m / 12);
    return y === 1 ? '1 year ago' : `${y} years ago`;
}

function buildGithubCard(repo) {
    const lang = getLangInfo(repo.language);
    const desc = repoDescription(repo);

    const card = document.createElement('a');
    card.href   = repo.html_url;
    card.target = '_blank';
    card.rel    = 'noopener noreferrer';
    card.className   = 'github-card';
    card.dataset.lang = repo.language || 'other';

    card.innerHTML = `
        <div class="github-card-top">
            <i class="fas fa-book-open github-card-icon"></i>
            <span class="github-card-name">${repo.name}</span>
            <i class="fas fa-external-link-alt github-card-external"></i>
        </div>
        <p class="github-card-desc">${desc}</p>
        <div class="github-card-footer">
            ${repo.language ? `
            <span class="lang-dot ${lang.cls}" title="${lang.label}"></span>
            <span>${lang.label}</span>` : ''}
            ${repo.stargazers_count > 0 ? `
            <span class="github-stat">
                <i class="fas fa-star"></i> ${repo.stargazers_count}
            </span>` : ''}
            ${repo.forks_count > 0 ? `
            <span class="github-stat">
                <i class="fas fa-code-branch"></i> ${repo.forks_count}
            </span>` : ''}
            <span class="github-stat">
                <i class="fas fa-clock"></i> ${timeAgo(repo.pushed_at)}
            </span>
        </div>
    `;
    return card;
}

function renderGithubRepos(repos, filterLang) {
    const grid = document.getElementById('githubGrid');
    if (!grid) return;

    grid.innerHTML = '';

    const filtered = repos.filter(r => {
        if (SKIP_REPOS.includes(r.name)) return false;
        if (!filterLang || filterLang === 'all') return true;
        if (filterLang === 'Blade') return r.language === 'Blade' || r.language === 'PHP';
        if (filterLang === 'HTML')  return r.language === 'HTML'  || r.language === 'CSS';
        return r.language === filterLang;
    });

    if (filtered.length === 0) {
        grid.innerHTML = '<p style="grid-column:1/-1;text-align:center;color:var(--text-secondary);padding:2rem;">No repositories found for this filter.</p>';
        return;
    }

    filtered.forEach((repo, i) => {
        const card = buildGithubCard(repo);
        card.style.opacity   = '0';
        card.style.transform = 'translateY(20px)';
        grid.appendChild(card);
        setTimeout(() => {
            card.style.transition = 'opacity 0.45s ease, transform 0.45s ease, border-color 0.3s, background 0.3s, box-shadow 0.3s, transform 0.3s';
            card.style.opacity    = '1';
            card.style.transform  = 'translateY(0)';
        }, i * 55);
    });
}

async function fetchGithubRepos() {
    // Check cache
    try {
        const cached = localStorage.getItem(CACHE_KEY);
        if (cached) {
            const { ts, data } = JSON.parse(cached);
            if (Date.now() - ts < CACHE_TTL) {
                renderGithubRepos(data, 'all');
                return;
            }
        }
    } catch (e) { /* ignore */ }

    const grid = document.getElementById('githubGrid');

    try {
        const res  = await fetch(`https://api.github.com/users/${GITHUB_USER}/repos?per_page=50&sort=updated`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const repos = await res.json();

        // Cache
        try {
            localStorage.setItem(CACHE_KEY, JSON.stringify({ ts: Date.now(), data: repos }));
        } catch (e) { /* quota exceeded */ }

        renderGithubRepos(repos, 'all');

        // Attach filter buttons after data loads
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                renderGithubRepos(repos, btn.dataset.lang);
            });
        });

    } catch (err) {
        console.error('GitHub API error:', err);
        if (grid) {
            grid.innerHTML = `
                <div class="github-loading">
                    <i class="fas fa-exclamation-triangle" style="font-size:2rem;color:var(--text-muted)"></i>
                    <p>Could not load repositories. <a href="https://github.com/Muzaki29?tab=repositories"
                       target="_blank" style="color:var(--accent-light)">View on GitHub →</a></p>
                </div>`;
        }
    }
}

/* ═══════════════════════════════════════
   11. THEME TOGGLE
═══════════════════════════════════════ */
function initTheme() {
    const html       = document.documentElement;
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon  = document.getElementById('themeIcon');

    const saved = localStorage.getItem('theme') || 'dark';
    html.setAttribute('data-theme', saved);
    if (themeIcon) themeIcon.className = saved === 'dark' ? 'fas fa-moon' : 'fas fa-sun';

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const cur = html.getAttribute('data-theme');
            const next = cur === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            if (themeIcon) themeIcon.className = next === 'dark' ? 'fas fa-moon' : 'fas fa-sun';
        });
    }
}

/* ═══════════════════════════════════════
   12. CUSTOM CURSOR
═══════════════════════════════════════ */
function initCursor() {
    if (!window.matchMedia('(pointer: fine)').matches || prefersReducedMotion) return;

    const ring = document.createElement('div');
    ring.className = 'cursor';
    const dot = document.createElement('div');
    dot.className = 'cursor-dot';
    document.body.appendChild(ring);
    document.body.appendChild(dot);

    let mx = 0, my = 0, rx = 0, ry = 0;

    document.addEventListener('mousemove', e => {
        mx = e.clientX; my = e.clientY;
        dot.style.left = mx + 'px';
        dot.style.top  = my + 'px';
        ring.style.opacity = '1';
        dot.style.opacity  = '1';
    });

    function animRing() {
        rx += (mx - rx) * 0.12;
        ry += (my - ry) * 0.12;
        ring.style.left = rx + 'px';
        ring.style.top  = ry + 'px';
        requestAnimationFrame(animRing);
    }
    animRing();

    document.querySelectorAll('a, button, .btn, .work-card, .service-card, .github-card, .skill-item').forEach(el => {
        el.addEventListener('mouseenter', () => ring.classList.add('active'));
        el.addEventListener('mouseleave', () => ring.classList.remove('active'));
    });
}

/* ═══════════════════════════════════════
   13. CONTACT FORM
═══════════════════════════════════════ */
function initContactForm() {
    const form        = document.getElementById('contactForm');
    const submitBtn   = document.getElementById('submitBtn');
    const submitText  = document.getElementById('submitText');
    const submitLoad  = document.getElementById('submitLoading');
    const formMsg     = document.getElementById('formMessage');
    const i18n        = (window.i18n && window.i18n.contact) ? window.i18n.contact : {};

    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        submitBtn.disabled      = true;
        submitText.style.display = 'none';
        submitLoad.style.display = 'inline-block';
        formMsg.style.display    = 'none';

        try {
            const res  = await fetch('/contact', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: new FormData(form),
            });
            const data = await res.json();

            formMsg.style.display = 'block';
            if (data.success) {
                formMsg.className   = 'form-message success';
                formMsg.textContent = data.message || i18n.successFallback || 'Message sent! Thank you.';
                form.reset();
            } else {
                formMsg.className   = 'form-message error';
                formMsg.textContent = data.message || i18n.errorFallback || 'An error occurred.';
            }
        } catch (err) {
            formMsg.style.display = 'block';
            formMsg.className   = 'form-message error';
            formMsg.textContent = i18n.genericRetryLater || 'Error sending message. Please try again.';
        } finally {
            submitBtn.disabled      = false;
            submitText.style.display = 'inline-block';
            submitLoad.style.display = 'none';
        }
    });
}

/* ═══════════════════════════════════════
   14. RIPPLE EFFECT ON BUTTONS
═══════════════════════════════════════ */
function initRipple() {
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (prefersReducedMotion) return;
            const rect  = this.getBoundingClientRect();
            const size  = Math.max(rect.width, rect.height);
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position:absolute; border-radius:50%;
                background:rgba(255,255,255,0.25);
                width:${size}px; height:${size}px;
                left:${e.clientX - rect.left - size/2}px;
                top:${e.clientY - rect.top - size/2}px;
                transform:scale(0);
                animation:ripple-anim 0.6s ease-out forwards;
                pointer-events:none;
            `;
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 650);
        });
    });

    const s = document.createElement('style');
    s.textContent = `@keyframes ripple-anim { to { transform:scale(4); opacity:0; } }`;
    document.head.appendChild(s);
}

/* ═══════════════════════════════════════
   INIT — DOMContentLoaded
═══════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initNavbar();
    initMobileMenu();
    initTypewriter();
    initScrollReveal();
    initTimelineReveal();
    initCounters();
    initSkillsTabs();
    fetchGithubRepos();
    initContactForm();

    if (!prefersReducedMotion) {
        initParticles();
        initBackToTop();
        initCursor();
        initRipple();
    }

    console.log('✨ Portfolio loaded — Muzaki Abdullah Irsyad 2025');
});
