<?php
/**
 * Title: Programme Contact CTA
 * Slug: loftwood/programme-contact-cta
 * Categories: loftwood
 */

if (!is_singular('programmes')) return;

$phone = get_field('op_phone', 'option') ?: '05 61 35 20 34';
?>

<section id="contact-programme" class="bg-foreground-dark text-white" style="padding-top: var(--section-gap); padding-bottom: var(--section-gap);">
    <div class="max-w-[800px] mx-auto px-6 text-center" data-reveal="scale">
        <h2 class="font-montserrat text-3xl lg:text-4xl font-semibold mb-4">
            Intéressé par <?php the_title(); ?> ?
        </h2>
        <p class="text-white/70 text-lg mb-8">
            Recevez la documentation complète et les disponibilités actualisées.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/contact/?programme=<?php echo esc_attr(get_the_ID()); ?>"
               class="btn-loftwood inline-flex items-center px-8 py-4 bg-eco-green text-white font-semibold rounded-md hover:bg-eco-green-light transition-all hover:-translate-y-0.5 hover:shadow-lg">
                Demander la documentation
            </a>
            <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>"
               class="inline-flex items-center px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-md hover:border-white/60 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <?php echo esc_html($phone); ?>
            </a>
        </div>
    </div>
</section>
