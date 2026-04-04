<?php
/**
 * Title: Programme Details
 * Slug: loftwood/programme-details
 * Categories: loftwood
 */

if (!is_singular('programmes')) return;

$info = get_field('pt_programme_info_product');
$residence = get_field('pt_residence');
$strengths = get_field('pt_strengths');
$apartments = get_field('pt_apartments');
?>

<section style="padding-top: var(--section-gap); padding-bottom: var(--section-gap);">
    <div class="max-w-[1200px] mx-auto px-6">

        <!-- Description -->
        <?php if (!empty($residence)) : ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 mb-20">
                <div data-reveal="left">
                    <?php if (!empty($residence['title'])) : ?>
                        <h2 class="font-montserrat text-3xl font-semibold text-foreground mb-6">
                            <?php echo wp_kses_post($residence['title']); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($residence['content'])) : ?>
                        <div class="text-slate leading-relaxed prose">
                            <?php echo wp_kses_post($residence['content']); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($residence['image'])) : ?>
                    <div class="rounded-2xl overflow-hidden" data-reveal="right">
                        <img
                            src="<?php echo esc_url($residence['image']['url']); ?>"
                            alt="<?php echo esc_attr($residence['image']['alt'] ?? get_the_title()); ?>"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        />
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Points forts -->
        <?php if (!empty($strengths)) : ?>
            <div class="mb-20">
                <h2 class="font-montserrat text-3xl font-semibold text-foreground text-center mb-12" data-reveal="up">
                    Les points forts
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" data-reveal-stagger>
                    <?php foreach ($strengths as $item) : ?>
                        <div class="flex items-start gap-4 p-6 rounded-xl bg-cream" data-reveal="up">
                            <svg class="w-6 h-6 text-bronze shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-foreground"><?php echo esc_html($item['text'] ?? $item); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Appartements / Lots -->
        <?php if (!empty($apartments)) : ?>
            <div class="mb-20" data-reveal="up">
                <h2 class="font-montserrat text-3xl font-semibold text-foreground text-center mb-12">
                    Les logements disponibles
                </h2>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-cream">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-foreground">Type</th>
                                    <th class="px-6 py-4 text-left font-semibold text-foreground">Surface</th>
                                    <th class="px-6 py-4 text-left font-semibold text-foreground">Prix</th>
                                    <th class="px-6 py-4 text-left font-semibold text-foreground">Disponibilité</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($apartments as $apt) : ?>
                                    <tr class="hover:bg-cream/50 transition-colors">
                                        <td class="px-6 py-4 font-medium"><?php echo esc_html($apt['type'] ?? ''); ?></td>
                                        <td class="px-6 py-4 text-slate"><?php echo esc_html($apt['surface'] ?? ''); ?></td>
                                        <td class="px-6 py-4 font-semibold text-eco-green"><?php echo esc_html($apt['price'] ?? ''); ?></td>
                                        <td class="px-6 py-4">
                                            <?php if (!empty($apt['available'])) : ?>
                                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disponible</span>
                                            <?php else : ?>
                                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">Réservé</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>
