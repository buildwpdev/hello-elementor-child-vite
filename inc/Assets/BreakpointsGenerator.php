<?php

// wp-content/themes/build-wp/wp/Assets/BreakpointsGenerator.php

namespace BuildWp\WpChildTheme\Assets;

class BreakpointsGenerator {
    /**
     * Register the SCSS generator hooks.
     */
    public static function register() {
        // Run when the theme is activated, but only if the SCSS file doesn’t exist
        add_action('after_switch_theme', function () {
            if (!file_exists(get_theme_file_path('resources/src/styles/_breakpoints.scss'))) {
                self::generate_scss();
            }
        });

        // Run when Elementor settings related to breakpoints are updated
        add_action('update_option_elementor_container_width', [self::class, 'generate_scss']);
        add_action('update_option_elementor_disable_default_fonts', [self::class, 'generate_scss']);
        add_action('update_option_elementor_disable_color_schemes', [self::class, 'generate_scss']);

        // Manual trigger via /wp-admin/admin-post.php?action=generate_elementor_breakpoints
        add_action('admin_post_generate_elementor_breakpoints', [self::class, 'generate_scss']);
    }

    /**
     * Generate SCSS file with Elementor breakpoints.
     */
    public static function generate_scss() {
        if (!is_admin()) return;

        // Define file paths
        $scss_dir  = get_theme_file_path('resources/src/styles/');
        $scss_file = $scss_dir . '_breakpoints.scss';

        // Ensure the directory exists
        if (!is_dir($scss_dir)) {
            if (!mkdir($scss_dir, 0755, true) && !is_dir($scss_dir)) {
                error_log("[BreakpointsGenerator] ERROR: Failed to create directory: {$scss_dir}");
                return;
            }
        }

        // Elementor default breakpoints (fallback values)
        $default_breakpoints = [
            'mobile'  => '767px',
            'tablet'  => '1024px',
            'laptop'  => '1366px',
            'desktop' => '1600px'
        ];

        // Get Elementor breakpoints if available
        $breakpoints = $default_breakpoints;
        if (class_exists('\Elementor\Plugin')) {
            $elementor = \Elementor\Plugin::$instance;
            if (!empty($elementor->breakpoints)) {
                $breakpoints = [];
                foreach ($elementor->breakpoints->get_breakpoints() as $key => $breakpoint) {
                    $breakpoints[$key] = $breakpoint->get_value() . 'px';
                }
            }
        }

        // Generate SCSS content
        $scss_content = "// Auto-generated Elementor breakpoints\n";
        foreach ($breakpoints as $key => $size) {
            $scss_content .= "$" . "e-global-breakpoint-{$key}: {$size};\n";
        }

        // Prevent redundant file writes
        if (file_exists($scss_file)) {
            $existing_content = file_get_contents($scss_file);
            if ($existing_content === $scss_content) {
                error_log("[BreakpointsGenerator] No changes detected, skipping SCSS generation.");
                return;
            }
        }

        // Write SCSS file
        if (file_put_contents($scss_file, $scss_content) !== false) {
            error_log("[BreakpointsGenerator] SCSS file successfully written to: {$scss_file}");
        } else {
            error_log("[BreakpointsGenerator] ERROR: Failed to write SCSS file to: {$scss_file}");
        }
    }
}
