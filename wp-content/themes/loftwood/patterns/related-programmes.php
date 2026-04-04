<?php
/**
 * Title: Programmes similaires
 * Slug: loftwood/related-programmes
 * Categories: loftwood
 */

if (!is_singular('programmes')) return;

$related = new WP_Query([
    'post_type'      => 'programmes',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'post__not_in'   => [get_the_ID()],
    'orderby'        => 'rand',
]);

if (!$related->have_posts()) return;
?>

<section class="bg-cream" style="padding-top: var(--section-gap); padding-bottom: var(--section-gap);">
    <div class="max-w-[1200px] mx-auto px-6">
        <h2 class="font-montserrat text-3xl font-semibold text-foreground text-center mb-12" data-reveal="up">
            Découvrez aussi
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-reveal-stagger>
            <?php while ($related->have_posts()) : $related->the_post();
                $info = get_field('pt_programme_info_product', get_the_ID());
                $terms = get_the_terms(get_the_ID(), 'programmes_categories');
            ?>
                <article class="card-loftwood bg-white rounded-xl overflow-hidden shadow-sm group" data-reveal="up">
                    <div class="card-image aspect-[4/3] relative">
                        <?php if (has_post_thumbnail()) : ?>
                            <img
                                src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'programme-card')); ?>"
                                alt="<?php echo esc_attr(get_the_title()); ?>"
                                class="w-full h-full object-cover"
                                loading="lazy"
                            />
                        <?php endif; ?>

                        <?php if (!empty($terms) && !is_wp_error($terms)) : ?>
                            <div class="absolute top-4 left-4">
                                <?php $term = $terms[0];
                                $color = get_field('taxonomy_color', $term->taxonomy . '_' . $term->term_id) ?: '#b9a380';
                                ?>
                                <span class="inline-block px-3 py-1 text-xs font-semibold text-white rounded-full"
                                      style="background-color: <?php echo esc_attr($color); ?>">
                                    <?php echo esc_html($term->name); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-6">
                        <h3 class="font-montserrat text-lg font-semibold mb-2">
                            <a href="<?php the_permalink(); ?>" class="link-loftwood text-foreground hover:text-eco-green">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <?php if (!empty($info['loc'])) : ?>
                            <p class="text-slate text-sm flex items-center gap-1">
                                <svg class="w-4 h-4 text-bronze" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <?php echo esc_html($info['loc']); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php wp_reset_postdata(); ?>
