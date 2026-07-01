<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Muzaki Abdullah Irsyad — Informatics Engineer, Full-Stack Web Developer & IT Support. Building modern web experiences from Jakarta, Indonesia.">
    <meta name="author" content="Muzaki Abdullah Irsyad">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>Muzaki Abdullah Irsyad — Portfolio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}?v=1.8">

    <script>
        (function(){
            var t = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
</head>
<body>

<canvas id="bg-canvas"></canvas>
<div id="scroll-bar"></div>
<div class="cursor" id="cursor"></div>
<div class="cursor-dot" id="cursorDot"></div>

{{-- ── NAVBAR ── --}}
<nav class="navbar" id="navbar">
    <div class="container">
        <div class="nav-brand" onclick="window.scrollTo({top:0,behavior:'smooth'})">Muzaki.dev</div>

        <ul class="nav-pill" id="navMenu">
            <li><a href="#about"          class="nav-link">{{ __('nav.about') }}</a></li>
            <li><a href="#experience"     class="nav-link">{{ __('nav.experience') }}</a></li>
            <li><a href="#skills"         class="nav-link">{{ __('nav.skills') }}</a></li>
            <li><a href="#works"          class="nav-link">{{ __('nav.works') }}</a></li>
            <li><a href="#certifications" class="nav-link">{{ __('nav.certifications') }}</a></li>
            <li><a href="#contact"        class="nav-link">{{ __('nav.contact') }}</a></li>
        </ul>

        <div class="nav-actions">
            <div class="lang-toggle">
                <a href="{{ route('locale.switch', ['locale'=>'id']) }}"
                   class="lang-option {{ app()->getLocale()==='id' ? 'active' : '' }}">ID</a>
                <span class="lang-sep">|</span>
                <a href="{{ route('locale.switch', ['locale'=>'en']) }}"
                   class="lang-option {{ app()->getLocale()==='en' ? 'active' : '' }}">EN</a>
            </div>
            <button class="theme-btn" id="themeToggle" aria-label="Toggle theme">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
        </div>

        <div class="hamburger" id="ham"><span></span><span></span><span></span></div>
    </div>
</nav>

{{-- ── HERO ── --}}
<section class="hero" id="hero">
    <div class="container">
        <div class="hero-grid">

            {{-- Left: Text --}}
            <div>
                <div class="hero-badge">
                    <span class="pulse-dot"></span> Available for work
                </div>
                <p class="hero-greeting">{{ __('hero.greeting') }}</p>
                <h1 class="hero-name">Muzaki <span class="hl">Abdullah</span><br>Irsyad</h1>
                <div class="tw-wrap">
                    <span id="tw-prefix">I am a </span><span class="tw-text" id="tw-text"></span><span class="tw-cursor"></span>
                </div>
                <p class="hero-desc">
                    {{ __('hero.title_1') }} — {{ __('hero.title_2') }}.
                    Building practical, high-quality digital solutions.
                </p>
                <div class="hero-btns">
                    <a href="#contact" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> {{ __('hero.hire_me') }}
                    </a>
                    <a href="{{ asset('cv/muzaki-abdullah-irsyad.pdf') }}" class="btn btn-outline" download>
                        <i class="fas fa-download"></i> Download CV
                    </a>
                </div>
                <div class="hero-socials">
                    <a href="https://github.com/Muzaki29" target="_blank" rel="noopener" class="soc-ic" aria-label="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank" rel="noopener" class="soc-ic" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="mailto:contact@muzakiabdullahirsyad.my.id" class="soc-ic" aria-label="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>

            {{-- Right: Profile --}}
            <div class="profile-image-wrap">
                <div class="profile-wrap">
                    <div class="profile-glow"></div>
                    <div class="profile-ring"></div>
                    <div class="profile-circle">
                        <img src="{{ asset('images/profil.png') }}"
                             alt="Muzaki Abdullah Irsyad"
                             onerror="this.parentElement.innerHTML='<div class=\'profile-ph\'><i class=\'fas fa-user\'></i></div>'">
                    </div>
                </div>
            </div>
        </div>

        {{-- Service Cards --}}
        <div class="hero-services">
            <div class="svc-card">
                <i class="fas fa-code"></i>
                <h3>{{ __('hero.service_software') }}</h3>
            </div>
            <div class="svc-card">
                <i class="fas fa-server"></i>
                <h3>{{ __('hero.service_it') }}</h3>
            </div>
            <div class="svc-card">
                <i class="fas fa-graduation-cap"></i>
                <h3>{{ __('hero.service_teaching') }}</h3>
            </div>
        </div>
    </div>
</section>

{{-- ── ABOUT ── --}}
<section class="about-section" id="about">
    <div class="container">
        <div style="text-align:center"><span class="sec-label">Who I Am</span></div>
        <h2 class="section-title">{{ __('about.title') }}</h2>
        <p class="about-desc">{{ __('about.description') }}</p>

        <div class="details-grid">
            <div class="det-row">
                <span class="det-lbl"><i class="fas fa-briefcase" style="color:var(--blue2);margin-right:.4rem"></i>{{ __('about.experience') }}</span>
                <span class="det-val">{{ __('about.experience_value') }}</span>
            </div>
            <div class="det-row">
                <span class="det-lbl"><i class="fas fa-tools" style="color:var(--blue2);margin-right:.4rem"></i>{{ __('about.specialty') }}</span>
                <span class="det-val">{{ __('about.specialty_value') }}</span>
            </div>
            <div class="det-row">
                <span class="det-lbl"><i class="fas fa-graduation-cap" style="color:var(--blue2);margin-right:.4rem"></i>{{ __('about.education') }}</span>
                <span class="det-val">STT Terpadu Nurul Fikri</span>
            </div>
            <div class="det-row">
                <span class="det-lbl"><i class="fas fa-star" style="color:var(--blue2);margin-right:.4rem"></i>{{ __('about.gpa') }}</span>
                <span class="det-val">3.86 / 4.00</span>
            </div>
            <div class="det-row">
                <span class="det-lbl"><i class="fas fa-map-marker-alt" style="color:var(--blue2);margin-right:.4rem"></i>{{ __('about.location') }}</span>
                <span class="det-val">Jakarta, Indonesia</span>
            </div>
            <div class="det-row">
                <span class="det-lbl"><i class="fab fa-linkedin" style="color:var(--blue2);margin-right:.4rem"></i>LinkedIn</span>
                <span class="det-val">
                    <a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank">muzaki-abdullah-irsyad</a>
                </span>
            </div>
        </div>

        <div class="edu-wrap">
            <h3 class="edu-title"><i class="fas fa-university" style="margin-right:.5rem"></i>{{ __('about.education_section') }}</h3>
            <div class="edu-grid">
                <div class="edu-item">
                    <span class="edu-year">2021 – 2025</span>
                    <h4>{{ __('about.edu_nf') }}</h4>
                    <p>{{ __('about.edu_nf_desc') }}</p>
                    <p style="color:var(--blue2);font-size:.8rem;margin-top:.3rem">{{ __('about.edu_nf_detail') }}</p>
                </div>
                <div class="edu-item">
                    <span class="edu-year">Feb – Jun 2024</span>
                    <h4>{{ __('about.edu_cipta') }}</h4>
                    <p>{{ __('about.edu_cipta_desc') }}</p>
                </div>
                <div class="edu-item">
                    <span class="edu-year">Aug – Dec 2023</span>
                    <h4>{{ __('about.edu_infinite') }}</h4>
                    <p>{{ __('about.edu_infinite_desc') }}</p>
                </div>
                <div class="edu-item">
                    <span class="edu-year">Graduated</span>
                    <h4>{{ __('about.edu_smkn') }}</h4>
                    <p>{{ __('about.edu_smkn_desc') }}</p>
                </div>
            </div>
        </div>

        <div style="text-align:center;margin-top:2.5rem">
            <a href="{{ asset('cv/muzaki-abdullah-irsyad.pdf') }}" class="btn btn-primary" download>
                <i class="fas fa-download"></i> {{ __('about.download_cv') }}
            </a>
        </div>
    </div>
</section>

{{-- ── WORK EXPERIENCE (Alternating Timeline) ── --}}
<section class="experience-section" id="experience">
    <div class="container">
        <div style="text-align:center"><span class="sec-label">{{ __('experience.sec_label') }}</span></div>
        <h2 class="section-title">{{ __('experience.title') }}</h2>
        <p class="section-subtitle">{{ __('experience.subtitle') }}</p>

        <div class="timeline-experience">
            <div class="exp-line"></div>

            {{-- Experience Item 1: Pranata Komputer --}}
            <div class="exp-timeline-item left">
                <div class="exp-dot-pulsing"></div>
                <div class="exp-card">
                    <div class="exp-header">
                        <div class="exp-icon-circle"><i class="fas fa-briefcase"></i></div>
                        <div class="exp-title-info">
                            <h3>{{ __('experience.role_pranata') }}</h3>
                            <p class="exp-company">{{ __('experience.comp_pranata') }}</p>
                        </div>
                    </div>
                    <div class="exp-body">
                        <p class="exp-desc-text">{{ __('experience.desc_pranata') }}</p>
                        <ul class="exp-bullets-list">
                            <li>{!! __('experience.bullet_pranata_1') !!}</li>
                            <li>{!! __('experience.bullet_pranata_2') !!}</li>
                            <li>{!! __('experience.bullet_pranata_3') !!}</li>
                            <li>{!! __('experience.bullet_pranata_4') !!}</li>
                            <li>{!! __('experience.bullet_pranata_5') !!}</li>
                            <li>{!! __('experience.bullet_pranata_6') !!}</li>
                        </ul>
                    </div>
                    <div class="exp-footer">
                        <span class="exp-year-badge">{{ __('experience.date_pranata') }}</span>
                        <div class="exp-actions">
                            <button class="exp-btn-cert" data-open-cert="maganghub"><i class="fas fa-certificate"></i> {{ __('experience.cert_btn') }}</button>
                            <a href="https://github.com/Muzaki29/website-presensi-magang-muspen" target="_blank" class="exp-btn-work">{{ __('experience.work_btn') }} <i class="fas fa-arrow-up-right-from-square"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Experience Item 2: OneXpert --}}
            <div class="exp-timeline-item right">
                <div class="exp-dot-pulsing"></div>
                <div class="exp-card">
                    <div class="exp-header">
                        <div class="exp-icon-circle"><i class="fas fa-briefcase"></i></div>
                        <div class="exp-title-info">
                            <h3>{{ __('experience.role_onexpert') }}</h3>
                            <p class="exp-company">{{ __('experience.comp_onexpert') }}</p>
                        </div>
                    </div>
                    <div class="exp-body">
                        <p class="exp-desc-text">{{ __('experience.desc_onexpert') }}</p>
                        <ul class="exp-bullets-list">
                            <li>{{ __('experience.bullet_onexpert_1') }}</li>
                            <li>{{ __('experience.bullet_onexpert_2') }}</li>
                            <li>{{ __('experience.bullet_onexpert_3') }}</li>
                        </ul>
                    </div>
                    <div class="exp-footer">
                        <span class="exp-year-badge">{{ __('experience.date_onexpert') }}</span>
                        <div class="exp-actions">
                            <a href="https://github.com/Muzaki29" target="_blank" class="exp-btn-work">{{ __('experience.work_btn') }} <i class="fas fa-arrow-up-right-from-square"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Experience Item 3: Assistant Lecturer --}}
            <div class="exp-timeline-item left">
                <div class="exp-dot-pulsing"></div>
                <div class="exp-card">
                    <div class="exp-header">
                        <div class="exp-icon-circle"><i class="fas fa-briefcase"></i></div>
                        <div class="exp-title-info">
                            <h3>{{ __('experience.role_lecturer') }}</h3>
                            <p class="exp-company">{{ __('experience.comp_lecturer') }}</p>
                        </div>
                    </div>
                    <div class="exp-body">
                        <p class="exp-desc-text">{{ __('experience.desc_lecturer') }}</p>
                        <ul class="exp-bullets-list">
                            <li>{{ __('experience.bullet_lecturer_1') }}</li>
                            <li>{{ __('experience.bullet_lecturer_2') }}</li>
                            <li>{{ __('experience.bullet_lecturer_3') }}</li>
                        </ul>
                    </div>
                    <div class="exp-footer">
                        <span class="exp-year-badge">{{ __('experience.date_lecturer') }}</span>
                        <div class="exp-actions">
                            <button class="exp-btn-cert" data-open-cert="asdos_devops"><i class="fas fa-certificate"></i> {{ __('experience.cert_btn') }}</button>
                            <a href="https://github.com/Muzaki29/web-mes-depok" target="_blank" class="exp-btn-work">{{ __('experience.work_btn') }} <i class="fas fa-arrow-up-right-from-square"></i></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ── SKILLS ── --}}
<section class="skills-section" id="skills">
    <div class="container">
        <div style="text-align:center"><span class="sec-label">What I Know</span></div>
        <h2 class="section-title">{{ __('skills.title') }}</h2>
        <p class="section-subtitle">Teknologi yang paling sering saya gunakan untuk membangun sistem handal</p>

        <div class="skills-tabs">
            <button class="tab-btn active" data-tab="languages"><i class="fas fa-code"></i> Languages</button>
            <button class="tab-btn" data-tab="frontend"><i class="fas fa-laptop-code"></i> Frontend</button>
            <button class="tab-btn" data-tab="backend"><i class="fas fa-server"></i> Backend</button>
            <button class="tab-btn" data-tab="deployment"><i class="fas fa-cloud-upload-alt"></i> DevOps & Cloud</button>
        </div>

        <div class="skills-panel active" id="panel-languages">
            <div class="sk-card-grid">
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fab fa-php"></i></div>
                    <span class="sk-card-name">PHP</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fab fa-js-square"></i></div>
                    <span class="sk-card-name">JavaScript</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fab fa-python"></i></div>
                    <span class="sk-card-name">Python</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fas fa-database"></i></div>
                    <span class="sk-card-name">SQL</span>
                </div>
            </div>
        </div>

        <div class="skills-panel" id="panel-frontend">
            <div class="sk-card-grid">
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fab fa-react"></i></div>
                    <span class="sk-card-name">React.js</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fas fa-cube"></i></div>
                    <span class="sk-card-name">Next.js</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fas fa-wind"></i></div>
                    <span class="sk-card-name">Tailwind CSS</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fab fa-html5"></i></div>
                    <span class="sk-card-name">HTML/CSS</span>
                </div>
            </div>
        </div>

        <div class="skills-panel" id="panel-backend">
            <div class="sk-card-grid">
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fab fa-laravel"></i></div>
                    <span class="sk-card-name">Laravel</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fab fa-node-js"></i></div>
                    <span class="sk-card-name">Node.js</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fas fa-database"></i></div>
                    <span class="sk-card-name">MySQL</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fas fa-database"></i></div>
                    <span class="sk-card-name">PostgreSQL</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fas fa-code"></i></div>
                    <span class="sk-card-name">REST API</span>
                </div>
            </div>
        </div>

        <div class="skills-panel" id="panel-deployment">
            <div class="sk-card-grid">
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fab fa-linux"></i></div>
                    <span class="sk-card-name">Linux</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fab fa-docker"></i></div>
                    <span class="sk-card-name">Docker</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fab fa-github"></i></div>
                    <span class="sk-card-name">Git/GitHub</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fas fa-shield-alt"></i></div>
                    <span class="sk-card-name">Nginx</span>
                </div>
                <div class="sk-card">
                    <div class="sk-card-circle"><i class="fas fa-infinity"></i></div>
                    <span class="sk-card-name">CI/CD</span>
                </div>
            </div>
        </div>

        <div style="text-align:center;margin-top:2.5rem">
            <a href="#contact" class="btn btn-outline"><i class="fas fa-handshake"></i> {{ __('skills.cta') }}</a>
        </div>
    </div>
</section>

{{-- ── STUFF I BUILT ── --}}
<section class="works-section" id="works">
    <div class="container">
        <div style="text-align:center"><span class="sec-label">{{ __('works.sec_label') }}</span></div>
        <h2 class="section-title">{{ __('works.title') }}</h2>
        <p class="section-subtitle">{{ __('works.subtitle') }}</p>

        <div class="works-grid">

            @foreach(config('portfolio.works_projects', []) as $project)
            <div class="project-grid-card">
                <div class="pgc-image {{ $project['gradient'] }}">
                    <img src="{{ asset($project['image']) }}"
                         alt="{{ __($project['title_key']) }}"
                         onerror="this.outerHTML='<div class=\'pc-img-placeholder\'><i class=\'{{ $project['placeholder_icon'] }}\'></i><span>{{ $project['placeholder_label'] }}</span></div>'">
                </div>
                <div class="pgc-info">
                    <div class="pgc-header">
                        <h3 class="pgc-name">{{ __($project['title_key']) }}</h3>
                        @if(($project['link_type'] ?? '') === 'github' && !empty($project['github_url']))
                        <a href="{{ $project['github_url'] }}" target="_blank" rel="noopener" class="pgc-visit" aria-label="Github Repo">
                            <i class="fab fa-github"></i>
                        </a>
                        @elseif(($project['link_type'] ?? '') === 'live' && !empty($project['live_url']))
                        <a href="{{ $project['live_url'] }}" target="_blank" rel="noopener" class="pgc-visit" aria-label="Live Site">
                            <i class="fas fa-globe"></i>
                        </a>
                        @endif
                    </div>
                    <p class="pgc-short-desc">{{ __($project['short_key']) }}</p>

                    <div class="pgc-details-template" style="display:none">
                        <div class="pm-detail-header">
                            <h3 class="pm-detail-title">{{ __($project['title_key']) }}</h3>
                            <span class="pm-detail-tagline">{{ __($project['tagline_key']) }}</span>
                        </div>
                        <p class="pm-detail-desc">{{ __($project['desc_key']) }}</p>
                        <h4 class="pm-sub-heading"><i class="fas fa-star" style="color:var(--blue2)"></i> {{ __('works.features_heading') }}</h4>
                        <ul class="pc-bullets">
                            @foreach($project['bullets'] ?? [] as $bulletKey)
                            <li>{!! __($bulletKey) !!}</li>
                            @endforeach
                        </ul>
                        <h4 class="pm-sub-heading"><i class="fas fa-tools" style="color:var(--blue2)"></i> {{ __('works.tech_stack') }}</h4>
                        <div class="pc-tags">
                            @foreach($project['tags'] ?? [] as $tag)
                            <span class="pc-tag"><i class="{{ $tag['icon'] }}"></i> {{ $tag['label'] }}</span>
                            @endforeach
                        </div>
                        <div class="pm-detail-footer">
                            @if(!empty($project['github_url']))
                            <a href="{{ $project['github_url'] }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm"><i class="fab fa-github"></i> Repository</a>
                            @elseif(!empty($project['live_url']))
                            <a href="{{ $project['live_url'] }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm"><i class="fas fa-globe"></i> {{ __($project['footer_label_key'] ?? 'works.live_demo') }}</a>
                            @endif
                        </div>
                    </div>

                    <button class="btn btn-outline btn-sm btn-view-details" style="width:100%;margin-top:1rem;justify-content:center"><i class="fas fa-search-plus"></i> {{ __('works.view_details') }}</button>
                </div>
            </div>
            @endforeach

        </div>

        <div style="text-align:center;margin-top:2.5rem">
            <a href="https://github.com/Muzaki29?tab=repositories" target="_blank" rel="noopener"
               class="btn btn-outline">
                <i class="fab fa-github"></i> See All on GitHub
            </a>
        </div>
    </div>
</section>



{{-- ── TIMELINE ── --}}
<section class="timeline-section" id="timeline">
    <div class="container">
        <div style="text-align:center"><span class="sec-label">Journey</span></div>
        <h2 class="section-title">{{ __('timeline.title') }}</h2>
        <p class="section-subtitle">{{ __('timeline.subtitle') }}</p>

        <div class="tl-items">
            <div class="tl-item">
                <div class="tl-date">24 Nov 2025 – 24 Mei 2026</div>
                <div class="tl-content"><h3>{{ __('timeline.item_pranata') }}</h3></div>
            </div>
            <div class="tl-item">
                <div class="tl-date">Sep 2024 – Jan 2025</div>
                <div class="tl-content"><h3>{{ __('timeline.item_lecturer_psi') }}</h3></div>
            </div>
            <div class="tl-item">
                <div class="tl-date">Feb 2024 – Jun 2024</div>
                <div class="tl-content"><h3>{{ __('timeline.item_codeless') }}</h3></div>
            </div>
            <div class="tl-item">
                <div class="tl-date">Sep 2023 – Jul 2024</div>
                <div class="tl-content"><h3>{{ __('timeline.item_lecturer_devops') }}</h3></div>
            </div>
            <div class="tl-item">
                <div class="tl-date">Aug 2023 – Dec 2023</div>
                <div class="tl-content"><h3>{{ __('timeline.item_webdev') }}</h3></div>
            </div>
            <div class="tl-item">
                <div class="tl-date">Dec 2022 – Dec 2024</div>
                <div class="tl-content"><h3>{{ __('timeline.item_onexpert') }}</h3></div>
            </div>
            <div class="tl-item">
                <div class="tl-date">2022 – {{ __('timeline.present') }}</div>
                <div class="tl-content"><h3>{{ __('timeline.item_karangtaruna') }}</h3></div>
            </div>
            <div class="tl-item">
                <div class="tl-date">Oct 2019 – Mar 2020</div>
                <div class="tl-content"><h3>{{ __('timeline.item_trainee') }}</h3></div>
            </div>
            <div class="tl-item">
                <div class="tl-date">2021 – 2025</div>
                <div class="tl-content"><h3>{{ __('timeline.item_informatics') }}</h3></div>
            </div>
        </div>

        <div class="achievements">
            <div class="ach-item">
                <span class="ach-num" id="counter-gpa">3.86</span>
                <div class="ach-lbl">{{ __('timeline.achievement_gpa') }}</div>
            </div>
            <div class="ach-item">
                <span class="ach-num" id="counter-certs">4</span>
                <div class="ach-lbl">{{ __('timeline.achievement_certs') }}</div>
            </div>
            <div class="ach-item">
                <span class="ach-num" id="counter-years">2+</span>
                <div class="ach-lbl">{{ __('timeline.achievement_years') }}</div>
            </div>
        </div>
    </div>
</section>

{{-- ── CERTIFICATIONS ── --}}
<section class="certs-section" id="certifications">
    <div class="container">
        <div style="text-align:center"><span class="sec-label">Credentials</span></div>
        <h2 class="section-title">{{ __('certs.title') }}</h2>
        <p class="section-subtitle">{{ __('certs.subtitle') }}</p>
        
        <div class="certs-visual-grid">

            {{-- Cert 1: MSIB Batch 6 Page 1 & 2 (Codeless Data Science) Slider --}}
            <div class="cert-card cert-card-slider" id="cert-slider-codeless" data-cert-img="{{ asset('images/certificates/msib_batch_6_page1.jpg') }}" data-cert-title="Sertifikat & Transkrip MSIB Batch 6 - Codeless Data Science" data-cert-issuer="Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi x PT Nurul Fikri Cipta Inovasi">
                <div class="cert-img-wrap cert-slider-wrap">
                    <div class="cert-slides">
                        <div class="cert-slide active" data-img-url="{{ asset('images/certificates/msib_batch_6_page1.jpg') }}">
                            <img src="{{ asset('images/certificates/msib_batch_6_page1.jpg') }}" alt="Sertifikat MSIB Batch 6 Page 1">
                        </div>
                        <div class="cert-slide" data-img-url="{{ asset('images/certificates/msib_batch_6_page2.jpg') }}">
                            <img src="{{ asset('images/certificates/msib_batch_6_page2.jpg') }}" alt="Sertifikat MSIB Batch 6 Page 2">
                        </div>
                    </div>
                    
                    {{-- Slider Controls --}}
                    <button class="cert-slide-btn prev-btn" aria-label="Previous Slide"><i class="fas fa-chevron-left"></i></button>
                    <button class="cert-slide-btn next-btn" aria-label="Next Slide"><i class="fas fa-chevron-right"></i></button>
                    
                    <div class="cert-slide-dots">
                        <span class="slide-dot active" data-slide-to="0"></span>
                        <span class="slide-dot" data-slide-to="1"></span>
                    </div>

                    <div class="cert-hover-overlay">
                        <i class="fas fa-search-plus"></i>
                        <span>{{ __('certs.view_credential') }}</span>
                    </div>
                </div>
                <div class="cert-info-wrap">
                    <span class="cert-badge">MSIB Batch 6 (2 Pages)</span>
                    <h3>Sertifikat & Transkrip Codeless Data Science</h3>
                    <p class="cert-issuer">PT Nurul Fikri Cipta Inovasi</p>
                    <p class="cert-date">Feb – Jun 2024</p>
                </div>
            </div>

            {{-- Cert 2: MSIB Batch 5 Page 1-4 (Infinite Learning) Slider --}}
            <div class="cert-card cert-card-slider" id="cert-slider-infinite" data-cert-img="{{ asset('images/certificates/msib_batch_5_page1.jpg') }}" data-cert-title="Sertifikat & Transkrip MSIB Batch 5 - Web Development" data-cert-issuer="Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi x Infinite Learning">
                <div class="cert-img-wrap cert-slider-wrap">
                    <div class="cert-slides">
                        <div class="cert-slide active" data-img-url="{{ asset('images/certificates/msib_batch_5_page1.jpg') }}">
                            <img src="{{ asset('images/certificates/msib_batch_5_page1.jpg') }}" alt="Sertifikat MSIB Batch 5 Page 1">
                        </div>
                        <div class="cert-slide" data-img-url="{{ asset('images/certificates/msib_batch_5_page2.jpg') }}">
                            <img src="{{ asset('images/certificates/msib_batch_5_page2.jpg') }}" alt="Sertifikat MSIB Batch 5 Page 2">
                        </div>
                        <div class="cert-slide" data-img-url="{{ asset('images/certificates/msib_batch_5_page3.jpg') }}">
                            <img src="{{ asset('images/certificates/msib_batch_5_page3.jpg') }}" alt="Sertifikat MSIB Batch 5 Page 3">
                        </div>
                        <div class="cert-slide" data-img-url="{{ asset('images/certificates/msib_batch_5_page4.jpg') }}">
                            <img src="{{ asset('images/certificates/msib_batch_5_page4.jpg') }}" alt="Sertifikat MSIB Batch 5 Page 4">
                        </div>
                    </div>
                    
                    {{-- Slider Controls --}}
                    <button class="cert-slide-btn prev-btn" aria-label="Previous Slide"><i class="fas fa-chevron-left"></i></button>
                    <button class="cert-slide-btn next-btn" aria-label="Next Slide"><i class="fas fa-chevron-right"></i></button>
                    
                    <div class="cert-slide-dots">
                        <span class="slide-dot active" data-slide-to="0"></span>
                        <span class="slide-dot" data-slide-to="1"></span>
                        <span class="slide-dot" data-slide-to="2"></span>
                        <span class="slide-dot" data-slide-to="3"></span>
                    </div>

                    <div class="cert-hover-overlay">
                        <i class="fas fa-search-plus"></i>
                        <span>{{ __('certs.view_credential') }}</span>
                    </div>
                </div>
                <div class="cert-info-wrap">
                    <span class="cert-badge">MSIB Batch 5 (4 Pages)</span>
                    <h3>Sertifikat & Transkrip Web Development</h3>
                    <p class="cert-issuer">Infinite Learning</p>
                    <p class="cert-date">Aug – Dec 2023</p>
                </div>
            </div>

            {{-- Cert 3: Magenta BUMN --}}
            <div class="cert-card" data-cert-img="{{ asset('images/certificates/magenta.png') }}" data-cert-title="Sertifikat Magang Magenta BUMN" data-cert-issuer="Kementerian BUMN / Forum Human Capital Indonesia (FHCI)">
                <div class="cert-img-wrap">
                    <img src="{{ asset('images/certificates/magenta.png') }}" alt="Sertifikat Magenta BUMN">
                    <div class="cert-hover-overlay">
                        <i class="fas fa-search-plus"></i>
                        <span>{{ __('certs.view_credential') }}</span>
                    </div>
                </div>
                <div class="cert-info-wrap">
                    <span class="cert-badge">Magenta BUMN</span>
                    <h3>Magang Kerja BUMN</h3>
                    <p class="cert-issuer">Kementerian BUMN & FHCI</p>
                    <p class="cert-date">2024</p>
                </div>
            </div>

            {{-- Cert 4: Maganghub Batch 2 --}}
            <div class="cert-card" data-cert-img="{{ asset('images/certificates/maganghub_batch_2.png') }}" data-cert-title="Sertifikat Maganghub Batch 2" data-cert-issuer="Maganghub Indonesia">
                <div class="cert-img-wrap">
                    <img src="{{ asset('images/certificates/maganghub_batch_2.png') }}" alt="Sertifikat Maganghub Batch 2">
                    <div class="cert-hover-overlay">
                        <i class="fas fa-search-plus"></i>
                        <span>{{ __('certs.view_credential') }}</span>
                    </div>
                </div>
                <div class="cert-info-wrap">
                    <span class="cert-badge">Maganghub</span>
                    <h3>Maganghub Batch 2</h3>
                    <p class="cert-issuer">Maganghub Indonesia</p>
                    <p class="cert-date">24 November 2025 – 24 Mei 2026</p>
                </div>
            </div>

            {{-- Cert 5: NETS 2023 --}}
            <div class="cert-card" data-cert-img="{{ asset('images/certificates/nets_2023.jpg') }}" data-cert-title="Sertifikat Seminar Nasional (NETS) 2023" data-cert-issuer="National Education and Technology Seminar">
                <div class="cert-img-wrap">
                    <img src="{{ asset('images/certificates/nets_2023.jpg') }}" alt="Sertifikat NETS 2023">
                    <div class="cert-hover-overlay">
                        <i class="fas fa-search-plus"></i>
                        <span>{{ __('certs.view_credential') }}</span>
                    </div>
                </div>
                <div class="cert-info-wrap">
                    <span class="cert-badge">Seminar Nasional</span>
                    <h3>Seminar Nasional NETS 2023</h3>
                    <p class="cert-issuer">National Education & Technology</p>
                    <p class="cert-date">2023</p>
                </div>
            </div>

            {{-- Cert 6: HMPSTI --}}
            <div class="cert-card" data-cert-img="{{ asset('images/certificates/hmpsti.png') }}" data-cert-title="Sertifikat Pengurus HMPSTI STT NF" data-cert-issuer="Himpunan Mahasiswa Program Studi Teknologi Informasi">
                <div class="cert-img-wrap">
                    <img src="{{ asset('images/certificates/hmpsti.png') }}" alt="Sertifikat HMPSTI">
                    <div class="cert-hover-overlay">
                        <i class="fas fa-search-plus"></i>
                        <span>{{ __('certs.view_credential') }}</span>
                    </div>
                </div>
                <div class="cert-info-wrap">
                    <span class="cert-badge">Organisasi</span>
                    <h3>HMPSTI STT NF</h3>
                    <p class="cert-issuer">Himpunan Mahasiswa TI STT NF</p>
                    <p class="cert-date">2023 – 2024</p>
                </div>
            </div>

            {{-- Cert 7: DevOps Assistant Praktikum --}}
            <div class="cert-card" data-cert-img="{{ asset('images/certificates/asdos_devops.png') }}" data-cert-title="Sertifikat Asisten Praktikum DevOps 2023/2024" data-cert-issuer="STT Terpadu Nurul Fikri">
                <div class="cert-img-wrap">
                    <img src="{{ asset('images/certificates/asdos_devops.png') }}" alt="Sertifikat Asisten Praktikum DevOps">
                    <div class="cert-hover-overlay">
                        <i class="fas fa-search-plus"></i>
                        <span>{{ __('certs.view_credential') }}</span>
                    </div>
                </div>
                <div class="cert-info-wrap">
                    <span class="cert-badge">Asisten Dosen</span>
                    <h3>Asisten Praktikum DevOps</h3>
                    <p class="cert-issuer">STT Terpadu Nurul Fikri</p>
                    <p class="cert-date">Genap 2023/2024</p>
                </div>
            </div>

        </div>
    </div>

    {{-- Lightbox Modal --}}
    <div class="cert-lightbox" id="certLightbox" role="dialog" aria-hidden="true">
        <span class="lightbox-close" id="lightboxClose">&times;</span>
        
        {{-- Lightbox Slide Navigation --}}
        <button class="lightbox-btn prev-btn" id="lightboxPrev" aria-label="Previous Slide"><i class="fas fa-chevron-left"></i></button>
        <button class="lightbox-btn next-btn" id="lightboxNext" aria-label="Next Slide"><i class="fas fa-chevron-right"></i></button>
        
        <div class="lightbox-content-wrap">
            <img class="lightbox-img" id="lightboxImg" src="" alt="Certificate View">
            <div class="lightbox-caption" id="lightboxCaption">
                <h3 id="lightboxTitle"></h3>
                <p id="lightboxIssuer"></p>
            </div>
        </div>
    </div>

    {{-- Project Details Modal --}}
    <div class="project-modal" id="projectModal" role="dialog" aria-hidden="true">
        <div class="pm-backdrop" id="pmBackdrop"></div>
        <div class="pm-content">
            <span class="pm-close" id="pmCloseBtn">&times;</span>
            <div class="pm-body" id="pmModalBody">
                {{-- Filled dynamically by JS --}}
            </div>
        </div>
    </div>
</section>

{{-- ── CONTACT ── --}}
<section class="contact-section" id="contact">
    <div class="container">
        <div style="text-align:center"><span class="sec-label">Get In Touch</span></div>
        <h2 class="section-title">{{ __('contact.title') }}</h2>
        <p class="section-subtitle">{{ __('contact.subtitle') }}</p>

        <div class="contact-grid">
            <div class="contact-cards">
                <div class="ct-card"><i class="fab fa-linkedin"></i><div><strong>{{ __('contact.linkedin') }}</strong><span><a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank">Muzaki Abdullah Irsyad</a></span></div></div>
                <div class="ct-card"><i class="fab fa-github"></i><div><strong>{{ __('contact.github') }}</strong><span><a href="https://github.com/Muzaki29" target="_blank">@Muzaki29</a></span></div></div>
                <div class="ct-card"><i class="fas fa-envelope"></i><div><strong>{{ __('contact.email') }}</strong><span><a href="mailto:contact@muzakiabdullahirsyad.my.id">contact@muzakiabdullahirsyad.my.id</a></span></div></div>
                <div class="ct-card"><i class="fas fa-graduation-cap"></i><div><strong>{{ __('contact.education') }}</strong><span>STT Terpadu Nurul Fikri</span></div></div>
                <div class="ct-card"><i class="fas fa-map-marker-alt"></i><div><strong>{{ __('contact.location') }}</strong><span>Jakarta, Indonesia</span></div></div>
                <div class="ct-card"><i class="fas fa-star"></i><div><strong>{{ __('contact.gpa') }}</strong><span>3.86 / 4.00</span></div></div>
            </div>

            <div class="form-split-card">
                <div class="form-visual-side">
                    <div class="form-visual-overlay"></div>
                    <img src="{{ asset('images/cta_invitation_banner.png') }}?v=1.6" alt="Muzaki Abdullah Irsyad" onerror="this.src='{{ asset('images/profil.jpg.jpg') }}'">
                    <div class="form-visual-content">
                        <p class="form-visual-tag"><i class="fas fa-paper-plane"></i> {{ __('contact.visual_tag') }}</p>
                        <p class="form-visual-text">{{ __('contact.form_desc') }}</p>
                    </div>
                </div>
                <div class="form-input-side">
                    <h3>{{ __('contact.form_title') }}</h3>
                    <form id="contactForm" class="contact-form">
                        @csrf
                        <input type="text"  name="name"    placeholder="{{ __('contact.placeholder_name') }}"    required>
                        <input type="email" name="email"   placeholder="{{ __('contact.placeholder_email') }}"   required>
                        <input type="text"  name="subject" placeholder="{{ __('contact.placeholder_subject') }}"  required>
                        <textarea name="message" rows="4"  placeholder="{{ __('contact.placeholder_message') }}" required></textarea>
                        
                        {{-- Premium Math CAPTCHA --}}
                        <div class="captcha-row" style="display: flex; gap: 0.8rem; align-items: center; margin-top: 0.2rem;">
                            <div class="captcha-question" style="background: var(--blue-dim); border: 1px solid var(--border2); color: var(--blue2); padding: 0.8rem 1.1rem; border-radius: var(--r); font-weight: 700; font-family: 'Fira Code', monospace; font-size: 0.95rem; white-space: nowrap; user-select: none;">
                                {{ $captchaQuestion }} = ?
                            </div>
                            <input type="number" name="captcha" placeholder="{{ __('contact.placeholder_captcha', ['question' => '']) }}" required style="margin: 0; flex: 1;">
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitBtn" style="width:100%;justify-content:center">
                            <span id="submitText"><i class="fas fa-paper-plane"></i> {{ __('contact.send') }}</span>
                            <span id="submitLoading" style="display:none"><i class="fas fa-spinner fa-spin"></i> {{ __('contact.sending') }}</span>
                        </button>
                        <div id="formMessage" class="form-msg" style="display:none" role="status" aria-live="polite"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── FOOTER ── --}}
<footer>
    <div class="container">
        <div class="footer-inner">
            <div class="footer-brand">
                <p>Muzaki Abdullah Irsyad</p>
                <p class="footer-copy">© {{ date('Y') }} Muzaki Abdullah Irsyad. {{ __('footer.rights') }}</p>
            </div>
            <div class="footer-nav">
                <ul>
                    <li><a href="#about">{{ __('footer.about') }}</a></li>
                    <li><a href="#works">{{ __('footer.works') }}</a></li>
                    <li><a href="#certifications">{{ __('footer.certifications') }}</a></li>
                    <li><a href="#contact">{{ __('footer.contact') }}</a></li>
                </ul>
                <div class="footer-soc">
                    <a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://github.com/Muzaki29" target="_blank" aria-label="GitHub"><i class="fab fa-github"></i></a>
                    <a href="mailto:contact@muzakiabdullahirsyad.my.id" aria-label="Email"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<button class="back-top" id="backTop" aria-label="Back to top"><i class="fas fa-arrow-up"></i></button>

{{-- ── AI CHATBOT WIDGET ── --}}
<div class="ai-chat-widget" id="aiChatWidget">
    <button class="ai-chat-bubble" id="aiChatBubble" aria-label="{{ __('chatbot.header_title') }}">
        <img src="{{ asset('images/profil.png') }}" alt="Muzaki Abdullah Irsyad" class="ai-bubble-avatar">
        <span class="ai-badge">AI</span>
    </button>
    <div class="ai-chat-window" id="aiChatWindow">
        <div class="ai-chat-header">
            <div class="ai-avatar">
                <img src="{{ asset('images/profil.png') }}" alt="Muzaki Abdullah Irsyad" class="ai-header-avatar">
                <span class="online-indicator"></span>
            </div>
            <div class="ai-header-info">
                <h4>{{ __('chatbot.header_title') }}</h4>
                <span>{{ __('chatbot.header_status') }}</span>
            </div>
            <button class="ai-close-btn" id="aiCloseBtn" aria-label="Tutup Chat">&times;</button>
        </div>
        <div class="ai-chat-body" id="aiChatBody">
            {{-- Bubbles injected by JS --}}
        </div>
        <div class="ai-quick-replies" id="aiQuickReplies">
            {{-- Dynamic Quick Replies --}}
        </div>
        <form class="ai-chat-input-area" id="aiChatForm">
            <input type="text" id="aiChatInput" placeholder="{{ __('chatbot.input_placeholder') }}" autocomplete="off" required>
            <button type="submit" id="aiSendBtn" aria-label="Kirim"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>

<script>
window.i18n = {
    contact: {
        successFallback:   @json(__('contact.success_message')),
        errorFallback:     @json(__('contact.error_fallback')),
        validationPrefix:  @json(__('contact.validation_prefix')),
        genericRetryLater: @json(__('contact.generic_retry_later')),
    }
};
</script>
<script src="{{ asset('js/portfolio.js') }}?v=1.8"></script>
</body>
</html>
