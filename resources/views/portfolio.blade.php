<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio of Muzaki Abdullah Irsyad - Fresh Graduate Informatics Engineering, Software Development & IT Support">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#skills" class="nav-link">Skills</a></li>
                <li><a href="#works" class="nav-link">Works</a></li>
                <li><a href="#certifications" class="nav-link">Certifications</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>
            <div class="nav-actions">
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>
                <div class="nav-search">
                    <i class="fas fa-search"></i>
                </div>
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
                    <p class="hero-greeting">Hello, I'm</p>
                    <h1 class="hero-name">Muzaki Abdullah Irsyad</h1>
                    <p class="hero-title">Fresh Graduate Informatics Engineering | Software Development & IT Support</p>
                    <div class="hero-buttons">
                        <a href="#contact" class="btn btn-primary">Hire Me</a>
                        <a href="#works" class="btn btn-outline">Review Portfolio</a>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="profile-circle">
                        <img src="{{ asset('images/profil.jpg.jpg') }}" alt="Muzaki Abdullah Irsyad" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="profile-placeholder" style="display: none;">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-services">
                <div class="service-card">
                    <i class="fas fa-code"></i>
                    <h3>Software Development</h3>
                </div>
                <div class="service-card">
                    <i class="fas fa-tools"></i>
                    <h3>IT Support</h3>
                </div>
                <div class="service-card">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Teaching Assistant</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <h2 class="section-title">Here's a bit about me</h2>
            <p class="about-description">
                I am Muzaki Abdullah Irsyad, a fresh graduate in Informatics Engineering from STT Terpadu Nurul Fikri with a GPA of 3.86/4.00. I have a strong passion for software development and IT support, and have earned certifications in Microsoft Office, cloud computing, graphic design, and video editing. I have gained practical experience through the Kampus Merdeka program in Web Development and Codeless Data Science, and have worked as a Junior IT Support and Trainee Engineer.
            </p>
            <div class="about-details">
                <div class="detail-row">
                    <span class="detail-label">Experience:</span>
                    <span class="detail-value">2+ Years</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Specialty:</span>
                    <span class="detail-value">Software Development & IT Support</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Education:</span>
                    <span class="detail-value">STT Terpadu Nurul Fikri</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">GPA:</span>
                    <span class="detail-value">3.86/4.00</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value">Jakarta, Indonesia</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">LinkedIn:</span>
                    <span class="detail-value"><a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank" style="color: var(--blue-primary);">muzaki-abdullah-irsyad</a></span>
                </div>
            </div>
            <div class="education-experience">
                <div class="education">
                    <h3>Education</h3>
                    <div class="edu-item">
                        <h4>STT Terpadu Nurul Fikri</h4>
                        <p>Informatics Engineering (2021-2025)</p>
                        <p style="color: var(--dark-text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">GPA: 3.86/4.00 | Graduation: 18 July 2025</p>
                    </div>
                    <div class="edu-item">
                        <h4>PT Nurul Fikri Cipta Inovasi</h4>
                        <p>Student Codeless Data Science - MSIB Batch 6</p>
                        <p style="color: var(--dark-text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">Feb - Jun 2024</p>
                    </div>
                    <div class="edu-item">
                        <h4>Infinite Learning</h4>
                        <p>Student Web Development - MSIB Batch 5</p>
                        <p style="color: var(--dark-text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">Aug - Dec 2023</p>
                    </div>
                    <div class="edu-item">
                        <h4>SMKN 5 Jakarta</h4>
                        <p>Electrical Power Installation Engineering (2018-2021)</p>
                    </div>
                </div>
                <div class="work-exp">
                    <h3>Work Experience</h3>
                    <div class="exp-item">
                        <h4>Pranata Komputer</h4>
                        <p>Museum Penerangan</p>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">Present</p>
                        <ul style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem; padding-left: 1.5rem;">
                            <li>Planning, analyzing, designing, implementing, and operating computer-based information systems</li>
                            <li>Managing data, IT infrastructure, and information systems</li>
                            <li>Ensuring technology services run securely, stably, and efficiently</li>
                        </ul>
                    </div>
                    <div class="exp-item">
                        <h4>Junior IT Support Freelancer</h4>
                        <p>PT. Onexpert International Indonesia</p>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">Dec 2022 – Dec 2024</p>
                        <ul style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem; padding-left: 1.5rem;">
                            <li>Ensure computer functionality and support company machines</li>
                            <li>Check and maintain application functionality</li>
                        </ul>
                    </div>
                    <div class="exp-item">
                        <h4>Assistant Lecturer</h4>
                        <p>STT Terpadu Nurul Fikri</p>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">Sep 2023 – Jul 2024 & Sep 2024 – Jan 2025</p>
                        <ul style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem; padding-left: 1.5rem;">
                            <li>English Course, Computer Network, Civic Education (PPKN), DevOps</li>
                            <li>Introduction to Information Systems (PSI), Software Engineering (RPL), Indonesian Language</li>
                        </ul>
                    </div>
                    <div class="exp-item">
                        <h4>Trainee Engineering</h4>
                        <p>The Mayflower, Jakarta – Marriot Executive Apartements</p>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">Oct 2019 – Mar 2020</p>
                        <ul style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem; padding-left: 1.5rem;">
                            <li>Apply electrical equipment and K3 working principles</li>
                            <li>Operate simple building electrical installations</li>
                        </ul>
                    </div>
                </div>
            </div>
            <a href="{{ asset('cv/muzaki-abdullah-irsyad.pdf') }}" class="btn btn-primary" download>
                Download CV
            </a>
        </div>
    </section>

    <!-- Skills Section -->
    <section class="skills" id="skills">
        <div class="container">
            <h2 class="section-title">Skills & Expertise</h2>
            <p class="section-subtitle">Comprehensive technologies in software development, IT support, & cloud computing.</p>
            <div class="skills-grid">
                <div class="skill-category">
                    <h3>Software Development</h3>
                    <div class="skill-tags">
                        <span class="skill-tag">Web Development</span>
                        <span class="skill-tag">HTML</span>
                        <span class="skill-tag">CSS</span>
                        <span class="skill-tag">JavaScript</span>
                        <span class="skill-tag">Codeless Data Science</span>
                        <span class="skill-tag">Git</span>
                    </div>
                </div>
                <div class="skill-category">
                    <h3>Microsoft Office & Design</h3>
                    <div class="skill-tags">
                        <span class="skill-tag">Microsoft Word</span>
                        <span class="skill-tag">Microsoft Excel</span>
                        <span class="skill-tag">Microsoft PowerPoint</span>
                        <span class="skill-tag">Canva</span>
                        <span class="skill-tag">Graphic Design</span>
                        <span class="skill-tag">Video Editing</span>
                        <span class="skill-tag">Capcut</span>
                    </div>
                </div>
                <div class="skill-category">
                    <h3>Cloud Computing</h3>
                    <div class="skill-tags">
                        <span class="skill-tag">Cloud & Web Instant</span>
                        <span class="skill-tag">Cloud Computing</span>
                        <span class="skill-tag">Web Technologies</span>
                    </div>
                </div>
                <div class="skill-category">
                    <h3>Soft Skills</h3>
                    <div class="skill-tags">
                        <span class="skill-tag">Public Speaking</span>
                        <span class="skill-tag">Communication</span>
                        <span class="skill-tag">Team Building</span>
                        <span class="skill-tag">Adaptability</span>
                        <span class="skill-tag">Time Management</span>
                        <span class="skill-tag">Critical Thinking</span>
                        <span class="skill-tag">Patience</span>
                    </div>
                </div>
                <div class="skill-category">
                    <h3>Languages</h3>
                    <div class="skill-tags">
                        <span class="skill-tag">Bahasa Indonesia (Native)</span>
                        <span class="skill-tag">English (Intermediate)</span>
                    </div>
                </div>
            </div>
            <div class="skills-cta">
                <a href="#contact" class="btn btn-primary">Ready to leverage these skills for your project?</a>
            </div>
        </div>
    </section>

    <!-- Featured Works Section -->
    <section class="works" id="works">
        <div class="container">
            <h2 class="section-title">Featured Works</h2>
            <p class="section-subtitle">Curated list of professional projects and experiences.</p>
            <div class="works-grid">
                <div class="work-card">
                    <div class="work-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3>PT. Onexpert International - IT Support</h3>
                    <p>Junior IT Support Freelancer ensuring computer functionality and application maintenance for company operations.</p>
                    <div class="work-tags">
                        <span>IT Support</span>
                        <span>Computer Maintenance</span>
                        <span>Application Support</span>
                    </div>
                </div>
                <div class="work-card">
                    <div class="work-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>STT Terpadu Nurul Fikri - Teaching Assistant</h3>
                    <p>Assistant Lecturer for multiple courses including English, Computer Network, DevOps, and Software Engineering.</p>
                    <div class="work-tags">
                        <span>Teaching</span>
                        <span>Education</span>
                        <span>Mentoring</span>
                    </div>
                </div>
                <div class="work-card">
                    <div class="work-icon">
                        <i class="fas fa-cloud"></i>
                    </div>
                    <h3>MSIB Batch 6 - Codeless Data Science</h3>
                    <p>Student at PT Nurul Fikri Cipta Inovasi learning data science and analytics without coding.</p>
                    <div class="work-tags">
                        <span>Data Science</span>
                        <span>Analytics</span>
                        <span>Codeless Tools</span>
                    </div>
                </div>
                <div class="work-card">
                    <div class="work-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3>MSIB Batch 5 - Web Development</h3>
                    <p>Student at Infinite Learning mastering web development technologies and frameworks.</p>
                    <div class="work-tags">
                        <span>Web Development</span>
                        <span>Frontend</span>
                        <span>Backend</span>
                    </div>
                </div>
                <div class="work-card">
                    <div class="work-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>The Mayflower - Electrical Engineering</h3>
                    <p>Trainee Engineering at Marriot Executive Apartements applying electrical equipment and K3 principles.</p>
                    <div class="work-tags">
                        <span>Electrical</span>
                        <span>Installation</span>
                        <span>K3</span>
                    </div>
                </div>
                <div class="work-card">
                    <div class="work-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Karang Taruna & Campus Organization</h3>
                    <p>Public Relations and Human Resource roles in community and campus organizations.</p>
                    <div class="work-tags">
                        <span>Public Relations</span>
                        <span>HR Management</span>
                        <span>Community</span>
                    </div>
                </div>
            </div>
            <div class="works-cta">
                <a href="#" class="btn btn-outline">View All Works</a>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="timeline" id="timeline">
        <div class="container">
            <h2 class="section-title">The path I've taken</h2>
            <p class="section-subtitle">Experience and education timeline.</p>
            <div class="timeline-items">
                <div class="timeline-item">
                    <div class="timeline-date">Present</div>
                    <div class="timeline-content">
                        <h3>Pranata Komputer - Museum Penerangan</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">Sep 2024 - Jan 2025</div>
                    <div class="timeline-content">
                        <h3>Assistant Lecturer - PSI, RPL, Indonesian Language</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">Feb 2024 - Jun 2024</div>
                    <div class="timeline-content">
                        <h3>Student Codeless Data Science - MSIB Batch 6</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">Sep 2023 - Jul 2024</div>
                    <div class="timeline-content">
                        <h3>Assistant Lecturer - English, Network, PPKN, DevOps</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">Aug 2023 - Dec 2023</div>
                    <div class="timeline-content">
                        <h3>Student Web Development - MSIB Batch 5</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">Dec 2022 - Dec 2024</div>
                    <div class="timeline-content">
                        <h3>Junior IT Support Freelancer - PT. Onexpert International</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2022 - Present</div>
                    <div class="timeline-content">
                        <h3>Public Relations - Karang Taruna RT & RW</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2023 - Present</div>
                    <div class="timeline-content">
                        <h3>Human Resource - Campus Student Association</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">Oct 2019 - Mar 2020</div>
                    <div class="timeline-content">
                        <h3>Trainee Engineering - The Mayflower</h3>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2021 - 2025</div>
                    <div class="timeline-content">
                        <h3>Informatics Engineering - STT Terpadu Nurul Fikri</h3>
                    </div>
                </div>
            </div>
            <div class="achievements">
                <div class="achievement-item">
                    <div class="achievement-number">3.86</div>
                    <div class="achievement-label">GPA Score</div>
                </div>
                <div class="achievement-item">
                    <div class="achievement-number">4</div>
                    <div class="achievement-label">Certifications</div>
                </div>
                <div class="achievement-item">
                    <div class="achievement-number">2+</div>
                    <div class="achievement-label">Years Experience</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Certifications Section -->
    <section class="certifications" id="certifications">
        <div class="container">
            <h2 class="section-title">Certifications & Training</h2>
            <p class="section-subtitle">My professional certifications and online training documents.</p>
            <div class="certs-grid">
                <div class="cert-category">
                    <h3>NF Computer, STT Terpadu Nurul Fikri</h3>
                    <div class="cert-list">
                        <div class="cert-item">Microsoft Office Professional</div>
                        <div class="cert-item">Graphic Design</div>
                        <div class="cert-item">Cloud & Web Instant</div>
                        <div class="cert-item">Video Editing & Youtube</div>
                    </div>
                </div>
                <div class="cert-category">
                    <h3>Kampus Merdeka Programs</h3>
                    <div class="cert-list">
                        <div class="cert-item">MSIB Batch 6 - Codeless Data Science</div>
                        <div class="cert-item">PT Nurul Fikri Cipta Inovasi</div>
                        <div class="cert-item">MSIB Batch 5 - Web Development</div>
                        <div class="cert-item">Infinite Learning</div>
                    </div>
                </div>
                <div class="cert-category">
                    <h3>Other Experiences</h3>
                    <div class="cert-list">
                        <div class="cert-item">Volunteer - Assistant Trainer Common Room</div>
                        <div class="cert-item">Basic Network Education</div>
                        <div class="cert-item">Digital Financial Literacy</div>
                        <div class="cert-item">Network Device Installation</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <h2 class="section-title">Let's work together</h2>
            <p class="section-subtitle">Ready to bring your vision to life? Let's connect.</p>
            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-card">
                        <i class="fab fa-linkedin"></i>
                        <div>
                            <strong>LinkedIn:</strong>
                            <span><a href="https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/" target="_blank" style="color: var(--text-primary);">Muzaki Abdullah Irsyad</a></span>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fab fa-github"></i>
                        <div>
                            <strong>GitHub:</strong>
                            <span><a href="https://github.com/Muzaki29" target="_blank" style="color: var(--text-primary);">Muzaki29</a></span>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-graduation-cap"></i>
                        <div>
                            <strong>Education:</strong>
                            <span>STT Terpadu Nurul Fikri</span>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Location:</strong>
                            <span>Jakarta, Indonesia</span>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-star"></i>
                        <div>
                            <strong>GPA:</strong>
                            <span>3.86/4.00</span>
                        </div>
                    </div>
                </div>
                <div class="contact-form-card">
                    <h3>Send me a message</h3>
                    <p>Feel free to reach out if you're looking for a fresh graduate with passion in software development and IT support, have a question, or just want to connect.</p>
                    
                    <form id="contactForm" class="contact-form">
                        @csrf
                        <div class="form-group">
                            <input type="text" id="name" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" id="subject" name="subject" placeholder="Subject" required>
                        </div>
                        <div class="form-group">
                            <textarea id="message" name="message" rows="5" placeholder="Your Message" required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span id="submitText">Send Message</span>
                                <span id="submitLoading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Sending...
                                </span>
                            </button>
                        </div>
                        <div id="formMessage" class="form-message" style="display: none;"></div>
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
                    <p class="footer-copyright">© 2025 Muzaki Abdullah Irsyad. All rights reserved.</p>
                </div>
                <div class="footer-links">
                    <ul>
                        <li><a href="#about">About</a></li>
                        <li><a href="#works">Works</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="#certifications">Certifications</a></li>
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
    <script src="{{ asset('js/portfolio.js') }}"></script>
</body>
</html>

