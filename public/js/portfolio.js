/* ============================================================
   MUZAKI PORTFOLIO — portfolio.js
   Features: particle canvas, typewriter, skill tabs,
   theme toggle, navbar, contact form, back-to-top, cursor
============================================================ */

(function () {
    'use strict';

    /* ── Utils ── */
    const $ = (sel, ctx = document) => ctx.querySelector(sel);
    const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

    /* ─────────────────────────────────────
       1. PARTICLE CANVAS BACKGROUND
    ───────────────────────────────────── */
    const canvas = $('#bg-canvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let W, H, particles = [];

        function resizeCanvas() {
            W = canvas.width  = window.innerWidth;
            H = canvas.height = window.innerHeight;
        }

        function makeParticle() {
            return {
                x: Math.random() * W,
                y: Math.random() * H,
                r: Math.random() * 1.5 + 0.3,
                vx: (Math.random() - 0.5) * 0.3,
                vy: (Math.random() - 0.5) * 0.3,
                a: Math.random() * 0.6 + 0.2,
            };
        }

        function initParticles(n = 120) {
            particles = Array.from({ length: n }, makeParticle);
        }

        function drawParticles() {
            ctx.clearRect(0, 0, W, H);
            const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
            particles.forEach(p => {
                p.x += p.vx; p.y += p.vy;
                if (p.x < 0) p.x = W;
                if (p.x > W) p.x = 0;
                if (p.y < 0) p.y = H;
                if (p.y > H) p.y = 0;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = isDark
                    ? `rgba(96,165,250,${p.a})`
                    : `rgba(37,99,235,${p.a * 0.5})`;
                ctx.fill();
            });
            requestAnimationFrame(drawParticles);
        }

        resizeCanvas();
        initParticles();
        drawParticles();
        window.addEventListener('resize', () => { resizeCanvas(); initParticles(); });
    }

    /* ─────────────────────────────────────
       2. SCROLL PROGRESS BAR
    ───────────────────────────────────── */
    const scrollBar = $('#scroll-bar');
    function updateScrollBar() {
        if (!scrollBar) return;
        const total = document.documentElement.scrollHeight - window.innerHeight;
        scrollBar.style.width = (total > 0 ? (window.scrollY / total) * 100 : 0) + '%';
    }
    window.addEventListener('scroll', updateScrollBar, { passive: true });

    /* ─────────────────────────────────────
       3. NAVBAR SCROLL EFFECT + ACTIVE LINK
    ───────────────────────────────────── */
    const navbar = $('#navbar');
    const navLinks = $$('.nav-link');

    function onScroll() {
        updateScrollBar();

        /* Scrolled class */
        if (navbar) navbar.classList.toggle('scrolled', window.scrollY > 50);

        /* Back to top */
        const backTop = $('#backTop');
        if (backTop) backTop.classList.toggle('vis', window.scrollY > 400);

        /* Active nav link */
        const sections = $$('section[id]');
        let current = '';
        sections.forEach(sec => {
            if (window.scrollY >= sec.offsetTop - 120) current = sec.id;
        });
        navLinks.forEach(link => {
            const href = link.getAttribute('href') || '';
            link.classList.toggle('active', href === '#' + current);
        });
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    /* ─────────────────────────────────────
       4. HAMBURGER MENU
    ───────────────────────────────────── */
    const ham = $('#ham');
    const navMenu = $('#navMenu');
    if (ham && navMenu) {
        ham.addEventListener('click', () => {
            ham.classList.toggle('open');
            navMenu.classList.toggle('open');
        });
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                ham.classList.remove('open');
                navMenu.classList.remove('open');
            });
        });
    }

    /* ─────────────────────────────────────
       5. THEME TOGGLE
    ───────────────────────────────────── */
    const themeToggle = $('#themeToggle');
    const themeIcon   = $('#themeIcon');

    function applyTheme(t) {
        document.documentElement.setAttribute('data-theme', t);
        localStorage.setItem('theme', t);
        if (themeIcon) {
            themeIcon.className = t === 'dark' ? 'fas fa-moon' : 'fas fa-sun';
        }
    }

    /* Apply saved theme on load */
    applyTheme(localStorage.getItem('theme') || 'dark');

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            applyTheme(current === 'dark' ? 'light' : 'dark');
        });
    }

    /* ─────────────────────────────────────
       6. TYPEWRITER
    ───────────────────────────────────── */
    const twEl = $('#tw-text');
    if (twEl) {
        const words = [
            'Full-Stack Web Developer',
            'Laravel Engineer',
            'IT Support Specialist',
            'DevOps Enthusiast',
            'Informatics Student',
        ];
        let wi = 0, ci = 0, deleting = false;

        function typeStep() {
            const word = words[wi];
            if (!deleting) {
                twEl.textContent = word.slice(0, ++ci);
                if (ci === word.length) {
                    deleting = true;
                    setTimeout(typeStep, 2000);
                    return;
                }
            } else {
                twEl.textContent = word.slice(0, --ci);
                if (ci === 0) {
                    deleting = false;
                    wi = (wi + 1) % words.length;
                    setTimeout(typeStep, 400);
                    return;
                }
            }
            setTimeout(typeStep, deleting ? 55 : 90);
        }
        setTimeout(typeStep, 800);
    }

    /* ─────────────────────────────────────
       7. SKILLS TABS
    ───────────────────────────────────── */
    const tabBtns = $$('.tab-btn');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            $$('.skills-panel').forEach(p => p.classList.remove('active'));
            const panel = $('#panel-' + btn.dataset.tab);
            if (panel) panel.classList.add('active');
        });
    });

    /* ─────────────────────────────────────
       8. CONTACT FORM
    ───────────────────────────────────── */
    const contactForm = $('#contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const submitBtn  = $('#submitBtn');
            const submitText = $('#submitText');
            const submitLoad = $('#submitLoading');
            const msgEl      = $('#formMessage');

            submitBtn.disabled = true;
            if (submitText) submitText.style.display = 'none';
            if (submitLoad) submitLoad.style.display = 'inline-flex';
            if (msgEl) msgEl.style.display = 'none';

            try {
                const data = new FormData(contactForm);
                const res  = await fetch('/contact', { method: 'POST', body: data });
                const json = await res.json().catch(() => ({}));

                if (res.ok) {
                    showMsg(msgEl, json.message || (window.i18n?.contact?.successFallback || 'Message sent!'), 'success');
                    contactForm.reset();
                } else {
                    const err = json.message || (window.i18n?.contact?.errorFallback || 'Failed to send. Try again.');
                    showMsg(msgEl, err, 'error');
                }
            } catch {
                showMsg(msgEl, window.i18n?.contact?.genericRetryLater || 'Network error. Please try again.', 'error');
            } finally {
                submitBtn.disabled = false;
                if (submitText) submitText.style.display = 'inline-flex';
                if (submitLoad) submitLoad.style.display = 'none';
            }
        });
    }

    function showMsg(el, text, type) {
        if (!el) return;
        el.textContent = text;
        el.className = 'form-msg ' + type;
        el.style.display = 'block';
        setTimeout(() => { if (el) el.style.display = 'none'; }, 6000);
    }

    /* ─────────────────────────────────────
       9. BACK TO TOP
    ───────────────────────────────────── */
    const backTop = $('#backTop');
    if (backTop) {
        backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    /* ─────────────────────────────────────
       10. CUSTOM CURSOR (desktop only)
    ───────────────────────────────────── */
    const cursor    = $('#cursor');
    const cursorDot = $('#cursorDot');

    if (cursor && cursorDot && window.innerWidth > 768) {
        cursor.style.opacity = '1';
        cursorDot.style.opacity = '1';

        document.addEventListener('mousemove', e => {
            cursor.style.left    = e.clientX + 'px';
            cursor.style.top     = e.clientY + 'px';
            cursorDot.style.left = e.clientX + 'px';
            cursorDot.style.top  = e.clientY + 'px';
        }, { passive: true });

        $$('a, button, .svc-card, .sk-item, .proj-card, .project-card, .cert-card').forEach(el => {
            el.addEventListener('mouseenter', () => cursor.classList.add('active'));
            el.addEventListener('mouseleave', () => cursor.classList.remove('active'));
        });
    }

    /* ─────────────────────────────────────
       11. SMOOTH SCROLL FOR NAV LINKS
    ───────────────────────────────────── */
    $$('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            const target = $(link.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    /* ─────────────────────────────────────
       12. CERTIFICATE LIGHTBOX
    ───────────────────────────────────── */
    const certCards = $$('.cert-card');
    const lightbox = $('#certLightbox');
    const lightboxImg = $('#lightboxImg');
    const lightboxTitle = $('#lightboxTitle');
    const lightboxIssuer = $('#lightboxIssuer');
    const lightboxClose = $('#lightboxClose');
    const lightboxPrev = $('#lightboxPrev');
    const lightboxNext = $('#lightboxNext');

    let currentSliderCard = null;
    let currentLightboxIndex = 0;
    let lightboxSlides = [];

    if (lightbox && lightboxImg && lightboxClose) {
        certCards.forEach(card => {
            card.addEventListener('click', () => {
                const title = card.getAttribute('data-cert-title');
                const issuer = card.getAttribute('data-cert-issuer');
                
                if (lightboxTitle) lightboxTitle.textContent = title;
                if (lightboxIssuer) lightboxIssuer.textContent = issuer;

                if (card.classList.contains('cert-card-slider')) {
                    currentSliderCard = card;
                    lightboxSlides = $$('.cert-slide', card).map(slide => slide.getAttribute('data-img-url'));
                    
                    const activeSlide = $('.cert-slide.active', card);
                    const slides = $$('.cert-slide', card);
                    currentLightboxIndex = slides.indexOf(activeSlide);
                    if (currentLightboxIndex < 0) currentLightboxIndex = 0;

                    if (lightboxPrev) lightboxPrev.style.display = 'flex';
                    if (lightboxNext) lightboxNext.style.display = 'flex';

                    updateLightboxSlide();
                } else {
                    currentSliderCard = null;
                    lightboxSlides = [];
                    
                    if (lightboxPrev) lightboxPrev.style.display = 'none';
                    if (lightboxNext) lightboxNext.style.display = 'none';

                    const imgUrl = card.getAttribute('data-cert-img');
                    lightboxImg.src = imgUrl;
                }

                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        function updateLightboxSlide() {
            if (lightboxSlides.length > 0) {
                lightboxImg.src = lightboxSlides[currentLightboxIndex];
            }
        }

        if (lightboxPrev && lightboxNext) {
            lightboxPrev.addEventListener('click', e => {
                e.stopPropagation();
                if (lightboxSlides.length > 0) {
                    currentLightboxIndex = (currentLightboxIndex - 1 + lightboxSlides.length) % lightboxSlides.length;
                    updateLightboxSlide();
                    
                    // Sync card active slide
                    if (currentSliderCard) {
                        const prevBtn = $('.prev-btn', currentSliderCard);
                        if (prevBtn) prevBtn.click();
                    }
                }
            });

            lightboxNext.addEventListener('click', e => {
                e.stopPropagation();
                if (lightboxSlides.length > 0) {
                    currentLightboxIndex = (currentLightboxIndex + 1) % lightboxSlides.length;
                    updateLightboxSlide();
                    
                    // Sync card active slide
                    if (currentSliderCard) {
                        const nextBtn = $('.next-btn', currentSliderCard);
                        if (nextBtn) nextBtn.click();
                    }
                }
            });
        }

        const closeLightbox = () => {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(() => {
                lightboxImg.src = '';
            }, 300);
        };

        lightboxClose.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', e => {
            if (e.target === lightbox || e.target.classList.contains('lightbox-content-wrap')) closeLightbox();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && lightbox.classList.contains('active')) closeLightbox();
            if (lightbox.classList.contains('active') && lightboxSlides.length > 0) {
                if (e.key === 'ArrowLeft' && lightboxPrev) lightboxPrev.click();
                if (e.key === 'ArrowRight' && lightboxNext) lightboxNext.click();
            }
        });
    }

    /* ─────────────────────────────────────
       13. CERTIFICATE SLIDERS
    ───────────────────────────────────── */
    const sliders = $$('.cert-card-slider');
    sliders.forEach(slider => {
        const slides = $$('.cert-slide', slider);
        const dots = $$('.slide-dot', slider);
        const prevBtn = $('.prev-btn', slider);
        const nextBtn = $('.next-btn', slider);
        let currentIndex = 0;

        function showSlide(index) {
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;
            currentIndex = index;

            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === currentIndex);
            });
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentIndex);
            });

            const activeSlideImg = slides[currentIndex].getAttribute('data-img-url');
            slider.setAttribute('data-cert-img', activeSlideImg);
        }

        if (prevBtn && nextBtn) {
            prevBtn.addEventListener('click', e => {
                e.stopPropagation();
                showSlide(currentIndex - 1);
            });
            nextBtn.addEventListener('click', e => {
                e.stopPropagation();
                showSlide(currentIndex + 1);
            });
        }

        dots.forEach((dot, i) => {
            dot.addEventListener('click', e => {
                e.stopPropagation();
                showSlide(i);
            });
        });
    });

    /* ─────────────────────────────────────
       14. EXPERIENCE CERTIFICATE TRIGGER
    ───────────────────────────────────── */
    const expCertBtns = $$('.exp-btn-cert');
    expCertBtns.forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();
            const certKey = btn.getAttribute('data-open-cert');
            if (certKey) {
                const targetCard = $$('.cert-card').find(card => {
                    const img = card.getAttribute('data-cert-img') || '';
                    const title = card.getAttribute('data-cert-title') || '';
                    return img.toLowerCase().includes(certKey) || title.toLowerCase().includes(certKey);
                });
                if (targetCard) {
                    targetCard.click();
                }
            }
        });
    });

    /* ─────────────────────────────────────
       15. FLOATING AI ASSISTANT CHATBOT
    ───────────────────────────────────── */
    const aiWidget = $('#aiChatWidget');
    const aiBubble = $('#aiChatBubble');
    const aiWindow = $('#aiChatWindow');
    const aiClose  = $('#aiCloseBtn');
    const aiBody   = $('#aiChatBody');
    const aiForm   = $('#aiChatForm');
    const aiInput  = $('#aiChatInput');
    const aiReplies = $('#aiQuickReplies');

    if (aiWidget && aiBubble && aiWindow && aiClose && aiBody && aiForm && aiInput && aiReplies) {
        let chatbotInitialized = false;

        const i18nChat = {
            id: {
                greeting: "Halo! Saya **Asisten AI Muzaki**. 🤖<br><br>" +
                          "Saya di sini untuk membantu Anda mengetahui lebih lanjut tentang Muzaki (Keahlian, Proyek Utama, Pengalaman Kerja, atau Kontak).<br><br>" +
                          "Tanyakan apa saja seputar portofolio Muzaki!",
                replies: [
                    { text: "🛠️ Keahlian", query: "keahlian utama" },
                    { text: "💻 Proyek Utama", query: "proyek utama" },
                    { text: "💼 Pengalaman", query: "pengalaman kerja" },
                    { text: "✉️ Hubungi", query: "kontak hubungi" }
                ],
                fallback: "Terima kasih atas pertanyaannya! Saya merekomendasikan Anda menanyakan tentang **keahlian**, **proyek**, **pengalaman**, atau **kontak** Muzaki untuk informasi yang lebih detail."
            },
            en: {
                greeting: "Hello! I am **Muzaki's AI Assistant**. 🤖<br><br>" +
                          "I'm here to help you learn more about Muzaki (Skills, Featured Projects, Work Experience, or Contact).<br><br>" +
                          "Feel free to ask me anything about Muzaki's portfolio!",
                replies: [
                    { text: "🛠️ Skills", query: "main skills" },
                    { text: "💻 Projects", query: "featured projects" },
                    { text: "💼 Experience", query: "work experience" },
                    { text: "✉️ Contact", query: "contact info" }
                ],
                fallback: "Thank you for your question! I recommend asking about Muzaki's **skills**, **projects**, **experience**, or **contact** details for more information."
            }
        };

        // Toggle Open/Close
        aiBubble.addEventListener('click', () => {
            aiWindow.classList.toggle('open');
            const isOpen = aiWindow.classList.contains('open');
            if (isOpen) {
                aiInput.focus();
                const badge = $('.ai-badge', aiBubble);
                if (badge) badge.style.display = 'none';

                if (!chatbotInitialized) {
                    initChatbot();
                }
            }
        });

        aiClose.addEventListener('click', () => {
            aiWindow.classList.remove('open');
        });

        // Initialize Chatbot with Greeting
        function initChatbot() {
            chatbotInitialized = true;
            aiBody.innerHTML = '';
            
            setTimeout(() => {
                const currentLang = document.documentElement.getAttribute('lang') || 'id';
                showBotResponse(i18nChat[currentLang].greeting);
            }, 300);
        }

        function renderQuickReplies() {
            const currentLang = document.documentElement.getAttribute('lang') || 'id';
            aiReplies.innerHTML = '';
            i18nChat[currentLang].replies.forEach(r => {
                const btn = document.createElement('button');
                btn.className = 'quick-reply-btn';
                btn.textContent = r.text;
                btn.addEventListener('click', () => {
                    handleUserMessage(r.query);
                });
                aiReplies.appendChild(btn);
            });
        }
        renderQuickReplies();

        // Handle user form submission
        aiForm.addEventListener('submit', e => {
            e.preventDefault();
            const msg = aiInput.value.trim();
            if (!msg) return;
            aiInput.value = '';
            handleUserMessage(msg);
        });

        function handleUserMessage(msg) {
            // Append User Message bubble
            appendMessage(msg, 'user');
            
            // Show typing indicator
            showTypingIndicator();

            // Call backend API with RAG + strict role enforcement
            const currentLang = document.documentElement.getAttribute('lang') || 'id';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('/api/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    message: msg,
                    locale: currentLang,
                }),
            })
            .then(res => res.json())
            .then(data => {
                removeTypingIndicator();
                if (data.success) {
                    showBotResponse(data.message);
                } else {
                    showBotResponse(data.message || i18nChat[currentLang].fallback);
                }
            })
            .catch(() => {
                removeTypingIndicator();
                // Fallback to local keyword matching if API fails
                const response = getBotAnswer(msg);
                showBotResponse(response);
            });
        }

        function formatMarkdown(text) {
            let html = text;
            // Format bold **text** to <strong>text</strong>
            html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            // Format markdown links [label](url) to styled target="_blank" HTML links
            html = html.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank" style="color: var(--blue2); text-decoration: underline; font-weight: 600;">$1</a>');
            // Format bullet points starting with '•' or '*' to elegant flex items with custom ✦ icon
            html = html.replace(/•\s*(.*?)(<br>|$)/g, '<div class="bot-bullet" style="display: flex; gap: 0.45rem; align-items: flex-start; margin-top: 0.35rem; padding-left: 0.2rem;"><span style="color: var(--blue2); text-shadow: 0 0 6px rgba(96,165,250,0.6); font-size: 0.72rem; margin-top: 0.12rem; flex-shrink: 0;">✦</span><span style="flex: 1;">$1</span></div>$2');
            return html;
        }

        function appendMessage(text, sender) {
            const bubble = document.createElement('div');
            bubble.className = `ai-msg ${sender}`;
            bubble.innerHTML = sender === 'bot' ? formatMarkdown(text) : text;
            aiBody.appendChild(bubble);
            aiBody.scrollTop = aiBody.scrollHeight;
        }

        let typingBubble = null;
        function showTypingIndicator() {
            if (typingBubble) return;
            typingBubble = document.createElement('div');
            typingBubble.className = 'ai-msg bot';
            typingBubble.innerHTML = `
                <div class="typing-dots">
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                </div>
            `;
            aiBody.appendChild(typingBubble);
            aiBody.scrollTop = aiBody.scrollHeight;
        }

        function removeTypingIndicator() {
            if (typingBubble && typingBubble.parentNode) {
                typingBubble.parentNode.removeChild(typingBubble);
                typingBubble = null;
            }
        }

        function showBotResponse(text) {
            appendMessage(text, 'bot');
        }

        // Conversational fuzzy match knowledge base
        function getBotAnswer(query) {
            const q = query.toLowerCase().replace(/[^\w\s]/g, '');
            const currentLang = document.documentElement.getAttribute('lang') || 'id';
            
            if (currentLang === 'en') {
                if (q.includes('hello') || q.includes('hi') || q.includes('hey') || q.includes('greetings')) {
                    return "Hello! How can I help you regarding Muzaki's skills, projects, work experience, or contact?";
                }
                if (q.includes('who') || q.includes('name') || q.includes('about') || q.includes('profile') || q.includes('muzaki')) {
                    return "Muzaki Abdullah Irsyad is an Informatics Engineering graduate from **STT Terpadu Nurul Fikri** (GPA **3.86/4.00**) with a strong passion for software development (Full-Stack Web Dev) and IT Support. He is based in Jakarta, Indonesia.";
                }
                if (q.includes('skill') || q.includes('expertise') || q.includes('capability') || q.includes('technology') || q.includes('language') || q.includes('programming')) {
                    return "Muzaki's key technical expertise includes:<br><br>" +
                           "• **Programming Languages**: PHP, JavaScript, Python, SQL<br>" +
                           "• **Frontend Development**: React.js, Next.js, Tailwind CSS, HTML/CSS<br>" +
                           "• **Backend Development**: Laravel, Node.js, MySQL, PostgreSQL, REST API<br>" +
                           "• **DevOps & Cloud**: Linux VPS, Docker, Git/GitHub, Nginx, CI/CD pipelines.";
                }
                if (q.includes('project') || q.includes('collection') || q.includes('sinar') || q.includes('museum') || q.includes('built') || q.includes('merch') || q.includes('syahdu') || q.includes('surat')) {
                    return "Muzaki has developed several featured projects:<br><br>" +
                           "1. **PMO Merch**: Official e-commerce for Pancarona Merch with 3D preview, Midtrans QRIS & Filament admin.<br>" +
                           "2. **SyahduLab**: Premium digital agency site with price calculator, MDX blog & Supabase CMS.<br>" +
                           "3. **MES Depok**: Sharia economic organization portal with online membership & admin panel.<br>" +
                           "4. **Jagad Property**: Property listing platform with filters, WhatsApp leads & super-admin.<br>" +
                           "5. **Cumo Cuci Motor**: Motorcycle wash booking site with service packages & order tracking.<br>" +
                           "6. **SuratRT**: Digital RT management — online letters, dues, forum & PDF generation.<br>" +
                           "7. **SDN Makasar 08**: School portal with Vue 3 SPA & Laravel admin CMS.";
                }
                if (q.includes('work') || q.includes('experience') || q.includes('intern') || q.includes('career') || q.includes('pranata') || q.includes('onexpert') || q.includes('lecturer')) {
                    return "Muzaki has a solid work experience portfolio:<br><br>" +
                           "• **Pranata Komputer / Systems Administrator** at Museum Penerangan (24 Nov 2025 – 24 May 2026). Managed local network infrastructure, database servers, IT hardware, and Tier 1 & 2 IT Support.<br>" +
                           "• **Web Developer Intern & IT Support** at CV. One Xpert Indonesia (2022 – 2024). Developed Laravel apps, managed Nginx Linux VPS, MySQL databases, and troubleshooted hardware/network issues.<br>" +
                           "• **DevOps TA / Assistant Lecturer** at STT Terpadu Nurul Fikri (2023 – 2025). Taught Docker containerization, Nginx routing, Git versioning, and Software Engineering.";
                }
                if (q.includes('contact') || q.includes('email') || q.includes('linkedin') || q.includes('reach') || q.includes('message')) {
                    return "You can reach Muzaki directly:<br><br>" +
                           "• ✉️ Email: **contact@muzakiabdullahirsyad.my.id**<br>" +
                           "• 💼 LinkedIn: [Muzaki Abdullah Irsyad](https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/)<br>" +
                           "• 💻 GitHub: [Muzaki29](https://github.com/Muzaki29)<br><br>" +
                           "Or you can send a message directly using the **Send me a message** form at the bottom of this page!";
                }
                if (q.includes('cert') || q.includes('infinite') || q.includes('codeless') || q.includes('credential') || q.includes('training')) {
                    return "Muzaki holds several professional certifications:<br>" +
                           "• MSIB Batch 6 Codeless Data Science (PT Nurul Fikri Cipta Inovasi)<br>" +
                           "• MSIB Batch 5 Web Development (Infinite Learning)<br>" +
                           "• BUMN Internship program (Magenta BUMN)<br>" +
                           "• Maganghub Indonesia Batch 2";
                }
                if (q.includes('gpa') || q.includes('ipk') || q.includes('college') || q.includes('study') || q.includes('score') || q.includes('grade')) {
                    return "Muzaki studied Informatics Engineering at **STT Terpadu Nurul Fikri** and graduated with an excellent GPA of **3.86 out of 4.00**!";
                }
                return i18nChat.en.fallback;
            } else {
                // Indonesian Match
                if (q.includes('halo') || q.includes('hi') || q.includes('hai') || q.includes('hello')) {
                    return "Halo! Ada yang bisa saya bantu terkait keahlian, proyek, pengalaman kerja, atau kontak Muzaki?";
                }
                if (q.includes('siapa') || q.includes('nama') || q.includes('tentang') || q.includes('who') || q.includes('about') || q.includes('profil') || q.includes('muzaki')) {
                    return "Muzaki Abdullah Irsyad adalah lulusan Sistem Informasi di **STT Terpadu Nurul Fikri** (IPK **3.86/4.00**) dengan passion kuat di pengembangan perangkat lunak (Full-Stack Web Dev) dan IT Support. Beliau berbasis di Jakarta, Indonesia.";
                }
                if (q.includes('skill') || q.includes('keahlian') || q.includes('kemampuan') || q.includes('bisa') || q.includes('teknologi') || q.includes('bahasa') || q.includes('programming')) {
                    return "Keahlian teknis utama Muzaki meliputi:<br><br>" +
                           "• **Programming Languages**: PHP, JavaScript, Python, SQL<br>" +
                           "• **Frontend Development**: React.js, Next.js, Tailwind CSS, HTML/CSS<br>" +
                           "• **Backend Development**: Laravel, Node.js, MySQL, PostgreSQL, REST API<br>" +
                           "• **DevOps & Cloud**: Linux VPS, Docker, Git/GitHub, Nginx, CI/CD pipelines.";
                }
                if (q.includes('proyek') || q.includes('project') || q.includes('koleksi') || q.includes('sinar') || q.includes('museum') || q.includes('built') || q.includes('merch') || q.includes('syahdu') || q.includes('surat') || q.includes('mes depok') || q.includes('cuci')) {
                    return "Muzaki telah mendevelop beberapa proyek unggulan:<br><br>" +
                           "1. **PMO Merch**: E-commerce merchandise resmi Pancarona Merch dengan preview 3D, QRIS Midtrans & admin Filament.<br>" +
                           "2. **SyahduLab**: Website agensi digital premium dengan kalkulator harga, blog MDX & CMS Supabase.<br>" +
                           "3. **MES Depok**: Portal organisasi ekonomi syariah dengan keanggotaan online & panel admin.<br>" +
                           "4. **Jagad Property**: Platform listing properti dengan filter, WhatsApp & super-admin.<br>" +
                           "5. **Cumo Cuci Motor**: Booking cuci motor online dengan paket layanan & lacak status.<br>" +
                           "6. **SuratRT**: Manajemen RT digital — surat online, iuran, forum & generate PDF.<br>" +
                           "7. **SDN Makasar 08**: Portal sekolah dengan SPA Vue 3 & CMS admin Laravel.";
                }
                if (q.includes('kerja') || q.includes('pengalaman') || q.includes('experience') || q.includes('magang') || q.includes('intern') || q.includes('pranata') || q.includes('onexpert') || q.includes('lecturer')) {
                    return "Muzaki memiliki portofolio pengalaman kerja yang solid:<br><br>" +
                           "• **Pranata Komputer / Administrator Sistem** di Museum Penerangan (24 Nov 2025 – 24 Mei 2026). Bertanggung jawab atas stabilitas infrastruktur TI, jaringan lokal, database server, IT Support & troubleshooting hardware/software.<br>" +
                           "• **Web Developer Intern & IT Support** di CV. One Xpert Indonesia (2022 – 2024). Web Laravel, VPS Linux Nginx, MySQL, troubleshooting hardware & network.<br>" +
                           "• **DevOps TA / Assistant Lecturer** di STT Terpadu Nurul Fikri (2023 – 2025). Mengajar materi Docker, Nginx, Git, RPL.";
                }
                if (q.includes('kontak') || q.includes('hubungi') || q.includes('contact') || q.includes('email') || q.includes('linkedin') || q.includes('pesan')) {
                    return "Anda dapat menghubungi Muzaki secara langsung:<br><br>" +
                           "• ✉️ Email: **contact@muzakiabdullahirsyad.my.id**<br>" +
                           "• 💼 LinkedIn: [Muzaki Abdullah Irsyad](https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/)<br>" +
                           "• 💻 GitHub: [Muzaki29](https://github.com/Muzaki29)<br><br>" +
                           "Atau silakan langsung kirim pesan melalui form **Kirim pesan** di bagian bawah halaman ini!";
                }
                if (q.includes('sertifikat') || q.includes('certifications') || q.includes('infinite') || q.includes('codeless') || q.includes('magenta') || q.includes('bumn')) {
                    return "Muzaki mengantongi sertifikasi profesional bergengsi:<br>" +
                           "• MSIB Batch 6 Codeless Data Science (PT Nurul Fikri Cipta Inovasi)<br>" +
                           "• MSIB Batch 5 Web Development (Infinite Learning)<br>" +
                           "• Magang Kerja BUMN (Magenta BUMN)<br>" +
                           "• Maganghub Indonesia Batch 2";
                }
                if (q.includes('gpa') || q.includes('ipk') || q.includes('kuliah') || q.includes('stt') || q.includes('nilai')) {
                    return "Muzaki menempuh pendidikan di **STT Terpadu Nurul Fikri** mengambil jurusan Teknik Informatika, dan lulus dengan nilai IPK yang sangat gemilang yaitu **3.86 dari skala 4.00**!";
                }
                return i18nChat.id.fallback;
            }
        }
    }

    /* ─────────────────────────────────────
       16. PROJECT DETAILS MODAL TRIGGER
    ───────────────────────────────────── */
    const projModal = $('#projectModal');
    const pmBody = $('#pmModalBody');
    const pmClose = $('#pmCloseBtn');
    const pmBackdrop = $('#pmBackdrop');
    const btnViewDetails = $$('.btn-view-details');

    if (projModal && pmBody && pmClose && pmBackdrop && btnViewDetails.length > 0) {
        btnViewDetails.forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const card = btn.closest('.project-grid-card');
                if (card) {
                    const template = $('.pgc-details-template', card);
                    if (template) {
                        pmBody.innerHTML = template.innerHTML;
                        projModal.classList.add('open');
                        document.body.style.overflow = 'hidden'; // prevent bg scroll
                    }
                }
            });
        });

        function closeProjModal() {
            projModal.classList.remove('open');
            document.body.style.overflow = '';
            setTimeout(() => {
                pmBody.innerHTML = '';
            }, 300);
        }

        pmClose.addEventListener('click', closeProjModal);
        pmBackdrop.addEventListener('click', closeProjModal);
        
        // Escape key close
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && projModal.classList.contains('open')) {
                closeProjModal();
            }
        });
    }

})();
