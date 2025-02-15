<?php

// wp-content/themes/buildwpdev/wp/Elementor/Widgets/SvgImage.php

namespace BuildWp\WpChildTheme\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use WP_Filesystem_Direct;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SvgImage extends Widget_Base {
    /**
     * Get widget name.
     */
    public function get_name() {
        return 'svg_image';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return __('SVG Image', 'buildwpdev');
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-image';
    }

    /**
     * Get widget categories.
     */
    public function get_categories() {
        return ['general'];
    }

    /**
     * Register controls for the widget.
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'buildwpdev'),
            ]
        );

        $this->add_control(
            'svg_code',
            [
                'label' => __('SVG Code', 'buildwpdev'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '<svg width="100" height="100"><circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red" /></svg>',
                'description' => __('Paste your SVG code here.', 'buildwpdev'),
            ]
        );

        $this->add_control(
            'svg_media',
            [
                'label' => __('Select SVG from Media Library', 'buildwpdev'),
                'type' => Controls_Manager::MEDIA,
                'media_types' => ['image/svg+xml'],
                'description' => __('Choose an SVG file from the media library. The actual SVG code will be inserted instead of an <img> tag.', 'buildwpdev'),
            ]
        );

        $this->add_control(
            'svg_class',
            [
                'label' => __('CSS Class', 'buildwpdev'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'description' => __('Add a CSS class to the SVG element.', 'buildwpdev'),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $svg_code = $settings['svg_code'] ?: '';
        $svg_class = !empty($settings['svg_class']) ? 'class="' . esc_attr($settings['svg_class']) . '"' : '';
        
        if (!empty($settings['svg_media']['url'])) {
            $svg_path = get_attached_file($settings['svg_media']['id']);
            if (file_exists($svg_path)) {
                $svg_code = file_get_contents($svg_path);
            }
        }
        
        // Ensure SVG has a valid wrapper for security
        echo '<div ' . $svg_class . '>' . $svg_code . '</div>';
    }
}