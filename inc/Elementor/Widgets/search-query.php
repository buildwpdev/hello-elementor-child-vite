<?php

// wp-content/themes/build-wp/wp/Elementor/Widgets/oembed-widget.php

namespace BuildWp\WpChildTheme\Elementor\Widgets;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Search_Query extends \Elementor\Widget_Base {


    /**
     * 
     */
    public function get_name(): string {
        return __('Search Query', 'build-wp-theme');
    }


    /**
     * 
     */
    public function get_title(): string {
        return esc_html__('Search Query', 'elementor-oembed-widget');
    }


    /**
     * 
     */
    public function get_icon(): string {
        return 'eicon-search';
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
        // $this->start_controls_section(
        //     'content_section',
        //     [
        //         'label' => __('Content', 'build-wp-theme'),
        //         'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        //     ]
        // );

        // $this->add_control(
        //     'button_id',
        //     [
        //         'label' => __('Button ID', 'build-wp-theme'),
        //         'type' => \Elementor\Controls_Manager::TEXT,
        //         'default' => '',
        //         'description' => __('Set a unique ID for the button (optional).', 'build-wp-theme'),
        //     ]
        // );

        // $this->add_control(
        //     'button_text',
        //     [
        //         'label' => __('Button Text', 'build-wp-theme'),
        //         'type' => \Elementor\Controls_Manager::TEXT,
        //         'default' => __('Toggle', 'build-wp-theme'),
        //     ]
        // );

        // $this->add_control(
        //     'toggle_state',
        //     [
        //         'label' => __('Default State', 'build-wp-theme'),
        //         'type' => \Elementor\Controls_Manager::SWITCHER,
        //         'label_on' => __('On', 'build-wp-theme'),
        //         'label_off' => __('Off', 'build-wp-theme'),
        //         'return_value' => 'on',
        //         'default' => 'off',
        //     ]
        // );

        // $this->add_control(
        //     'data_action',
        //     [
        //         'label' => __('Data Action Attribute', 'build-wp-theme'),
        //         'type' => \Elementor\Controls_Manager::TEXT,
        //         'default' => '',
        //         'description' => __('Specify a data-action attribute value.', 'build-wp-theme'),
        //     ]
        // );

        $this->end_controls_section();
    }


    /**
     * 
     */
    protected function render() {
        $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

        ?>
        <div class="flex search-query-widget">
            <?php if (!empty($search_query)) : ?>
                <label><?php echo esc_html__('Search results for:', 'build-wp-theme');?></label>
                <em>"<?php echo esc_html($search_query); ?>"</em>
            <?php else : ?>
                <p><?php echo esc_html__('No search query provided.', 'build-wp-theme'); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }



}
