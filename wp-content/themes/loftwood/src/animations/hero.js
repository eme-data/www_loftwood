import { gsap } from 'gsap';

/**
 * Hero section timeline animation
 */
export function initHero() {
  const hero = document.querySelector('[data-hero]');
  if (!hero) return;

  const headlines = hero.querySelectorAll('[data-hero-headline]');
  const subtitle = hero.querySelector('[data-hero-subtitle]');
  const cta = hero.querySelector('[data-hero-cta]');

  // Skip animations if user prefers reduced motion
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    return; // Elements are already visible by default
  }

  const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

  // Headlines: slide up + fade
  if (headlines.length) {
    gsap.set(headlines, { opacity: 0, y: 50 });
    tl.to(headlines, {
      opacity: 1,
      y: 0,
      duration: 1,
      stagger: 0.15,
    });
  }

  // Subtitle
  if (subtitle) {
    gsap.set(subtitle, { opacity: 0, y: 30 });
    tl.to(subtitle, { opacity: 1, y: 0, duration: 0.7 }, '-=0.5');
  }

  // CTA buttons
  if (cta) {
    gsap.set(cta, { opacity: 0, y: 20 });
    tl.to(cta, { opacity: 1, y: 0, duration: 0.6 }, '-=0.3');
  }
}
