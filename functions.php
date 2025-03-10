<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */



if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once __DIR__ . '/vendor/autoload.php';

use BuildWp\WpChildTheme\Init;

// Initialize all the theme services
add_action('after_setup_theme', function () {
    Init::register_services();
});

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );


function ejstone_force_hierarchical_attributes($args, $taxonomy) {
    if (strpos($taxonomy, 'pa_') === 0) { // Ensures it only applies to WooCommerce attributes
        $args['hierarchical'] = true; // Enable hierarchy
        $args['rewrite'] = true; // Allow URL rewrites
        $args['show_admin_column'] = true; // Show in WP admin
    }
    return $args;
}
add_filter('woocommerce_taxonomy_args', 'ejstone_force_hierarchical_attributes', 10, 2);








function my_filtered_query( $query ) {
    if ( ! is_a( $query, 'WP_Query' ) ) {
        return;
    }

    // Set post types
    $query->set( 'post_type', [ 'product' ] );

    // Get the term ID dynamically (Example: 74)
    $term_id = 74; // Change dynamically based on your logic

    $tax_query = [
        [
            'taxonomy' => 'pa_jewelry-piece-type',
            'field'    => 'term_id',
            'terms'    => $term_id,
        ]
    ];

    $query->set( 'tax_query', $tax_query );
}

add_action( 'elementor/query/fuckface', 'my_filtered_query' );

function ejstone_render_wc_product_template() {
    ob_start();
    wc_get_template_part('content', 'product'); 
    return ob_get_clean();
}
add_shortcode('wc_content_product', 'ejstone_render_wc_product_template');



// function add_query_id_control_to_product_loop( $widget ) {
//     // Ensure this runs only on the correct widget
//     if ( 'woocommerce-products' !== $widget->get_name() ) {
//         return;
//     }

//     $widget->start_controls_section(
//         'section_query_id',
//         [
//             'label' => __( 'Custom Query ID', 'your-text-domain' ),
//             'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
//         ]
//     );

//     $widget->add_control(
//         'query_id',
//         [
//             'label'       => __( 'Query ID', 'your-text-domain' ),
//             'type'        => \Elementor\Controls_Manager::TEXT,
//             'placeholder' => __( 'Enter a custom Query ID', 'your-text-domain' ),
//         ]
//     );

//     $widget->end_controls_section();
// }
// add_action( 'elementor/element/woocommerce-products/section_query/after_section_end', 'add_query_id_control_to_product_loop', 10, 1 );

function add_custom_query_source_to_product_loop( $widget ) {
    if ( 'woocommerce-products' !== $widget->get_name() ) {
        return;
    }

    $widget->update_control(
        'query_post_type',
        [
            'options' => [
                'current_query'    => __( 'Current Query', 'your-text-domain' ),
                'product'          => __( 'Latest Products', 'your-text-domain' ),
                'sale'             => __( 'Sale', 'your-text-domain' ),
                'featured'         => __( 'Featured', 'your-text-domain' ),
                'by_id'            => __( 'Manual Selection', 'your-text-domain' ),
                'related_products' => __( 'Related Products', 'your-text-domain' ),
                'upsells'          => __( 'Upsells', 'your-text-domain' ),
                'cross_sells'      => __( 'Cross-Sells', 'your-text-domain' ),
                'custom_query'     => __( 'Custom Query', 'your-text-domain' ), 
            ]
        ]
    );

    // ✅ Add Custom Query ID Field
    $widget->start_controls_section(
        'section_custom_query',
        [
            'label' => __( 'Custom Query Settings', 'your-text-domain' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            'condition' => [
                'query_post_type' => 'custom_query',
            ],
        ]
    );

    $widget->add_control(
        'custom_query_id',
        [
            'label'       => __( 'Custom Query ID', 'your-text-domain' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __( 'Enter Custom Query ID', 'your-text-domain' ),
        ]
    );

    $widget->end_controls_section();
}
add_action( 'elementor/element/woocommerce-products/section_query/after_section_end', 'add_custom_query_source_to_product_loop', 10, 1 );

function apply_elementor_custom_query_to_product_loop( $query ) {
    if ( ! $query instanceof \WP_Query ) {
        return;
    }

    $settings = isset( $query->query_vars['settings'] ) ? $query->query_vars['settings'] : [];

    if ( empty( $settings['query_post_type'] ) || $settings['query_post_type'] !== 'custom_query' ) {
        return; // ❌ Query Type is NOT Custom
    }

    if ( empty( $settings['custom_query_id'] ) ) {
        error_log("[Elementor] ❌ No Custom Query ID found.");
        return;
    }

    $query_id = sanitize_text_field($settings['custom_query_id']);
    error_log("[Elementor] ✅ Using Custom Query ID: " . $query_id);

    // ✅ Dynamically Fetch Query Arguments
    $custom_query_args = apply_filters( 'elementor_custom_query_args', [], $query_id );

    if ( ! empty( $custom_query_args ) && is_array( $custom_query_args ) ) {
        foreach ( $custom_query_args as $key => $value ) {
            $query->set( $key, $value );
        }
        error_log("[Elementor] ✅ Custom Query Applied: " . print_r($custom_query_args, true));
    } else {
        error_log("[Elementor] ❌ No query args found for ID: " . $query_id);
    }
}
add_action( 'pre_get_posts', 'apply_elementor_custom_query_to_product_loop', 99 );



add_action('wp_footer', function () {
    echo '<p style="text-align: center; font-weight: bold; color: white; background: #c1f;">Testing shit!</p>';
});

