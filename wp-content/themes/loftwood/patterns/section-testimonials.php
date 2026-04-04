<?php
/**
 * Title: Section Témoignages
 * Slug: loftwood/section-testimonials
 * Categories: loftwood-sections
 */

$front_id = get_option('page_on_front');
$testimonials = get_field('h_testimonials', $front_id);

// Fallback testimonials if ACF field is empty
if (empty($testimonials)) {
    $testimonials = [
        [
            'quote'  => 'La qualité de construction en ossature bois est exceptionnelle. Notre maison est à la fois chaleureuse et performante énergétiquement. Loftwood a su nous accompagner à chaque étape.',
            'author' => 'Marie & Thomas D.',
            'role'   => 'Propriétaires — Résidence Les Jardins',
            'stars'  => 5,
        ],
        [
            'quote'  => 'Un accompagnement professionnel du début à la fin. L\'équipe Loftwood est à l\'écoute et les délais ont été parfaitement respectés. Nous recommandons sans hésitation.',
            'author' => 'Jean-Pierre L.',
            'role'   => 'Investisseur — Programme Éco-Quartier',
            'stars'  => 5,
        ],
        [
            'quote'  => 'Nous cherchions un logement éco-responsable sans compromis sur le confort. Loftwood a dépassé nos attentes avec des finitions impeccables et une isolation remarquable.',
            'author' => 'Sophie & Marc R.',
            'role'   => 'Propriétaires — Résidence Bois Doré',
            'stars'  => 5,
        ],
    ];
}
?>

<section class="bg-cream" style="padding-top: var(--section-gap); padding-bottom: var(--section-gap);">
    <div class="max-w-[1200px] mx-auto px-6">

        <div class="text-center mb-12" data-reveal="up">
            <p class="eco-badge mx-auto mb-4">Ce qu'ils en disent</p>
            <h2 class="section-title-serif text-4xl lg:text-5xl text-foreground">
                Ils nous font confiance
            </h2>
        </div>

        <div class="lw-testimonials" data-reveal="fade">
            <div class="lw-testimonials-track">
                <?php foreach ($testimonials as $t) : ?>
                    <div class="lw-testimonial-slide">
                        <div class="lw-testimonial-card">
                            <?php if (!empty($t['stars'])) : ?>
                                <div class="lw-testimonial-stars">
                                    <?php for ($s = 0; $s < (int)$t['stars']; $s++) : ?>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>

                            <p class="lw-testimonial-quote">
                                <?php echo esc_html($t['quote']); ?>
                            </p>

                            <p class="lw-testimonial-author"><?php echo esc_html($t['author']); ?></p>
                            <?php if (!empty($t['role'])) : ?>
                                <p class="lw-testimonial-role"><?php echo esc_html($t['role']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (count($testimonials) > 1) : ?>
                <div class="lw-carousel-dots">
                    <?php foreach ($testimonials as $i => $t) : ?>
                        <button class="lw-carousel-dot <?php echo $i === 0 ? 'is-active' : ''; ?>"
                                aria-label="Témoignage <?php echo $i + 1; ?>"></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
