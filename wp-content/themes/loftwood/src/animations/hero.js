import { gsap } from 'gsap';

/**
 * Hero section timeline animation
 * - Headline reveal line by line
 * - Subtitle fade in
 * - CTA scale + glow
 */
export function initHero() {
  const hero = document.querySelector('[data-hero]');
  if (!hero) return;

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    hero.querySelectorAll('[data-hero-headline], [data-hero-subtitle], [data-hero-cta]')
      .forEach((el) => { el.style.opacity = '1'; el.style.transform = 'none'; });
    return;
  }

  const tl = gsap.timeline({
    defaults: {
      ease: 'power3.out',
      duration: 0.8,
    },
  });

  // Headline lines reveal
  const headlines = hero.querySelectorAll('[data-hero-headline]');
  if (headlines.length) {
    gsap.set(headlines, { opacity: 0, y: 40, clipPath: 'inset(0 0 100% 0)' });
    tl.to(headlines, {
      opacity: 1,
      y: 0,
      clipPath: 'inset(0 0 0% 0)',
      stagger: 0.12,
      duration: 0.9,
    });
  }

  // Subtitle
  const subtitle = hero.querySelector('[data-hero-subtitle]');
  if (subtitle) {
    gsap.set(subtitle, { opacity: 0, y: 20 });
    tl.to(subtitle, { opacity: 1, y: 0, duration: 0.6 }, '-=0.3');
  }

  // CTA button
  const cta = hero.querySelector('[data-hero-cta]');
  if (cta) {
    gsap.set(cta, { opacity: 0, scale: 0.95 });
    tl.to(cta, { opacity: 1, scale: 1, duration: 0.5 }, '-=0.2');
  }
}
