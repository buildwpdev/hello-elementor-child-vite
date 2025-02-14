<?php

namespace BuildWp\WpChildTheme\Elementor\Widgets;

use Elementor\Widgets_Manager;
use Elementor\Elements_Manager; // Needed to register widget categories

class Loader {
    private static string $base_namespace = 'BuildWp\WpChildTheme\Elementor\Widgets';
    private static ?array $widgets = null; // ✅ Store widget list statically

    public function register(): void {
        // Ensure Elementor is fully loaded before registering widgets
        if (!did_action('elementor/loaded')) {
            add_action('elementor/loaded', [self::class, 'register']);
            return;
        }

        // ✅ Register custom category
        add_action('elementor/elements/categories_registered', [self::class, 'register_category']);

        // ✅ Preload widget files once instead of inside a loop
        add_action('elementor/init', [self::class, 'load_widget_files']);

        // ✅ Register widgets
        add_action('elementor/widgets/register', [self::class, 'register_widgets'], 10);
    }

    /**
     * ✅ Register custom Elementor category: "EJ Stone"
     */
    public static function register_category(Elements_Manager $elements_manager): void {
        $elements_manager->add_category(
            'ej-stone',
            [
                'title' => esc_html__('EJ Stone', 'elementor-ejstone'),
                'icon'  => 'fa fa-cube', // Optional icon
            ]
        );
        error_log("✅ Registered category: EJ Stone");
    }

    public static function load_widget_files(): void {
        $base_path = get_stylesheet_directory() . '/wp/Elementor/Widgets/';

        foreach (self::get_widget_classes() as $class => $file) {
            $file_path = $base_path . $file;

            if (file_exists($file_path)) {
                require_once $file_path;
            } else {
                error_log("❌ Widget file not found: " . $file_path);
            }
        }
    }

    public static function register_widgets(Widgets_Manager $widgets_manager): void {
        foreach (self::get_widget_classes() as $class => $file) {
            if (class_exists($class) && is_subclass_of($class, \Elementor\Widget_Base::class)) {
                //error_log("✅ Registering widget: " . $class);
                $widgets_manager->register(new $class());
            } else {
                error_log("❌ Invalid or missing widget class: " . $class);
            }
        }
    }

    /**
     * 
     */
    private static function get_widget_classes(): array {
        if (self::$widgets === null) {
            $widgets = [
                'Toggle_Button' => 'toggle-button.php',
                'OEmbed_Widget' => 'oembed-widget.php',
                'WooCommerce_Menu_Cart' => 'woocommerce-cart.php',
                'Search_Query' => 'search-query.php',
            ];
    
            // ✅ Automatically prepend `self::$base_namespace`
            self::$widgets = array_map(fn($file, $class) => [self::$base_namespace . "\\$class" => $file], $widgets, array_keys($widgets));
            
            // ✅ Flatten the array (since `array_map()` returns nested arrays)
            self::$widgets = array_merge(...self::$widgets);
        }
    
        return self::$widgets;
    }
    


}
