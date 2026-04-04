<?php
/**
 * Title: Section Vente Terrain
 * Slug: loftwood/section-land-sale
 * Categories: loftwood-sections
 */

$front_id = get_option('page_on_front');
$sale_image = get_field('h_sale_image', $front_id);
$sale_content = get_field('h_sale_content', $front_id);
?>

<section class="relative overflow-hidden" style="padding-top: var(--section-gap); padding-bottom: var(--section-gap);">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            <!-- Content -->
            <div data-reveal="left">
                <?php if (!empty($sale_content['image']['url'])) : ?>
                    <div class="w-16 h-16 mb-6">
                        <img src="<?php echo esc_url($sale_content['image']['url']); ?>" alt="" class="w-full h-full object-contain" />
                    </div>
                <?php endif; ?>

                <?php if (!empty($sale_content['title'])) : ?>
                    <h2 class="font-montserrat text-3xl lg:text-4xl font-semibold text-foreground mb-6">
                        <?php echo wp_kses_post($sale_content['title']); ?>
                    </h2>
                <?php else : ?>
                    <h2 class="font-montserrat text-3xl lg:text-4xl font-semibold text-foreground mb-6">
                        Vous êtes propriétaire d'un terrain ?
                    </h2>
                <?php endif; ?>

                <?php if (!empty($sale_content['sub_title'])) : ?>
                    <p class="text-slate text-lg leading-relaxed mb-6">
                        <?php echo wp_kses_post($sale_content['sub_title']); ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($sale_content['content'])) : ?>
                    <div class="text-slate leading-relaxed mb-8 prose">
                        <?php echo wp_kses_post($sale_content['content']); ?>
                    </div>
                <?php endif; ?>

                <a href="/contact/" class="btn-loftwood inline-flex items-center px-8 py-4 bg-bronze text-white font-semibold rounded hover:bg-bronze-dark transition-colors">
                    <?php echo esc_html(!empty($sale_content['button']['text']) ? $sale_content['button']['text'] : 'Proposer votre terrain'); ?>
                </a>
            </div>

            <!-- Image -->
            <?php if ($sale_image) : ?>
                <div class="relative rounded-2xl overflow-hidden aspect-[4/3]" data-reveal="right">
                    <img
                        src="<?php echo esc_url($sale_image['url']); ?>"
                        alt="<?php echo esc_attr($sale_image['alt'] ?? 'Vente de terrain'); ?>"
                        class="w-full h-full object-cover"
                        loading="lazy"
                        data-parallax="0.1"
                    />
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
