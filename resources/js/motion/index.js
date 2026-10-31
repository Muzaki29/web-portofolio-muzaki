import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { motion } from './config';
import { animateHero } from './hero';
import { animateSections } from './scroll-reveal';
import '../../css/motion.css';

gsap.registerPlugin(ScrollTrigger);
let cleanup;

function initialize() {
    if (cleanup) return;
    const media = gsap.matchMedia();
    const events = new AbortController();
    let tabTween;
    const resetTab = () => {
        tabTween?.revert();
        tabTween = undefined;
    };
    try {
        media.add({ all: '(min-width: 0px)', desktop: motion.desktop, reduce: motion.reduce }, context => {
            resetTab();
            if (context.conditions.reduce) return;
            animateHero(context.conditions.desktop);
            animateSections(context.conditions.desktop);
        });

        const refresh = () => ScrollTrigger.refresh();
        document.fonts?.ready.then(() => { if (!events.signal.aborted) refresh(); });
        window.addEventListener('load', refresh, { once: true, signal: events.signal });
        document.querySelectorAll('img').forEach(image => {
            if (!image.complete) image.addEventListener('load', refresh, { once: true, signal: events.signal });
        });
        document.addEventListener('portfolio:tabchange', event => {
            resetTab();
            if (event.detail?.panel && !matchMedia(motion.reduce).matches) {
                tabTween = gsap.fromTo(event.detail.panel.querySelectorAll('.sk-card'), { opacity: 0, y: 12 }, {
                    opacity: 1, y: 0, duration: 0.35, stagger: 0.035, overwrite: true, clearProps: 'opacity,transform',
                });
            }
            refresh();
        }, { signal: events.signal });
        const syncVisibility = () => document.hidden ? gsap.globalTimeline.pause() : gsap.globalTimeline.resume();
        document.addEventListener('visibilitychange', syncVisibility, { signal: events.signal });
        syncVisibility();
        cleanup = () => {
            events.abort();
            resetTab();
            media.revert();
            gsap.globalTimeline.resume();
            cleanup = undefined;
        };
    } catch (error) {
        media.revert();
        resetTab();
        events.abort();
        console.warn('Portfolio motion unavailable; static content retained.', error);
    }
}

initialize();
window.addEventListener('pagehide', () => cleanup?.());
window.addEventListener('pageshow', initialize);
if (import.meta.hot) import.meta.hot.dispose(() => cleanup?.());
