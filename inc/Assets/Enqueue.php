<?php

// wp-content/themes/build-wp/wp/Assets/Enqueue.php

namespace BuildWp\WpChildTheme\Assets;

use BuildWp\WpChildTheme\Assets\Vite;

class Enqueue {
    public static function register() {
        add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
        //add_action('wp_print_scripts', [self::class, 'dequeue_facetwp_scripts'], 20);
        add_action('admin_enqueue_scripts', [self::class, 'enqueue_admin_assets']); // ✅ Add admin styles
        self::register_ajax_handlers();
    }

    /**
     * Enqueue Styles & Scripts
     */
    public static function enqueue_assets() {

        self::remove_unwanted_assets();

        //self::log_enqueued_items();

        // Enqueue Styles
        wp_enqueue_style(
            'hello-child-style',
            get_stylesheet_directory_uri() . '/assets/css/style.css',
            ['hello-elementor-theme-style'],
            HELLO_ELEMENTOR_CHILD_VERSION
        );

        //wp_enqueue_script('alpine-js', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', [], null, true);

        // Load the manifest ONCE here
        $manifest = Vite::getManifest();

        if (Vite::isDevServerRunning() && defined('VITE_HMR') && VITE_HMR) {
            self::enqueueHmrScripts();
        } else {
            self::enqueueProductionAssets($manifest);
        }
    }

    /**
     * Enqueue Admin Styles
     */
    public static function enqueue_admin_assets() {
        wp_enqueue_style(
            'BuildWp-admin-css',
            get_theme_file_uri('/resources/admin-style.css'), // ✅ Correct path
            [],
            null
        );
    }

    /**
     * Remove Unwanted Scripts & Styles
     */
    public static function remove_unwanted_assets() {
        $unwanted_styles = [
            'wp-emoji-styles',
            'wp-block-library',
            'rank-math-toc-block-style',
            'rank-math-rich-snippet-style',
            'brands-styles',
            'rank-math',
            'elementor-wp-admin-bar',
            'ubermenu-font-awesome-all',
            'ubermenu-grey-white',
            'widget-form',
            'widget-blockquote',
        ];

        foreach ($unwanted_styles as $handle) {
            wp_dequeue_style($handle);
            wp_deregister_style($handle);
        }

        $unwanted_scripts = [
            'wp-emoji',
            'rank-math',
            'elementor-wp-admin-bar'
        ];

        foreach ($unwanted_scripts as $handle) {
            wp_dequeue_script($handle);
            wp_deregister_script($handle);
        }
    }

    /**
     * Dequeue FacetWP Scripts
     */
    public static function dequeue_facetwp_scripts() {
        // wp_dequeue_script('facetwp-front'); 
        // wp_deregister_script('facetwp-front');
    }


    public static function log_enqueued_items(){
        global $wp_styles;

        $loaded_css = [];
        foreach ($wp_styles->queue as $handle) {
            $loaded_css[] = $handle . ' → ' . wp_styles()->registered[$handle]->src;
        }
    
        error_log("Loaded CSS Files:\n" . implode("\n", $loaded_css));  
    }

    

    /**
     * Enqueue Vite HMR (Hot Module Replacement) Scripts
     */
    private static function enqueueHmrScripts() {
        wp_enqueue_script('vite-client', 'http://localhost:5173/@vite/client', [], null, true);
        wp_enqueue_script('ej-stone-main-js', 'http://localhost:5173/src/main.js', [], null, true);

        add_filter('script_loader_tag', function ($tag, $handle) {
            if (in_array($handle, ['vite-client', 'ej-stone-main-js'])) {
                return str_replace('<script', '<script type="module"', $tag);
            }
            return $tag;
        }, 10, 2);

        wp_localize_script('ej-stone-main-js', 'EjStoneAjax', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('custom_ajax_nonce')
        ]);
    }

    /**
     * Enqueue production assets from Vite's manifest
     */
    private static function enqueueProductionAssets(array $manifest) {
        foreach ($manifest as $entryName => $entryData) {
            if (isset($entryData['file'])) {
                $jsFile = get_theme_file_uri('resources/dist/' . $entryData['file']);
                $fileHandleName = 'vite-' . sanitize_title($entryName);

                wp_enqueue_script(
                    $fileHandleName,
                    $jsFile,
                    [],
                    'jquery',
                    true
                );

                if (isset($entryData['src']) && $entryData['src'] === 'src/main.js') {
                    wp_localize_script($fileHandleName, 'EjStoneAjax', [
                        'ajaxUrl' => admin_url('admin-ajax.php'),
                        'nonce'   => wp_create_nonce('custom_ajax_nonce')
                    ]);
                }
            }

            if (isset($entryData['css'])) {
                foreach ($entryData['css'] as $cssFilePath) {
                    $cssFile = get_theme_file_uri('resources/dist/' . $cssFilePath);
                    wp_enqueue_style(
                        'vite-' . sanitize_title($cssFilePath),
                        $cssFile,
                        [],
                        null
                    );
                }
            }
        }
    }






    /**
     * Register AJAX handlers
     */
    private static function register_ajax_handlers() {
        $ajax_handlers = [
            'load_menu_cart_tray' => 'handle_ajax_load_menu_cart_tray',
            'test_ajax_method' => 'handle_ajax_test_method', // ✅ New AJAX Method
        ];

        foreach ($ajax_handlers as $action => $method) {
            add_action("wp_ajax_{$action}", [self::class, $method]);
            add_action("wp_ajax_nopriv_{$action}", [self::class, $method]);
        }
    }

    /**
     * Handle AJAX request
     */
    public static function handle_ajax_load_menu_cart_tray() {
        error_log("⚡ handle_ajax_load_menu_cart_tray TRIGGERED");
    
        // Log raw POST data
        error_log("📩 RAW POST DATA: " . print_r($_POST, true));
    
        // Log headers (if needed)
        error_log("📡 REQUEST HEADERS: " . print_r(getallheaders(), true));
    
        // Log referrer
        if (isset($_SERVER['HTTP_REFERER'])) {
            error_log("🔗 REFERER: " . $_SERVER['HTTP_REFERER']);
        }
    
        // Log request URI
        if (isset($_SERVER['REQUEST_URI'])) {
            error_log("📌 REQUEST URI: " . $_SERVER['REQUEST_URI']);
        }
    
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'custom_ajax_nonce')) {
            error_log("⛔ INVALID NONCE: " . print_r($_POST['nonce'], true));
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
            exit;
        }
    
        // Ensure WooCommerce exists
        if (!class_exists('WooCommerce')) {
            error_log("🚨 WooCommerce NOT LOADED");
            wp_send_json_error(['message' => 'WooCommerce is not active'], 400);
            exit;
        }
    
        // Ensure WooCommerce cart is accessible
        if (!WC()->cart) {
            error_log("🛑 WC()->cart is NULL!");
            wp_send_json_error(['message' => 'WooCommerce cart is unavailable'], 500);
            exit;
        }
    
        // Log WooCommerce cart contents
        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_subtotal = WC()->cart->get_cart_subtotal();
    
        error_log("🛒 Cart Count: " . $cart_count);
        error_log("💰 Cart Subtotal: " . $cart_subtotal);
    
        // Capture rendered output
        ob_start();
        get_template_part('template-parts/menu-cart/tray', null, [
            'cart_count' => $cart_count,
            'cart_subtotal' => $cart_subtotal,
        ]);
        $tray_html = ob_get_clean();
    
        if (!$tray_html) {
            error_log("🚫 Template rendering failed!");
        }
    
        error_log("✅ AJAX Response: " . print_r(['message' => 'Cart tray loaded', 'html' => $tray_html], true));
    
        wp_send_json_success([
            'message' => 'Cart tray loaded successfully',
            'html' => $tray_html
        ]);
    }

    /**
     * Handle Test AJAX request
     */
    public static function handle_ajax_test_method() {
        error_log("⚡ handle_ajax_test_method TRIGGERED");

        // Log request data
        error_log("📩 RAW POST DATA: " . print_r($_POST, true));

        // Log request headers (if needed)
        error_log("📡 REQUEST HEADERS: " . print_r(getallheaders(), true));

        // Log request URI
        if (isset($_SERVER['REQUEST_URI'])) {
            error_log("📌 REQUEST URI: " . $_SERVER['REQUEST_URI']);
        }

        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'custom_ajax_nonce')) {
            error_log("⛔ INVALID NONCE: " . print_r($_POST['nonce'], true));
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
            exit;
        }

        // Simulate a test response
        $response_data = [
            'success' => true,
            'message' => 'Test AJAX response received!',
            'timestamp' => time(),
        ];

        error_log("✅ Test AJAX Response: " . print_r($response_data, true));

        wp_send_json_success($response_data);
    }




}
