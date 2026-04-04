import './main.css';
import { initHero } from './animations/hero.js';
import { initParallax, initProgressBar } from './animations/scroll-effects.js';

/**
 * Loftwood — Motion Design System
 */

// ============================================
// Scroll Reveal — IntersectionObserver
// ============================================

function initScrollReveal() {
  const elements = document.querySelectorAll('[data-reveal]');
  if (!elements.length) return;

  // Respect prefers-reduced-motion
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    elements.forEach((el) => el.classList.add('is-revealed'));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-revealed');
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.15,
      rootMargin: '0px 0px -50px 0px',
    }
  );

  elements.forEach((el) => observer.observe(el));
}

// ============================================
// Smart Sticky Header
// ============================================

function initStickyHeader() {
  const header = document.querySelector('.header-loftwood');
  if (!header) return;

  let lastScroll = 0;
  const scrollThreshold = 100;

  window.addEventListener(
    'scroll',
    () => {
      const currentScroll = window.scrollY;

      // Add scrolled state for backdrop blur
      header.classList.toggle('is-scrolled', currentScroll > 50);

      // Hide/show on scroll direction
      if (currentScroll > scrollThreshold) {
        header.classList.toggle('is-hidden', currentScroll > lastScroll);
      } else {
        header.classList.remove('is-hidden');
      }

      lastScroll = currentScroll;
    },
    { passive: true }
  );
}

// ============================================
// Animated Counters
// ============================================

function initCounters() {
  const counters = document.querySelectorAll('[data-counter]');
  if (!counters.length) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.5 }
  );

  counters.forEach((el) => observer.observe(el));
}

function animateCounter(el) {
  const target = parseInt(el.dataset.counter, 10);
  const suffix = el.dataset.counterSuffix || '';
  const duration = 1500;
  const start = performance.now();

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    el.textContent = target + suffix;
    return;
  }

  function update(now) {
    const elapsed = now - start;
    const progress = Math.min(elapsed / duration, 1);
    // Ease out cubic
    const eased = 1 - Math.pow(1 - progress, 3);
    const current = Math.round(eased * target);

    el.textContent = current + suffix;

    if (progress < 1) {
      requestAnimationFrame(update);
    }
  }

  requestAnimationFrame(update);
}

// ============================================
// Image lazy load fade-in
// ============================================

function initImageReveal() {
  const images = document.querySelectorAll('img[loading="lazy"]');

  images.forEach((img) => {
    if (img.complete) return;

    img.style.opacity = '0';
    img.style.transition = `opacity 400ms var(--ease-enter)`;

    img.addEventListener('load', () => {
      img.style.opacity = '1';
    }, { once: true });
  });
}

// ============================================
// Mobile Menu Toggle
// ============================================

function initMobileMenu() {
  const toggle = document.getElementById('mobile-menu-toggle');
  const menu = document.getElementById('mobile-menu');
  if (!toggle || !menu) return;

  toggle.addEventListener('click', () => {
    const isOpen = !menu.classList.contains('hidden');
    menu.classList.toggle('hidden');
    toggle.setAttribute('aria-expanded', !isOpen);

    // Animate hamburger
    const bars = toggle.querySelectorAll('span');
    if (!isOpen) {
      bars[0].style.transform = 'rotate(45deg) translateY(8px)';
      bars[1].style.opacity = '0';
      bars[2].style.transform = 'rotate(-45deg) translateY(-8px)';
    } else {
      bars[0].style.transform = '';
      bars[1].style.opacity = '';
      bars[2].style.transform = '';
    }
  });
}

// ============================================
// Init
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  initScrollReveal();
  initStickyHeader();
  initCounters();
  initImageReveal();
  initHero();
  initParallax();
  initProgressBar();
  initMobileMenu();
});
