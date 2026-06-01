<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Muzaki Abdullah Irsyad — Informatics Engineer, Full-Stack Web Developer & IT Support. Building modern web experiences from Jakarta, Indonesia.">
    <meta name="keywords" content="Muzaki Abdullah Irsyad, Muzaki29, portfolio, web developer, Laravel, PHP, JavaScript, Jakarta">
    <meta name="author" content="Muzaki Abdullah Irsyad">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph -->
    <meta property="og:title" content="Muzaki Abdullah Irsyad | Web Developer & IT Engineer">
    <meta property="og:description" content="Portfolio showcasing full-stack web development, IT support, and software engineering projects.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://muzakiabdullahirsyad.my.id/">
    <meta property="og:image" content="{{ asset('images/profil.png') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Muzaki Abdullah Irsyad | Web Developer">
    <meta name="twitter:description" content="Full-stack web developer and IT engineer from Jakarta.">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>Muzaki Abdullah Irsyad — Portfolio</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}">

    <!-- Theme flash prevention -->
    <script>
        (function() {
            const t = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
</head>
<body>

<!-- Animated BG Canvas -->
<canvas id="bg-canvas"></canvas>

<!-- Scroll Progress -->
<div class="scroll-progress" id="scrollProgress"></div>

<!-- ── NAVBAR ── -->
<nav class="navbar" id="navbar">
    <div class="container">
        <div class="nav-brand" onclick="scrollToSection('hero')">Muzaki.dev</div>

        <ul class="nav-menu" id="navMenu">
            <li><a href="#about"          class="nav-link">{{ __('nav.about') }}</a></li>
            <li><a href="#skills"         class="nav-link">{{ __('nav.skills') }}</a></li>
            <li><a href="#works"          class="nav-link">{{ __('nav.works') }}</a></li>
            <li><a href="#github-projects"class="nav-link">Projects</a></li>
            <li><a href="#certifications" class="nav-link">{{ __('nav.certifications') }}</a></li>
            <li><a href="#contact"        class="nav-link">{{ __('nav.contact') }}</a></li>
        </ul>

        <div class="nav-actions">
            <div class="lang-toggle" role="group" aria-label="{{ __('nav.language') }}">
                <a href="{{ route('locale.switch', ['locale' => 'id']) }}"
                   class="lang-option {{ app()->getLocale() === 'id' ? 'active' : '' }}"
                   title="Bahasa Indonesia">ID</a>
                <span class="lang-sep">|</span>
                <a href="{{ route('locale.switch', ['locale' => 'en']) }}"
                   class="lang-option {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                   title="English">EN</a>
            </div>
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
        </div>

        <div class="hamburger" id="hamburger">
            <span></span><span></span><span></span>
        </div>
    </div>
</nav>

<!-- ── HERO SECTION ── -->
<section class="hero" id="hero">
    <div class="container">
        <div class="hero-content">
            <!-- Text -->
            <div class="hero-text">
                <div class="hero-badge">
                    <span>Available for work</span>
                </div>

                <p class="hero-greeting">{{ __('hero.greeting') }}</p>

                <h1 class="hero-name">
                    <span>Muzaki</span> <span class="highlight">Abdullah</span><br>
                    <span>Irsyad</span>
                </h1>

                <div class="typewriter-wrapper">
                    <span id="typewriter-prefix"></span>
                    <span class="typewriter-text" id="typewriter-text"></span>
                    <span class="typewriter-cursor"></span>
                </div>

                <p class="hero-desc">
                    {{ __('hero.title_1') }} — {{ __('hero.title_2') }}.
                    Building practical, high-quality solutions that make a real difference.
                </p>

                <div class="hero-buttons">
                    <a href="#contact" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        {{ __('hero.hire_me') }}
                    </a>
                    <a href="{{ asset('cv/muzaki-abdullah-irsyad.pdf') }}" class="btn btn-outline" download>
                        <i class="fas fa-download"></i>
                        Download CV
                    </a>
                </div>

                <div class="hero-socials">
                    <a href="https://github.com/Muzaki29" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="mailto:contact@muzakiabdullahirsyad.my.id" class="social-icon" aria-label="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>

            <!-- Profile Image -->
            <div class="hero-image">
                <div class="profile-wrapper reveal-scale">
                    <div class="profile-glow"></div>
                    <div class="profile-ring"></div>
                    <div class="profile-circle">
                        <img src="{{ asset('images/profil.png') }}"
                             alt="Muzaki Abdullah Irsyad"
                             width="300" height="300"
                             loading="eager"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="profile-placeholder" style="display:none;">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Cards -->
        <div class="hero-services">
            <div class="service-card reveal">
                <i class="fas fa-code"></i>
                <h3>{{ __('hero.service_software') }}</h3>
            </div>
            <div class="service-card reveal">
                <i class="fas fa-server"></i>
                <h3>{{ __('hero.service_it') }}</h3>
            </div>
            <div class="service-card reveal">
                <i class="fas fa-graduation-cap"></i>
                <h3>{{ __('hero.service_teaching') }}</h3>
            </div>
        </div>
    </div>
</section>

<!-- ── ABOUT SECTION ── -->
<section class="about" id="about">
    <div class="container">
        <div style="text-align:center; margin-bottom:0.5rem;">
            <span class="section-label">Who I Am</span>
        </div>
        <h2 class="section-title reveal">{{ __('about.title') }}</h2>
        <p class="about-description reveal">{{ __('about.description') }}</p>

        <div class="about-details">
            <div class="detail-row reveal">
                <span class="detail-label"><i class="fas fa-briefcase" style="margin-right:0.4rem;color:var(--accent-light)"></i>{{ __('about.experience') }}</span>
                <span class="detail-value">{{ __('about.experience_value') }}</span>
            </div>
            <div class="detail-row reveal">
                <span class="detail-label"><i class="fas fa-tools" style="margin-right:0.4rem;color:var(--accent-light)"></i>{{ __('about.specialty') }}</span>
                <span class="detail-value">{{ __('about.specialty_value') }}</span>
            </div>
            <div class="detail-row reveal">
                <span class="detail-label"><i class="fas fa-graduation-cap" style="margin-right:0.4rem;color:var(--accent-light)"></i>{{ __('about.education') }}</span>
                <span class="detail-value">STT Terpadu Nurul Fikri</span>
            </div>
            <div class="detail-row reveal">
                <span class="detail-label"><i class="fas fa-star" style="margin-right:0.4rem;color:var(--accent-light)"></i>{{ __('about.gpa') }}</span>
                <span class="detail-value">3.86 / 4.00</span>
            </div>
            <div class="detail-row reveal">
                <span class="detail-label"><i class="fas fa-map-marker-alt" style="margin-right:0.4rem;color:var(--accent-light)"></i>{{ __('about.location') }}</span>
                <span class="detail-value">Jakarta, Indonesia</span>
            </div>
            <div class="detail-row reveal">
                <span class="detail-label"><i class="fab fa-linkedin" style="margin-right:0.4rem;color:var(--accent-light)"></i>{{ __('about.linkedin') }}</span>
                <span class="detail-value">
                    <a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank">muzaki-abdullah-irsyad</a>
                </span>
            </div>
        </div>

        <div class="education-experience">
            <div class="education reveal-left">
                <h3><i class="fas fa-university" style="margin-right:0.5rem;"></i>{{ __('about.education_section') }}</h3>
                <div class="edu-item">
                    <h4>{{ __('about.edu_nf') }}</h4>
                    <p>{{ __('about.edu_nf_desc') }}</p>
                    <p style="color:var(--accent-light);font-size:0.82rem;margin-top:0.4rem;">{{ __('about.edu_nf_detail') }}</p>
                </div>
                <div class="edu-item">
                    <h4>{{ __('about.edu_cipta') }}</h4>
                    <p>{{ __('about.edu_cipta_desc') }}</p>
                    <p style="color:var(--text-muted);font-size:0.82rem;margin-top:0.4rem;">Feb – Jun 2024</p>
                </div>
                <div class="edu-item">
                    <h4>{{ __('about.edu_infinite') }}</h4>
                    <p>{{ __('about.edu_infinite_desc') }}</p>
                    <p style="color:var(--text-muted);font-size:0.82rem;margin-top:0.4rem;">Aug – Dec 2023</p>
                </div>
                <div class="edu-item">
                    <h4>{{ __('about.edu_smkn') }}</h4>
                    <p>{{ __('about.edu_smkn_desc') }}</p>
                </div>
            </div>

            <div class="work-exp reveal-left" style="transition-delay:0.15s;">
                <h3><i class="fas fa-briefcase" style="margin-right:0.5rem;"></i>{{ __('about.work_experience') }}</h3>
                <div class="exp-item">
                    <h4>{{ __('about.exp_pranata') }}</h4>
                    <p>{{ __('about.exp_pranata_place') }}</p>
                    <p style="color:var(--accent-light);font-size:0.82rem;margin-top:0.4rem;">{{ __('about.exp_present') }}</p>
                    <ul style="color:var(--text-secondary);font-size:0.85rem;margin-top:0.5rem;padding-left:1.2rem;line-height:1.8;">
                        <li>{{ __('about.exp_pranata_1') }}</li>
                        <li>{{ __('about.exp_pranata_2') }}</li>
                        <li>{{ __('about.exp_pranata_3') }}</li>
                    </ul>
                </div>
                <div class="exp-item">
                    <h4>{{ __('about.exp_onexpert') }}</h4>
                    <p>{{ __('about.exp_onexpert_place') }}</p>
                    <p style="color:var(--text-muted);font-size:0.82rem;margin-top:0.4rem;">Dec 2022 – Dec 2024</p>
                    <ul style="color:var(--text-secondary);font-size:0.85rem;margin-top:0.5rem;padding-left:1.2rem;line-height:1.8;">
                        <li>{{ __('about.exp_onexpert_1') }}</li>
                        <li>{{ __('about.exp_onexpert_2') }}</li>
                    </ul>
                </div>
                <div class="exp-item">
                    <h4>{{ __('about.exp_lecturer') }}</h4>
                    <p>STT Terpadu Nurul Fikri</p>
                    <p style="color:var(--text-muted);font-size:0.82rem;margin-top:0.4rem;">Sep 2023 – Jan 2025</p>
                    <ul style="color:var(--text-secondary);font-size:0.85rem;margin-top:0.5rem;padding-left:1.2rem;line-height:1.8;">
                        <li>{{ __('about.exp_lecturer_1') }}</li>
                        <li>{{ __('about.exp_lecturer_2') }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div style="text-align:center;">
            <a href="{{ asset('cv/muzaki-abdullah-irsyad.pdf') }}" class="btn btn-primary" download>
                <i class="fas fa-download"></i> {{ __('about.download_cv') }}
            </a>
        </div>
    </div>
</section>

<!-- ── SKILLS SECTION ── -->
<section class="skills" id="skills">
    <div class="container">
        <div style="text-align:center; margin-bottom:0.5rem;">
            <span class="section-label">What I Know</span>
        </div>
        <h2 class="section-title reveal">{{ __('skills.title') }}</h2>
        <p class="section-subtitle reveal">{{ __('skills.subtitle') }}</p>

        <!-- Tab Buttons -->
        <div class="skills-tabs reveal">
            <button class="skills-tab-btn active" data-tab="web" id="tab-web">
                <i class="fas fa-globe"></i> Web Dev
            </button>
            <button class="skills-tab-btn" data-tab="devops" id="tab-devops">
                <i class="fas fa-server"></i> DevOps & Cloud
            </button>
            <button class="skills-tab-btn" data-tab="tools" id="tab-tools">
                <i class="fas fa-tools"></i> Tools
            </button>
            <button class="skills-tab-btn" data-tab="soft" id="tab-soft">
                <i class="fas fa-users"></i> Soft Skills
            </button>
        </div>

        <!-- Panel: Web Dev -->
        <div class="skills-panel active" id="panel-web">
            <div class="skills-grid">
                @foreach([
                    ['icon'=>'fab fa-php',       'name'=>'PHP'],
                    ['icon'=>'fab fa-laravel',    'name'=>'Laravel'],
                    ['icon'=>'fab fa-js-square',  'name'=>'JavaScript'],
                    ['icon'=>'fab fa-html5',      'name'=>'HTML5'],
                    ['icon'=>'fab fa-css3-alt',   'name'=>'CSS3'],
                    ['icon'=>'fab fa-react',      'name'=>'React'],
                    ['icon'=>'fas fa-database',   'name'=>'MySQL'],
                    ['icon'=>'fas fa-database',   'name'=>'PostgreSQL'],
                    ['icon'=>'fab fa-python',     'name'=>'Python'],
                    ['icon'=>'fas fa-code',       'name'=>'REST API'],
                    ['icon'=>'fas fa-mobile-alt', 'name'=>'Responsive Design'],
                    ['icon'=>'fab fa-bootstrap',  'name'=>'Bootstrap'],
                ] as $skill)
                <div class="skill-item reveal">
                    <i class="{{ $skill['icon'] }} skill-icon"></i>
                    <span class="skill-name">{{ $skill['name'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Panel: DevOps & Cloud -->
        <div class="skills-panel" id="panel-devops">
            <div class="skills-grid">
                @foreach([
                    ['icon'=>'fab fa-linux',         'name'=>'Linux'],
                    ['icon'=>'fab fa-docker',        'name'=>'Docker'],
                    ['icon'=>'fab fa-aws',           'name'=>'AWS'],
                    ['icon'=>'fas fa-cloud',         'name'=>'GCP'],
                    ['icon'=>'fab fa-git-alt',       'name'=>'Git'],
                    ['icon'=>'fab fa-github',        'name'=>'GitHub'],
                    ['icon'=>'fas fa-shield-alt',    'name'=>'Nginx'],
                    ['icon'=>'fas fa-network-wired', 'name'=>'Networking'],
                    ['icon'=>'fas fa-terminal',      'name'=>'Bash/Shell'],
                    ['icon'=>'fas fa-infinity',      'name'=>'CI/CD'],
                ] as $skill)
                <div class="skill-item reveal">
                    <i class="{{ $skill['icon'] }} skill-icon"></i>
                    <span class="skill-name">{{ $skill['name'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Panel: Tools -->
        <div class="skills-panel" id="panel-tools">
            <div class="skills-grid">
                @foreach([
                    ['icon'=>'fas fa-code',         'name'=>'VS Code'],
                    ['icon'=>'fas fa-bug',          'name'=>'Postman'],
                    ['icon'=>'fab fa-figma',        'name'=>'Figma'],
                    ['icon'=>'fas fa-database',     'name'=>'DBeaver'],
                    ['icon'=>'fas fa-table',        'name'=>'MS Office'],
                    ['icon'=>'fab fa-trello',       'name'=>'Trello'],
                    ['icon'=>'fab fa-slack',        'name'=>'Slack'],
                    ['icon'=>'fas fa-project-diagram','name'=>'Mermaid'],
                    ['icon'=>'fas fa-robot',        'name'=>'AI Tools'],
                ] as $skill)
                <div class="skill-item reveal">
                    <i class="{{ $skill['icon'] }} skill-icon"></i>
                    <span class="skill-name">{{ $skill['name'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Panel: Soft Skills -->
        <div class="skills-panel" id="panel-soft">
            <div class="skills-grid">
                @foreach([
                    ['icon'=>'fas fa-comments',         'name'=>'Communication'],
                    ['icon'=>'fas fa-users',            'name'=>'Teamwork'],
                    ['icon'=>'fas fa-lightbulb',        'name'=>'Problem Solving'],
                    ['icon'=>'fas fa-clock',            'name'=>'Time Management'],
                    ['icon'=>'fas fa-book-open',        'name'=>'Fast Learner'],
                    ['icon'=>'fas fa-chalkboard-teacher','name'=>'Teaching'],
                    ['icon'=>'fas fa-tasks',            'name'=>'Project Mgmt'],
                    ['icon'=>'fas fa-language',         'name'=>'Bilingual ID/EN'],
                ] as $skill)
                <div class="skill-item reveal">
                    <i class="{{ $skill['icon'] }} skill-icon"></i>
                    <span class="skill-name">{{ $skill['name'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="skills-cta">
            <a href="#contact" class="btn btn-outline">
                <i class="fas fa-handshake"></i> {{ __('skills.cta') }}
            </a>
        </div>
    </div>
</section>

<!-- ── FEATURED WORKS SECTION ── -->
<section class="works" id="works">
    <div class="container">
        <div style="text-align:center; margin-bottom:0.5rem;">
            <span class="section-label">Featured</span>
        </div>
        <h2 class="section-title reveal">{{ __('works.title') }}</h2>
        <p class="section-subtitle reveal">{{ __('works.subtitle') }}</p>

        <div class="works-grid">
            @foreach($featuredWorks ?? [] as $work)
            <div class="work-card reveal">
                <div class="work-icon">
                    <i class="{{ $work['icon'] ?? 'fas fa-code' }}"></i>
                </div>
                <h3>{{ $work['title'] }}</h3>
                <p>{{ $work['description'] }}</p>
                <div class="work-tags">
                    @foreach($work['tags'] ?? [] as $tag)
                    <span>{{ $tag }}</span>
                    @endforeach
                </div>
                @if(!empty($work['live_url']) || !empty($work['github_url']))
                <div class="work-links">
                    @if(!empty($work['live_url']))
                    <a href="{{ $work['live_url'] }}" class="work-link work-link-demo" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-external-link-alt"></i> {{ __('works.live_demo') }}
                    </a>
                    @endif
                    @if(!empty($work['github_url']))
                    <a href="{{ $work['github_url'] }}" class="work-link work-link-github" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-github"></i> {{ __('works.github') }}
                    </a>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <div class="works-cta">
            <a href="#github-projects" class="btn btn-outline">
                <i class="fas fa-arrow-down"></i> {{ __('works.view_all') }}
            </a>
        </div>
    </div>
</section>

<!-- ── GITHUB PROJECTS SECTION ── -->
<section class="github-section" id="github-projects">
    <div class="container">
        <div style="text-align:center; margin-bottom:0.5rem;">
            <span class="section-label">Open Source</span>
        </div>
        <h2 class="section-title reveal">GitHub Repositories</h2>
        <p class="section-subtitle reveal">
            Repositories saya di GitHub — live dari GitHub API
        </p>

        <!-- Language Filter -->
        <div class="github-filter reveal" id="githubFilter">
            <button class="filter-btn active" data-lang="all">All</button>
            <button class="filter-btn" data-lang="Blade">Blade/PHP</button>
            <button class="filter-btn" data-lang="Python">Python</button>
            <button class="filter-btn" data-lang="JavaScript">JavaScript</button>
            <button class="filter-btn" data-lang="HTML">HTML/CSS</button>
        </div>

        <!-- Cards Container -->
        <div class="github-grid" id="githubGrid">
            <div class="github-loading">
                <div class="loading-spinner"></div>
                <p>Fetching repositories from GitHub...</p>
            </div>
        </div>

        <div class="github-cta">
            <a href="https://github.com/Muzaki29?tab=repositories"
               target="_blank" rel="noopener noreferrer"
               class="btn btn-outline">
                <i class="fab fa-github"></i> View All on GitHub
            </a>
        </div>
    </div>
</section>

<!-- ── TIMELINE SECTION ── -->
<section class="timeline" id="timeline">
    <div class="container">
        <div style="text-align:center; margin-bottom:0.5rem;">
            <span class="section-label">Journey</span>
        </div>
        <h2 class="section-title reveal">{{ __('timeline.title') }}</h2>
        <p class="section-subtitle reveal">{{ __('timeline.subtitle') }}</p>

        <div class="timeline-items" id="timelineItems">
            <div class="timeline-item">
                <div class="timeline-date">{{ __('timeline.present') }}</div>
                <div class="timeline-content">
                    <h3>{{ __('timeline.item_pranata') }}</h3>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-date">Sep 2024 – Jan 2025</div>
                <div class="timeline-content">
                    <h3>{{ __('timeline.item_lecturer_psi') }}</h3>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-date">Feb 2024 – Jun 2024</div>
                <div class="timeline-content">
                    <h3>{{ __('timeline.item_codeless') }}</h3>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-date">Sep 2023 – Jul 2024</div>
                <div class="timeline-content">
                    <h3>{{ __('timeline.item_lecturer_devops') }}</h3>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-date">Aug 2023 – Dec 2023</div>
                <div class="timeline-content">
                    <h3>{{ __('timeline.item_webdev') }}</h3>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-date">Dec 2022 – Dec 2024</div>
                <div class="timeline-content">
                    <h3>{{ __('timeline.item_onexpert') }}</h3>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-date">2022 – {{ __('timeline.present') }}</div>
                <div class="timeline-content">
                    <h3>{{ __('timeline.item_karangtaruna') }}</h3>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-date">Oct 2019 – Mar 2020</div>
                <div class="timeline-content">
                    <h3>{{ __('timeline.item_trainee') }}</h3>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-date">2021 – 2025</div>
                <div class="timeline-content">
                    <h3>{{ __('timeline.item_informatics') }}</h3>
                </div>
            </div>
        </div>

        <div class="achievements">
            <div class="achievement-item reveal-scale">
                <span class="achievement-number counter-number">3.86</span>
                <div class="achievement-label">{{ __('timeline.achievement_gpa') }}</div>
            </div>
            <div class="achievement-item reveal-scale" style="transition-delay:0.1s;">
                <span class="achievement-number counter-number">4</span>
                <div class="achievement-label">{{ __('timeline.achievement_certs') }}</div>
            </div>
            <div class="achievement-item reveal-scale" style="transition-delay:0.2s;">
                <span class="achievement-number counter-number">2</span>
                <div class="achievement-label">{{ __('timeline.achievement_years') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- ── CERTIFICATIONS SECTION ── -->
<section class="certifications" id="certifications">
    <div class="container">
        <div style="text-align:center; margin-bottom:0.5rem;">
            <span class="section-label">Credentials</span>
        </div>
        <h2 class="section-title reveal">{{ __('certs.title') }}</h2>
        <p class="section-subtitle reveal">{{ __('certs.subtitle') }}</p>

        <div class="certs-grid">
            @foreach($certifications ?? [] as $category)
            <div class="cert-category reveal">
                <h3><i class="fas fa-certificate"></i>{{ $category['category'] }}</h3>
                <div class="cert-list">
                    @foreach($category['items'] ?? [] as $item)
                    <div class="cert-item">
                        <span class="cert-name">{{ $item['name'] }}</span>
                        @if(!empty($item['url']))
                        <a href="{{ $item['url'] }}" class="cert-credential-link" target="_blank" rel="noopener noreferrer">
                            @if(str_ends_with(strtolower($item['url']), '.pdf'))
                                <i class="fas fa-download"></i> {{ __('certs.download') }}
                            @else
                                <i class="fas fa-external-link-alt"></i> {{ __('certs.view_credential') }}
                            @endif
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── CONTACT SECTION ── -->
<section class="contact" id="contact">
    <div class="container">
        <div style="text-align:center; margin-bottom:0.5rem;">
            <span class="section-label">Get In Touch</span>
        </div>
        <h2 class="section-title reveal">{{ __('contact.title') }}</h2>
        <p class="section-subtitle reveal">{{ __('contact.subtitle') }}</p>

        <div class="contact-content">
            <div class="contact-info">
                <div class="contact-card reveal">
                    <i class="fab fa-linkedin"></i>
                    <div>
                        <strong>{{ __('contact.linkedin') }}</strong>
                        <span><a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank">Muzaki Abdullah Irsyad</a></span>
                    </div>
                </div>
                <div class="contact-card reveal">
                    <i class="fab fa-github"></i>
                    <div>
                        <strong>{{ __('contact.github') }}</strong>
                        <span><a href="https://github.com/Muzaki29" target="_blank">@Muzaki29</a></span>
                    </div>
                </div>
                <div class="contact-card reveal">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>{{ __('contact.email') }}</strong>
                        <span><a href="mailto:contact@muzakiabdullahirsyad.my.id">contact@muzakiabdullahirsyad.my.id</a></span>
                    </div>
                </div>
                <div class="contact-card reveal">
                    <i class="fas fa-graduation-cap"></i>
                    <div>
                        <strong>{{ __('contact.education') }}</strong>
                        <span>STT Terpadu Nurul Fikri</span>
                    </div>
                </div>
                <div class="contact-card reveal">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>{{ __('contact.location') }}</strong>
                        <span>Jakarta, Indonesia</span>
                    </div>
                </div>
                <div class="contact-card reveal">
                    <i class="fas fa-star"></i>
                    <div>
                        <strong>{{ __('contact.gpa') }}</strong>
                        <span>3.86 / 4.00</span>
                    </div>
                </div>
            </div>

            <div class="contact-form-card reveal">
                <h3>{{ __('contact.form_title') }}</h3>
                <p>{{ __('contact.form_desc') }}</p>

                <form id="contactForm" class="contact-form">
                    @csrf
                    <div class="form-group">
                        <input type="text" id="name" name="name"
                               placeholder="{{ __('contact.placeholder_name') }}" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email"
                               placeholder="{{ __('contact.placeholder_email') }}" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="subject" name="subject"
                               placeholder="{{ __('contact.placeholder_subject') }}" required>
                    </div>
                    <div class="form-group">
                        <textarea id="message" name="message" rows="5"
                                  placeholder="{{ __('contact.placeholder_message') }}" required></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn" style="width:100%;justify-content:center;">
                            <span id="submitText"><i class="fas fa-paper-plane"></i> {{ __('contact.send') }}</span>
                            <span id="submitLoading" style="display:none;">
                                <i class="fas fa-spinner fa-spin"></i> {{ __('contact.sending') }}
                            </span>
                        </button>
                    </div>
                    <div id="formMessage" class="form-message" style="display:none;"
                         role="status" aria-live="polite" aria-atomic="true"></div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ── FOOTER ── -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <p>Muzaki Abdullah Irsyad</p>
                <p class="footer-copyright">© {{ date('Y') }} Muzaki Abdullah Irsyad. {{ __('footer.rights') }}</p>
            </div>
            <div class="footer-links">
                <ul>
                    <li><a href="#about">{{ __('footer.about') }}</a></li>
                    <li><a href="#works">{{ __('footer.works') }}</a></li>
                    <li><a href="#github-projects">Projects</a></li>
                    <li><a href="#contact">{{ __('footer.contact') }}</a></li>
                    <li><a href="#certifications">{{ __('footer.certifications') }}</a></li>
                </ul>
                <div class="footer-social">
                    <a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://github.com/Muzaki29" target="_blank" aria-label="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="mailto:contact@muzakiabdullahirsyad.my.id" aria-label="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- ── Scripts ── -->
<script>
    window.i18n = window.i18n || {};
    window.i18n.contact = {
        successFallback:  @json(__('contact.success_message')),
        errorFallback:    @json(__('contact.error_fallback')),
        validationPrefix: @json(__('contact.validation_prefix')),
        genericRetryLater:@json(__('contact.generic_retry_later')),
    };
</script>
<script src="{{ asset('js/portfolio.js') }}"></script>
</body>
</html>
