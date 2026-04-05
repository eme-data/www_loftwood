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
        <!-- Le vrai logo SVG révélé pastille par pastille -->
        <div class="lw-preloader-reveal">
            <img src="<?php echo esc_url((!empty($logo) && !empty($logo['url'])) ? $logo['url'] : LOFTWOOD_URI . '/assets/images/logo_loftwood.svg'); ?>" alt="Loftwood" class="lw-preloader-logo-img" />
        </div>

        <!-- Barre de progression -->
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

        <!-- Actions droite -->
        <div class="lw-header-actions">
            <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="lw-header-phone" title="Appelez-nous">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </a>

            <a href="<?php echo esc_url(home_url('/contactez-nous/')); ?>" class="lw-header-cta">
                Nous contacter
            </a>

            <!-- Menu toggle -->
            <button type="button" id="menu-toggle" class="lw-menu-toggle" aria-label="Menu" aria-expanded="false">
                <span class="lw-menu-toggle-label">Menu</span>
                <span class="lw-menu-toggle-icon">
                    <span></span><span></span>
                </span>
            </button>
        </div>

    </div>

    <!-- Flyout menu (slide from right) -->
    <div id="fullscreen-menu" class="lw-fullscreen-menu">
        <div class="lw-fullscreen-menu-inner">
            <!-- Header: logo + close -->
            <div class="lw-flyout-header">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="lw-flyout-logo">
                    <?php if (!empty($logo) && !empty($logo['url'])) : ?>
                        <img src="<?php echo esc_url($logo['url']); ?>" alt="Loftwood" />
                    <?php else : ?>
                        <span>Loft<strong>wood</strong></span>
                    <?php endif; ?>
                </a>
                <button type="button" class="lw-flyout-close" id="menu-close" aria-label="Fermer">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="lw-fullscreen-nav">
                <?php wp_nav_menu([
                    'theme_location' => 'main_menu',
                    'container'      => false,
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ]); ?>
            </nav>

            <div class="lw-fullscreen-footer">
                <div class="lw-fullscreen-contact">
                    <a href="tel:<?php echo esc_attr($phone_clean); ?>"><?php echo esc_html($phone); ?></a>
                    <span class="lw-fullscreen-sep">·</span>
                    <a href="mailto:contact@loftwood.fr">contact@loftwood.fr</a>
                </div>
                <p class="lw-fullscreen-address">20 Rue de Novital — 31150 Gagnac-sur-Garonne</p>
            </div>
        </div>
    </div>
</header>
<div class="lw-header-spacer"></div>
