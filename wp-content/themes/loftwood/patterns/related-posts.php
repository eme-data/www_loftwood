<?php
/**
 * Title: Articles similaires
 * Slug: loftwood/related-posts
 * Categories: loftwood
 */

if (!is_singular('post')) return;

$related = new WP_Query([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'post__not_in'   => [get_the_ID()],
    'orderby'        => 'rand',
]);

if (!$related->have_posts()) return;
?>

<div class="mt-16">
    <h2 class="font-montserrat text-2xl font-semibold text-foreground mb-8" data-reveal="up">
        Articles similaires
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-reveal-stagger>
        <?php while ($related->have_posts()) : $related->the_post(); ?>
            <article class="card-loftwood bg-white rounded-xl overflow-hidden shadow-sm group" data-reveal="up">
                <div class="card-image aspect-video">
                    <?php if (has_post_thumbnail()) : ?>
                        <img
                            src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'blog-card')); ?>"
                            alt="<?php echo esc_attr(get_the_title()); ?>"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        />
                    <?php endif; ?>
                </div>
                <div class="p-5">
                    <time class="text-xs text-slate" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo esc_html(get_the_date('j M Y')); ?>
                    </time>
                    <h3 class="font-montserrat text-base font-semibold mt-1">
                        <a href="<?php the_permalink(); ?>" class="link-loftwood text-foreground hover:text-eco-green">
                            <?php the_title(); ?>
                        </a>
                    </h3>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</div>
<?php wp_reset_postdata(); ?>
