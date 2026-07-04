<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Muzaki Abdullah Irsyad — Full Stack Developer, Freelancer & Content Creator. Building modern web experiences from Jakarta, Indonesia.">
    <meta name="author" content="Muzaki Abdullah Irsyad">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">
    <title>Muzaki Abdullah Irsyad — Portfolio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Syne:wght@700;800&family=Fira+Code:wght@400;500&family=Playfair+Display:ital,wght@0,500;0,700;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}?v=3.17">

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
        <div class="nav-brand" onclick="window.scrollTo({top:0,behavior:'smooth'})">MUZAKI</div>

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

<div id="portfolio-export" class="portfolio-export-root">

{{-- ── HERO (minimal — foto + kicker + headline + CTA) ── --}}
<section class="hero hero-full hero-raymond" id="hero">
    <div class="hero-media" aria-hidden="true">
        <img src="{{ asset('images/profil_hero_studio.png') }}?v=1"
             alt="Muzaki Abdullah Irsyad"
             width="1920" height="1080">
        <div class="hero-media-overlay hero-raymond-overlay"></div>
    </div>

    <div class="hero-dock container">
        <div class="hero-dock-left">
            <h1 class="sr-only">Muzaki Abdullah Irsyad — Portfolio</h1>
            <p class="hero-kicker">{{ __('hero.kicker') }}</p>
            <div class="hero-headline">
                <p class="hero-role-line">
                    <span class="tw-text" id="tw-text"></span><span class="tw-cursor" aria-hidden="true"></span>
                </p>
            </div>
            <div class="hero-actions">
                <a href="#contact" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> {{ __('hero.hire_me') }}
                </a>
                <a href="{{ asset('cv/muzaki-abdullah-irsyad.pdf') }}" class="btn btn-outline" download>
                    <i class="fas fa-download"></i> {{ __('about.download_cv') }}
                </a>
            </div>
        </div>
    </div>
</section>

<div class="name-marquee" aria-hidden="true">
    <div class="marquee-track">
        @for ($i = 0; $i < 10; $i++)
        <span>FULL STACK · FREELANCER · CONTENT CREATOR • </span>
        @endfor
    </div>
</div>

<div class="hero-services-wrap">
    <div class="container">
        <div class="hero-services">
            <div class="svc-card">
                <i class="fas fa-layer-group"></i>
                <h3>{{ __('hero.role_fullstack') }}</h3>
            </div>
            <div class="svc-card">
                <i class="fas fa-briefcase"></i>
                <h3>{{ __('hero.role_freelancer') }}</h3>
            </div>
            <div class="svc-card">
                <i class="fas fa-video"></i>
                <h3>{{ __('hero.role_creator') }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- ── ABOUT ── --}}
<section class="about-section" id="about">
    <div class="container">
        <div style="text-align:center"><span class="sec-label">About</span></div>
        <h2 class="section-title">{{ __('about.title') }}</h2>
        <p class="about-desc">{{ __('about.description') }}</p>

        <div class="details-grid details-grid-minimal">
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

        <div class="resume-showcase resume-showcase-compact">
            <div class="resume-studio-art">
                <img src="{{ asset('images/resume-studio.png') }}?v=1"
                     alt="Muzaki Abdullah Irsyad — Full Stack Developer"
                     width="771" height="1024" loading="lazy">
            </div>
            <div class="resume-copy">
                <span class="sec-label">{{ __('about.resume_label') }}</span>
                <h3>{{ __('about.resume_title') }}</h3>
                <p>{{ __('about.resume_desc') }}</p>
                <div class="resume-actions">
                    <a href="{{ asset('cv/muzaki-abdullah-irsyad.pdf') }}" class="btn btn-primary" download>
                        <i class="fas fa-file-pdf"></i> {{ __('about.download_cv') }}
                    </a>
                    <div class="resume-export-actions">
                        <button type="button" class="btn btn-outline btn-sm" id="exportPortfolioPdf" aria-label="{{ __('footer.export_pdf') }}">
                            <i class="fas fa-file-export"></i> {{ __('footer.export_pdf') }}
                        </button>
                        <button type="button" class="btn btn-outline btn-sm" id="exportPortfolioPpt" aria-label="{{ __('footer.export_ppt') }}">
                            <i class="fas fa-file-powerpoint"></i> {{ __('footer.export_ppt') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── WORK EXPERIENCE (Raymond-style topics) ── --}}
<section class="experience-section exp-raymond-section" id="experience">
    <div class="container">
        <div class="exp-raymond-head">
            <span class="sec-label">{{ __('experience.sec_label') }}</span>
            <h2 class="section-title exp-raymond-title">{{ __('experience.title') }}<span class="exp-raymond-accent">.</span></h2>
            <p class="exp-raymond-lead">{{ __('experience.subtitle') }}</p>
        </div>

        <div class="exp-topics-grid">
            <article class="exp-topic">
                <span class="exp-topic-marker" aria-hidden="true"></span>
                <span class="exp-topic-num">01</span>
                <h3 class="exp-topic-title exp-topic-title-serif">{{ __('experience.role_pranata') }}</h3>
                <p class="exp-topic-meta">{{ __('experience.comp_pranata') }} · {{ __('experience.date_pranata') }}</p>
                <p class="exp-topic-desc">{{ __('experience.desc_pranata_short') }}</p>
                <div class="exp-topic-actions">
                    <a href="https://github.com/Muzaki29/website-presensi-magang-muspen" target="_blank" rel="noopener" class="exp-topic-link">{{ __('experience.work_btn') }} <i class="fas fa-arrow-up-right-from-square"></i></a>
                </div>
            </article>

            <article class="exp-topic">
                <span class="exp-topic-marker" aria-hidden="true"></span>
                <span class="exp-topic-num">02</span>
                <h3 class="exp-topic-title exp-topic-title-serif">{{ __('experience.role_onexpert') }}</h3>
                <p class="exp-topic-meta">{{ __('experience.comp_onexpert') }} · {{ __('experience.date_onexpert') }}</p>
                <p class="exp-topic-desc">{{ __('experience.desc_onexpert_short') }}</p>
                <div class="exp-topic-actions">
                    <a href="https://github.com/Muzaki29" target="_blank" rel="noopener" class="exp-topic-link">{{ __('experience.work_btn') }} <i class="fas fa-arrow-up-right-from-square"></i></a>
                </div>
            </article>

            <article class="exp-topic exp-topic-interactive exp-topic-minimal" role="button" tabindex="0" data-open-cert="asdos_devops" aria-label="{{ __('experience.lecturer_cert_hint') }}">
                <span class="exp-topic-marker" aria-hidden="true"></span>
                <span class="exp-topic-num">03</span>
                <h3 class="exp-topic-title exp-topic-title-serif">{{ __('experience.role_lecturer_short') }}</h3>
                <span class="exp-topic-cert-hint"><i class="fas fa-certificate"></i> {{ __('experience.lecturer_cert_hint') }}</span>
            </article>
        </div>

        <p class="exp-topics-counter" aria-live="polite">3 / 3 {{ __('experience.topics_label') }}</p>
    </div>
</section>

{{-- ── SKILLS ── --}}
<section class="skills-section" id="skills">
    <div class="container">
        <div style="text-align:center"><span class="sec-label">Skills</span></div>
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
            <div class="project-grid-card project-card-text-only">
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



{{-- ── TIMELINE / JOURNEY ── --}}
<section class="timeline-section" id="timeline">
    <div class="container">
        <div style="text-align:center"><span class="sec-label">Journey</span></div>
        <h2 class="section-title">{{ __('timeline.title') }}</h2>
        <p class="section-subtitle timeline-intro-sub">{{ __('timeline.subtitle') }}</p>

        <div class="timeline-journey-layout">
            <div class="timeline-story-art">
                <img src="{{ asset('images/journey-story.png') }}"
                     alt="{{ __('timeline.story_alt') }}"
                     loading="lazy"
                     width="900" height="1200">
            </div>

            <div class="tl-items tl-items-compact">
            <div class="tl-item">
                <div class="tl-date">24 Nov 2025 – 24 Mei 2026</div>
                <div class="tl-content"><h3>{{ __('timeline.item_pranata') }}</h3></div>
            </div>
            <div class="tl-item tl-item-cert" role="button" tabindex="0" data-open-cert="asdos_devops" aria-label="{{ __('experience.lecturer_cert_hint') }}">
                <div class="tl-date">Sep 2023 – Jan 2025</div>
                <div class="tl-content">
                    <h3>{{ __('timeline.item_lecturer_short') }}</h3>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-date">Feb 2024 – Jun 2024</div>
                <div class="tl-content"><h3>{{ __('timeline.item_codeless') }}</h3></div>
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
        </div>

        <div class="achievements">
            <div class="ach-item">
                <span class="ach-num" id="counter-projects">9+</span>
                <div class="ach-lbl">{{ __('hero.stat_projects') }}</div>
            </div>
            <div class="ach-item">
                <span class="ach-num" id="counter-certs">4</span>
                <div class="ach-lbl">{{ __('timeline.achievement_certs') }}</div>
            </div>
            <div class="ach-item">
                <span class="ach-num" id="counter-years">{{ __('hero.years_value') }}</span>
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
        <div style="text-align:center"><span class="sec-label">Contact</span></div>
        <h2 class="section-title">{{ __('contact.title') }}</h2>
        <p class="section-subtitle">{{ __('contact.subtitle') }}</p>

        <div class="contact-layout">
            <div class="contact-visual-panel">
                <img src="{{ asset('images/contact-lets-talk.png') }}"
                     alt="{{ __('contact.visual_alt') }}"
                     loading="lazy"
                     width="800" height="1200">
            </div>

            <div class="contact-main-panel">
                <div class="contact-cards contact-cards-minimal">
                    <div class="ct-card"><i class="fab fa-linkedin"></i><div><strong>{{ __('contact.linkedin') }}</strong><span><a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank">LinkedIn</a></span></div></div>
                    <div class="ct-card"><i class="fab fa-github"></i><div><strong>{{ __('contact.github') }}</strong><span><a href="https://github.com/Muzaki29" target="_blank">@Muzaki29</a></span></div></div>
                    <div class="ct-card"><i class="fas fa-envelope"></i><div><strong>{{ __('contact.email') }}</strong><span><a href="mailto:contact@muzakiabdullahirsyad.my.id">contact@muzakiabdullahirsyad.my.id</a></span></div></div>
                    <div class="ct-card"><i class="fas fa-map-marker-alt"></i><div><strong>{{ __('contact.location') }}</strong><span>Jakarta, Indonesia</span></div></div>
                </div>

                <div class="form-card form-card-minimal">
                <h3>{{ __('contact.form_title') }}</h3>
                <p class="form-card-lead">{{ __('contact.form_desc') }}</p>
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

</div>{{-- /#portfolio-export --}}

{{-- ── FOOTER ── --}}
<footer class="footer-clean">
    <div class="container">
        <div class="footer-clean-top">
            <div class="footer-clean-brand">
                <span class="footer-logo">MUZAKI</span>
                <p>{{ __('footer.tagline') }}</p>
            </div>
            <nav class="footer-clean-nav" aria-label="Footer">
                <a href="#about">{{ __('footer.about') }}</a>
                <a href="#works">{{ __('footer.works') }}</a>
                <a href="#certifications">{{ __('footer.certifications') }}</a>
                <a href="#contact">{{ __('footer.contact') }}</a>
            </nav>
            <div class="footer-soc footer-soc-clean">
                <a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://github.com/Muzaki29" target="_blank" aria-label="GitHub"><i class="fab fa-github"></i></a>
                <a href="mailto:contact@muzakiabdullahirsyad.my.id" aria-label="Email"><i class="fas fa-envelope"></i></a>
            </div>
        </div>
        <div class="footer-export-bar">
            <span class="footer-export-label">{{ __('footer.export_label') }}</span>
            <button type="button" class="btn btn-outline btn-sm footer-export-btn" id="exportPortfolioPdfFooter">
                <i class="fas fa-file-pdf"></i> {{ __('footer.export_pdf') }}
            </button>
            <button type="button" class="btn btn-outline btn-sm footer-export-btn" id="exportPortfolioPptFooter">
                <i class="fas fa-file-powerpoint"></i> {{ __('footer.export_ppt') }}
            </button>
        </div>
        <div class="footer-clean-bottom">
            <p>© {{ date('Y') }} Muzaki Abdullah Irsyad. {{ __('footer.rights') }}</p>
        </div>
    </div>
</footer>

<button class="back-top" id="backTop" aria-label="Back to top"><i class="fas fa-arrow-up"></i></button>

{{-- ── AI CHATBOT WIDGET ── --}}
<div class="ai-chat-widget" id="aiChatWidget">
    <button class="ai-chat-bubble" id="aiChatBubble" aria-label="{{ __('chatbot.header_title') }}">
        <i class="fas fa-robot ai-bubble-icon" aria-hidden="true"></i>
        <span class="ai-badge">AI</span>
    </button>
    <div class="ai-chat-window" id="aiChatWindow">
        <div class="ai-chat-header">
            <div class="ai-avatar ai-avatar-icon">
                <i class="fas fa-robot" aria-hidden="true"></i>
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

@php
    $exportProjects = collect(config('portfolio.works_projects', []))
        ->map(fn ($p) => __($p['title_key'] ?? ''))
        ->filter()
        ->values()
        ->all();
    $exportExperience = [
        ['role' => __('experience.role_pranata'), 'meta' => __('experience.comp_pranata') . ' · ' . __('experience.date_pranata'), 'desc' => __('experience.desc_pranata_short')],
        ['role' => __('experience.role_onexpert'), 'meta' => __('experience.comp_onexpert') . ' · ' . __('experience.date_onexpert'), 'desc' => __('experience.desc_onexpert_short')],
        ['role' => __('experience.role_lecturer_short'), 'meta' => __('experience.comp_lecturer') . ' · ' . __('experience.date_lecturer'), 'desc' => __('experience.desc_lecturer_short')],
    ];
    $portfolioExport = [
        'name' => 'Muzaki Abdullah Irsyad',
        'tagline' => __('hero.tagline'),
        'taglineShort' => __('hero.tagline_short'),
        'about' => __('about.description'),
        'years' => __('hero.years_value'),
        'projectsLabel' => __('hero.stat_projects'),
        'rolesLabel' => __('hero.stat_roles'),
        'yearsLabel' => __('timeline.achievement_years'),
        'experienceTitle' => __('experience.title'),
        'experience' => $exportExperience,
        'skillsTitle' => __('skills.title'),
        'skills' => ['PHP', 'JavaScript', 'Python', 'Laravel', 'React', 'Next.js', 'Node.js', 'MySQL', 'PostgreSQL', 'Docker', 'Git', 'Linux'],
        'projectsTitle' => __('works.title'),
        'projects' => $exportProjects,
        'contactTitle' => __('contact.title'),
        'contactEmail' => 'contact@muzakiabdullahirsyad.my.id',
        'contactLinkedIn' => 'linkedin.com/in/muzaki-abdullah-irsyad-893a98220',
        'contactGithub' => 'github.com/Muzaki29',
        'website' => 'muzakiabdullahirsyad.my.id',
        'exportPdf' => __('footer.export_pdf'),
        'exportPpt' => __('footer.export_ppt'),
        'exportLoading' => __('footer.export_loading'),
        'exportError' => __('footer.export_error'),
    ];
@endphp
<script>
window.i18n = {
    contact: {
        successFallback:   @json(__('contact.success_message')),
        errorFallback:     @json(__('contact.error_fallback')),
        validationPrefix:  @json(__('contact.validation_prefix')),
        genericRetryLater: @json(__('contact.generic_retry_later')),
    }
};
window.portfolioExport = @json($portfolioExport);
</script>
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.2/jspdf.umd.min.js" as="script" crossorigin="anonymous">
<link rel="preload" href="https://cdn.jsdelivr.net/npm/pptxgenjs@3.12.0/dist/pptxgen.bundle.js" as="script" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.2/jspdf.umd.min.js" defer crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/pptxgenjs@3.12.0/dist/pptxgen.bundle.js" defer crossorigin="anonymous"></script>
<script src="{{ asset('js/portfolio.js') }}?v=3.6" defer></script>
<script src="{{ asset('js/portfolio-export.js') }}?v=1.4" defer></script>
</body>
</html>
