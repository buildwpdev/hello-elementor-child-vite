<?php

// wp-content/themes/build-wp/wp/Elementor/Widgets/oembed-widget.php

namespace BuildWp\WpChildTheme\Elementor\Widgets;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Toggle_Button extends \Elementor\Widget_Base {


    /**
     * 
     */
    public function get_name(): string {
        return __('Toggle Button', 'build-wp-theme');
    }


    /**
     * 
     */
    public function get_title(): string {
        return esc_html__('Toggle Button', 'elementor-oembed-widget');
    }


    /**
     * 
     */
    public function get_icon(): string {
        return 'eicon-button';
    }


    /**
     * 
     */
    public function get_categories(): array {
        return ['ej-stone'];
    }


    /**
     * 
     */
    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'build-wp-theme'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_id',
            [
                'label' => __('Button ID', 'build-wp-theme'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'description' => __('Set a unique ID for the button (optional).', 'build-wp-theme'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'build-wp-theme'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Toggle', 'build-wp-theme'),
            ]
        );

        $this->add_control(
            'toggle_state',
            [
                'label' => __('Default State', 'build-wp-theme'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'build-wp-theme'),
                'label_off' => __('Off', 'build-wp-theme'),
                'return_value' => 'on',
                'default' => 'off',
            ]
        );

        $this->add_control(
            'data_action',
            [
                'label' => __('Data Action Attribute', 'build-wp-theme'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'description' => __('Specify a data-action attribute value.', 'build-wp-theme'),
            ]
        );

        $this->end_controls_section();
    }


    /**
     * 
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $toggle_state = $settings['toggle_state'] === 'on' ? 'active' : '';
        $data_action = !empty($settings['data_action']) ? 'data-action="' . esc_attr($settings['data_action']) . '"' : '';
        $button_id = !empty($settings['button_id']) ? 'id="' . esc_attr($settings['button_id']) . '"' : '';
        ?>
        <button <?php echo $button_id; ?> class="toggle-button <?php echo esc_attr($toggle_state); ?>" data-toggle <?php echo $data_action; ?>>
            <i class="bi bi-list"></i>
        </button>
        <?php
    }


}
