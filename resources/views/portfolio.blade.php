<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio of Muzaki Abdullah Irsyad - Informatics Engineering, Software Development & IT Support">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>Muzaki Abdullah Irsyad - Portfolio</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <div class="nav-brand">Muzaki Abdullah Irsyad</div>
            <ul class="nav-menu" id="navMenu">
                <li><a href="#about" class="nav-link">{{ __('nav.about') }}</a></li>
                <li><a href="#skills" class="nav-link">{{ __('nav.skills') }}</a></li>
                <li><a href="#works" class="nav-link">{{ __('nav.works') }}</a></li>
                <li><a href="#certifications" class="nav-link">{{ __('nav.certifications') }}</a></li>
                <li><a href="#contact" class="nav-link">{{ __('nav.contact') }}</a></li>
            </ul>
            <div class="nav-actions">
                <div class="lang-toggle" role="group" aria-label="{{ __('nav.language') }}">
                    <a href="{{ route('locale.switch', ['locale' => 'id']) }}" class="lang-option {{ app()->getLocale() === 'id' ? 'active' : '' }}" title="Bahasa Indonesia">ID</a>
                    <span class="lang-sep">|</span>
                    <a href="{{ route('locale.switch', ['locale' => 'en']) }}" class="lang-option {{ app()->getLocale() === 'en' ? 'active' : '' }}" title="English">EN</a>
                </div>
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>
            </div>
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <p class="hero-greeting">{{ __('hero.greeting') }}</p>
                    <h1 class="hero-name">Muzaki Abdullah <br><span class="hero-name-last">Irsyad</span></h1>
                    <p class="hero-title">{{ __('hero.title_1') }}<br>{{ __('hero.title_2') }}</p>
                    <div class="hero-buttons">
                        <a href="#contact" class="btn btn-primary">{{ __('hero.hire_me') }}</a>
                        <a href="#works" class="btn btn-outline">{{ __('hero.review_portfolio') }}</a>
                    </div>
                </div>
                <div class="hero-image parallax-element">
                    <div class="profile-circle float-animation">
                        <img src="{{ asset('images/profile-placeholder.svg') }}" alt="Muzaki Abdullah Irsyad" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="profile-placeholder" style="display: none;">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-services">
                <div class="service-card">
                    <i class="fas fa-code"></i>
                    <h3>{{ __('hero.service_software') }}</h3>
                </div>
                <div class="service-card">
                    <i class="fas fa-tools"></i>
                    <h3>{{ __('hero.service_it') }}</h3>
                </div>
                <div class="service-card">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>{{ __('hero.service_teaching') }}</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <h2 class="section-title">{{ __('about.title') }}</h2>
            <p class="about-description">{{ __('about.description') }}</p>
            <div class="about-details">
                <div class="detail-row">
                    <span class="detail-label">{{ __('about.experience') }}</span>
                    <span class="detail-value">{{ __('about.experience_value') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ __('about.specialty') }}</span>
                    <span class="detail-value">{{ __('about.specialty_value') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ __('about.education') }}</span>
                    <span class="detail-value">STT Terpadu Nurul Fikri</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ __('about.gpa') }}</span>
                    <span class="detail-value">3.86/4.00</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ __('about.location') }}</span>
                    <span class="detail-value">Jakarta, Indonesia</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ __('about.linkedin') }}</span>
                    <span class="detail-value"><a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank" style="color: var(--blue-primary);">muzaki-abdullah-irsyad</a></span>
                </div>
            </div>
            <div class="education-experience">
                <div class="education">
                    <h3>{{ __('about.education_section') }}</h3>
                    <div class="edu-item">
                        <h4>{{ __('about.edu_nf') }}</h4>
                        <p>{{ __('about.edu_nf_desc') }}</p>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">{{ __('about.edu_nf_detail') }}</p>
                    </div>
                    <div class="edu-item">
                        <h4>{{ __('about.edu_cipta') }}</h4>
                        <p>{{ __('about.edu_cipta_desc') }}</p>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">Feb - Jun 2024</p>
                    </div>
                    <div class="edu-item">
                        <h4>{{ __('about.edu_infinite') }}</h4>
                        <p>{{ __('about.edu_infinite_desc') }}</p>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">Aug - Dec 2023</p>
                    </div>
                    <div class="edu-item">
                        <h4>{{ __('about.edu_smkn') }}</h4>
                        <p>{{ __('about.edu_smkn_desc') }}</p>
                    </div>
                </div>
                <div class="work-exp">
                    <h3>{{ __('about.work_experience') }}</h3>
                    <div class="exp-item">
                        <h4>{{ __('about.exp_pranata') }}</h4>
                        <p>{{ __('about.exp_pranata_place') }}</p>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">{{ __('about.exp_present') }}</p>
                        <ul style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem; padding-left: 1.5rem;">
                            <li>{{ __('about.exp_pranata_1') }}</li>
                            <li>{{ __('about.exp_pranata_2') }}</li>
                            <li>{{ __('about.exp_pranata_3') }}</li>
                        </ul>
                    </div>
                    <div class="exp-item">
                        <h4>{{ __('about.exp_onexpert') }}</h4>
                        <p>{{ __('about.exp_onexpert_place') }}</p>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">Dec 2022 – Dec 2024</p>
                        <ul style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem; padding-left: 1.5rem;">
                            <li>{{ __('about.exp_onexpert_1') }}</li>
                            <li>{{ __('about.exp_onexpert_2') }}</li>
                        </ul>
                    </div>
                    <div class="exp-item">
                        <h4>{{ __('about.exp_lecturer') }}</h4>
                        <p>STT Terpadu Nurul Fikri</p>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">Sep 2023 – Jul 2024 & Sep 2024 – Jan 2025</p>
                        <ul style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem; padding-left: 1.5rem;">
                            <li>{{ __('about.exp_lecturer_1') }}</li>
                            <li>{{ __('about.exp_lecturer_2') }}</li>
                        </ul>
                    </div>
                    <div class="exp-item">
                        <h4>{{ __('about.exp_trainee') }}</h4>
                        <p>{{ __('about.exp_trainee_place') }}</p>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">Oct 2019 – Mar 2020</p>
                        <ul style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem; padding-left: 1.5rem;">
                            <li>{{ __('about.exp_trainee_1') }}</li>
                            <li>{{ __('about.exp_trainee_2') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <a href="{{ asset('cv/muzaki-abdullah-irsyad.pdf') }}" class="btn btn-primary" download>
                {{ __('about.download_cv') }}
            </a>
        </div>
    </section>

    <!-- Skills Section -->
    <section class="skills" id="skills">
        <div class="container">
            <h2 class="section-title">{{ __('skills.title') }}</h2>
            <p class="section-subtitle">{{ __('skills.subtitle') }}</p>
            <div class="skills-grid">
                <div class="skill-category">
                    <h3>{{ __('skills.category_software') }}</h3>
                    <div class="skill-tags">
                        @foreach(array_map('trim', explode(',', __('skills.software_tags'))) as $tag)
                        <span class="skill-tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="skill-category">
                    <h3>{{ __('skills.category_office') }}</h3>
                    <div class="skill-tags">
                        @foreach(array_map('trim', explode(',', __('skills.office_tags'))) as $tag)
                        <span class="skill-tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="skill-category">
                    <h3>{{ __('skills.category_cloud') }}</h3>
                    <div class="skill-tags">
                        @foreach(array_map('trim', explode(',', __('skills.cloud_tags'))) as $tag)
                        <span class="skill-tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="skill-category">
                    <h3>{{ __('skills.category_soft') }}</h3>
                    <div class="skill-tags">
                        @foreach(array_map('trim', explode(',', __('skills.soft_tags'))) as $tag)
                        <span class="skill-tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="skill-category">
                    <h3>{{ __('skills.category_lang') }}</h3>
                    <div class="skill-tags">
                        @foreach(array_map('trim', explode(',', __('skills.lang_tags'))) as $tag)
                        <span class="skill-tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="skills-cta">
                <a href="#contact" class="btn btn-primary">{{ __('skills.cta') }}</a>
            </div>
        </div>
    </section>

    <!-- Featured Works Section -->
    <section class="works" id="works">
        <div class="container">
            <h2 class="section-title">{{ __('works.title') }}</h2>
            <p class="section-subtitle">{{ __('works.subtitle') }}</p>
            <div class="works-grid">
                @foreach($featuredWorks ?? [] as $work)
                <div class="work-card">
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
                        <a href="{{ $work['live_url'] }}" class="work-link work-link-demo" target="_blank" rel="noopener noreferrer" aria-label="{{ __('works.live_demo') }}">
                            <i class="fas fa-external-link-alt"></i> {{ __('works.live_demo') }}
                        </a>
                        @endif
                        @if(!empty($work['github_url']))
                        <a href="{{ $work['github_url'] }}" class="work-link work-link-github" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
                            <i class="fab fa-github"></i> {{ __('works.github') }}
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="works-cta">
                <a href="#" class="btn btn-outline">{{ __('works.view_all') }}</a>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="timeline" id="timeline">
        <div class="container">
            <h2 class="section-title">{{ __('timeline.title') }}</h2>
            <p class="section-subtitle">{{ __('timeline.subtitle') }}</p>
            <div class="timeline-items">
                <div class="timeline-item">
                    <div class="timeline-date">{{ __('timeline.present') }}</div>
                    <div class="timeline-content">
                        <h3>{{ __('timeline.item_pranata') }}</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">Sep 2024 - Jan 2025</div>
                    <div class="timeline-content">
                        <h3>{{ __('timeline.item_lecturer_psi') }}</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">Feb 2024 - Jun 2024</div>
                    <div class="timeline-content">
                        <h3>{{ __('timeline.item_codeless') }}</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">Sep 2023 - Jul 2024</div>
                    <div class="timeline-content">
                        <h3>{{ __('timeline.item_lecturer_devops') }}</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">Aug 2023 - Dec 2023</div>
                    <div class="timeline-content">
                        <h3>{{ __('timeline.item_webdev') }}</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">Dec 2022 - Dec 2024</div>
                    <div class="timeline-content">
                        <h3>{{ __('timeline.item_onexpert') }}</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2022 - {{ __('timeline.present') }}</div>
                    <div class="timeline-content">
                        <h3>{{ __('timeline.item_karangtaruna') }}</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2023 - {{ __('timeline.present') }}</div>
                    <div class="timeline-content">
                        <h3>{{ __('timeline.item_hr') }}</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">Oct 2019 - Mar 2020</div>
                    <div class="timeline-content">
                        <h3>{{ __('timeline.item_trainee') }}</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2021 - 2025</div>
                    <div class="timeline-content">
                        <h3>{{ __('timeline.item_informatics') }}</h3>
                    </div>
                </div>
            </div>
            <div class="achievements">
                <div class="achievement-item">
                    <div class="achievement-number counter-number">3.86</div>
                    <div class="achievement-label">{{ __('timeline.achievement_gpa') }}</div>
                </div>
                <div class="achievement-item">
                    <div class="achievement-number counter-number">4</div>
                    <div class="achievement-label">{{ __('timeline.achievement_certs') }}</div>
                </div>
                <div class="achievement-item">
                    <div class="achievement-number counter-number">2</div>
                    <div class="achievement-label">{{ __('timeline.achievement_years') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Certifications Section -->
    <section class="certifications" id="certifications">
        <div class="container">
            <h2 class="section-title">{{ __('certs.title') }}</h2>
            <p class="section-subtitle">{{ __('certs.subtitle') }}</p>
            <div class="certs-grid">
                @foreach($certifications ?? [] as $category)
                <div class="cert-category">
                    <h3>{{ $category['category'] }}</h3>
                    <div class="cert-list">
                        @foreach($category['items'] ?? [] as $item)
                        <div class="cert-item">
                            <span class="cert-name">{{ $item['name'] }}</span>
                            @if(!empty($item['url']))
                            <a href="{{ $item['url'] }}" class="cert-credential-link" target="_blank" rel="noopener noreferrer" aria-label="{{ __('certs.view_credential') }}">
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

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <h2 class="section-title">{{ __('contact.title') }}</h2>
            <p class="section-subtitle">{{ __('contact.subtitle') }}</p>
            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-card">
                        <i class="fab fa-linkedin"></i>
                        <div>
                            <strong>{{ __('contact.linkedin') }}</strong>
                            <span><a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank" style="color: var(--text-primary);">Muzaki Abdullah Irsyad</a></span>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fab fa-github"></i>
                        <div>
                            <strong>{{ __('contact.github') }}</strong>
                            <span><a href="https://github.com/Muzaki29" target="_blank" style="color: var(--text-primary);">Muzaki29</a></span>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-graduation-cap"></i>
                        <div>
                            <strong>{{ __('contact.education') }}</strong>
                            <span>STT Terpadu Nurul Fikri</span>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>{{ __('contact.location') }}</strong>
                            <span>Jakarta, Indonesia</span>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-star"></i>
                        <div>
                            <strong>{{ __('contact.gpa') }}</strong>
                            <span>3.86/4.00</span>
                        </div>
                    </div>
                </div>
                <div class="contact-form-card">
                    <h3>{{ __('contact.form_title') }}</h3>
                    <p>{{ __('contact.form_desc') }}</p>
                    
                    <form id="contactForm" class="contact-form">
                        @csrf
                        <div class="form-group">
                            <input type="text" id="name" name="name" placeholder="{{ __('contact.placeholder_name') }}" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="{{ __('contact.placeholder_email') }}" required>
                        </div>
                        <div class="form-group">
                            <input type="text" id="subject" name="subject" placeholder="{{ __('contact.placeholder_subject') }}" required>
                        </div>
                        <div class="form-group">
                            <textarea id="message" name="message" rows="5" placeholder="{{ __('contact.placeholder_message') }}" required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span id="submitText">{{ __('contact.send') }}</span>
                                <span id="submitLoading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> {{ __('contact.sending') }}
                                </span>
                            </button>
                        </div>
                        <div id="formMessage" class="form-message" style="display: none;" role="status" aria-live="polite" aria-atomic="true"></div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <p>Muzaki Abdullah Irsyad</p>
                    <p class="footer-copyright">© 2025 Muzaki Abdullah Irsyad. {{ __('footer.rights') }}</p>
                </div>
                <div class="footer-links">
                    <ul>
                        <li><a href="#about">{{ __('footer.about') }}</a></li>
                        <li><a href="#works">{{ __('footer.works') }}</a></li>
                        <li><a href="#contact">{{ __('footer.contact') }}</a></li>
                        <li><a href="#certifications">{{ __('footer.certifications') }}</a></li>
                    </ul>
                    <div class="footer-social">
                        <a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        <a href="https://github.com/Muzaki29" target="_blank" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        <a href="#contact" aria-label="Contact"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        window.i18n = window.i18n || {};
        window.i18n.contact = window.i18n.contact || {
            successFallback: @json(__('contact.success_message')),
            errorFallback: @json(__('contact.error_fallback')),
            validationPrefix: @json(__('contact.validation_prefix')),
            genericRetryLater: @json(__('contact.generic_retry_later')),
        };
    </script>
    <script src="{{ asset('js/portfolio.js') }}"></script>
</body>
</html>

