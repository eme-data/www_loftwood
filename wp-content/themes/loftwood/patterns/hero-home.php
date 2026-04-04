<?php
/**
 * Title: Hero Homepage
 * Slug: loftwood/hero-home
 * Categories: loftwood
 */

$banner_slider = get_field('h_banner_slider', get_option('page_on_front'));
$first_slide = !empty($banner_slider) ? $banner_slider[0] : null;
$bg_image = $first_slide ? $first_slide['image']['url'] : '';
$content = $first_slide ? ($first_slide['content'] ?? '') : '';
$btn = $first_slide ? ($first_slide['btn_txt'] ?? []) : [];
?>

<section class="lw-hero" data-hero>
    <?php if ($bg_image) : ?>
        <div class="lw-hero-bg">
            <img src="<?php echo esc_url($bg_image); ?>" alt="" class="lw-hero-image" loading="eager" />
            <div class="lw-hero-overlay"></div>
        </div>
    <?php endif; ?>

    <div class="lw-hero-content">
        <div class="lw-hero-text">
            <h1 class="lw-hero-title">
                <span class="lw-hero-line" data-hero-headline>L'innovation immobilière</span>
                <span class="lw-hero-line" data-hero-headline>en <em>ossature bois</em></span>
            </h1>

            <?php if ($content) : ?>
                <div class="lw-hero-subtitle" data-hero-subtitle>
                    <?php echo wp_kses_post($content); ?>
                </div>
            <?php else : ?>
                <p class="lw-hero-subtitle" data-hero-subtitle>
                    Des logements durables, performants et confortables.
                    Construits avec passion depuis 2013.
                </p>
            <?php endif; ?>

            <div class="lw-hero-buttons" data-hero-cta>
                <a href="/nos-programmes-neufs/" class="lw-btn-primary">
                    Découvrir nos programmes
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>

                <?php if (!empty($btn['text'])) : ?>
                    <a href="<?php echo esc_url(get_permalink($btn['link'])); ?>" class="lw-btn-outline">
                        <?php echo esc_html($btn['text']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="lw-scroll-indicator" data-hero-cta>
        <div class="lw-scroll-mouse">
            <div class="lw-scroll-dot"></div>
        </div>
    </div>
</section>
