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

<header class="header-loftwood fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="flex items-center justify-between h-20">

            <!-- Logo -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="shrink-0">
                <?php if ($logo) : ?>
                    <img src="<?php echo esc_url($logo['url']); ?>"
                         alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                         class="h-10 w-auto" />
                <?php else : ?>
                    <span class="font-montserrat text-xl font-bold text-foreground">
                        Loft<strong>wood</strong>
                    </span>
                <?php endif; ?>
            </a>

            <!-- Navigation desktop -->
            <nav class="hidden lg:block">
                <?php wp_nav_menu([
                    'theme_location' => 'main_menu',
                    'container'      => false,
                    'menu_class'     => 'flex items-center gap-8 list-none m-0 p-0',
                    'link_before'    => '<span class="link-loftwood text-sm font-medium text-slate hover:text-deep-purple transition-colors">',
                    'link_after'     => '</span>',
                    'depth'          => 2,
                    'fallback_cb'    => false,
                ]); ?>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <a href="tel:<?php echo esc_attr($phone_clean); ?>"
                   class="hidden md:flex items-center gap-2 text-sm text-slate hover:text-bronze transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <?php echo esc_html($phone); ?>
                </a>

                <a href="<?php echo esc_url(home_url('/contact/')); ?>"
                   class="btn-loftwood hidden sm:inline-flex items-center px-5 py-2.5 bg-bronze text-white text-sm font-semibold rounded hover:bg-bronze-dark transition-colors">
                    Nous contacter
                </a>

                <!-- Hamburger mobile -->
                <button type="button"
                        class="lg:hidden flex flex-col gap-1.5 p-2"
                        id="mobile-menu-toggle"
                        aria-label="Menu">
                    <span class="block w-6 h-0.5 bg-foreground transition-transform"></span>
                    <span class="block w-6 h-0.5 bg-foreground transition-opacity"></span>
                    <span class="block w-6 h-0.5 bg-foreground transition-transform"></span>
                </button>
            </div>

        </div>
    </div>

    <!-- Navigation mobile -->
    <div id="mobile-menu" class="lg:hidden hidden bg-white border-t border-gray-100">
        <div class="px-6 py-4">
            <?php wp_nav_menu([
                'theme_location' => 'main_menu',
                'container'      => false,
                'menu_class'     => 'flex flex-col gap-3 list-none m-0 p-0',
                'link_before'    => '<span class="text-base text-foreground font-medium">',
                'link_after'     => '</span>',
                'depth'          => 1,
                'fallback_cb'    => false,
            ]); ?>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="tel:<?php echo esc_attr($phone_clean); ?>"
                   class="flex items-center gap-2 text-sm text-slate mb-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <?php echo esc_html($phone); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>"
                   class="block w-full text-center px-5 py-3 bg-bronze text-white font-semibold rounded">
                    Nous contacter
                </a>
            </div>
        </div>
    </div>
</header>
