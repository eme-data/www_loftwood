import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * Parallax images — subtle depth on scroll
 */
export function initParallax() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  document.querySelectorAll('[data-parallax]').forEach((el) => {
    const speed = parseFloat(el.dataset.parallax) || 0.15;

    gsap.to(el, {
      yPercent: speed * 100,
      ease: 'none',
      scrollTrigger: {
        trigger: el.parentElement || el,
        start: 'top bottom',
        end: 'bottom top',
        scrub: true,
      },
    });
  });
}

/**
 * Horizontal progress bar — e.g. reading progress
 */
export function initProgressBar() {
  const bar = document.querySelector('[data-progress-bar]');
  if (!bar) return;

  gsap.to(bar, {
    scaleX: 1,
    ease: 'none',
    scrollTrigger: {
      trigger: document.body,
      start: 'top top',
      end: 'bottom bottom',
      scrub: true,
    },
  });
}

/**
 * Section color transitions — background changes on scroll
 */
export function initSectionTransitions() {
  document.querySelectorAll('[data-section-bg]').forEach((section) => {
    const bg = section.dataset.sectionBg;

    ScrollTrigger.create({
      trigger: section,
      start: 'top 60%',
      end: 'bottom 40%',
      onEnter: () => gsap.to('body', { backgroundColor: bg, duration: 0.6, ease: 'power2.inOut' }),
      onLeaveBack: () => gsap.to('body', { backgroundColor: '#ffffff', duration: 0.6, ease: 'power2.inOut' }),
    });
  });
}
