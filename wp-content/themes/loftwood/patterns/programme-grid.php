<?php
/**
 * Title: Grille Programmes
 * Slug: loftwood/programme-grid
 * Categories: loftwood
 */

$paged = get_query_var('paged') ? get_query_var('paged') : 1;

$programmes = new WP_Query([
    'post_type'      => 'programmes',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

// Taxonomy filter
$categories = get_terms([
    'taxonomy'   => 'programmes_categories',
    'hide_empty' => true,
]);
?>

<?php if (!empty($categories) && !is_wp_error($categories)) : ?>
    <div class="flex flex-wrap justify-center gap-3 mb-12" data-reveal="up">
        <a href="<?php echo esc_url(get_post_type_archive_link('programmes')); ?>"
           class="px-5 py-2 rounded-full text-sm font-medium border-2 border-bronze bg-bronze text-white transition-colors hover:bg-bronze-dark">
            Tous
        </a>
        <?php foreach ($categories as $cat) : ?>
            <a href="<?php echo esc_url(get_term_link($cat)); ?>"
               class="px-5 py-2 rounded-full text-sm font-medium border-2 border-slate/20 text-slate transition-colors hover:border-bronze hover:text-bronze">
                <?php echo esc_html($cat->name); ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($programmes->have_posts()) : ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-reveal-stagger>
        <?php while ($programmes->have_posts()) : $programmes->the_post();
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
                        <div class="absolute top-4 left-4 flex flex-wrap gap-1">
                            <?php foreach ($terms as $term) :
                                $color = get_field('taxonomy_color', $term->taxonomy . '_' . $term->term_id) ?: '#b9a380';
                            ?>
                                <span class="inline-block px-3 py-1 text-xs font-semibold text-white rounded-full"
                                      style="background-color: <?php echo esc_attr($color); ?>">
                                    <?php echo esc_html($term->name); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="p-6">
                    <h3 class="font-montserrat text-xl font-semibold mb-2">
                        <a href="<?php the_permalink(); ?>" class="link-loftwood text-foreground hover:text-deep-purple">
                            <?php the_title(); ?>
                        </a>
                    </h3>

                    <?php if (!empty($info['loc'])) : ?>
                        <p class="text-slate text-sm flex items-center gap-1 mb-3">
                            <svg class="w-4 h-4 text-bronze shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <?php echo esc_html($info['loc']); ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($info['short_excerpt'])) : ?>
                        <p class="text-slate text-sm mb-4"><?php echo esc_html($info['short_excerpt']); ?></p>
                    <?php endif; ?>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <?php if (!empty($info['price'])) : ?>
                            <span class="text-sm">
                                <span class="text-slate">À partir de</span>
                                <span class="font-semibold text-deep-purple"><?php echo esc_html($info['price']); ?> €</span>
                            </span>
                        <?php endif; ?>

                        <a href="<?php the_permalink(); ?>" class="text-sm font-semibold text-bronze hover:text-bronze-dark inline-flex items-center gap-1 transition-colors">
                            Découvrir
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>

    <?php if ($programmes->max_num_pages > 1) : ?>
        <div class="flex justify-center mt-12 gap-2" data-reveal="up">
            <?php
            echo paginate_links([
                'total'     => $programmes->max_num_pages,
                'current'   => $paged,
                'prev_text' => '&larr;',
                'next_text' => '&rarr;',
                'type'      => 'list',
            ]);
            ?>
        </div>
    <?php endif; ?>

<?php else : ?>
    <div class="text-center py-20">
        <p class="text-slate text-lg">Aucun programme disponible pour le moment.</p>
    </div>
<?php endif;
wp_reset_postdata(); ?>
