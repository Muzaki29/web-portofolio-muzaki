// Portfolio JavaScript - Enhanced 2025 Edition ✨

// Custom Cursor with Trail
let cursor = null;
let cursorTrails = [];
const trailCount = 10;
const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function initCustomCursor() {
    // Only enable on desktop (non-touch devices)
    if (window.matchMedia('(pointer: fine)').matches) {
        cursor = document.createElement('div');
        cursor.className = 'cursor';
        document.body.appendChild(cursor);

        // Create trail elements
        for (let i = 0; i < trailCount; i++) {
            const trail = document.createElement('div');
            trail.className = 'cursor-trail';
            document.body.appendChild(trail);
            cursorTrails.push(trail);
        }

        let mouseX = 0, mouseY = 0;
        let cursorX = 0, cursorY = 0;

        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
            cursor.style.opacity = '1';
        });

        // Animate cursor
        function animateCursor() {
            cursorX += (mouseX - cursorX) * 0.1;
            cursorY += (mouseY - cursorY) * 0.1;
            
            cursor.style.left = cursorX + 'px';
            cursor.style.top = cursorY + 'px';

            // Animate trails
            cursorTrails.forEach((trail, index) => {
                const delay = index * 0.02;
                setTimeout(() => {
                    trail.style.left = (cursorX - 4) + 'px';
                    trail.style.top = (cursorY - 4) + 'px';
                    trail.style.opacity = (trailCount - index) / trailCount * 0.6;
                }, delay * 1000);
            });

            requestAnimationFrame(animateCursor);
        }
        animateCursor();

        // Cursor interactions
        document.querySelectorAll('a, button, .btn, .work-card, .service-card').forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursor.classList.add('cursor-active');
            });
            el.addEventListener('mouseleave', () => {
                cursor.classList.remove('cursor-active');
            });
        });
    }
}

// Scroll Progress Bar
function initScrollProgress() {
    const progressBar = document.createElement('div');
    progressBar.className = 'scroll-progress';
    document.body.appendChild(progressBar);

    window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        progressBar.style.transform = `scaleX(${scrolled / 100})`;
    });
}

// Back to Top Button
function initBackToTop() {
    const backToTop = document.createElement('button');
    backToTop.className = 'back-to-top';
    backToTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTop.setAttribute('aria-label', 'Back to top');
    document.body.appendChild(backToTop);

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    });

    backToTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Text Reveal Animation
function initTextReveal() {
    const revealElements = document.querySelectorAll('.section-title, .hero-name, .hero-title');
    
    revealElements.forEach((el, index) => {
        const text = el.textContent;
        el.classList.add('reveal-text');
        el.innerHTML = '';
        
        [...text].forEach((char, charIndex) => {
            const span = document.createElement('span');
            span.textContent = char === ' ' ? '\u00A0' : char;
            span.style.animationDelay = `${(index * 0.1) + (charIndex * 0.03)}s`;
            el.appendChild(span);
        });
    });
}

// Counter Animation
function animateCounter(element, target, duration = 2000) {
    const start = 0;
    const originalText = element.textContent;
    const hasDecimal = originalText.includes('.');
    const hasPlus = originalText.includes('+');
    const increment = target / (duration / 16);
    let current = start;

    const updateCounter = () => {
        current += increment;
        if (current < target) {
            if (hasDecimal) {
                element.textContent = current.toFixed(2);
            } else {
                element.textContent = Math.floor(current).toLocaleString();
            }
            requestAnimationFrame(updateCounter);
        } else {
            // Restore original text (with + if it had one)
            if (hasPlus && !hasDecimal) {
                element.textContent = Math.floor(target).toLocaleString() + '+';
            } else {
                element.textContent = originalText;
            }
        }
    };

    updateCounter();
}

// Initialize Counter Observers
function initCounters() {
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px'
    };

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.counted) {
                entry.target.dataset.counted = 'true';
                const target = parseFloat(entry.target.textContent.replace(/[^\d.]/g, ''));
                if (!isNaN(target)) {
                    animateCounter(entry.target, target);
                }
            }
        });
    }, observerOptions);

    document.querySelectorAll('.achievement-number, .counter-number').forEach(el => {
        counterObserver.observe(el);
    });
}

// Parallax Effect
function initParallax() {
    const parallaxElements = document.querySelectorAll('.parallax-element');
    
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        
        parallaxElements.forEach((el, index) => {
            const speed = 0.5 + (index % 3) * 0.1;
            const yPos = -(scrolled * speed);
            el.style.transform = `translateY(${yPos}px)`;
        });
    });
}

// Enhanced Intersection Observer with Stagger
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
        if (entry.isIntersecting && !prefersReducedMotion) {
            setTimeout(() => {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0) scale(1)';
            }, index * 100);
        }
    });
}, observerOptions);

// Mobile Menu Toggle
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');

if (hamburger && navMenu) {
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
    });

    // Close menu when clicking on a link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
        });
    });
}

// Smooth Scroll for Navigation Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            const offsetTop = target.offsetTop - 80;
            window.scrollTo({
                top: offsetTop,
                behavior: prefersReducedMotion ? 'auto' : 'smooth'
            });
        }
    });
});

// Navbar Background on Scroll
const navbar = document.getElementById('navbar');
function updateNavbarOnScroll() {
    if (!navbar) return;
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
    if (window.scrollY > 50) {
        if (currentTheme === 'light') {
            navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
            navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        } else {
            navbar.style.backgroundColor = 'rgba(10, 10, 10, 0.98)';
            navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.3)';
        }
    } else {
        if (currentTheme === 'light') {
            navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
        } else {
            navbar.style.backgroundColor = 'rgba(10, 10, 10, 0.95)';
        }
        navbar.style.boxShadow = 'none';
    }
}

if (navbar) {
    window.addEventListener('scroll', updateNavbarOnScroll);
    // Initial call
    updateNavbarOnScroll();
}

// Active Navigation Link on Scroll
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-link');

function activateNavLink() {
    const scrollY = window.pageYOffset;

    sections.forEach(section => {
        const sectionHeight = section.offsetHeight;
        const sectionTop = section.offsetTop - 100;
        const sectionId = section.getAttribute('id');

        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${sectionId}`) {
                    link.classList.add('active');
                }
            });
        }
    });
}

window.addEventListener('scroll', activateNavLink);

// Add active class styling
const style = document.createElement('style');
style.textContent = `
    .nav-link.active {
        color: var(--blue-primary) !important;
    }
`;
document.head.appendChild(style);

// Observer untuk cards sudah didefinisikan di atas (line 193)
// Tidak perlu duplikat deklarasi observerOptions

// Observe all cards and items with enhanced animations
document.querySelectorAll('.service-card, .work-card, .skill-category, .cert-category, .timeline-item, .achievement-item, .contact-card').forEach((el, index) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px) scale(0.95)';
    el.style.transition = `opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)`;
    el.style.transitionDelay = `${index * 0.1}s`;
    observer.observe(el);
});

// Add parallax class to hero image
const heroImage = document.querySelector('.hero-image');
if (heroImage) {
    heroImage.classList.add('parallax-element');
    heroImage.classList.add('float-animation');
}

// Form Validation (if contact form is added later)
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Add hover effect for service cards
document.querySelectorAll('.service-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px) scale(1.02)';
    });
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// Add click animation for buttons
document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (prefersReducedMotion) return;
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        
        this.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});

// Add ripple effect styles
const rippleStyle = document.createElement('style');
rippleStyle.textContent = `
    .btn {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(rippleStyle);

// Contact Form Submission
const contactForm = document.getElementById('contactForm');
const submitBtn = document.getElementById('submitBtn');
const submitText = document.getElementById('submitText');
const submitLoading = document.getElementById('submitLoading');
const formMessage = document.getElementById('formMessage');
const contactI18n = (window.i18n && window.i18n.contact) ? window.i18n.contact : {};

if (contactForm) {
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Disable submit button
        submitBtn.disabled = true;
        submitText.style.display = 'none';
        submitLoading.style.display = 'inline-block';
        formMessage.style.display = 'none';
        
        // Get form data
        const formData = new FormData(contactForm);
        
        try {
            const response = await fetch('/contact', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                formMessage.style.display = 'block';
                formMessage.className = 'form-message success';
                formMessage.textContent = data.message || contactI18n.successFallback || 'Message sent successfully! Thank you.';
                contactForm.reset();
                
                // Scroll to message
                formMessage.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth', block: 'nearest' });
            } else {
                formMessage.style.display = 'block';
                formMessage.className = 'form-message error';
                formMessage.textContent = data.message || contactI18n.errorFallback || 'An error occurred. Please try again.';
                
                // Show validation errors if any
                if (data.errors) {
                    let errorText = data.message || contactI18n.validationPrefix || 'Please fix the following errors:';
                    for (let field in data.errors) {
                        errorText += '\n- ' + data.errors[field][0];
                    }
                    formMessage.textContent = errorText;
                }
            }
        } catch (error) {
            formMessage.style.display = 'block';
            formMessage.className = 'form-message error';
            formMessage.textContent = contactI18n.genericRetryLater || 'An error occurred while sending the message. Please try again later.';
            console.error('Error:', error);
        } finally {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitText.style.display = 'inline-block';
            submitLoading.style.display = 'none';
        }
    });
}

// Theme Toggle Functionality
const html = document.documentElement;

// Set theme immediately to prevent flash
const savedTheme = localStorage.getItem('theme') || 'dark';
html.setAttribute('data-theme', savedTheme);

// Update theme icon function
function updateThemeIcon(theme) {
    const themeIcon = document.getElementById('themeIcon');
    if (themeIcon) {
        if (theme === 'dark') {
            themeIcon.className = 'fas fa-moon';
            themeIcon.setAttribute('aria-label', 'Switch to light mode');
        } else {
            themeIcon.className = 'fas fa-sun';
            themeIcon.setAttribute('aria-label', 'Switch to dark mode');
        }
    }
}

// Toggle theme function
function toggleTheme() {
    const currentTheme = html.getAttribute('data-theme') || 'dark';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
    
    // Update navbar background if function exists
    if (typeof updateNavbarOnScroll === 'function') {
        updateNavbarOnScroll();
    }
}

// Initialize all enhanced features
document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('themeToggle');
    
    // Initialize theme icon
    updateThemeIcon(savedTheme);
    
    // Add click event listener to theme toggle button
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
        console.log('Theme toggle initialized');
    } else {
        console.error('Theme toggle button not found!');
    }
    if (!prefersReducedMotion) {
        initCustomCursor();
        initScrollProgress();
        initBackToTop();
        initTextReveal();
        initCounters();
        initParallax();
    }
    
    // Add stagger animation to cards
    document.querySelectorAll('.service-card, .work-card').forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Add glow effect to profile circle
    const profileCircle = document.querySelector('.profile-circle');
    if (profileCircle) {
        profileCircle.classList.add('glow-effect');
    }
});

console.log('Portfolio script loaded successfully! ✨🎉 Interactive 2025 Edition!');

