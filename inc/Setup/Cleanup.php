<?php

// wp-content/themes/build-wp/wp/Setup/Cleanup.php

namespace BuildWp\WpChildTheme\Setup;

class Cleanup {
    public static function register() {

        // Only run Cleanup on the front-end and NOT in Elementor
        if (is_admin() || self::is_elementor_editor()) {
            return;
        }

        // Core Cleanup & Optimizations
        add_action('init', [self::class, 'init_cleanup']);
        add_action('pre_ping', [self::class, 'disable_self_pingbacks']);
        add_action('wp_default_scripts', [self::class, 'remove_jquery_migrate']);
        add_action('wp_enqueue_scripts', [self::class, 'remove_dashicons']);
        // add_filter('script_loader_tag', [self::class, 'defer_parsing_js'], 10, 2);
        add_action('admin_menu', [self::class, 'remove_gutenberg_patterns_menu'], 999);

        // Security Hardening
        add_filter('login_errors', '__return_false');
        add_filter('the_generator', '__return_empty_string');
        //add_filter('rest_authentication_errors', [self::class, 'disable_rest_api_for_guests']);
        add_action('wp_head', [self::class, 'remove_unwanted_meta'], 1);
        add_action('template_redirect', [self::class, 'disable_feeds'], 1);
        add_action('init', [self::class, 'disable_emojis']);
        add_action('init', [self::class, 'disable_theme_editor']);
        add_action('wp_enqueue_scripts', [self::class, 'remove_unnecessary_wp_scripts'], 100);

        // Allow admin access even when WooCommerce is active
        // add_filter('woocommerce_prevent_admin_access', '__return_false');

        // Ensure WooCommerce doesn’t interfere with AJAX requests
        add_filter('woocommerce_disable_admin_bar', '__return_false');

        // Prevent WooCommerce from requiring authentication for AJAX requests
        // add_filter('woocommerce_registration_redirect', function($redirect) {
        //     return (wp_doing_ajax()) ? false : $redirect;
        // });



    }


    /**
     * Detect if Elementor editor is currently active.
     */
    private static function is_elementor_editor() {
        return defined('ELEMENTOR_VERSION') && isset($_GET['elementor-preview']);
    }

    public static function init_cleanup() {
        self::disable_unwanted_wp_features();
        self::remove_gutenberg();
        self::disable_embeds();
        self::disable_comments();
        self::disable_unused_taxonomies();
        self::disable_dashboard_widgets();
        self::disable_admin_bar();
        self::disable_xmlrpc();
        self::disable_trackbacks();
        self::disable_attachment_pages();
        self::remove_all_widgets();
        self::disable_author_archives();
    }

    public static function disable_unwanted_wp_features() {
        remove_filter('the_content', 'wpautop');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    }

    public static function remove_unwanted_meta() {
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
        remove_action('wp_head', 'wp_oembed_add_host_js');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    }

    public static function disable_theme_editor() {
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
        if (!defined('DISALLOW_FILE_MODS')) {
            define('DISALLOW_FILE_MODS', true);
        }
    }

    public static function disable_emojis() {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

        add_filter('tiny_mce_plugins', function ($plugins) {
            return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
        });

        add_filter('wp_resource_hints', function ($urls, $relation_type) {
            if ($relation_type === 'dns-prefetch') {
                $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
                $urls = array_diff($urls, [$emoji_svg_url]);
            }
            return $urls;
        }, 10, 2);
    }

    public static function remove_unnecessary_wp_scripts() {
        if (!is_admin()) {
            wp_dequeue_script('api-fetch');
            wp_dequeue_script('wp-url');
            wp_dequeue_script('wp-data');
            wp_dequeue_script('react-jsx-runtime');
            wp_dequeue_script('compose');
            wp_dequeue_script('deprecated');
            wp_dequeue_script('dom');
            wp_dequeue_script('element');
            wp_dequeue_script('escape-html');
            wp_dequeue_script('is-shallow-equal');
            wp_dequeue_script('keycodes');
            wp_dequeue_script('priority-queue');
            wp_dequeue_script('private-apis');
            wp_dequeue_script('redux-routine');
            wp_dequeue_script('data-controls');
            wp_dequeue_script('html-entities');
            wp_dequeue_script('notices');
            wp_dequeue_script('a11y');
            wp_dequeue_script('dom-ready');
            wp_dequeue_script('primitives');
            wp_dequeue_script('warning');
            wp_dequeue_script('autop');
            wp_dequeue_script('plugins');
            wp_dequeue_script('style-engine');
            wp_dequeue_script('wordcount');
        }
    }
    
    

    public static function disable_feeds() {
        if (is_feed()) {
            wp_redirect(home_url());
            exit;
        }
    }

    public static function defer_parsing_js($tag, $handle) {
        if (is_admin()) {
            return $tag;
        }
        return str_replace(' src', ' defer src', $tag);
    }

    public static function remove_jquery_migrate($scripts) {
        if (!is_admin() && isset($scripts->registered['jquery'])) {
            $scripts->registered['jquery']->deps = array_diff(
                $scripts->registered['jquery']->deps,
                ['jquery-migrate']
            );
        }
    }

    public static function remove_dashicons() {
        if (!is_admin()) {
            wp_deregister_style('dashicons');
        }
    }

    public static function remove_gutenberg() {
        add_filter('use_block_editor_for_post', '__return_false', 10);
        add_filter('use_widgets_block_editor', '__return_false');
        add_filter('gutenberg_use_widgets_block_editor', '__return_false');
        add_filter('block_editor_settings_all', '__return_empty_array');

        add_action('after_setup_theme', function() {
            remove_theme_support('block-templates');
            remove_theme_support('core-block-patterns');
        });

        add_action('wp_enqueue_scripts', function() {
            wp_dequeue_style('global-styles');
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');
            wp_dequeue_style('wc-blocks-style');
        }, 100);

        add_action('init', function() {
            remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles');
        }, 100);
    }

    public static function remove_gutenberg_patterns_menu() {
        remove_submenu_page('themes.php', 'edit.php?post_type=wp_block');
    }

    public static function disable_embeds() {
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        add_filter('embed_oembed_discover', '__return_false');
        add_filter('tiny_mce_plugins', function($plugins) {
            return array_diff($plugins, ['wpembed']);
        });
    }

    public static function disable_comments() {
        remove_post_type_support('post', 'comments');
        remove_post_type_support('page', 'comments');
        add_filter('comments_open', '__return_false', 20, 2);
    }

    public static function disable_unused_taxonomies() {
        unregister_taxonomy('post_tag');
    }

    public static function disable_dashboard_widgets() {
        remove_action('welcome_panel', 'wp_welcome_panel');
        add_action('wp_dashboard_setup', function() {
            remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
            remove_meta_box('dashboard_activity', 'dashboard', 'normal');
            remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
            remove_meta_box('dashboard_primary', 'dashboard', 'side');
            remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
        });
    }

    public static function disable_admin_bar() {
        add_filter('show_admin_bar', '__return_false');
    }

    public static function disable_xmlrpc() {
        add_filter('xmlrpc_enabled', '__return_false');
    }

    public static function disable_trackbacks() {
        add_filter('xmlrpc_methods', function($methods) {
            unset($methods['pingback.ping']);
            return $methods;
        });
        remove_action('do_pings', 'do_all_pings', 10, 1);
    }

    public static function disable_attachment_pages() {
        add_action('template_redirect', function() {
            if (is_attachment()) {
                wp_redirect(home_url());
                exit;
            }
        });
    }

    public static function remove_all_widgets() {
        global $wp_widget_factory;
        foreach ($wp_widget_factory->widgets as $widget_class => $widget) {
            unregister_widget($widget_class);
        }
    }

    public static function disable_author_archives() {
        add_action('template_redirect', function() {
            if (is_author()) {
                wp_redirect(home_url());
                exit;
            }
        });
    }
}
