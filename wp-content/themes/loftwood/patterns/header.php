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
        <!-- Phase 1 : 5 pastilles du vrai logo, apparaissent une par une -->
        <div class="lw-preloader-pastilles">
            <!-- 1. Pin parasol / Arbre -->
            <div class="lw-pastille" style="--i:0">
                <svg viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="1.2"/>
                    <line x1="30" y1="44" x2="30" y2="30" stroke="currentColor" stroke-width="1.2"/>
                    <path d="M30 18c-4 0-11 5-11 12 0 4 3 5 5 5h12c2 0 5-1 5-5 0-7-7-12-11-12z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                    <path d="M22 44h16" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
            </div>
            <!-- 2. Collines / Paysage vallonné -->
            <div class="lw-pastille" style="--i:1">
                <svg viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="1.2"/>
                    <path d="M10 40c4-3 7-10 12-10s6 4 8 4 4-6 10-6 8 5 12 12" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 40h42" stroke="currentColor" stroke-width="1.2"/>
                    <circle cx="42" cy="20" r="3" stroke="currentColor" stroke-width="1.2"/>
                </svg>
            </div>
            <!-- 3. Lac / Eau avec reflet -->
            <div class="lw-pastille" style="--i:2">
                <svg viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="1.2"/>
                    <path d="M14 30c3-5 7-12 16-12s13 7 16 12" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M14 30h32" stroke="currentColor" stroke-width="1.2"/>
                    <path d="M18 34h6M20 37h8M22 40h10" stroke="currentColor" stroke-width="1" stroke-linecap="round" opacity="0.5"/>
                    <line x1="30" y1="18" x2="30" y2="24" stroke="currentColor" stroke-width="1.2"/>
                    <path d="M27 22c0-3 3-5 3-5s3 2 3 5" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                </svg>
            </div>
            <!-- 4. Maison / Habitat bois -->
            <div class="lw-pastille" style="--i:3">
                <svg viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="1.2"/>
                    <path d="M18 32l12-10 12 10" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    <rect x="22" y="32" width="16" height="11" stroke="currentColor" stroke-width="1.2"/>
                    <rect x="27" y="36" width="6" height="7" stroke="currentColor" stroke-width="1.2"/>
                    <path d="M18 43h24" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
            </div>
            <!-- 5. Terrain / Parcelle avec végétation -->
            <div class="lw-pastille" style="--i:4">
                <svg viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="1.2"/>
                    <path d="M12 38l8-6 6 3 8-8 6 4 8-5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 42h36" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M20 34v-4M24 31v-3M36 28v-4" stroke="currentColor" stroke-width="1" stroke-linecap="round" opacity="0.6"/>
                    <circle cx="20" cy="28" r="2" stroke="currentColor" stroke-width="1" opacity="0.6"/>
                    <circle cx="36" cy="22" r="2.5" stroke="currentColor" stroke-width="1" opacity="0.6"/>
                </svg>
            </div>
        </div>

        <!-- Phase 2 : Logo complet qui apparaît (crossfade) -->
        <div class="lw-preloader-logo-full">
            <?php if (!empty($logo) && !empty($logo['url'])) : ?>
                <img src="<?php echo esc_url($logo['url']); ?>" alt="Loftwood" />
            <?php else : ?>
                <img src="<?php echo esc_url(LOFTWOOD_URI . '/assets/images/logo_loftwood.svg'); ?>" alt="Loftwood" />
            <?php endif; ?>
        </div>

        <!-- Phase 3 : Barre de progression -->
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
