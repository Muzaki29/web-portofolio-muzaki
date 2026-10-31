import { gsap } from 'gsap';
import { motion } from './config';

export function animateSections(desktop) {
    const selector = [
        'section:not(#hero) .section-title', 'section:not(#hero) .section-subtitle',
        '.about-desc', '.resume-showcase', '.exp-topic', '.tl-item',
        '.project-grid-card', '.cert-card', '.contact-grid', '.hero-services', '.footer-clean .container',
    ].join(',');

    gsap.utils.toArray(selector).forEach(element => {
        const isCard = element.matches('.project-grid-card, .cert-card, .exp-topic, .tl-item');
        const index = isCard ? [...element.parentElement.children].indexOf(element) : 0;
        // Start only on entry: interrupted loading cannot leave offscreen content hidden.
        gsap.from(element, {
            y: desktop ? motion.distance : 16, opacity: 0, duration: motion.duration,
            delay: desktop ? (index % 3) * motion.stagger : 0,
            clearProps: 'opacity,transform',
            ease: motion.ease, immediateRender: false,
            scrollTrigger: { trigger: element, start: motion.start, once: true },
        });
    });
    gsap.utils.toArray('.project-grid-card .pgc-image img').forEach(image => {
        gsap.from(image, {
            scale: 1.08, duration: motion.heroDuration, ease: motion.ease, immediateRender: false, clearProps: 'transform',
            scrollTrigger: { trigger: image, start: motion.start, once: true },
        });
    });
    if (desktop && document.querySelector('.tl-items')) {
        gsap.from('.tl-items', {
            '--timeline-progress': 0, ease: 'none',
            scrollTrigger: { trigger: '.tl-items', start: 'top 70%', end: 'bottom 65%', scrub: true },
        });
    }
}
