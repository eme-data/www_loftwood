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
}
add_action('wp_enqueue_scripts', 'loftwood_enqueue_assets');

/**
 * Theme setup
 */
function loftwood_setup(): void
{
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'loftwood_setup');
