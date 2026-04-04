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

<header class="header-loftwood fixed top-0 left-0 right-0 z-50 bg-white shadow-sm" style="background:rgba(255,255,255,0.97);">
    <div style="max-width:1400px;margin:0 auto;padding:0 1.5rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;height:70px;">

            <!-- Logo -->
            <a href="<?php echo esc_url(home_url('/')); ?>" style="flex-shrink:0;">
                <?php if ($logo) : ?>
                    <img src="<?php echo esc_url($logo['url']); ?>"
                         alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                         style="height:36px;width:auto;" />
                <?php else : ?>
                    <span style="font-family:Montserrat,sans-serif;font-size:1.25rem;font-weight:700;color:#1a1a1a;">
                        Loft<strong>wood</strong>
                    </span>
                <?php endif; ?>
            </a>

            <!-- Navigation desktop -->
            <nav class="lw-nav-desktop">
                <?php wp_nav_menu([
                    'theme_location' => 'main_menu',
                    'container'      => false,
                    'depth'          => 2,
                    'fallback_cb'    => false,
                ]); ?>
            </nav>

            <!-- Actions -->
            <div style="display:flex;align-items:center;gap:1rem;flex-shrink:0;">
                <a href="tel:<?php echo esc_attr($phone_clean); ?>"
                   class="lw-header-phone">
                    <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <?php echo esc_html($phone); ?>
                </a>

                <a href="<?php echo esc_url(home_url('/contactez-nous/')); ?>"
                   style="display:inline-flex;align-items:center;padding:0.5rem 1.25rem;background:#b9a380;color:white;font-size:0.8125rem;font-weight:600;border-radius:4px;text-decoration:none;white-space:nowrap;transition:background 200ms ease;"
                   onmouseover="this.style.background='#9a7f6d'" onmouseout="this.style.background='#b9a380'">
                    Nous contacter
                </a>

                <!-- Hamburger mobile -->
                <button type="button"
                        id="mobile-menu-toggle"
                        class="lw-hamburger"
                        aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

        </div>
    </div>

    <!-- Navigation mobile -->
    <div id="mobile-menu" class="lw-mobile-menu" style="display:none;">
        <div style="padding:1rem 1.5rem;">
            <?php wp_nav_menu([
                'theme_location' => 'main_menu',
                'container'      => false,
                'depth'          => 2,
                'fallback_cb'    => false,
            ]); ?>

            <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #eee;">
                <a href="tel:<?php echo esc_attr($phone_clean); ?>"
                   style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;color:#535D6A;margin-bottom:0.75rem;text-decoration:none;">
                    <?php echo esc_html($phone); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/contactez-nous/')); ?>"
                   style="display:block;text-align:center;padding:0.75rem;background:#b9a380;color:white;font-weight:600;border-radius:4px;text-decoration:none;">
                    Nous contacter
                </a>
            </div>
        </div>
    </div>
</header>
<!-- Spacer to prevent content from going under fixed header -->
<div style="height:70px;"></div>
