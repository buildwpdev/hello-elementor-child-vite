<?php

// wp-content/themes/build-wp/wp/FacetWP/SearchSync.php

namespace BuildWp\WpChildTheme\InformationRetrieval\FacetWP;

class SearchSync {


    private static int $facetwp_search_page_id = 143; // Set this to your FacetWP-powered page ID


    /**
     * Register hooks for WooCommerce-FacetWP search sync
     */
    public static function register() {
        add_action('pre_get_posts', [self::class, 'allowSParamOnFacetWPPage']);
        add_action('template_redirect', [self::class, 'redirectWooCommerceSearch']);
        add_filter('facetwp_preload_url_vars', [self::class, 'setSearchFromQueryVar']);
        //add_filter('facetwp_facet_html', [self::class, 'overrideFacetHtml'], 10, 2); 
    }


    /**
     * Prevent WordPress from treating `?s=` as a native search query on FacetWP pages.
     *
     * @param WP_Query $query
     */
    public static function allowSParamOnFacetWPPage($query) {
        if (!is_admin() && $query->is_main_query() && isset($_GET['s']) && is_page()) {
            $query->is_search = false; // Prevent WordPress from thinking this is a search request
            $query->query_vars['s'] = ''; // Remove native search behavior
        }
    }


    /**
     * Redirect WooCommerce search (`?s=`) to the FacetWP-powered search page.
     */
    public static function redirectWooCommerceSearch() {
        if (is_search() && isset($_GET['post_type']) && $_GET['post_type'] === 'product') {
            $custom_search_url = get_permalink(self::$facetwp_search_page_id);

            if ($custom_search_url) {
                // Redirect WooCommerce search (?s=) to FacetWP's filter (?s=)
                wp_redirect(esc_url($custom_search_url . '?s=' . urlencode(get_search_query())));
                exit;
            }
        }
    }


    /**
     * Prepopulate FacetWP's search facet with the `?s=` query parameter.
     *
     * @param array $url_vars
     * @return array
     */
    public static function setSearchFromQueryVar($url_vars) {
        if (isset($_GET['s']) && !empty($_GET['s'])) {
            $url_vars['search_products'] = sanitize_text_field($_GET['s']);
        }
        return $url_vars;
    }


    /**
     * 
     */
    public static function overrideFacetHtml($output, $params) {
        if ('search_products' !== $params['facet']['name']) {
            return $output;
        }
    
        // Generate a unique cache key based on the facet output
        $cache_key = 'facetwp_search_html_' . md5($output);
        $cached_output = get_transient($cache_key);
    
        if ($cached_output) {
            return $cached_output;
        }
    
        // Run the expensive regex operation
        $pattern = '/<i[^>]*>.*?<\/i>/is';
        $new_icon = '<i class="bi bi-search"></i>';
        $output = preg_replace($pattern, $new_icon, $output);
    
        // Cache the result for 6 hours
        set_transient($cache_key, $output, 6 * HOUR_IN_SECONDS);
    
        return $output;
    }
    

}
