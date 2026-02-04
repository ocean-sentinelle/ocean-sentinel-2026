<?php

declare(strict_types=1);

add_action('wp_enqueue_scripts', static function (): void {
    wp_enqueue_style(
        'ocean-sentinel-theme',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get('Version')
    );
});

add_action('wp_head', static function (): void {
    $href = get_stylesheet_uri();
    if (!is_string($href) || $href === '') {
        return;
    }

    echo '<link rel="preload" as="style" href="' . esc_url($href) . '" />' . "\n";
}, 1);

add_action('wp_head', static function (): void {
    if (!is_page('a-propos')) {
        return;
    }

    echo '<meta name="description" content="Ocean Sentinel 2026 : surveillance de l’acidification des océans et de la saturation en aragonite (ΩArag) pour l’ostréiculture en Nouvelle-Aquitaine. Alertes scientifiques, impact économique ABACODE (€/ha)." />' . "\n";
}, 2);

add_action('after_setup_theme', static function (): void {
    add_theme_support('title-tag');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
});

add_action('admin_init', static function (): void {
    if (get_option('os_about_page_created_v1') === '1') {
        return;
    }

    if (!function_exists('wp_insert_post')) {
        return;
    }

    $existing = get_page_by_path('a-propos');
    if ($existing instanceof WP_Post) {
        update_post_meta($existing->ID, '_wp_page_template', 'page-about.php');
        update_option('os_about_page_created_v1', '1', true);
        return;
    }

    $page_id = wp_insert_post([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => 'À Propos',
        'post_name' => 'a-propos',
        'post_content' => '',
    ], true);

    if (is_wp_error($page_id)) {
        return;
    }

    update_post_meta((int) $page_id, '_wp_page_template', 'page-about.php');
    update_option('os_about_page_created_v1', '1', true);
});
