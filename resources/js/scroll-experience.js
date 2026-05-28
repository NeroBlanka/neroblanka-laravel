import Lenis from 'lenis';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function initScrollExperience() {
    if (prefersReducedMotion) {
        document.querySelectorAll('[data-reveal]').forEach(el => el.classList.add('is-visible'));
        return;
    }

    // ─── Lenis smooth scroll ───
    const lenis = new Lenis({
        duration: 1.1,
        easing: t => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        smoothTouch: false,
    });

    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);

    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(time => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);

    // Expose for debugging / anchor-link scrolling
    window.__lenis = lenis;

    // Smooth-scroll in-page anchor links via Lenis
    document.querySelectorAll('a[href^="/#"], a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            const id = link.getAttribute('href').split('#')[1];
            const target = id && document.getElementById(id);
            if (target) {
                e.preventDefault();
                lenis.scrollTo(target, { offset: -80 });
            }
        });
    });

    // ─── Hero 3D card rotation on scroll ───
    const heroCard = document.querySelector('[data-hero-card]');
    if (heroCard) {
        gsap.to(heroCard, {
            rotateY: -10,
            rotateX: 6,
            scale: 0.94,
            ease: 'none',
            scrollTrigger: {
                trigger: heroCard,
                start: 'top top+=100',
                end: '+=600',
                scrub: 0.6,
            },
        });
    }

    // ─── Letter/word reveal on scroll ───
    document.querySelectorAll('[data-split-text]').forEach(el => {
        const text = el.textContent;
        const words = text.split(/(\s+)/);
        el.innerHTML = words.map(w => {
            if (/^\s+$/.test(w)) return w;
            return `<span class="inline-block translate-y-[110%] opacity-0" style="will-change: transform, opacity">${w}</span>`;
        }).join('');

        const spans = el.querySelectorAll('span');
        gsap.to(spans, {
            y: 0,
            opacity: 1,
            duration: 0.9,
            stagger: 0.05,
            ease: 'expo.out',
            scrollTrigger: {
                trigger: el,
                start: 'top 85%',
                once: true,
            },
        });
    });

    // ─── Reveal sections (fade-up) ───
    document.querySelectorAll('[data-reveal]').forEach(el => {
        gsap.from(el, {
            y: 40,
            opacity: 0,
            duration: 1.0,
            ease: 'expo.out',
            scrollTrigger: {
                trigger: el,
                start: 'top 85%',
                once: true,
            },
        });
    });

    // ─── Storytelling pinned section (Design → 3D → IA) ───
    const storyTrack = document.querySelector('[data-story-track]');
    if (storyTrack) {
        const panels = gsap.utils.toArray(storyTrack.querySelectorAll('[data-story-panel]'));
        const dots = gsap.utils.toArray(storyTrack.querySelectorAll('[data-story-dot]'));

        // Explicit initial state — only first panel visible
        panels.forEach((panel, i) => {
            gsap.set(panel, { autoAlpha: i === 0 ? 1 : 0, y: i === 0 ? 0 : 50 });
        });
        if (dots.length) gsap.set(dots, { scale: 1, backgroundColor: 'var(--gris-bord)' });

        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: storyTrack,
                start: 'top top',
                end: () => `+=${(panels.length - 1) * window.innerHeight}`,
                pin: true,
                scrub: 0.7,
                invalidateOnRefresh: true,
            },
        });

        // Each step: brief hold, crossfade prev→next with slight overlap
        panels.forEach((panel, i) => {
            if (i === 0) return;
            const at = (i - 1) + 0.35; // hold the current panel before transitioning
            tl.to(panels[i - 1], { autoAlpha: 0, y: -50, duration: 0.5, ease: 'power2.in' }, at);
            tl.to(panel, { autoAlpha: 1, y: 0, duration: 0.5, ease: 'power2.out' }, at + 0.25);
            if (dots[i] && dots[i - 1]) {
                tl.to(dots[i - 1], { backgroundColor: 'var(--gris-bord)', duration: 0.4 }, at + 0.25);
                tl.to(dots[i], { backgroundColor: 'var(--carbone)', duration: 0.4 }, at + 0.25);
            }
        });
    }

    // ─── Process : horizontal scroll on vertical scroll ───
    const processSection = document.querySelector('[data-h-scroll]');
    if (processSection && window.innerWidth >= 768) {
        const track = processSection.querySelector('[data-h-scroll-track]');
        if (track) {
            const distance = () => track.scrollWidth - processSection.clientWidth + 48;
            gsap.to(track, {
                x: () => -distance(),
                ease: 'none',
                scrollTrigger: {
                    trigger: processSection,
                    start: 'top top+=80',
                    end: () => `+=${distance()}`,
                    pin: true,
                    scrub: 0.8,
                    invalidateOnRefresh: true,
                },
            });
        }
    }

    // ─── Portfolio parallax velocity ───
    document.querySelectorAll('[data-parallax]').forEach(el => {
        const speed = parseFloat(el.dataset.parallax) || 0.4;
        gsap.to(el, {
            yPercent: -20 * speed,
            ease: 'none',
            scrollTrigger: {
                trigger: el,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true,
            },
        });
    });

    // ─── Marquee velocity-driven ───
    const marquees = document.querySelectorAll('[data-marquee]');
    marquees.forEach(marquee => {
        const track = marquee.querySelector('.marquee-track');
        if (!track) return;

        let baseSpeed = 0.6;
        let speed = baseSpeed;
        let x = 0;

        lenis.on('scroll', e => {
            const velocity = e.velocity || 0;
            speed = baseSpeed + Math.min(Math.abs(velocity) * 0.15, 8);
            if (velocity < 0) {
                track.style.animationDirection = 'reverse';
            } else {
                track.style.animationDirection = 'normal';
            }
        });

        gsap.ticker.add(() => {
            x -= speed;
            const trackWidth = track.scrollWidth / 2;
            if (-x >= trackWidth) x = 0;
            track.style.transform = `translateX(${x}px)`;
            speed += (baseSpeed - speed) * 0.05;
        });

        // disable CSS animation since JS drives it
        track.style.animation = 'none';
    });

    // ─── Image tilt 3D on hover (cards portfolio + hero card) ───
    document.querySelectorAll('[data-tilt]').forEach(el => {
        const max = parseFloat(el.dataset.tilt) || 8;
        let rect;

        const onEnter = () => {
            rect = el.getBoundingClientRect();
            el.style.transition = 'transform 200ms cubic-bezier(0.16, 1, 0.3, 1)';
        };
        const onMove = e => {
            if (!rect) return;
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;
            el.style.transform = `perspective(1000px) rotateX(${-y * max}deg) rotateY(${x * max}deg) translateZ(0)`;
        };
        const onLeave = () => {
            el.style.transition = 'transform 600ms cubic-bezier(0.16, 1, 0.3, 1)';
            el.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
        };

        el.addEventListener('mouseenter', onEnter);
        el.addEventListener('mousemove', onMove);
        el.addEventListener('mouseleave', onLeave);
    });

    // ─── Magnetic CTAs ───
    document.querySelectorAll('[data-magnetic]').forEach(el => {
        const strength = parseFloat(el.dataset.magnetic) || 0.3;
        let rect;

        const onEnter = () => { rect = el.getBoundingClientRect(); };
        const onMove = e => {
            if (!rect) return;
            const x = (e.clientX - rect.left - rect.width / 2) * strength;
            const y = (e.clientY - rect.top - rect.height / 2) * strength;
            gsap.to(el, { x, y, duration: 0.6, ease: 'power3.out' });
        };
        const onLeave = () => {
            gsap.to(el, { x: 0, y: 0, duration: 0.8, ease: 'elastic.out(1, 0.4)' });
        };

        el.addEventListener('mouseenter', onEnter);
        el.addEventListener('mousemove', onMove);
        el.addEventListener('mouseleave', onLeave);
    });

    // ─── Refresh on resize ───
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => ScrollTrigger.refresh(), 200);
    });
}
