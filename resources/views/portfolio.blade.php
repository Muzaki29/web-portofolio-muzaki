<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Muzaki Abdullah Irsyad — Full-Stack Developer building reliable digital products and operational systems, with QA, system analysis, and infrastructure experience. Jakarta, Indonesia.">
    <meta name="author" content="Muzaki Abdullah Irsyad">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Muzaki Abdullah Irsyad">
    <meta property="og:title" content="Muzaki Abdullah Irsyad — Portfolio">
    <meta property="og:description" content="Full Stack Developer, Freelancer & Content Creator — Jakarta, Indonesia.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('images/mai-social.png') }}">
    <meta property="og:image:width" content="1536">
    <meta property="og:image:height" content="1024">
    <meta property="og:image:alt" content="MAI — Muzaki Abdullah Irsyad monogram logo">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Muzaki Abdullah Irsyad — Portfolio">
    <meta name="twitter:description" content="Full Stack Developer, Freelancer & Content Creator — Jakarta, Indonesia.">
    <meta name="twitter:image" content="{{ asset('images/mai-social.png') }}">
    <meta name="twitter:image:alt" content="MAI — Muzaki Abdullah Irsyad monogram logo">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="sitemap" type="application/xml" href="{{ url('/sitemap.xml') }}">
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => 'Muzaki Abdullah Irsyad',
        'jobTitle' => 'AI-assisted Developer',
        'url' => url('/'),
        'email' => 'mailto:'.$profile['contact_email'],
        'image' => asset('images/mai-social.png'),
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Jakarta', 'addressCountry' => 'ID'],
        'knowsAbout' => ['Full-Stack Development', 'QA Engineering', 'System Analysis', 'IT Support & Infrastructure', 'Linux and Networking', 'System Administration', 'Digital Transformation'],
        'sameAs' => array_values(array_filter([
            $profile['linkedin_url'],
            $profile['github_username'] ? 'https://github.com/'.$profile['github_username'] : null,
            $profile['dribbble_url'],
            $profile['pinterest_url'],
            $profile['instagram_username'] ? 'https://www.instagram.com/'.$profile['instagram_username'].'/' : null,
            $profile['threads_username'] ? 'https://www.threads.com/@'.$profile['threads_username'] : null,
        ])),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=mai-2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}?v=mai-2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}?v=mai-2">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192.png') }}?v=mai-2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=mai-2">
    <title>Muzaki Abdullah Irsyad — Portfolio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Syne:wght@700;800&family=Fira+Code:wght@400;500&family=Playfair+Display:ital,wght@0,500;0,700;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}?v=4.4">
    <link rel="stylesheet" href="{{ asset('css/work.css') }}?v=2.8">
    @vite('resources/js/motion/index.js')
    <noscript><style>
        .skills-panel { display: block !important; margin-bottom: 2rem; }
        .pgc-details-template { display: block !important; }
        .btn-view-details, .skills-tabs, .hamburger, .ai-chat-widget { display: none !important; }
        @media (max-width: 768px) { .nav-pill { display: flex !important; position: static !important; flex-wrap: wrap; flex-direction: row !important; } .navbar { position: relative !important; } .navbar .container { flex-wrap: wrap; } }
    </style></noscript>

    <script>
        (function(){
            var t = 'dark';
            try { t = localStorage.getItem('theme') || t; } catch (e) {}
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
</head>
<body>
<a class="skip-link" href="#about">{{ __('motion.skip') }}</a>

<canvas id="bg-canvas" aria-hidden="true"></canvas>
<div id="scroll-bar" aria-hidden="true"></div>
<div class="cursor" id="cursor" aria-hidden="true"></div>
<div class="cursor-dot" id="cursorDot" aria-hidden="true"></div>

{{-- ── NAVBAR ── --}}
<nav class="navbar" id="navbar">
    <div class="container">
        <a class="nav-brand" href="#hero" aria-label="MAI — Muzaki Home">
            <svg class="nav-brand-logo" viewBox="323 143 890 590" aria-hidden="true">
                <polygon points="333,160 611,410 863,160 1178,722 1054,722 837,318 611,540 433,370 433,722 333,722"/>
                <polygon points="832,365 870,435 688,722 593,722"/>
                <polygon points="825,550 931,550 971,625 778,625"/>
                <polygon points="1102,224 1202,153 1202,250 1102,322"/>
                <polygon points="1102,350 1202,279 1202,722 1193,722 1102,562"/>
            </svg>
        </a>

        <ul class="nav-pill nav-v2" id="navMenu">
            <li><a href="#about"          class="nav-link">{{ __('nav.about') }}</a></li>
            <li><a href="#works"          class="nav-link">{{ __('nav.works') }}</a></li>
            <li><a href="#skills"         class="nav-link">{{ __('nav.skills') }}</a></li>
            @if(\App\Models\Setting::flag('show_experience'))<li><a href="#experience"     class="nav-link">{{ __('nav.experience') }}</a></li>@endif
            @if(\App\Models\Setting::flag('show_certifications'))<li><a href="#certifications" class="nav-link">{{ __('nav.certifications') }}</a></li>@endif
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

        <button type="button" class="hamburger" id="ham" aria-label="{{ __('motion.menu') }}" aria-controls="navMenu" aria-expanded="false"><span></span><span></span><span></span></button>
    </div>
</nav>

<main id="portfolio-export" class="portfolio-export-root">

{{-- ── HERO (minimal — foto + kicker + headline + CTA) ── --}}
<section class="hero hero-full hero-raymond hero-cinematic" id="hero">
    <div class="hero-atmosphere" aria-hidden="true"></div>
    <div class="hero-media" aria-hidden="true">
        <img src="{{ asset('images/hero-studio.webp') }}"
             alt="Muzaki Abdullah Irsyad"
             width="1920" height="1080" fetchpriority="high" decoding="async">
        <div class="hero-media-overlay hero-raymond-overlay"></div>
    </div>

    {{-- Hierarchy: small positioning + text CTAs top-left, giant name anchored bottom, role label and meta in the corners. --}}
    <div class="hero-v2 container">
        <div class="hero-v2-intro">
            <p class="hero-kicker">{{ $profile['hero_role'] }}</p>
            <p class="hero-positioning">{{ __('hero.positioning') }}</p>
            <p class="hero-supporting">{{ __('hero.supporting') }}</p>
            <div class="hero-actions">
                <a href="#works" class="hero-cta hero-cta-primary">{{ __('hero.cta_work') }} <span aria-hidden="true">↘</span></a>
                <a href="#contact" class="hero-cta">{{ __('hero.cta_contact') }}</a>
                <a href="{{ asset('cv/muzaki-abdullah-irsyad.pdf') }}" class="hero-cta" download>{{ __('about.download_cv') }}</a>
            </div>
        </div>

        <div class="hero-v2-base">
            <p class="hero-v2-role"><span>01/</span> AI-assisted<br>Developer</p>
            <h1 class="hero-name hero-v2-name" aria-label="Muzaki Abdullah Irsyad">
                <span class="hero-line"><span>Muzaki<span class="hero-period" aria-hidden="true">.</span></span></span>
                <span class="hero-v2-surname" aria-hidden="true">Abdullah Irsyad</span>
            </h1>
            <p class="hero-v2-meta">© {{ date('Y') }}<br>{{ $profile['location'] }}</p>
        </div>
        <div class="hero-v2-ruler" aria-hidden="true"><span>© {{ date('Y') }}</span><i></i><span>JKT</span></div>
    </div>
</section>

<div class="name-marquee" aria-hidden="true">
    <div class="marquee-track">
        @for ($i = 0; $i < 10; $i++)
        <span>AI-ASSISTED DEVELOPER · FULL-STACK · QA ENGINEERING · SYSTEM ANALYSIS • </span>
        @endfor
    </div>
</div>

<div class="hero-services-wrap">
    <div class="container">
        <div class="hero-services hero-services-six">
            <div class="svc-card">
                <i class="fas fa-layer-group" aria-hidden="true"></i>
                <p class="svc-title">{{ __('positioning.fullstack') }}</p>
            </div>
            <div class="svc-card">
                <i class="fas fa-vial-circle-check" aria-hidden="true"></i>
                <p class="svc-title">{{ __('positioning.qa') }}</p>
            </div>
            <div class="svc-card">
                <i class="fas fa-diagram-project" aria-hidden="true"></i>
                <p class="svc-title">{{ __('positioning.analysis') }}</p>
            </div>
            <div class="svc-card">
                <i class="fas fa-server" aria-hidden="true"></i>
                <p class="svc-title">{{ __('positioning.infra') }}</p>
            </div>
            <div class="svc-card">
                <i class="fab fa-linux" aria-hidden="true"></i>
                <p class="svc-title">{{ __('positioning.linux') }}</p>
            </div>
            <div class="svc-card">
                <i class="fas fa-arrows-rotate" aria-hidden="true"></i>
                <p class="svc-title">{{ __('positioning.transformation') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- ── ABOUT ── --}}
<section class="about-section" id="about" tabindex="-1">
    <div class="container">
        <header class="w-head">
            <span class="w-head-label">{{ __('label.about') }}</span>
            <h2 class="w-head-title">{{ $profile['about_title'] }}</h2>
        </header>
        <p class="about-desc">{{ $profile['about_description'] }}</p>

        <div class="details-grid details-grid-minimal">
            <div class="det-row">
                <span class="det-lbl"><i class="fas fa-map-marker-alt" style="color:var(--blue2);margin-right:.4rem"></i>{{ __('about.location') }}</span>
                <span class="det-val">{{ $profile['location'] }}</span>
            </div>
            <div class="det-row">
                <span class="det-lbl"><i class="fab fa-linkedin" style="color:var(--blue2);margin-right:.4rem"></i>LinkedIn</span>
                <span class="det-val">
                    <a href="{{ $profile['linkedin_url'] }}" target="_blank" rel="noopener">{{ $profile['linkedin_label'] }}</a>
                </span>
            </div>
        </div>

        <div class="resume-showcase resume-showcase-compact">
            <div class="resume-studio-art">
                <img src="{{ asset('images/resume-studio.webp') }}?v=1"
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

{{-- ── STUFF I BUILT ── --}}
<section class="works-section w-section" id="works" aria-labelledby="works-title">
    @php
        $workImg = fn ($p) => asset(str_starts_with($p['image'] ?? '', 'images/') ? preg_replace('/\.png$/', '.webp', $p['image']) : ($p['image'] ?? ''));
        $featuredWorks = collect($worksProjects)->where('featured', true)->take(6)->values();
        if ($featuredWorks->isEmpty()) { $featuredWorks = collect($worksProjects)->take(4)->values(); }
        $categories = collect(config('cms.project_categories'))
            ->map(fn ($c) => ['key' => $c, 'count' => collect($worksProjects)->where('category', $c)->count()])
            ->filter(fn ($c) => $c['count'] > 0)->values();
    @endphp
    <div class="w-container">
        <header class="w-head">
            <span class="w-head-label">{{ __('nav.works') }}</span>
            <h2 class="w-head-title" id="works-title">{{ __('work.featured_title') }}</h2>
            <p class="w-head-lead">{{ __('work.featured_lead') }}</p>
        </header>

        <div class="w-featured">
            @foreach($featuredWorks as $project)
            @php $stack = collect($project['tags'] ?? [])->pluck('label')->take(5); @endphp
            <article class="w-feature" data-reveal>
                <a class="w-feature-media w-media" href="{{ $project['url'] ?? '#works' }}" tabindex="-1" aria-hidden="true">
                    <img src="{{ $workImg($project) }}" alt="" width="1672" height="941" loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async">
                </a>
                <div class="w-feature-body">
                    <div class="w-feature-top">
                        <span class="w-eyebrow-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} — {{ __('work.cat_'.($project['category'] ?: 'web')) }}</span>
                        <span class="w-status" data-status="{{ $project['status'] }}">{{ __('work.status_'.$project['status']) }}</span>
                    </div>
                    <h3>@if($project['url'])<a href="{{ $project['url'] }}">{{ $project['title'] }}</a>@else{{ $project['title'] }}@endif</h3>
                    @if($project['project_type'])<p class="w-feature-type">{{ $project['project_type'] }}</p>@endif
                    @if($project['short'])<p class="w-feature-summary">{{ $project['short'] }}</p>@endif
                    @if($project['role'])
                    <dl class="w-meta"><dt>{{ __('work.role') }}</dt><dd>{{ $project['role'] }}</dd></dl>
                    @endif
                    @if($stack->isNotEmpty())
                    <ul class="w-chips" aria-label="{{ __('work.stack') }}">@foreach($stack as $t)<li class="w-chip">{{ $t }}</li>@endforeach</ul>
                    @endif
                    <div class="w-actions">
                        @if($project['url'])<a class="w-link" href="{{ $project['url'] }}">{{ __('work.case_study') }} <i class="fas fa-arrow-right" aria-hidden="true"></i></a>@endif
                        @if($project['live_url'])<a class="w-link" href="{{ $project['live_url'] }}" target="_blank" rel="noopener noreferrer">{{ __('work.live') }}<span class="sr-only"> — {{ $project['title'] }}</span> <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i></a>@endif
                        @if($project['github_url'])<a class="w-link" href="{{ $project['github_url'] }}" target="_blank" rel="noopener noreferrer">{{ __('work.repo') }}<span class="sr-only"> — {{ $project['title'] }}</span> <i class="fab fa-github" aria-hidden="true"></i></a>@endif
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>

    <div class="w-container" style="margin-top: var(--w-s9)">
        <header class="w-head w-head-sub">
            <h3 class="w-head-title" id="library-title">{{ __('work.library_title') }}</h3>
            <p class="w-head-lead">{{ __('work.library_lead') }} <span id="libraryCount" aria-live="polite">{{ __('work.count', ['count' => count($worksProjects)]) }}</span></p>
        </header>

        <div class="w-filters" role="group" aria-label="{{ __('work.filter_label') }}">
            <button type="button" class="w-filter" data-filter="all" aria-pressed="true">{{ __('work.cat_all') }}<span>{{ count($worksProjects) }}</span></button>
            @foreach($categories as $c)
            <button type="button" class="w-filter" data-filter="{{ $c['key'] }}" aria-pressed="false">{{ __('work.cat_'.$c['key']) }}<span>{{ $c['count'] }}</span></button>
            @endforeach
        </div>

        <div class="w-library" id="projectLibrary" data-count-template="{{ __('work.count', ['count' => '__N__']) }}">
            @foreach($worksProjects as $project)
            <article class="w-card" data-reveal data-category="{{ $project['category'] ?: 'web' }}">
                <div class="w-card-media"><img src="{{ $workImg($project) }}" alt="{{ $project['title'] }} — {{ $project['project_type'] ?: __('motion.preview') }}" width="1672" height="941" loading="lazy" decoding="async"></div>
                <div class="w-card-body">
                    <div class="w-card-top">
                        <span class="w-eyebrow-num">{{ __('work.cat_'.($project['category'] ?: 'web')) }}</span>
                        <span class="w-status" data-status="{{ $project['status'] }}">{{ __('work.status_'.$project['status']) }}</span>
                    </div>
                    <h3>@if($project['url'])<a href="{{ $project['url'] }}">{{ $project['title'] }}</a>@else{{ $project['title'] }}@endif</h3>
                    @if($project['short'])<p>{{ $project['short'] }}</p>@endif
                    <ul class="w-chips" aria-label="{{ __('work.stack') }}">@foreach(collect($project['tags'] ?? [])->pluck('label')->take(3) as $t)<li class="w-chip">{{ $t }}</li>@endforeach</ul>
                    @if($project['live_url'] || $project['github_url'])
                    <div class="w-card-links">
                        @if($project['live_url'])<a class="w-link" href="{{ $project['live_url'] }}" target="_blank" rel="noopener noreferrer">{{ __('work.live') }}<span class="sr-only"> — {{ $project['title'] }}</span></a>@endif
                        @if($project['github_url'])<a class="w-link" href="{{ $project['github_url'] }}" target="_blank" rel="noopener noreferrer">{{ __('work.repo') }}<span class="sr-only"> — {{ $project['title'] }}</span></a>@endif
                    </div>
                    @endif
                </div>
            </article>
            @endforeach
        </div>
        <p class="w-empty" id="libraryEmpty" hidden>{{ __('work.empty_filter') }}</p>
    </div>
</section>

{{-- ── SKILLS ── --}}
<section class="skills-section" id="skills">
    <div class="container">
        <header class="w-head">
            <span class="w-head-label">{{ __('label.skills') }}</span>
            <h2 class="w-head-title">{{ __('skills.title') }}</h2>
            <p class="w-head-lead">Teknologi yang paling sering saya gunakan untuk membangun sistem handal</p>
        </header>

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

@if(\App\Models\Setting::flag('show_experience'))
{{-- ── WORK EXPERIENCE (Raymond-style topics) ── --}}
<section class="experience-section exp-raymond-section" id="experience">
    <div class="container">
        <header class="w-head">
            <span class="w-head-label">{{ __('experience.sec_label') }}</span>
            <h2 class="w-head-title">{{ __('experience.title') }}</h2>
            <p class="w-head-lead">{{ __('experience.subtitle') }}</p>
        </header>

        <div class="exp-topics-grid">
            @foreach($experiences as $exp)
            @php $highlights = $exp->lines('highlights'); $certKey = $exp->v('cert_key'); $minimal = $certKey && ! $exp->v('description'); @endphp
            <article class="exp-topic {{ $minimal ? 'exp-topic-interactive exp-topic-minimal' : '' }}" @if($minimal) role="button" tabindex="0" data-open-cert="{{ $certKey }}" @endif>
                <span class="exp-topic-marker" aria-hidden="true"></span>
                <span class="exp-topic-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                <h3 class="exp-topic-title exp-topic-title-serif">{{ $exp->v('role') }}</h3>
                @if($exp->v('company') || $exp->v('period'))
                <p class="exp-topic-meta">{{ collect([$exp->v('company'), $exp->v('period')])->filter()->implode(' · ') }}</p>
                @endif
                @if($exp->v('location'))<p class="exp-topic-location">{{ $exp->v('location') }}</p>@endif
                @if($exp->v('description'))<p class="exp-topic-desc">{{ $exp->v('description') }}</p>@endif
                @if($highlights)
                <ul class="exp-topic-highlights">
                    @foreach($highlights as $line)<li>{{ $line }}</li>@endforeach
                </ul>
                @endif
                @if($exp->v('link_url') || ($certKey && ! $minimal))
                <div class="exp-topic-actions">
                    @if($exp->v('link_url'))
                    <a href="{{ $exp->v('link_url') }}" target="_blank" rel="noopener" class="exp-topic-link">{{ __('experience.work_btn') }} <i class="fas fa-arrow-up-right-from-square"></i></a>
                    @endif
                    @if($certKey && ! $minimal)
                    <button type="button" class="exp-topic-link exp-btn-cert" data-open-cert="{{ $certKey }}"><i class="fas fa-certificate"></i> {{ __('experience.lecturer_cert_hint') }}</button>
                    @endif
                </div>
                @elseif($minimal)
                <span class="exp-topic-cert-hint"><i class="fas fa-certificate"></i> {{ __('experience.lecturer_cert_hint') }}</span>
                @endif
            </article>
            @endforeach
        </div>

        <p class="exp-topics-counter" aria-live="polite">{{ $experiences->count() }} / {{ $experiences->count() }} {{ __('experience.topics_label') }}</p>
    </div>
</section>
@endif

@if(\App\Models\Setting::flag('show_timeline'))
{{-- ── TIMELINE / JOURNEY ── --}}
<section class="timeline-section" id="timeline">
    <div class="container">
        <header class="w-head">
            <span class="w-head-label">{{ __('label.journey') }}</span>
            <h2 class="w-head-title">{{ __('timeline.title') }}</h2>
            <p class="w-head-lead">{{ __('timeline.subtitle') }}</p>
        </header>

        <div class="timeline-journey-layout w-journey">
            <div class="timeline-story-art">
                <img src="{{ asset('images/journey-story.webp') }}"
                     alt="{{ __('timeline.story_alt') }}"
                     loading="lazy"
                     width="900" height="1200">
            </div>

            <ol class="w-rows">
            @foreach($timelineItems as $item)
            @php $certKey = $item->v('cert_key'); @endphp
            <li class="w-row {{ $certKey ? 'w-row-link tl-item-cert' : '' }}" data-reveal @if($certKey) role="button" tabindex="0" data-open-cert="{{ $certKey }}" @endif>
                <span class="w-row-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="w-row-title">{{ $item->v('title') }}</span>
                <span class="w-row-meta">{{ $item->v('period') }}</span>
            </li>
            @endforeach
            </ol>
</div>
        </div>

        <div class="achievements">
            <div class="ach-item">
                <span class="ach-num" id="counter-projects">{{ count($worksProjects) }}+</span>
                <div class="ach-lbl">{{ __('hero.stat_projects') }}</div>
            </div>
            <div class="ach-item">
                <span class="ach-num" id="counter-certs">{{ $certificates->count() }}</span>
                <div class="ach-lbl">{{ __('timeline.achievement_certs') }}</div>
            </div>
            <div class="ach-item">
                <span class="ach-num" id="counter-years">{{ __('hero.years_value') }}</span>
                <div class="ach-lbl">{{ __('timeline.achievement_years') }}</div>
            </div>
        </div>
    </div>
</section>
@endif

@if(\App\Models\Setting::flag('show_process'))
{{-- ── DEVELOPMENT PROCESS ── --}}
<section class="w-section" id="process" aria-labelledby="process-title">
    <div class="w-container">
        <header class="w-head">
            <span class="w-head-label">{{ __('label.process') }}</span>
            <h2 class="w-head-title" id="process-title">{{ __('process.title') }}</h2>
            <p class="w-head-lead">{{ __('process.lead') }}</p>
        </header>
        <ol class="w-process">
            @foreach(['discover', 'design', 'build', 'test', 'document'] as $step)
            <li class="w-process-step" data-reveal>
                <span class="w-eyebrow-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                <h3>{{ __('process.'.$step.'_title') }}</h3>
                <p>{{ __('process.'.$step.'_desc') }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>
@endif

@if(\App\Models\Setting::flag('show_certifications'))
{{-- ── CERTIFICATIONS ── --}}
<section class="certs-section" id="certifications">
    <div class="container">
        <header class="w-head">
            <span class="w-head-label">{{ __('label.credentials') }}</span>
            <h2 class="w-head-title">{{ __('certs.title') }}</h2>
            <p class="w-head-lead">{{ __('certs.subtitle') }}</p>
        </header>
        
        <div class="certs-visual-grid">
            @foreach($certificates as $cert)
            @php
                $pages = $cert->v('images') ?? [];
                $first = $pages[0] ?? null;
                $multi = count($pages) > 1;
            @endphp
            <div class="cert-card {{ $multi ? 'cert-card-slider' : '' }}" @if($multi) id="cert-slider-{{ $cert->v('key') ?: $cert->id }}" @endif
                 data-cert-key="{{ $cert->v('key') }}"
                 data-cert-img="{{ $first ? asset($first['full']) : '' }}"
                 data-cert-title="{{ $cert->v('full_title') ?: $cert->v('title') }}"
                 data-cert-issuer="{{ $cert->v('full_issuer') ?: $cert->v('issuer') }}">
                <div class="cert-img-wrap {{ $multi ? 'cert-slider-wrap' : '' }}">
                    @if($multi)
                    <div class="cert-slides">
                        @foreach($pages as $page)
                        <div class="cert-slide {{ $loop->first ? 'active' : '' }}" data-img-url="{{ asset($page['full']) }}">
                            <img width="960" height="679" loading="lazy" decoding="async" src="{{ asset($page['preview'] ?? $page['full']) }}" alt="{{ $cert->v('title') }} — {{ $loop->iteration }}">
                        </div>
                        @endforeach
                    </div>
                    <button class="cert-slide-btn prev-btn" aria-label="Previous Slide"><i class="fas fa-chevron-left"></i></button>
                    <button class="cert-slide-btn next-btn" aria-label="Next Slide"><i class="fas fa-chevron-right"></i></button>
                    <div class="cert-slide-dots">
                        @foreach($pages as $page)<span class="slide-dot {{ $loop->first ? 'active' : '' }}" data-slide-to="{{ $loop->index }}"></span>@endforeach
                    </div>
                    @elseif($first)
                    <img width="960" height="679" loading="lazy" decoding="async" src="{{ asset($first['preview'] ?? $first['full']) }}" alt="{{ $cert->v('title') }}">
                    @endif
                    <div class="cert-hover-overlay">
                        <i class="fas fa-search-plus"></i>
                        <span>{{ __('certs.view_credential') }}</span>
                    </div>
                </div>
                <div class="cert-info-wrap">
                    @if($cert->v('badge'))<span class="cert-badge">{{ $cert->v('badge') }}</span>@endif
                    <h3>{{ $cert->v('title') }}</h3>
                    @if($cert->v('issuer'))<p class="cert-issuer">{{ $cert->v('issuer') }}</p>@endif
                    @if($cert->v('date'))<p class="cert-date">{{ $cert->v('date') }}</p>@endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Lightbox Modal --}}
    <div class="cert-lightbox" id="certLightbox" role="dialog" aria-hidden="true" aria-modal="true" aria-labelledby="lightboxTitle" inert>
        <button type="button" class="lightbox-close" id="lightboxClose" aria-label="{{ __('motion.close') }}">&times;</button>
        
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
    <div class="project-modal" id="projectModal" role="dialog" aria-hidden="true" aria-modal="true" aria-label="{{ __('works.view_details') }}" inert>
        <div class="pm-backdrop" id="pmBackdrop"></div>
        <div class="pm-content">
            <button type="button" class="pm-close" id="pmCloseBtn" aria-label="{{ __('motion.close') }}">&times;</button>
            <div class="pm-body" id="pmModalBody">
                {{-- Filled dynamically by JS --}}
            </div>
        </div>
    </div>
</section>
@endif

{{-- ── CONTACT ── --}}
<section class="contact-section w-contact" id="contact" aria-labelledby="contact-title">
    <img class="w-contact-photo" src="{{ asset('images/contact-lets-talk.webp') }}"
         alt="{{ __('contact.visual_alt') }}" width="576" height="1024" loading="lazy" decoding="async">
    <div class="w-contact-overlay w-container">
        <h2 class="sr-only" id="contact-title">{{ __('contact.title') }}</h2>
        <div class="w-contact-actions">
            <button type="button" class="w-contact-btn w-contact-btn-primary" data-open-contact>
                <i class="fas fa-paper-plane" aria-hidden="true"></i> {{ __('contact.form_title') }}
            </button>
            <a class="w-contact-btn" href="mailto:{{ $profile['contact_email'] }}">
                <i class="fas fa-envelope" aria-hidden="true"></i> {{ $profile['contact_email'] }}
            </a>
        </div>
    </div>
</section>

{{-- Contact form lives in a native dialog (focus trap + Esc built in). Messages go to the admin-set mailbox. --}}
<dialog class="w-dialog" id="contactDialog" aria-labelledby="contactDialogTitle">
    <div class="w-dialog-head">
        <div>
            <h3 id="contactDialogTitle">{{ __('contact.form_title') }}</h3>
            <p>{{ __('contact.form_desc') }}</p>
        </div>
        <button type="button" class="w-dialog-close" data-close-contact aria-label="{{ __('motion.close') }}">&times;</button>
    </div>
    <div class="form-card form-card-minimal w-dialog-body">
        <form id="contactForm" class="contact-form" action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <label class="sr-only" for="contactName">{{ __('contact.placeholder_name') }}</label>
                        <input id="contactName" type="text" name="name" autocomplete="name" maxlength="255" placeholder="{{ __('contact.placeholder_name') }}" required>
                        <label class="sr-only" for="contactEmail">{{ __('contact.placeholder_email') }}</label>
                        <input id="contactEmail" type="email" name="email" autocomplete="email" maxlength="255" placeholder="{{ __('contact.placeholder_email') }}" required>
                        <label class="sr-only" for="contactSubject">{{ __('contact.placeholder_subject') }}</label>
                        <input id="contactSubject" type="text" name="subject" maxlength="255" placeholder="{{ __('contact.placeholder_subject') }}" required>
                        <label class="sr-only" for="contactMessage">{{ __('contact.placeholder_message') }}</label>
                        <textarea id="contactMessage" name="message" rows="4" maxlength="5000" placeholder="{{ __('contact.placeholder_message') }}" required></textarea>
                        
                        {{-- Premium Math CAPTCHA --}}
                        <div class="captcha-row" style="display: flex; gap: 0.8rem; align-items: center; margin-top: 0.2rem;">
                            <div class="captcha-question" style="background: var(--blue-dim); border: 1px solid var(--border2); color: var(--blue2); padding: 0.8rem 1.1rem; border-radius: var(--r); font-weight: 700; font-family: 'Fira Code', monospace; font-size: 0.95rem; white-space: nowrap; user-select: none;">
                                {{ $captchaQuestion }} = ?
                            </div>
                            <label class="sr-only" for="contactCaptcha">{{ $captchaQuestion }} = ?</label>
                            <input id="contactCaptcha" type="number" name="captcha" placeholder="{{ __('contact.placeholder_captcha', ['question' => '']) }}" required style="margin: 0; flex: 1;">
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitBtn" style="width:100%;justify-content:center">
                            <span id="submitText"><i class="fas fa-paper-plane"></i> {{ __('contact.send') }}</span>
                            <span id="submitLoading" style="display:none"><i class="fas fa-spinner fa-spin"></i> {{ __('contact.sending') }}</span>
                        </button>
                        <div id="formMessage" class="form-msg" style="display:none" role="status" aria-live="polite"></div>
                    </form>
    </div>
</dialog>

</main>{{-- /#portfolio-export --}}

{{-- ── FOOTER ── --}}
@php
    $p = $profile;
    $contactLinks = [
        __('contact.group_reach') => [
            ['icon' => 'fas fa-envelope', 'name' => 'Email', 'href' => 'mailto:'.$p['contact_email'], 'text' => $p['contact_email'], 'hint' => __('contact.cta_email')],
            ['icon' => 'fab fa-linkedin', 'name' => 'LinkedIn', 'href' => $p['linkedin_url'], 'text' => 'Muzaki Abdullah Irsyad', 'hint' => __('contact.cta_linkedin')],
        ],
        __('contact.group_work') => [
            ['icon' => 'fab fa-github', 'name' => 'GitHub', 'href' => $p['github_username'] ? 'https://github.com/'.$p['github_username'] : null, 'text' => '@'.$p['github_username'], 'hint' => __('contact.cta_github')],
            ['icon' => 'fab fa-dribbble', 'name' => 'Dribbble', 'href' => $p['dribbble_url'], 'text' => 'Muzaki Abdullah Irsyad', 'hint' => __('contact.cta_dribbble')],
            ['icon' => 'fab fa-pinterest', 'name' => 'Pinterest', 'href' => $p['pinterest_url'], 'text' => $p['pinterest_label'], 'hint' => __('contact.cta_pinterest')],
            ['icon' => 'fab fa-instagram', 'name' => 'Instagram', 'href' => $p['instagram_username'] ? 'https://www.instagram.com/'.$p['instagram_username'].'/' : null, 'text' => '@'.$p['instagram_username'], 'hint' => __('contact.cta_instagram')],
            ['icon' => 'fab fa-threads', 'name' => 'Threads', 'href' => $p['threads_username'] ? 'https://www.threads.com/@'.$p['threads_username'] : null, 'text' => '@'.$p['threads_username'], 'hint' => __('contact.cta_threads')],
        ],
    ];
@endphp
<footer class="footer-clean w-footer">
    <div class="w-container">
        {{-- CTA line --}}
        <div class="w-footer-cta">
            <p class="w-footer-kicker">{{ __('footer.cta_kicker') }}</p>
            <a class="w-footer-mail" href="mailto:{{ $profile['contact_email'] }}">{{ $profile['contact_email'] }} <span aria-hidden="true">↗</span></a>
        </div>

        {{-- Columns: navigation (numbered), channels, export --}}
        <div class="w-footer-grid">
            <div>
                <p class="w-footer-label">(01) {{ __('footer.col_nav') }}</p>
                <nav class="w-footer-nav" aria-label="Footer">
                    <a href="#about">{{ __('footer.about') }}</a>
                    <a href="#works">{{ __('footer.works') }}</a>
                    @if(\App\Models\Setting::flag('show_experience'))<a href="#experience">{{ __('nav.experience') }}</a>@endif
                    @if(\App\Models\Setting::flag('show_certifications'))<a href="#certifications">{{ __('footer.certifications') }}</a>@endif
                    <a href="#contact">{{ __('footer.contact') }}</a>
                </nav>
            </div>
            <div>
                <p class="w-footer-label">(02) {{ __('footer.col_social') }}</p>
                <ul class="w-footer-social">
                    @foreach(collect($contactLinks)->flatten(1) as $link)
                    @continue(! $link['href'] || str_starts_with($link['href'], 'mailto:'))
                    <li><a href="{{ $link['href'] }}" target="_blank" rel="noopener noreferrer"><i class="{{ $link['icon'] }}" aria-hidden="true"></i>{{ $link['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <p class="w-footer-label">(03) {{ __('footer.export_label') }}</p>
                <div class="w-footer-export">
                    <button type="button" class="w-footer-btn" id="exportPortfolioPdfFooter"><i class="fas fa-file-pdf" aria-hidden="true"></i> {{ __('footer.export_pdf') }}</button>
                    <button type="button" class="w-footer-btn" id="exportPortfolioPptFooter"><i class="fas fa-file-powerpoint" aria-hidden="true"></i> {{ __('footer.export_ppt') }}</button>
                    <a class="w-footer-btn" href="{{ asset('cv/muzaki-abdullah-irsyad.pdf') }}" download><i class="fas fa-download" aria-hidden="true"></i> {{ __('about.download_cv') }}</a>
                </div>
            </div>
        </div>

        {{-- Giant wordmark + ruler, echoing the hero --}}
        <p class="w-footer-word" aria-hidden="true">Muzaki<span>.</span></p>
        <div class="w-footer-bottom">
            <span>© {{ date('Y') }} Muzaki Abdullah Irsyad</span>
            <i aria-hidden="true"></i>
            <span>{{ __('footer.tagline') }}</span>
            <a href="#hero" class="w-footer-top">{{ __('footer.back_top') }} ↑</a>
        </div>
    </div>
</footer>

<button class="back-top" id="backTop" aria-label="Back to top"><i class="fas fa-arrow-up"></i></button>

{{-- ── AI CHATBOT WIDGET ── --}}
@if(config('services.gemini.enabled'))
<div class="ai-chat-widget" id="aiChatWidget">
    <button class="ai-chat-bubble" id="aiChatBubble" aria-label="{{ __('chatbot.header_title') }}" aria-controls="aiChatWindow" aria-expanded="false">
        <i class="fas fa-robot ai-bubble-icon" aria-hidden="true"></i>
        <span class="ai-badge">AI</span>
    </button>
    <div class="ai-chat-window" id="aiChatWindow" role="region" aria-labelledby="chatTitle" aria-hidden="true" inert>
        <div class="ai-chat-header">
            <div class="ai-avatar ai-avatar-icon">
                <i class="fas fa-robot" aria-hidden="true"></i>
                <span class="online-indicator"></span>
            </div>
            <div class="ai-header-info">
                <h2 id="chatTitle">{{ __('chatbot.header_title') }}</h2>
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
            <label class="sr-only" for="aiChatInput">{{ __('chatbot.input_placeholder') }}</label>
            <input type="text" id="aiChatInput" placeholder="{{ __('chatbot.input_placeholder') }}" autocomplete="off" required>
            <button type="submit" id="aiSendBtn" aria-label="Kirim"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>
@endif

@php
    $exportProjects = collect($worksProjects)
        ->map(fn ($p) => $p['title'] ?? '')
        ->filter()
        ->values()
        ->all();
    $exportExperience = $experiences->map(fn ($e) => [
        'role' => $e->v('role'),
        'meta' => collect([$e->v('company'), $e->v('period')])->filter()->implode(' · '),
        'desc' => $e->v('description'),
    ])->values()->all();
    $portfolioExport = [
        'name' => 'Muzaki Abdullah Irsyad',
        'tagline' => __('hero.tagline'),
        'taglineShort' => __('hero.tagline_short'),
        'about' => $profile['about_description'],
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
        'contactEmail' => $profile['contact_email'],
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
<script src="{{ asset('js/portfolio.js') }}?v=4.4" defer></script>
<script src="{{ asset('js/portfolio-export.js') }}?v=2.0" defer></script>
</body>
</html>
