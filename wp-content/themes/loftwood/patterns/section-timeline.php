<?php
/**
 * Title: Section Timeline Construction
 * Slug: loftwood/section-timeline
 * Categories: loftwood-sections
 */

$front_id = get_option('page_on_front');
$timeline = get_field('h_timeline', $front_id);

// Fallback timeline if ACF field is empty
if (empty($timeline)) {
    $timeline = [
        [
            'date'  => 'Étape 1',
            'title' => 'Conception & Permis',
            'desc'  => 'Étude de faisabilité, conception architecturale en ossature bois et dépôt du permis de construire. Choix des matériaux biosourcés.',
        ],
        [
            'date'  => 'Étape 2',
            'title' => 'Fabrication en atelier',
            'desc'  => 'Pré-fabrication des panneaux ossature bois en atelier contrôlé. Qualité et précision garanties, réduction des déchets de chantier.',
        ],
        [
            'date'  => 'Étape 3',
            'title' => 'Montage sur site',
            'desc'  => 'Assemblage rapide des modules sur le chantier. La construction bois permet un montage 3 à 4 fois plus rapide que le traditionnel.',
        ],
        [
            'date'  => 'Étape 4',
            'title' => 'Second œuvre & Finitions',
            'desc'  => 'Isolation haute performance, menuiseries, plomberie, électricité et finitions intérieures soignées.',
        ],
        [
            'date'  => 'Étape 5',
            'title' => 'Livraison & Garanties',
            'desc'  => 'Remise des clés, accompagnement post-livraison et garanties décennales. Votre logement éco-responsable est prêt.',
        ],
    ];
}
?>

<section style="padding-top: var(--section-gap); padding-bottom: var(--section-gap);">
    <div class="max-w-[1200px] mx-auto px-6">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

            <!-- Left: Title -->
            <div data-reveal="left" class="lg:sticky lg:top-32">
                <p class="eco-badge mb-4">Notre processus</p>
                <h2 class="section-title-serif text-4xl lg:text-5xl text-foreground mb-6">
                    De la conception à la livraison
                </h2>
                <p class="text-slate text-lg leading-relaxed mb-8">
                    Chaque projet Loftwood suit un processus rigoureux pour garantir qualité,
                    performance énergétique et respect des délais.
                </p>
                <a href="/contactez-nous/" class="btn-loftwood inline-flex items-center gap-2 px-8 py-4 bg-eco-green text-white font-semibold rounded-md hover:bg-eco-green-light transition-all hover:-translate-y-0.5 hover:shadow-lg">
                    Démarrer votre projet
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <!-- Right: Timeline -->
            <div class="lw-timeline" data-reveal-stagger>
                <?php foreach ($timeline as $item) : ?>
                    <div class="lw-timeline-item" data-reveal="up">
                        <div class="lw-timeline-dot"></div>
                        <p class="lw-timeline-date"><?php echo esc_html($item['date']); ?></p>
                        <h3 class="lw-timeline-title"><?php echo esc_html($item['title']); ?></h3>
                        <p class="lw-timeline-desc"><?php echo esc_html($item['desc']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

    </div>
</section>
