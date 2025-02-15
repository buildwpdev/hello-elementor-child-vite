<?php

// wp-content/themes/buildwpdev/wp/Elementor/Widgets/ColorModeToggle.php

namespace BuildWp\WpChildTheme\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ColorModeToggle extends Widget_Base {
    /**
     * Get widget name.
     */
    public function get_name() {
        return 'color_mode_toggle';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return __('Color Mode Toggle', 'buildwpdev');
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-toggle';
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
            'light_icon_html',
            [
                'label' => __('Light Mode Icon HTML', 'buildwpdev'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '<i class="bi bi-sun light-icon"></i>',
                'description' => __('Enter the full HTML for the light mode icon.', 'buildwpdev'),
            ]
        );

        $this->add_control(
            'dark_icon_html',
            [
                'label' => __('Dark Mode Icon HTML', 'buildwpdev'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '<i class="bi bi-moon dark-icon"></i>',
                'description' => __('Enter the full HTML for the dark mode icon.', 'buildwpdev'),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $light_icon_html = $settings['light_icon_html'] ?: '<i class="bi bi-sun light-icon"></i>';
        $dark_icon_html = $settings['dark_icon_html'] ?: '<i class="bi bi-moon dark-icon"></i>';
        ?>
        <button id="btn-toggle-color-mode" class="text-color-mode bg-transparent">
            <span class="light-icon"><?php echo $light_icon_html; ?></span>
            <span class="dark-icon"><?php echo $dark_icon_html; ?></span>
        </button>
        <?php
    }
}