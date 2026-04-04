<?php
/**
 * Title: Programme Hero
 * Slug: loftwood/programme-hero
 * Categories: loftwood
 */

if (!is_singular('programmes')) return;

$info = get_field('pt_programme_info_product');
$terms = get_the_terms(get_the_ID(), 'programmes_categories');
?>

<section class="relative min-h-[60vh] flex items-end overflow-hidden" data-hero>
    <?php if (has_post_thumbnail()) : ?>
        <div class="absolute inset-0 z-0">
            <img
                src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'hero-banner')); ?>"
                alt="<?php echo esc_attr(get_the_title()); ?>"
                class="hero-image w-full h-full object-cover"
                loading="eager"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
        </div>
    <?php endif; ?>

    <div class="relative z-10 w-full max-w-[1200px] mx-auto px-6 py-16">
        <?php if (!empty($terms) && !is_wp_error($terms)) : ?>
            <div class="flex flex-wrap gap-2 mb-4" data-hero-subtitle>
                <?php foreach ($terms as $term) :
                    $color = get_field('taxonomy_color', $term->taxonomy . '_' . $term->term_id) ?: '#b9a380';
                ?>
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-white rounded-full"
                          style="background-color: <?php echo esc_attr($color); ?>">
                        <?php echo esc_html($term->name); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h1 class="font-montserrat text-4xl md:text-5xl font-bold text-white mb-4" data-hero-headline>
            <?php the_title(); ?>
        </h1>

        <?php if (!empty($info['loc'])) : ?>
            <p class="text-white/80 text-lg flex items-center gap-2 mb-6" data-hero-subtitle>
                <svg class="w-5 h-5 text-bronze-light" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <?php echo esc_html($info['loc']); ?>
            </p>
        <?php endif; ?>

        <div class="flex flex-wrap gap-6" data-hero-cta>
            <?php if (!empty($info['price'])) : ?>
                <div class="bg-white/10 backdrop-blur-sm px-6 py-3 rounded-lg">
                    <span class="text-white/60 text-sm block">À partir de</span>
                    <span class="text-white text-2xl font-bold"><?php echo esc_html($info['price']); ?> €</span>
                </div>
            <?php endif; ?>

            <a href="#contact-programme" class="btn-loftwood inline-flex items-center px-8 py-4 bg-eco-green text-white font-semibold rounded-md hover:bg-eco-green-light transition-all hover:-translate-y-0.5 hover:shadow-lg self-center">
                Demander une documentation
            </a>
        </div>
    </div>
</section>
