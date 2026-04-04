<?php
/**
 * Title: Programme Location / Map
 * Slug: loftwood/programme-location
 * Categories: loftwood
 */

if (!is_singular('programmes')) return;

$location = get_field('pt_location');
if (empty($location)) return;

$map = $location['map'] ?? null;
?>

<section style="padding-top: var(--section-gap); padding-bottom: var(--section-gap);">
    <div class="max-w-[1200px] mx-auto px-6">

        <h2 class="font-montserrat text-3xl font-semibold text-foreground text-center mb-12" data-reveal="up">
            Localisation
        </h2>

        <?php if (!empty($location['description'])) : ?>
            <div class="max-w-3xl mx-auto text-center text-slate leading-relaxed mb-12" data-reveal="up">
                <?php echo wp_kses_post($location['description']); ?>
            </div>
        <?php endif; ?>

        <?php if ($map) : ?>
            <div class="rounded-2xl overflow-hidden shadow-lg aspect-[16/9]" data-reveal="scale">
                <div class="acf-map w-full h-full" data-zoom="14">
                    <div class="marker"
                         data-lat="<?php echo esc_attr($map['lat']); ?>"
                         data-lng="<?php echo esc_attr($map['lng']); ?>">
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>
