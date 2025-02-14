<?php

// wp-content/themes/ej-stone-co/wp/FacetWP/SearchSync.php

namespace EjStoneCo\WpChildTheme\InformationRetrieval\AdvancedWooSearch;

class AdvancedWooSearch {

    /**
     * Register the hook for overriding the AWS searchbox markup
     */
    public static function register() {
        add_filter('aws_searchbox_markup', [self::class, 'overrideSearchbarIcon'], 10, 2); 
    }

    /**
     * Override the search bar's SVG icon with a Bootstrap icon
     * 
     * @param string $markup  The original search box markup.
     * @param array  $params  Parameters passed by Advanced Woo Search.
     * @return string  Modified search box markup.
     */
    public static function overrideSearchbarIcon($markup, $params) {
        $cache_key = 'aws_searchbox_markup_' . md5($markup); // Generate a unique cache key
        $cached_markup = get_transient($cache_key);

        if ($cached_markup) {
            return $cached_markup;
        }

        // Run expensive regex replacement
        $pattern = '/<svg[^>]*>.*?<\/svg>/is'; // Match <svg> with any attributes and contents
        $new_icon = '<i class="bi bi-search"></i>';
        $markup = preg_replace($pattern, $new_icon, $markup);

        // Cache the result for 6 hours
        set_transient($cache_key, $markup, 6 * HOUR_IN_SECONDS);

        return $markup;
    }
}