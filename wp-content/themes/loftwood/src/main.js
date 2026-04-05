import './main.css';
import Lenis from 'lenis';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { initHero } from './animations/hero.js';
import { initParallax, initProgressBar } from './animations/scroll-effects.js';
import {
  initImageReveal,
  initMagneticButtons,
  initCardTilt,
  initTextParallax,
  initLineReveal,
  initSmoothCounters,
} from './animations/motion-premium.js';

gsap.registerPlugin(ScrollTrigger);

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

function initLazyImages() {
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
// Fullscreen Menu Toggle
// ============================================

function initMobileMenu() {
  const toggle = document.getElementById('menu-toggle');
  const menu = document.getElementById('fullscreen-menu');
  if (!toggle || !menu) return;

  // Create backdrop
  const backdrop = document.createElement('div');
  backdrop.className = 'lw-menu-backdrop';
  document.body.appendChild(backdrop);

  let isOpen = false;

  function openMenu() {
    isOpen = true;
    menu.classList.add('is-open');
    backdrop.classList.add('is-visible');
    toggle.classList.add('is-active');
    toggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    isOpen = false;
    menu.classList.remove('is-open');
    backdrop.classList.remove('is-visible');
    toggle.classList.remove('is-active');
    toggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  toggle.addEventListener('click', () => {
    isOpen ? closeMenu() : openMenu();
  });

  // Close on backdrop click
  backdrop.addEventListener('click', closeMenu);

  // Close on nav link click
  menu.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', closeMenu);
  });

  // Close on Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isOpen) closeMenu();
  });
}

// ============================================
// Custom Cursor — Premium interaction
// ============================================

function initCustomCursor() {
  // Skip on touch devices
  if (window.matchMedia('(hover: none)').matches) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  document.body.classList.add('has-custom-cursor');

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
// Preloader — Animated logo reveal
// ============================================

function initPreloader() {
  const preloader = document.querySelector('.lw-preloader');
  if (!preloader) return;

  // Minimum: pastilles reveal (1.5s) + text (0.6s) + progress (1s) + buffer
  const minDuration = 3300;
  const startTime = performance.now();

  window.addEventListener('load', () => {
    const elapsed = performance.now() - startTime;
    const remaining = Math.max(0, minDuration - elapsed);

    setTimeout(() => {
      preloader.classList.add('is-loaded');
    }, remaining);
  });

  // Safety: force hide after 5s even if load event is slow
  setTimeout(() => {
    preloader.classList.add('is-loaded');
  }, 5000);
}

// ============================================
// Lightbox — Full-screen image viewer
// ============================================

function initLightbox() {
  const triggers = document.querySelectorAll('[data-lightbox]');
  if (!triggers.length) return;

  // Group images by gallery name
  const galleries = {};
  triggers.forEach((el) => {
    const name = el.dataset.lightbox;
    if (!galleries[name]) galleries[name] = [];
    galleries[name].push(el.href || el.src);
  });

  // Create lightbox DOM
  const lightbox = document.createElement('div');
  lightbox.className = 'lw-lightbox';
  lightbox.innerHTML = `
    <button class="lw-lightbox-close" aria-label="Fermer">&times;</button>
    <button class="lw-lightbox-nav lw-lightbox-prev" aria-label="Précédent">&#8249;</button>
    <button class="lw-lightbox-nav lw-lightbox-next" aria-label="Suivant">&#8250;</button>
    <img src="" alt="" />
    <div class="lw-lightbox-counter"></div>
  `;
  document.body.appendChild(lightbox);

  const img = lightbox.querySelector('img');
  const counter = lightbox.querySelector('.lw-lightbox-counter');
  let currentGallery = [];
  let currentIndex = 0;

  function open(galleryName, index) {
    currentGallery = galleries[galleryName];
    currentIndex = index;
    show();
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    lightbox.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  function show() {
    img.src = currentGallery[currentIndex];
    counter.textContent = `${currentIndex + 1} / ${currentGallery.length}`;
  }

  function prev() {
    currentIndex = (currentIndex - 1 + currentGallery.length) % currentGallery.length;
    show();
  }

  function next() {
    currentIndex = (currentIndex + 1) % currentGallery.length;
    show();
  }

  // Event listeners
  triggers.forEach((el, i) => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      const name = el.dataset.lightbox;
      const indexInGallery = galleries[name].indexOf(el.href || el.src);
      open(name, indexInGallery >= 0 ? indexInGallery : 0);
    });
  });

  lightbox.querySelector('.lw-lightbox-close').addEventListener('click', close);
  lightbox.querySelector('.lw-lightbox-prev').addEventListener('click', prev);
  lightbox.querySelector('.lw-lightbox-next').addEventListener('click', next);
  lightbox.addEventListener('click', (e) => { if (e.target === lightbox) close(); });

  document.addEventListener('keydown', (e) => {
    if (!lightbox.classList.contains('is-open')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') prev();
    if (e.key === 'ArrowRight') next();
  });
}

// ============================================
// Testimonial Carousel
// ============================================

function initTestimonialCarousel() {
  const carousel = document.querySelector('.lw-testimonials');
  if (!carousel) return;

  const track = carousel.querySelector('.lw-testimonials-track');
  const slides = carousel.querySelectorAll('.lw-testimonial-slide');
  const dots = carousel.querySelectorAll('.lw-carousel-dot');
  if (!slides.length) return;

  let current = 0;
  let autoplayTimer;

  function goTo(index) {
    current = index;
    track.style.transform = `translateX(-${current * 100}%)`;
    dots.forEach((d, i) => d.classList.toggle('is-active', i === current));
  }

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      goTo(i);
      resetAutoplay();
    });
  });

  function autoplay() {
    autoplayTimer = setInterval(() => {
      goTo((current + 1) % slides.length);
    }, 6000);
  }

  function resetAutoplay() {
    clearInterval(autoplayTimer);
    autoplay();
  }

  // Swipe support
  let startX = 0;
  track.addEventListener('touchstart', (e) => { startX = e.touches[0].clientX; }, { passive: true });
  track.addEventListener('touchend', (e) => {
    const diff = startX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) {
      diff > 0 ? goTo(Math.min(current + 1, slides.length - 1)) : goTo(Math.max(current - 1, 0));
      resetAutoplay();
    }
  }, { passive: true });

  goTo(0);
  autoplay();
}

// ============================================
// Back to Top
// ============================================

function initBackToTop() {
  const btn = document.createElement('button');
  btn.className = 'lw-back-to-top';
  btn.setAttribute('aria-label', 'Retour en haut');
  btn.innerHTML = `<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>`;
  document.body.appendChild(btn);

  window.addEventListener('scroll', () => {
    btn.classList.toggle('is-visible', window.scrollY > 400);
  }, { passive: true });

  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

// ============================================
// Skip to Content — Accessibility
// ============================================

function initSkipToContent() {
  const main = document.querySelector('main');
  if (!main) return;
  main.id = 'main-content';

  const link = document.createElement('a');
  link.href = '#main-content';
  link.className = 'skip-to-content';
  link.textContent = 'Aller au contenu principal';
  document.body.prepend(link);
}

// ============================================
// Smooth Scroll — Lenis
// ============================================

function initSmoothScroll() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    smoothWheel: true,
    touchMultiplier: 1.5,
  });

  // Connect Lenis to GSAP ScrollTrigger
  lenis.on('scroll', ScrollTrigger.update);

  gsap.ticker.add((time) => {
    lenis.raf(time * 1000);
  });
  gsap.ticker.lagSmoothing(0);

  // Expose for anchor clicks
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', (e) => {
      const target = document.querySelector(anchor.getAttribute('href'));
      if (target) {
        e.preventDefault();
        lenis.scrollTo(target, { offset: -80 });
      }
    });
  });
}

// ============================================
// Cookie Consent — RGPD
// ============================================

function initCookieConsent() {
  if (localStorage.getItem('lw-cookies') !== null) return;

  const banner = document.createElement('div');
  banner.className = 'lw-cookie-banner';
  banner.innerHTML = `
    <div class="lw-cookie-inner">
      <div class="lw-cookie-text">
        <p>Nous utilisons des cookies pour améliorer votre expérience et analyser le trafic du site.
        En continuant, vous acceptez notre <a href="/politique-de-confidentialite/">politique de confidentialité</a>.</p>
      </div>
      <div class="lw-cookie-actions">
        <button class="lw-cookie-accept">Accepter</button>
        <button class="lw-cookie-refuse">Refuser</button>
      </div>
    </div>
  `;
  document.body.appendChild(banner);

  // Show after short delay
  setTimeout(() => banner.classList.add('is-visible'), 1500);

  const close = (accepted) => {
    localStorage.setItem('lw-cookies', accepted ? 'accepted' : 'refused');
    banner.classList.remove('is-visible');

    if (accepted && typeof gtag === 'function') {
      gtag('consent', 'update', {
        analytics_storage: 'granted',
        ad_storage: 'denied',
      });
    }
  };

  banner.querySelector('.lw-cookie-accept').addEventListener('click', () => close(true));
  banner.querySelector('.lw-cookie-refuse').addEventListener('click', () => close(false));
}

// ============================================
// Init
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  initPreloader();
  initSmoothScroll();
  initSkipToContent();
  initScrollReveal();
  initStickyHeader();
  initCounters();
  initLazyImages();
  initHero();
  initParallax();
  initProgressBar();
  initMobileMenu();
  initCustomCursor();
  initLightbox();
  initTestimonialCarousel();
  initBackToTop();
  initCookieConsent();

  // Premium motion effects
  initImageReveal();
  initMagneticButtons();
  initCardTilt();
  initTextParallax();
  initLineReveal();
  initSmoothCounters();
});
