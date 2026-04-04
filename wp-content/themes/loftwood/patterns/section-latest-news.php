<?php
/**
 * Title: Section Dernières Actualités
 * Slug: loftwood/section-latest-news
 * Categories: loftwood-sections
 */

$front_id = get_option('page_on_front');
$news_title = get_field('h_latesr_new_title', $front_id) ?: 'Actualités';
$news_sub = get_field('h_latesr_new_sub_title', $front_id) ?: '';
$news_btn = get_field('h_latesr_new_btn', $front_id);

$posts = new WP_Query([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
?>

<section class="bg-cream" style="padding-top: var(--section-gap); padding-bottom: var(--section-gap);">
    <div class="max-w-[1200px] mx-auto px-6">

        <div class="text-center mb-12 lg:mb-16" data-reveal="up">
            <h2 class="font-montserrat text-3xl lg:text-4xl font-semibold text-foreground">
                <?php echo esc_html($news_title); ?>
            </h2>
            <?php if ($news_sub) : ?>
                <p class="text-slate text-lg mt-4 max-w-2xl mx-auto">
                    <?php echo esc_html($news_sub); ?>
                </p>
            <?php endif; ?>
        </div>

        <?php if ($posts->have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-reveal-stagger>
                <?php while ($posts->have_posts()) : $posts->the_post(); ?>
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

                        <div class="p-6">
                            <div class="flex items-center gap-3 text-xs text-slate mb-3">
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date('j M Y')); ?>
                                </time>
                                <?php
                                $cats = get_the_category();
                                if (!empty($cats)) : ?>
                                    <span class="text-bronze font-medium"><?php echo esc_html($cats[0]->name); ?></span>
                                <?php endif; ?>
                            </div>

                            <h3 class="font-montserrat text-lg font-semibold mb-3">
                                <a href="<?php the_permalink(); ?>" class="link-loftwood text-foreground hover:text-deep-purple">
                                    <?php the_title(); ?>
                                </a>
                            </h3>

                            <p class="text-slate text-sm line-clamp-3">
                                <?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?>
                            </p>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php if (!empty($news_btn['text'])) : ?>
                <div class="text-center mt-12" data-reveal="up">
                    <a href="<?php echo esc_url($news_btn['link']); ?>"
                       class="btn-loftwood inline-flex items-center px-8 py-4 border-2 border-deep-purple text-deep-purple font-semibold rounded hover:bg-deep-purple hover:text-white transition-colors">
                        <?php echo esc_html($news_btn['text']); ?>
                    </a>
                </div>
            <?php endif; ?>
        <?php endif;
        wp_reset_postdata(); ?>

    </div>
</section>
