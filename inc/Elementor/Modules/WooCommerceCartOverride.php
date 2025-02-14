<?php

// wp-content/themes/build-wp/wp/Elementor/Modules/WooCommerceCartOverride.php

namespace BuildWp\WpChildTheme\Elementor\Modules;



if (!defined('ABSPATH')) {
    exit;
}

class WooCommerceCartOverride {


    /**
     * Register hooks for WooCommerce-FacetWP search sync
     */
    public static function register() {
        add_action('elementor_pro/init', [self::class, 'testLoad']);
    }

    public static function testLoad() {
        error_log('✅ This works!');
        remove_action('elementor/init', [\ElementorPro\Modules\Woocommerce\Module::class, '__construct']);
    }

}