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

<header class="header-loftwood" id="site-header">
    <div class="lw-header-inner">

        <!-- Logo -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="lw-logo">
            <?php if ($logo) : ?>
                <img src="<?php echo esc_url($logo['url']); ?>"
                     alt="<?php echo esc_attr(get_bloginfo('name')); ?>" />
            <?php else : ?>
                <span>Loft<strong>wood</strong></span>
            <?php endif; ?>
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

            <a href="<?php echo esc_url(home_url('/contactez-nous/')); ?>" class="lw-header-cta">
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
