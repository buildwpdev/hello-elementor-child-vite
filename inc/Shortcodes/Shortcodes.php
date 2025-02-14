<?php

// wp-content/themes/build-wp/wp/Shortcodes/Shortcodes.php

namespace BuildWp\WpChildTheme\Shortcodes;

use Elementor\TemplateLibrary\Source_Local;
use ElementorPro\Plugin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Shortcodes {
    /**
     * List of shortcodes and their corresponding methods.
     */
    private static array $shortcodes = [
        'customH1' => 'customH1',
        'customTaxonomyH1' => 'customTaxonomyH1',
        'customTaxonomyFriendlyHeading' => 'customTaxonomyFriendlyHeading',
        'taxonomyHeroTagline' => 'taxonomyHeroTagline',
        'productDescriptionHeading' => 'productDescriptionHeading',
        'elementor-flexible-content' => 'elementorFlexibleContent',
        'colorModeToggle' => 'colorModeToggle',
        'facetWPDropdown' => 'facetWPDropdown',
    ];

    /**
     * Register the shortcode action.
     */
    public static function register() {
        add_action('init', [self::class, 'registerShortcodes']);

        // Add admin actions for Elementor shortcode display
        if (is_admin()) {
            add_action('manage_' . Source_Local::CPT . '_posts_columns', [self::class, 'adminColumnsHeaders']);
            add_action('manage_' . Source_Local::CPT . '_posts_custom_column', [self::class, 'adminColumnsContent'], 10, 2);
        }
    }

    /**
     * Dynamically register all shortcodes from the array.
     */
    public static function registerShortcodes() {
        foreach (self::$shortcodes as $shortcode => $method) {
            add_shortcode($shortcode, [self::class, $method]);
        }
    }


    /**
     * 
     */
    public static function customH1(): string {
        global $post;

        // Ensure we have a valid post object
        if (!$post || !isset($post->ID)) {
            return '';
        }

        //return get_the_ID();

        // Check global metadata first
        $customH1 = get_post_meta($post->ID, 'custom_h1_text', true);
        if (!empty($customH1)) {
            return esc_html($customH1);
        }

        $friendlyTitle = get_post_meta($post->ID, 'friendly_title', true);
        if (!empty($friendlyTitle)) {
            return esc_html($friendlyTitle);
        }

        // Fallback to post title
        return 'FUCK' . esc_html(get_the_title($post->ID));
    }


    /**
     * 
     */
    public static function customTaxonomyH1(): string {
        if (!is_tax() && !is_category() && !is_tag()) {
            return ''; // Only process on taxonomy pages
        }
    
        $term = get_queried_object();
    
        if (!$term || !isset($term->term_id)) {
            return ''; // Return empty if term is invalid
        }
    
        // Check global metadata for taxonomy term
        $customH1 = get_field('custom_h1_text', $term);
        if (!empty($customH1)) {
            return esc_html(wp_strip_all_tags($customH1));
        }
    
        $friendlyTitle = get_field('friendly_title', $term);
        if (!empty($friendlyTitle)) {
            return esc_html(wp_strip_all_tags($friendlyTitle));
        }
    
        // Fallback to taxonomy term name
        return esc_html($term->name);
    }
    


    /**
     * 
     */
    public static function customTaxonomyFriendlyHeading(): string {
        if (!is_tax() && !is_category() && !is_tag()) {
            return ''; // Only process on taxonomy pages
        }

        $term = get_queried_object();

        if (!$term || !isset($term->term_id)) {
            return ''; // Return empty if term is invalid
        }

        $friendlyTitle = get_field('friendly_heading', $term);
        if (!empty($friendlyTitle)) {
            return esc_html($friendlyTitle);
        }

        // Fallback to taxonomy term name
        return esc_html($term->name);
    }


    /**
     * 
     */
    public static function taxonomyHeroTagline(): string {
        if (!is_tax('product_cat')) {
            return ''; // Only process on product category pages
        }
    
        $term = get_queried_object();
    
        if (!$term || !isset($term->term_id)) {
            return ''; // Return empty if term is invalid
        }
    
        // Allowed HTML tags
        $allowed_html = [
            'p'      => [],
            'br'     => [],
            'strong' => [],
            'em'     => [],
            'b'      => [],
            'i'      => [],
            'u'      => [],
            'a'      => ['href' => [], 'title' => []], // Allow links with href and title attributes
            'ul'     => [],
            'ol'     => [],
            'li'     => [],
            'span'   => ['class' => []], // Allow class on span elements
        ];
    
        // Check if 'hero_tagline' field exists in ACF (or other custom field plugins)
        $heroTagline = get_field('hero_tagline', $term);
        if (!empty($heroTagline)) {
            return wp_kses($heroTagline, $allowed_html); // Sanitize while keeping safe HTML
        }
    
        // Fallback to taxonomy description (instead of name)
        $termDescription = term_description($term->term_id, 'product_cat');
        if (!empty($termDescription)) {
            return wp_kses($termDescription, $allowed_html);
        }
    
        return ''; // Default empty if no description or custom field found
    }



    /**
     * Shortcode: [productDescriptionHeading]
     * Prioritization:
     *  1. `custom_h1_text` (if exists and not empty)
     *  2. `friendly_title` (if exists and not empty)
     *  3. Default: Post title
     */
    public static function productDescriptionHeading(): string {
        global $post;

        // Ensure we have a valid post object
        if (!$post || !isset($post->ID)) {
            return '';
        }

        $friendlyTitle = get_post_meta($post->ID, 'friendly_title', true);
        if (!empty($friendlyTitle)) {
            return esc_html($friendlyTitle);
        }

        // Fallback to post title
        return esc_html(get_the_title($post->ID));
    }




    /**
     * Shortcode: [elementor-flexible-content]
     * Displays an Elementor template by ID.
     */
    public static function elementorFlexibleContent_v1($attributes = []): string {
        if (empty($attributes['id'])) {
            return '';
        }

        $include_css = isset($attributes['css']) && 'false' !== $attributes['css'];

        return Plugin::elementor()->frontend->get_builder_content_for_display($attributes['id'], $include_css);
    }

    public static function elementorFlexibleContent($attributes = []): ?string {
        global $post;
    
        // Return early if meta_field_attribute is missing
        if (empty($attributes['meta_field_attribute'])) {
            return null;
        }
    
        $metaField = $attributes['meta_field_attribute'];
        $templateId = null;
    
        // // Retrieve the request URI for regex matching
        // $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    
        // // Define regex patterns for different types of pages
        // $patterns = [
        //     'single'     => '/\/\d{4}\/\d{2}\/[^\/]+\/?$/', // Example: /2023/09/post-title/
        //     'taxonomy'   => '/\/(category|tag|taxonomy)\/([^\/]+)\/?$/', // Example: /category/news/
        //     'archive'    => '/\/\d{4}\/?$/', // Example: /2023/
        //     'page'       => '/\/([^\/]+)\/?$/', // Example: /about-us/
        //     'homepage'   => '/^\/$/', // Root homepage
        // ];
    
        // // Determine request type based on regex
        // $matchedType = 'unknown';
        // foreach ($patterns as $type => $pattern) {
        //     if (preg_match($pattern, $requestUri)) {
        //         $matchedType = $type;
        //         break;
        //     }
        // }
    
        // error_log("Request Type Detected: " . $matchedType . " | URI: " . $requestUri);
    
        // Determine template ID based on the type of page
        if (is_singular()) {
            $templateId = get_post_meta($post->ID, $metaField, true);
        } elseif (is_tax() || is_category() || is_tag()) {
            $term = get_queried_object();
            if (!empty($term->term_id)) {
                $templateId = get_field($metaField, $term);
            }
        }
    
        // Ensure retrieved template ID is valid
        if (!is_numeric($templateId) || intval($templateId) <= 0) {
            return null;
        }
    
        // Determine whether to include CSS
        $include_css = !isset($attributes['css']) || $attributes['css'] !== 'false';
    
        return Plugin::elementor()->frontend->get_builder_content_for_display((int) $templateId, $include_css);
    }
    
    


    /**
     * Add admin column header for Elementor shortcodes.
     */
    public static function adminColumnsHeaders($defaults) {
        $defaults['shortcode'] = esc_html__('Shortcode', 'elementor-pro');
        return $defaults;
    }

    /**
     * Display the shortcode column content in admin.
     */
    public static function adminColumnsContent($column_name, $post_id) {
        if ('shortcode' !== $column_name) {
            return;
        }

        printf(
            '<label class="screen-reader-text" for="%1$s">%2$s</label>
            <input class="elementor-shortcode-input" type="text" id="%1$s" readonly onfocus="this.select()" value="%3$s" />',
            sprintf(esc_attr('elementor-template-%s-shortcode'), $post_id),
            sprintf(esc_html__('Elementor flexible content shortcode for template %s', 'elementor-pro'), $post_id),
            sprintf(esc_attr('[elementor-flexible-content id="%d"]'), $post_id)
        );
    }

    
    /**
     * 
     */
    public static function colorModeToggle(): string
    {
        ob_start(); ?>
        <button id="btn-toggle-color-mode" class="btn btn-outline-secondary">
            <i class="bi bi-sun light-icon"></i>
            <i class="bi bi-moon dark-icon"></i>
        </button>
        <?php
        return ob_get_clean();
    }


    /**
     * Renders a FacetWP dropdown with a customizable facet and button anchor.
     *
     * @param array $atts Attributes:
     *   - 'facet_display' (string) The FacetWP facet name (e.g., 'shop_by_price').
     *   - 'button_anchor' (string) The button text or HTML (e.g., Bootstrap icon).
     * @return string The generated HTML output.
     */
    public static function facetWPDropdown($atts = []): string
    {
        // Get the template part filename or fallback to empty (no output if invalid)
        $atts = shortcode_atts([
            'template_name' => '' // Default to nothing for performance
        ], $atts);

        if (empty($atts['template_name'])) {
            return ''; // Early exit if no template is provided
        }

        ob_start();
        get_template_part('template-parts/facet-wp/top-controls/' . sanitize_file_name($atts['template_name']));
        return ob_get_clean();
    }



}

