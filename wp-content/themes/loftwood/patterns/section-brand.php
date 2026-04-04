<?php
/**
 * Title: Section Brand / La Marque
 * Slug: loftwood/section-brand
 * Categories: loftwood-sections
 */

$front_id = get_option('page_on_front');
$brand_img = get_field('h_brand_img', $front_id);
$brand_content = get_field('h_brand_content', $front_id);
$brand_list = get_field('h_brand_list', $front_id);
?>

<section class="relative overflow-hidden" style="padding-top: var(--section-gap); padding-bottom: var(--section-gap);" data-section-bg="#FBFAF8">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            <!-- Image -->
            <?php if ($brand_img) : ?>
                <div class="relative rounded-2xl overflow-hidden aspect-[4/5]" data-reveal="left">
                    <img
                        src="<?php echo esc_url($brand_img['url']); ?>"
                        alt="<?php echo esc_attr($brand_img['alt'] ?? 'Loftwood'); ?>"
                        class="w-full h-full object-cover"
                        loading="lazy"
                        data-parallax="0.1"
                    />
                </div>
            <?php endif; ?>

            <!-- Content -->
            <?php if ($brand_content) : ?>
                <div data-reveal="right">
                    <?php if (!empty($brand_content['title'])) : ?>
                        <h2 class="font-montserrat text-3xl lg:text-4xl font-semibold text-foreground mb-6">
                            <?php echo wp_kses_post($brand_content['title']); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($brand_content['sub_title'])) : ?>
                        <p class="text-slate text-lg leading-relaxed mb-8">
                            <?php echo wp_kses_post($brand_content['sub_title']); ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($brand_content['button']['text'])) : ?>
                        <a href="<?php echo esc_url($brand_content['button']['link']); ?>"
                           class="btn-loftwood inline-flex items-center gap-2 px-8 py-4 bg-eco-green text-white font-semibold rounded-md hover:bg-eco-green-light transition-all hover:-translate-y-0.5 hover:shadow-lg">
                            <?php echo esc_html($brand_content['button']['text']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>

        <!-- Brand pillars -->
        <?php if (!empty($brand_list)) : ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-20" data-reveal-stagger>
                <?php foreach ($brand_list as $item) : ?>
                    <div class="text-center p-8 rounded-xl bg-white shadow-sm" data-reveal="up">
                        <?php if (!empty($item['image']['url'])) : ?>
                            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-warm-white flex items-center justify-center">
                                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="" class="w-10 h-10 object-contain" />
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($item['name'])) : ?>
                            <h3 class="font-montserrat text-lg font-semibold mb-3">
                                <?php echo esc_html($item['name']); ?>
                            </h3>
                        <?php endif; ?>

                        <?php if (!empty($item['text'])) : ?>
                            <div class="text-slate text-sm leading-relaxed">
                                <?php echo wp_kses_post($item['text']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
