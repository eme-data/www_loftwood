<?php
/**
 * Title: Header
 * Slug: loftwood/header
 * Categories: loftwood
 */

$logo = get_field('op_logo', 'option');
$phone = get_field('op_phone', 'option') ?: '05 61 35 20 34';
$phone_clean = preg_replace('/\s+/', '', $phone);
?>

<!-- Preloader -->
<div class="lw-preloader" aria-hidden="true">
    <div class="lw-preloader-content">
        <!-- 5 pastilles animées -->
        <div class="lw-preloader-pastilles">
            <div class="lw-pastille" style="--delay: 0">
                <svg viewBox="0 0 60 60"><circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M30 42c-8 0-14-6-14-14h2c0 6.6 5.4 12 12 12s12-5.4 12-12h2c0 8-6 14-14 14z" fill="currentColor" opacity="0.6"/><path d="M24 28c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
            </div>
            <div class="lw-pastille" style="--delay: 1">
                <svg viewBox="0 0 60 60"><circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M15 38h30M18 34l6-10 6 6 6-10 6 14" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div class="lw-pastille" style="--delay: 2">
                <svg viewBox="0 0 60 60"><circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M20 35c3-4 7-6 10-6s7 2 10 6M18 38c4-5 8-7 12-7s8 2 12 7" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/><circle cx="30" cy="24" r="3" fill="currentColor" opacity="0.5"/></svg>
            </div>
            <div class="lw-pastille" style="--delay: 3">
                <svg viewBox="0 0 60 60"><circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="1.5" fill="none"/><rect x="22" y="25" width="16" height="12" rx="1" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M22 28h16M26 25v-3h8v3" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
            </div>
            <div class="lw-pastille" style="--delay: 4">
                <svg viewBox="0 0 60 60"><circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M20 36l5-8 5 4 5-6 5 10" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/><path d="M18 38h24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            </div>
        </div>

        <!-- Logo complet qui apparaît après les pastilles -->
        <?php if (!empty($logo) && !empty($logo['url'])) : ?>
            <div class="lw-preloader-logo-img">
                <img src="<?php echo esc_url($logo['url']); ?>" alt="Loftwood" />
            </div>
        <?php else : ?>
            <div class="lw-preloader-logo-img">
                <div class="lw-preloader-text">
                    <span class="lw-preloader-wordmark">LOFT<strong>WOOD</strong></span>
                    <span class="lw-preloader-subtitle">PROMOTION IMMOBILIÈRE</span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Ligne de progression -->
        <div class="lw-preloader-progress">
            <div class="lw-preloader-progress-bar"></div>
        </div>
    </div>
</div>

<header class="header-loftwood" id="site-header">
    <div class="lw-header-inner">

        <!-- Logo -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="lw-logo">
            <?php if (!empty($logo) && !empty($logo['url'])) : ?>
                <img src="<?php echo esc_url($logo['url']); ?>"
                     alt="<?php echo esc_attr(get_bloginfo('name')); ?>" />
            <?php endif; ?>
            <span class="lw-logo-text <?php echo (!empty($logo) && !empty($logo['url'])) ? 'sr-only' : ''; ?>">Loft<strong>wood</strong></span>
        </a>

        <!-- Navigation desktop — items principaux seulement -->
        <nav class="lw-nav-desktop" id="main-nav">
            <?php wp_nav_menu([
                'theme_location' => 'main_menu',
                'container'      => false,
                'depth'          => 2,
                'fallback_cb'    => false,
            ]); ?>
        </nav>

        <!-- Actions droite -->
        <div class="lw-header-actions">
            <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="lw-header-phone" title="Appelez-nous">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </a>

            <a href="<?php echo esc_url(home_url('/contactez-nous/')); ?>" class="lw-header-cta lw-cta-eco">
                Nous contacter
            </a>

            <!-- Hamburger -->
            <button type="button" id="mobile-menu-toggle" class="lw-hamburger" aria-label="Menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>

    </div>

    <!-- Navigation mobile -->
    <div id="mobile-menu" class="lw-mobile-menu">
        <?php wp_nav_menu([
            'theme_location' => 'main_menu',
            'container'      => false,
            'depth'          => 2,
            'fallback_cb'    => false,
        ]); ?>
        <div class="lw-mobile-actions">
            <a href="tel:<?php echo esc_attr($phone_clean); ?>"><?php echo esc_html($phone); ?></a>
            <a href="<?php echo esc_url(home_url('/contactez-nous/')); ?>" class="lw-mobile-cta">Nous contacter</a>
        </div>
    </div>
</header>
<div class="lw-header-spacer"></div>
