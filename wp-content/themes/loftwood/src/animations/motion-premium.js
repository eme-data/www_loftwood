import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * Image Reveal — clip-path wipe on scroll
 * Usage: <div data-reveal-image="left|right|up|down">
 */
export function initImageReveal() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const directions = {
    left:  { from: 'inset(0 100% 0 0)', to: 'inset(0 0% 0 0)' },
    right: { from: 'inset(0 0 0 100%)', to: 'inset(0 0 0 0%)' },
    up:    { from: 'inset(100% 0 0 0)', to: 'inset(0% 0 0 0)' },
    down:  { from: 'inset(0 0 100% 0)', to: 'inset(0 0 0% 0)' },
  };

  document.querySelectorAll('[data-reveal-image]').forEach((el) => {
    const dir = el.dataset.revealImage || 'left';
    const d = directions[dir] || directions.left;

    gsap.set(el, { clipPath: d.from });

    gsap.to(el, {
      clipPath: d.to,
      duration: 1.2,
      ease: 'power3.inOut',
      scrollTrigger: {
        trigger: el,
        start: 'top 80%',
        end: 'top 30%',
        toggleActions: 'play none none none',
      },
    });
  });
}

/**
 * Magnetic Buttons — CTA follows cursor slightly
 * Usage: add class .magnetic-hover or data-magnetic
 */
export function initMagneticButtons() {
  if (window.matchMedia('(hover: none)').matches) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const elements = document.querySelectorAll('.lw-header-cta, .lw-btn-primary, .footer-cta-eco, [data-magnetic]');

  elements.forEach((el) => {
    el.addEventListener('mousemove', (e) => {
      const rect = el.getBoundingClientRect();
      const x = e.clientX - rect.left - rect.width / 2;
      const y = e.clientY - rect.top - rect.height / 2;

      gsap.to(el, {
        x: x * 0.2,
        y: y * 0.2,
        duration: 0.3,
        ease: 'power2.out',
      });
    });

    el.addEventListener('mouseleave', () => {
      gsap.to(el, {
        x: 0,
        y: 0,
        duration: 0.5,
        ease: 'elastic.out(1, 0.5)',
      });
    });
  });
}

/**
 * 3D Card Tilt — perspective tilt on hover
 * Usage: add data-tilt to card elements
 */
export function initCardTilt() {
  if (window.matchMedia('(hover: none)').matches) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  document.querySelectorAll('.card-loftwood, [data-tilt]').forEach((card) => {
    const intensity = parseFloat(card.dataset.tiltIntensity) || 8;

    card.style.transformStyle = 'preserve-3d';
    card.style.willChange = 'transform';

    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;

      gsap.to(card, {
        rotateY: x * intensity,
        rotateX: -y * intensity,
        duration: 0.4,
        ease: 'power2.out',
      });
    });

    card.addEventListener('mouseleave', () => {
      gsap.to(card, {
        rotateY: 0,
        rotateX: 0,
        duration: 0.6,
        ease: 'elastic.out(1, 0.6)',
      });
    });
  });
}

/**
 * Text Parallax — headings move at different speed
 * Usage: data-text-parallax="0.1" on any heading
 */
export function initTextParallax() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  document.querySelectorAll('[data-text-parallax]').forEach((el) => {
    const speed = parseFloat(el.dataset.textParallax) || 0.1;

    gsap.to(el, {
      yPercent: speed * -50,
      ease: 'none',
      scrollTrigger: {
        trigger: el.parentElement || el,
        start: 'top bottom',
        end: 'bottom top',
        scrub: 1,
      },
    });
  });
}

/**
 * Staggered Line Reveal — text lines animate in sequence
 * Usage: data-line-reveal on paragraph/heading
 */
export function initLineReveal() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  document.querySelectorAll('[data-line-reveal]').forEach((el) => {
    // Wrap each line-like child in a span for animation
    const words = el.textContent.split(' ');
    el.innerHTML = words.map((w) =>
      `<span class="lw-word-wrap"><span class="lw-word">${w}</span></span>`
    ).join(' ');

    const wordSpans = el.querySelectorAll('.lw-word');

    gsap.set(wordSpans, { y: '100%', opacity: 0 });

    gsap.to(wordSpans, {
      y: '0%',
      opacity: 1,
      duration: 0.6,
      stagger: 0.03,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: el,
        start: 'top 85%',
        toggleActions: 'play none none none',
      },
    });
  });
}

/**
 * Smooth Counter — enhanced with GSAP for smoother feel
 * Replaces the vanilla counter when GSAP is available
 */
export function initSmoothCounters() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  document.querySelectorAll('[data-counter]').forEach((el) => {
    if (el.dataset.gsapCounted) return;
    el.dataset.gsapCounted = 'true';

    const target = parseInt(el.dataset.counter, 10);
    const suffix = el.dataset.counterSuffix || '';
    const obj = { val: 0 };

    ScrollTrigger.create({
      trigger: el,
      start: 'top 85%',
      once: true,
      onEnter: () => {
        gsap.to(obj, {
          val: target,
          duration: 2,
          ease: 'power2.out',
          onUpdate: () => {
            el.textContent = Math.round(obj.val) + suffix;
          },
        });
      },
    });
  });
}
