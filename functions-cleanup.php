<?php


remove_filter('the_content', 'wpautop'); // Disables auto-paragraph formatting
remove_action('wp_head', 'wp_generator'); // Removes WP version meta
remove_action('wp_head', 'wlwmanifest_link'); // Windows Live Writer
remove_action('wp_head', 'rsd_link'); // Really Simple Discovery link
remove_action('wp_head', 'feed_links_extra', 3); // Extra feeds
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0); // Shortlink

delete_transient('custom_woocommerce_orderby_options');


add_filter('woocommerce_catalog_orderby', function ($orderby) {
    // Check for cached version
    $cached_orderby = get_transient('custom_woocommerce_orderby_options');
    if ($cached_orderby !== false) {
        return $cached_orderby;
    }

    // Remove "Sort by" from labels
    $modified_orderby = array_map(function ($label) {
        return ucwords(preg_replace('/^Sort by\s+/i', '', $label));
    }, $orderby);

    // Cache for 24 hours
    set_transient('custom_woocommerce_orderby_options', $modified_orderby, DAY_IN_SECONDS);

    return $modified_orderby;
});






// add_action('wp_enqueue_scripts', function () {
//     global $wp_styles;

//     $loaded_css = [];
//     foreach ($wp_styles->queue as $handle) {
//         $loaded_css[] = $handle . ' → ' . wp_styles()->registered[$handle]->src;
//     }

//     error_log("Loaded CSS Files:\n" . implode("\n", $loaded_css));
// }, 100);



// add_filter( 'aws_searchbox_markup', 'my_aws_searchbox_markup', 10, 2 );
// function my_aws_searchbox_markup( $markup, $params ) {
//     $pattern = '/<svg[^>]*>.*?<\/svg>/is'; // Match <svg> with any attributes and contents
//     $new_icon = '<i class="bi bi-search"></i>';
//     $markup = preg_replace( $pattern, $new_icon, $markup );
//     return $markup;
// }

// add_filter( 'facetwp_facet_html', function( $output, $params ) {
//     if ( 'search_products' === $params['facet']['name'] ) {
//         $pattern = '/<i[^>]*>.*?<\/i>/is';
//         $new_icon = '<i class="bi bi-search"></i>';
//         $output = preg_replace( $pattern, $new_icon, $output );
//     }
//     return $output;
// }, 10, 2 );




// add_filter('facetwp_preload_filter', function($params) {
//     if (is_product_category()) {
//         $category = get_queried_object();
//         if ($category) {
//             $params['fwp_product_cat'] = [$category->slug]; // Adjust 'fwp_product_cat' to your facet name
//         }
//     }
//     return $params;
// });



// /**
//  * 
//  */
// function register_custom_elementor_widgets( $widgets_manager ) {
//     require_once get_stylesheet_directory() . '/elementor-widgets/toggle-button.php';
//     $widgets_manager->register( new \Elementor_Toggle_Button_Widget() );

//     // require_once get_stylesheet_directory() . '/elementor-widgets/flexible-block-widget.php';
//     // $widgets_manager->register( new \Elementor_Relational_Content_Widget() );

// }
// add_action( 'elementor/widgets/register', 'register_custom_elementor_widgets' );


// function enable_elementor_for_cpt($post_types) {
//     $post_types[] = 'shit';
//     return $post_types;
// }
// add_filter('elementor/cpt_support', 'enable_elementor_for_cpt');






// /**
//  * 
//  */
// function add_dismiss_button_to_notices($message) {
//     $dismiss_button = '<button class="woocommerce-message-dismiss" aria-label="Dismiss">×</button>';
//     return '<div class="woocommerce-notice-content">' . $message . '</div>' . $dismiss_button;
// }

// // Apply the filter correctly
// add_filter('woocommerce_add_message', 'add_dismiss_button_to_notices', 10, 1);
// add_filter('woocommerce_add_error', 'add_dismiss_button_to_notices', 10, 1);
// add_filter('woocommerce_add_notice', 'add_dismiss_button_to_notices', 10, 1);




// // add_action('acf/update_value/name=product_parent', 'update_post_parent_from_acf', 10, 3);

// // function update_post_parent_from_acf($value, $post_id, $field) {
// //     // Ensure we're working with the 'product' post type
// //     $post_type = get_post_type($post_id);
// //     if ($post_type !== 'product') {
// //         return $value;
// //     }

// //     // Update the post_parent field
// //     $parent_id = intval($value); // ACF returns the parent product ID
// //     wp_update_post([
// //         'ID' => $post_id,
// //         'post_parent' => $parent_id,
// //     ]);

// //     return $value;
// // }









// add_action('elementor/theme/get_css_file', function ($css_file) {
//     $global_colors = [
//         '--e-global-color-primary' => '#ff0000',  // Red
//         '--e-global-color-secondary' => '#00ff00', // Green
//         '--e-global-color-text' => '#333333', // Dark Gray
//         '--e-global-color-accent' => '#0000ff'  // Blue
//     ];

//     foreach ($global_colors as $var => $color) {
//         $css_file->get_stylesheet()->add_ruleset(':root', [
//             $var => $color
//         ]);
//     }
// });

/*
// add_filter('elementor_pro/theme_builder/header/print_template', function ($content, $element) {
//     if (!isset($element['widgetType']) || $element['widgetType'] !== 'woocommerce-menu-cart') {
//         return $content;
//     }

//     ob_start();
//     ?>
//     <div class="custom-menu-cart">
//         <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="custom-cart-button">
//             <span class="cart-icon">
//                 🛒
//             </span>
//             <span class="cart-count">
//                 <?php echo WC()->cart->get_cart_contents_count(); ?>
//             </span>
//         </a>
//     </div>
//     <?php
//     return ob_get_clean();
// }, 10, 2);
*/



// /**
//  * 
//  */
// function override_elementor_woocommerce_module() {
//     if (!class_exists('\ElementorPro\Modules\Woocommerce\Module')) {
//         return;
//     }

//     require_once get_stylesheet_directory() . '/inc/custom-elementor-woocommerce.php';

//     // Ensure the module is removed
//     add_action('elementor/init', function () {
//         remove_action('elementor/init', [\ElementorPro\Modules\Woocommerce\Module::class, '__construct']);
//         new \CustomElementor\Modules\Woocommerce\Custom_WooCommerce_Module();
//     }, 1);

//     // error_log('✅ Custom WooCommerce Module is replacing Elementor’s default.');
// }
// add_action('elementor_pro/init', 'override_elementor_woocommerce_module', 5);




// /**
//  * 
//  */
// /**
//  * Override Elementor's render_menu_cart with the custom module.
//  */
// function custom_override_menu_cart_buttons( $content, $widget ) {
//     // Check if the widget is the WooCommerce Menu Cart
//     if ( 'woocommerce-menu-cart' === $widget->get_name() ) {

//         // Start output buffering
//         ob_start();

//         // ✅ Call the Custom WooCommerce Module
//         \CustomElementor\Modules\Woocommerce\Custom_WooCommerce_Module::render_menu_cart([]);

//         // Get the output
//         $content = ob_get_clean();

//     }

//     return $content;
// }
// add_filter( 'elementor/widget/render_content', 'custom_override_menu_cart_buttons', 10, 2 );




// // Ensure WooCommerce AJAX cart fragments work
// add_action('wp_ajax_nopriv_woocommerce_get_refreshed_fragments', 'woocommerce_get_refreshed_fragments');
// add_action('wp_ajax_woocommerce_get_refreshed_fragments', 'woocommerce_get_refreshed_fragments');

// /**
//  * Ensure WooCommerce AJAX fragments return data
//  */
// function woocommerce_get_refreshed_fragments() {
//     if (!WC()->cart) {
//         wp_send_json_error(['message' => 'WooCommerce cart not initialized.']);
//         return;
//     }

//     ob_start();
//     woocommerce_mini_cart();
//     $mini_cart = ob_get_clean();

//     wp_send_json_success([
//         'fragments' => [
//             '.widget_shopping_cart_content' => $mini_cart
//         ],
//         'cart_hash' => WC()->cart->get_cart_hash()
//     ]);
// }


// add_filter('elementor/frontend/page_title', '__return_empty_string');


// function allow_webp_uploads( $mimes ) {
//     // Add WebP support
//     $mimes['webp'] = 'image/webp';
//     return $mimes;
// }
// add_filter( 'upload_mimes', 'allow_webp_uploads' );




add_filter('query_vars', function ($vars) {
    $vars[] = 'pa_stone'; // Register 'stone' as a query var
    return $vars;
});


add_filter('woocommerce_product_query_tax_query', function ($tax_query, $query) {
    if (!is_admin() && is_tax('opal')) {
        $tax_query[] = [
            'taxonomy' => 'opal',
            'field'    => 'slug',
            'terms'    => get_query_var('opal'), // Fetch the current taxonomy slug
        ];
    }
    return $tax_query;
}, 10, 2);


add_filter('facetwp_query_args', function ($args, $class) {
    if (is_tax('opal')) {
        $args['tax_query'][] = [
            'taxonomy' => 'opal',
            'field'    => 'slug',
            'terms'    => get_queried_object()->slug,
        ];
    }
    return $args;
}, 10, 2);


add_filter('facetwp_facet_filter_posts', function ($post_ids, $facet) {
    if ('stone' === $facet['name'] && is_tax('opal')) {
        $meta_query = [
            [
                'key'     => 'attribute_pa_stone',
                'value'   => get_query_var('pa_stone'),
                'compare' => 'LIKE',
            ],
        ];

        $query = new WP_Query([
            'post_type'  => 'product',
            'post__in'   => $post_ids,
            'meta_query' => $meta_query,
        ]);

        return wp_list_pluck($query->posts, 'ID');
    }

    return $post_ids;
}, 10, 2);
