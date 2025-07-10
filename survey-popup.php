<?php

/**
 * Plugin Name: Survey Popup
 * Description: A WordPress plugin that shows a floating React-based survey popup.
 * Version: 1.0
 * Author: Your Name
 */

if (! defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', 'enqueue_survey_popup_assets', 10000);
add_action('wp_footer', 'print_survey_popup_container');

/**
 * Enqueue React/Vite built assets
 */
function enqueue_survey_popup_assets()
{
    $dist = plugin_dir_path(__FILE__) . 'dist/';
    $manifest_path = $dist . '.vite/manifest.json';

    if (! file_exists($manifest_path)) {
        return;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);
    $entry = $manifest['index.html'] ?? reset($manifest);

    if (! empty($entry['file'])) {
        wp_enqueue_script(
            'survey-popup-js',
            plugins_url("dist/{$entry['file']}", __FILE__),
            [],
            null,
            true
        );
    }

    if (! empty($entry['css'])) {
        foreach ($entry['css'] as $i => $css) {
            wp_enqueue_style(
                "survey-popup-css-$i",
                plugins_url("dist/{$css}", __FILE__),
                [],
                null
            );
        }
    }
}

/**
 * Inject the survey popup container into the page footer
 */
function print_survey_popup_container()
{
    echo '<div id="survey-popup-root"></div>';
}
