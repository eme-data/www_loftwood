<?php
/**
 * Title: Programme Gallery
 * Slug: loftwood/programme-gallery
 * Categories: loftwood
 */

if (!is_singular('programmes')) return;

$gallery = get_field('pt_gallery');
if (empty($gallery)) return;
?>

<section class="bg-cream" style="padding-top: var(--section-gap); padding-bottom: var(--section-gap);">
    <div class="max-w-[1200px] mx-auto px-6">

        <h2 class="section-title-serif text-4xl lg:text-5xl text-foreground text-center mb-12" data-reveal="up">
            Galerie
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4" data-reveal-stagger>
            <?php foreach ($gallery as $i => $image) : ?>
                <a href="<?php echo esc_url($image['url']); ?>"
                   class="block rounded-xl overflow-hidden <?php echo $i === 0 ? 'col-span-2 row-span-2' : ''; ?> group"
                   data-reveal="scale"
                   data-lightbox="programme-gallery">
                    <div class="card-image aspect-square w-full h-full">
                        <img
                            src="<?php echo esc_url($image['sizes']['programme-gallery'] ?? $image['url']); ?>"
                            alt="<?php echo esc_attr($image['alt'] ?? get_the_title()); ?>"
                            class="w-full h-full object-cover transition-transform duration-500 ease-loftwood group-hover:scale-105"
                            loading="lazy"
                        />
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
