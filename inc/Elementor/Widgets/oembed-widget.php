<?php

// wp-content/themes/build-wp/wp/Elementor/Widgets/oembed-widget.php

namespace BuildWp\WpChildTheme\Elementor\Widgets;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class OEmbed_Widget extends \Elementor\Widget_Base {
    public function get_name(): string {
        return 'oembed';
    }

    public function get_title(): string {
        return esc_html__('oEmbed', 'elementor-oembed-widget');
    }

    public function get_icon(): string {
        return 'eicon-code';
    }

    public function get_categories(): array {
        return ['ej-stone'];
    }

    public function register_controls(): void {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'elementor-oembed-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => esc_html__('URL to embed', 'elementor-oembed-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__('https://your-link.com', 'elementor-oembed-widget'),
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void {
        $settings = $this->get_settings_for_display();

        if (empty($settings['url'])) {
            return;
        }

        $html = wp_oembed_get($settings['url']);
        echo '<div class="oembed-elementor-widget">' . ($html ?: esc_html($settings['url'])) . '</div>';
    }
}
