<?php

// 

namespace EjStoneCo\WpChildTheme;

class Hooks {
    public static function register() {
        add_action('elementor/init', [self::class, 'elementor_init']);
        add_action('wp_enqueue_scripts', [self::class, 'load_assets']);
        add_action('admin_init', [self::class, 'admin_setup']);
    }

    public static function elementor_init() {
        error_log("✅ Elementor initialized once!");
    }

    public static function load_assets() {
        wp_enqueue_style('theme-styles', get_stylesheet_directory_uri() . '/assets/css/style.css');
    }

    public static function admin_setup() {
        error_log("✅ Admin hooks loaded!");
    }
}
