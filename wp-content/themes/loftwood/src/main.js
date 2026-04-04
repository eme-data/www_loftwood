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
    const isOpen = menu.style.display === 'block';
    menu.style.display = isOpen ? 'none' : 'block';
    toggle.setAttribute('aria-expanded', !isOpen);

    // Animate hamburger
    const bars = toggle.querySelectorAll('span');
    if (!isOpen) {
      bars[0].style.transform = 'rotate(45deg) translateY(7px)';
      bars[1].style.opacity = '0';
      bars[2].style.transform = 'rotate(-45deg) translateY(-7px)';
    } else {
      bars[0].style.transform = '';
      bars[1].style.opacity = '';
      bars[2].style.transform = '';
    }
  });
}

// ============================================
// Custom Cursor — Premium interaction
// ============================================

function initCustomCursor() {
  // Skip on touch devices
  if (window.matchMedia('(hover: none)').matches) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const cursor = document.createElement('div');
  cursor.className = 'lw-cursor';
  const follower = document.createElement('div');
  follower.className = 'lw-cursor-follower';
  document.body.appendChild(cursor);
  document.body.appendChild(follower);

  let cx = 0, cy = 0; // cursor position
  let fx = 0, fy = 0; // follower position

  document.addEventListener('mousemove', (e) => {
    cx = e.clientX;
    cy = e.clientY;
    cursor.style.transform = `translate(${cx}px, ${cy}px) translate(-50%, -50%)`;
  }, { passive: true });

  // Smooth follower
  function animateFollower() {
    fx += (cx - fx) * 0.12;
    fy += (cy - fy) * 0.12;
    follower.style.transform = `translate(${fx}px, ${fy}px) translate(-50%, -50%)`;
    requestAnimationFrame(animateFollower);
  }
  requestAnimationFrame(animateFollower);

  // Hover state on interactive elements
  const hoverTargets = document.querySelectorAll('a, button, [role="button"], .card-loftwood');
  hoverTargets.forEach((el) => {
    el.addEventListener('mouseenter', () => {
      cursor.classList.add('is-hover');
      follower.classList.add('is-hover');
    });
    el.addEventListener('mouseleave', () => {
      cursor.classList.remove('is-hover');
      follower.classList.remove('is-hover');
    });
  });
}

// ============================================
// Preloader — Page load animation
// ============================================

function initPreloader() {
  const preloader = document.querySelector('.lw-preloader');
  if (!preloader) return;

  window.addEventListener('load', () => {
    setTimeout(() => {
      preloader.classList.add('is-loaded');
    }, 300);
  });
}

// ============================================
// Init
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  initPreloader();
  initScrollReveal();
  initStickyHeader();
  initCounters();
  initImageReveal();
  initHero();
  initParallax();
  initProgressBar();
  initMobileMenu();
  initCustomCursor();
});
