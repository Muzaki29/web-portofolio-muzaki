import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { motion } from './config';

export function animateHero(desktop) {
    const timeline = gsap.timeline({ defaults: { ease: motion.ease, duration: motion.duration } });
    const portraitEntrance = desktop
        ? { clipPath: 'inset(0 0 100% 0)', duration: motion.heroDuration }
        : { scale: 1.02, duration: 0.55 };
    timeline.from('.hero-media', portraitEntrance)
        .from('.hero-line > span', { yPercent: 110, stagger: motion.stagger, duration: motion.heroDuration }, 0.15)
        .from('.hero-kicker, .hero-headline, .hero-actions, .hero-footnote', { autoAlpha: 0, y: 16, stagger: motion.stagger }, 0.4)
        .from('.navbar .container', { y: -16, autoAlpha: 0 }, 0.2);

    if (desktop) {
        gsap.to('.hero-media img', {
            yPercent: 7, ease: 'none',
            scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: 0.6 },
        });
        const ambient = gsap.to('.hero-atmosphere', {
            xPercent: 5, yPercent: -4, duration: 8, repeat: -1, yoyo: true, ease: 'sine.inOut',
        });
        ScrollTrigger.create({ trigger: '#hero', start: 'top bottom', end: 'bottom top', onToggle: state => ambient.paused(!state.isActive) });
    }
}
