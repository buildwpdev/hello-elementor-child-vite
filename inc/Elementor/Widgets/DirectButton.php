<?php

// wp-content/themes/buildwpdev/wp/Elementor/Widgets/DirectButton.php

namespace BuildWp\WpChildTheme\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class DirectButton extends Widget_Base {
    /**
     * Get widget name.
     */
    public function get_name() {
        return 'direct_button';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return __('Direct Button', 'buildwpdev');
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-button';
    }

    /**
     * Get widget categories.
     */
    public function get_categories() {
        return ['basic'];
    }

    /**
     * Register controls for the widget.
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__('Button', 'elementor'),
            ]
        );

        $this->add_control(
            'button_type',
            [
                'label' => esc_html__('Button Type', 'elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'a' => esc_html__('Link (a)', 'elementor'),
                    'button' => esc_html__('Button (button)', 'elementor'),
                ],
                'default' => 'button',
            ]
        );

        $this->add_control(
            'button_anchor_text',
            [
                'label' => esc_html__('Button Anchor Text', 'elementor'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Click Here', 'elementor'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'button_attr_id',
            [
                'label' => esc_html__('Button ID Attribute', 'elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'description' => esc_html__('Optional ID for the button', 'elementor'),
            ]
        );

        $this->add_control(
            'button_attr_class',
            [
                'label' => esc_html__('Button Class Attribute', 'elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'description' => esc_html__('Add additional classes to the button', 'elementor'),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $settings = $this->get_settings_for_display();
        $this->direct_render_button($settings);

    }

    /**
     * Custom button rendering to apply ID, class, popup trigger, and switch between <a> and <button>.
     */
    protected function direct_render_button($settings) {
        $button_type = $settings['button_type'];
        $button_anchor_text = isset($settings['button_anchor_text']) && trim($settings['button_anchor_text']) !== ''
        ? $this->sanitize_button_html(force_balance_tags(html_entity_decode(wp_kses_post($settings['button_anchor_text']))))
        : '';
    

    if (!empty($settings['button_attr_id'])) {
        $this->add_render_attribute('button', 'id', esc_attr($settings['button_attr_id']));
    }

    if (!empty($settings['button_attr_class'])) {
        $this->add_render_attribute('button', 'class', esc_attr($settings['button_attr_class']));
    }
        ?>
        <button <?php echo($this->get_render_attribute_string('button')) ?>>
            <?php echo($button_anchor_text);?>
        </button>
        <?php
    }

    private function sanitize_button_html($html) {
        $allowed_tags = [
            'span' => [
                'class' => true,
                'style' => true,
            ],
            'strong' => [],
            'em' => [],
            'br' => [],
            'b' => [],
            'i' => [
                'data-*' => true, // Allows any data-* attribute
            ],
            'u' => [],
            'svg' => [
                'xmlns' => true, 'width' => true, 'height' => true, 'viewBox' => true, 
                'fill' => true, 'stroke' => true, 'stroke-width' => true, 
                'stroke-linecap' => true, 'stroke-linejoin' => true, 'class' => true
            ],
            'path' => ['d' => true],
        ];
        
        return wp_kses($html, $allowed_tags);
    }
    

}