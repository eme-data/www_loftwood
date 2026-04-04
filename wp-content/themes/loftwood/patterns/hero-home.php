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

<section class="relative flex items-center overflow-hidden" style="min-height:100vh;margin-top:-70px;" data-hero>
    <?php if ($bg_image) : ?>
        <div class="absolute inset-0 z-0">
            <img
                src="<?php echo esc_url($bg_image); ?>"
                alt=""
                class="hero-image w-full h-full object-cover"
                loading="eager"
            />
            <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent"></div>
        </div>
    <?php endif; ?>

    <div class="relative z-10 w-full max-w-[1200px] mx-auto px-6 py-20">
        <div class="max-w-2xl">
            <h1 class="font-montserrat text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                <span data-hero-headline>L'innovation immobilière</span><br>
                <span data-hero-headline>en <em class="text-bronze-light not-italic">ossature bois</em></span>
            </h1>

            <?php if ($content) : ?>
                <div class="text-white/90 text-lg md:text-xl leading-relaxed mb-8 max-w-xl" data-hero-subtitle>
                    <?php echo wp_kses_post($content); ?>
                </div>
            <?php else : ?>
                <p class="text-white/90 text-lg md:text-xl leading-relaxed mb-8 max-w-xl" data-hero-subtitle>
                    Des logements durables, performants et confortables.
                    Construits avec passion depuis 2013.
                </p>
            <?php endif; ?>

            <div class="flex flex-wrap gap-4" data-hero-cta>
                <a href="/programmes/" class="btn-loftwood inline-flex items-center px-8 py-4 bg-bronze text-white font-semibold rounded transition-colors hover:bg-bronze-dark">
                    Découvrir nos programmes
                    <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>

                <?php if (!empty($btn['text'])) : ?>
                    <a href="<?php echo esc_url($btn['link']); ?>" class="inline-flex items-center px-8 py-4 border-2 border-white/30 text-white font-semibold rounded hover:border-white/60 transition-colors">
                        <?php echo esc_html($btn['text']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce">
        <svg class="w-6 h-10 text-white/60" fill="none" viewBox="0 0 24 40" stroke="currentColor" stroke-width="2">
            <rect x="1" y="1" width="22" height="38" rx="11" />
            <circle cx="12" cy="12" r="3" fill="currentColor" class="animate-pulse" />
        </svg>
    </div>
</section>
