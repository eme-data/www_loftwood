<?php
/**
 * Loftwood - Functions and definitions
 *
 * @package Loftwood
 */

if (!defined('ABSPATH')) {
    exit;
}

define('LOFTWOOD_VERSION', '1.0.0');
define('LOFTWOOD_DIR', get_template_directory());
define('LOFTWOOD_URI', get_template_directory_uri());


/**
 * Enqueue styles and scripts
 */
function loftwood_enqueue_assets(): void
{
    $manifest_path = LOFTWOOD_DIR . '/dist/.vite/manifest.json';

    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true);

        if (isset($manifest['src/main.js'])) {
            $entry = $manifest['src/main.js'];

            if (isset($entry['css'])) {
                foreach ($entry['css'] as $i => $css_file) {
                    wp_enqueue_style(
                        "loftwood-style-{$i}",
                        LOFTWOOD_URI . '/dist/' . $css_file,
                        [],
                        LOFTWOOD_VERSION
                    );
                }
            }

            wp_enqueue_script(
                'loftwood-script',
                LOFTWOOD_URI . '/dist/' . $entry['file'],
                [],
                LOFTWOOD_VERSION,
                true
            );
        }
    }

    // Google Fonts
    wp_enqueue_style(
        'loftwood-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap',
        [],
        null
    );
}
add_action('wp_enqueue_scripts', 'loftwood_enqueue_assets');

/**
 * Theme setup
 */
function loftwood_setup(): void
{
    load_theme_textdomain('loftwood', LOFTWOOD_DIR . '/languages');

    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('post-thumbnails');

    add_image_size('programme-card', 750, 500, true);
    add_image_size('programme-gallery', 1200, 800, true);
    add_image_size('blog-card', 600, 400, true);
    add_image_size('hero-banner', 1920, 900, true);

    register_nav_menus([
        'main_menu'   => __('Menu principal', 'loftwood'),
        'footer_menu' => __('Menu footer', 'loftwood'),
    ]);
}
add_action('after_setup_theme', 'loftwood_setup');

/**
 * Register Custom Post Type: Programmes
 */
function loftwood_register_cpt(): void
{
    register_post_type('programmes', [
        'labels' => [
            'name'               => __('Programmes', 'loftwood'),
            'singular_name'      => __('Programme', 'loftwood'),
            'add_new'            => __('Ajouter un programme', 'loftwood'),
            'add_new_item'       => __('Ajouter un nouveau programme', 'loftwood'),
            'edit_item'          => __('Modifier le programme', 'loftwood'),
            'view_item'          => __('Voir le programme', 'loftwood'),
            'search_items'       => __('Rechercher un programme', 'loftwood'),
            'not_found'          => __('Aucun programme trouvé', 'loftwood'),
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'programmes'],
        'menu_icon'    => 'dashicons-building',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    register_taxonomy('programmes_categories', 'programmes', [
        'labels' => [
            'name'          => __('Catégories de programmes', 'loftwood'),
            'singular_name' => __('Catégorie', 'loftwood'),
            'search_items'  => __('Rechercher une catégorie', 'loftwood'),
            'all_items'     => __('Toutes les catégories', 'loftwood'),
            'add_new_item'  => __('Ajouter une catégorie', 'loftwood'),
        ],
        'hierarchical'  => true,
        'public'        => true,
        'rewrite'       => ['slug' => 'categorie-programme'],
        'show_in_rest'  => true,
    ]);
}
add_action('init', 'loftwood_register_cpt');

/**
 * ACF Options page
 */
function loftwood_acf_options(): void
{
    if (!function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page([
        'page_title' => __('Options du thème', 'loftwood'),
        'menu_title' => __('Options Loftwood', 'loftwood'),
        'menu_slug'  => 'loftwood-options',
        'capability' => 'edit_theme_options',
        'redirect'   => false,
        'icon_url'   => 'dashicons-admin-customizer',
        'position'   => 2,
    ]);
}
add_action('acf/init', 'loftwood_acf_options');

/**
 * ACF Google Maps API key
 */
function loftwood_acf_google_maps(): void
{
    acf_update_setting('google_api_key', get_field('google_maps_api_key', 'option') ?: '');
}
add_action('acf/init', 'loftwood_acf_google_maps');

/**
 * Allow SVG uploads
 */
function loftwood_mime_types(array $mimes): array
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'loftwood_mime_types');

/**
 * Register block patterns
 */
function loftwood_register_patterns(): void
{
    register_block_pattern_category('loftwood', [
        'label' => __('Loftwood', 'loftwood'),
    ]);
    register_block_pattern_category('loftwood-sections', [
        'label' => __('Loftwood - Sections', 'loftwood'),
    ]);

    // Explicitly register all patterns from the patterns/ directory
    $patterns_dir = LOFTWOOD_DIR . '/patterns';
    if (!is_dir($patterns_dir)) return;

    foreach (glob($patterns_dir . '/*.php') as $file) {
        $headers = get_file_data($file, [
            'title'      => 'Title',
            'slug'       => 'Slug',
            'categories' => 'Categories',
        ]);

        if (empty($headers['slug'])) continue;

        $categories = !empty($headers['categories'])
            ? array_map('trim', explode(',', $headers['categories']))
            : ['loftwood'];

        register_block_pattern($headers['slug'], [
            'title'      => $headers['title'] ?: basename($file, '.php'),
            'categories' => $categories,
            'filePath'   => $file,
        ]);
    }
}
add_action('init', 'loftwood_register_patterns');

/**
 * Breadcrumbs
 */
function loftwood_breadcrumbs(): void
{
    if (is_front_page()) return;

    echo '<nav class="lw-breadcrumbs" aria-label="Fil d\'Ariane">';
    echo '<a href="' . esc_url(home_url('/')) . '">Accueil</a>';
    echo '<span class="sep">›</span>';

    if (is_singular('programmes')) {
        echo '<a href="' . esc_url(get_post_type_archive_link('programmes')) . '">Programmes</a>';
        echo '<span class="sep">›</span>';
        echo '<span>' . esc_html(get_the_title()) . '</span>';
    } elseif (is_singular('post')) {
        echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">Actualités</a>';
        echo '<span class="sep">›</span>';
        echo '<span>' . esc_html(get_the_title()) . '</span>';
    } elseif (is_post_type_archive('programmes')) {
        echo '<span>Programmes</span>';
    } elseif (is_category()) {
        echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">Actualités</a>';
        echo '<span class="sep">›</span>';
        echo '<span>' . esc_html(single_cat_title('', false)) . '</span>';
    } elseif (is_archive()) {
        echo '<span>' . esc_html(get_the_archive_title()) . '</span>';
    } elseif (is_search()) {
        echo '<span>Recherche : ' . esc_html(get_search_query()) . '</span>';
    } elseif (is_404()) {
        echo '<span>Page introuvable</span>';
    } elseif (is_page()) {
        echo '<span>' . esc_html(get_the_title()) . '</span>';
    }

    echo '</nav>';
}

/**
 * Back link for detail pages
 */
function loftwood_back_link(): void
{
    if (is_singular('programmes')) {
        $url = get_post_type_archive_link('programmes');
        $label = 'Tous les programmes';
    } elseif (is_singular('post')) {
        $url = get_permalink(get_option('page_for_posts')) ?: home_url('/');
        $label = 'Toutes les actualités';
    } else {
        return;
    }

    echo '<a href="' . esc_url($url) . '" class="lw-back-link">';
    echo '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m0 0l7 7m-7-7l7-7"/></svg>';
    echo esc_html($label);
    echo '</a>';
}

/**
 * SEO — Meta tags, Open Graph, structured data
 */
function loftwood_seo_meta(): void
{
    if (is_admin()) return;

    $title = wp_title('|', false, 'right') . get_bloginfo('name');
    $description = get_bloginfo('description');
    $url = home_url(add_query_arg([], $_SERVER['REQUEST_URI'] ?? ''));
    $image = '';

    if (is_singular()) {
        $post = get_queried_object();
        if ($post && !empty($post->post_excerpt)) {
            $description = wp_strip_all_tags($post->post_excerpt);
        } elseif ($post) {
            $description = wp_trim_words(wp_strip_all_tags($post->post_content), 30, '...');
        }
        if (has_post_thumbnail()) {
            $image = get_the_post_thumbnail_url(null, 'hero-banner');
        }
    }

    $description = esc_attr($description);
    ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <meta property="og:title" content="<?php echo esc_attr($title); ?>" />
    <meta property="og:description" content="<?php echo $description; ?>" />
    <meta property="og:url" content="<?php echo esc_url($url); ?>" />
    <meta property="og:type" content="<?php echo is_singular() ? 'article' : 'website'; ?>" />
    <meta property="og:site_name" content="Loftwood" />
    <meta property="og:locale" content="fr_FR" />
    <?php if ($image) : ?>
        <meta property="og:image" content="<?php echo esc_url($image); ?>" />
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image" />
    <?php
}
add_action('wp_head', 'loftwood_seo_meta', 1);

/**
 * SEO — JSON-LD structured data for homepage
 */
function loftwood_structured_data(): void
{
    if (!is_front_page()) return;

    $phone = get_field('op_phone', 'option') ?: '05 61 35 20 34';
    $data = [
        '@context'    => 'https://schema.org',
        '@type'       => 'RealEstateAgent',
        'name'        => 'Loftwood',
        'description' => 'Promoteur immobilier éco-responsable spécialisé en construction ossature bois.',
        'url'         => home_url('/'),
        'telephone'   => $phone,
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => '20 Rue de Novital',
            'addressLocality' => 'Gagnac-sur-Garonne',
            'postalCode'      => '31150',
            'addressCountry'  => 'FR',
        ],
        'areaServed'  => [
            '@type' => 'Place',
            'name'  => 'Haute-Garonne, Occitanie, France',
        ],
    ];

    $logo = get_field('op_logo', 'option');
    if (!empty($logo['url'])) {
        $data['logo'] = $logo['url'];
    }

    echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'loftwood_structured_data', 5);

/**
 * Performance — Preload fonts, add resource hints
 */
function loftwood_resource_hints(): void
{
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";
    echo '<link rel="dns-prefetch" href="https://www.googletagmanager.com" />' . "\n";
}
add_action('wp_head', 'loftwood_resource_hints', 0);

/**
 * Performance — Add loading="lazy" and decoding="async" to images
 */
function loftwood_lazy_images(string $content): string
{
    if (is_admin()) return $content;

    $content = preg_replace(
        '/<img(?![^>]*loading=)([^>]*)\/?>/i',
        '<img loading="lazy" decoding="async"$1/>',
        $content
    );

    return $content;
}
add_filter('the_content', 'loftwood_lazy_images');

/**
 * Performance — Remove WordPress emoji scripts
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/**
 * Security — Remove WP version from head
 */
remove_action('wp_head', 'wp_generator');

/**
 * SEO — XML Sitemap enabled (WP 5.5+ built-in)
 */
// Already enabled by default in WordPress 5.5+
