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
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap',
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
function loftwood_register_pattern_categories(): void
{
    register_block_pattern_category('loftwood', [
        'label' => __('Loftwood', 'loftwood'),
    ]);
    register_block_pattern_category('loftwood-sections', [
        'label' => __('Loftwood - Sections', 'loftwood'),
    ]);
}
add_action('init', 'loftwood_register_pattern_categories');
