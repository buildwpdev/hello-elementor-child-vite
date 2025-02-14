<?php

namespace BuildWp\WpChildTheme\WooCommerce;

class Breadcrumbs {
    /**
     * ID of the fallback page if no archive page was visited.
     */
    private static int $fallbackPageId = 123; // Set your preferred fallback page ID

    /**
     * Registers WooCommerce breadcrumb customization and tracking hooks.
     */
    public static function register() {
        add_filter('woocommerce_get_breadcrumb', [self::class, 'customizeBreadcrumb'], 20, 2);
        add_action('wp', [self::class, 'trackLastArchivePage']); // Hook to track archive visits
    }

    /**
     * Customizes WooCommerce breadcrumbs on single product pages.
     *
     * @param array $crumbs The original breadcrumb array.
     * @param object $breadcrumb The WooCommerce breadcrumb object.
     * @return array Modified breadcrumbs.
     */
    public static function customizeBreadcrumb(array $crumbs, $breadcrumb): array {
        if (!is_product()) {
            return $crumbs;
        }

        self::log('Original Crumbs', $crumbs);

        global $post;

        // Initialize new breadcrumb array with the first item (Home)
        $newCrumbs[] = reset($crumbs);

        // Determine the second breadcrumb item
        $archiveUrl = self::getLastVisitedArchivePage();
        if ($archiveUrl) {
            self::log('Using archive page URL', $archiveUrl);
            $newCrumbs[] = ['Product Archive', esc_url($archiveUrl)];
        } else {
            $fallbackPageTitle = get_the_title(self::$fallbackPageId);
            $fallbackPageUrl = get_permalink(self::$fallbackPageId);
            self::log('Using fallback page', ['title' => $fallbackPageTitle, 'url' => $fallbackPageUrl]);

            if ($fallbackPageTitle && $fallbackPageUrl) {
                $newCrumbs[] = [esc_html($fallbackPageTitle), esc_url($fallbackPageUrl)];
            }
        }

        // Check if the product has a parent product
        if ($post->post_parent) {
            $postParent = get_post($post->post_parent);

            if ($postParent) {
                self::log('$post_parent', $postParent);
                $newCrumbs[] = [
                    esc_html($postParent->post_title),
                    esc_url(get_permalink($post->post_parent))
                ];
            }
        }

        // Add the last element (current product)
        $newCrumbs[] = end($crumbs);

        self::log('Modified Crumbs', $newCrumbs);

        return $newCrumbs;
    }

    /**
     * Tracks the last visited product archive page based on the referrer.
     */
    public static function trackLastArchivePage() {
        if (is_product()) {
            return; // Don't track if we're already on a product page
        }

        if (is_shop() || is_product_category() || is_product_tag()) {
            $currentUrl = esc_url(home_url($_SERVER['REQUEST_URI']));

            if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
                error_log("Tracking Archive Visit: " . $currentUrl);
            }

            update_option('ej_last_product_archive_url', $currentUrl);
        }
    }

    /**
     * Retrieves the last visited archive page URL if available.
     *
     * @return string|null The archive page URL or null if none found.
     */
    private static function getLastVisitedArchivePage(): ?string {
        return get_option('ej_last_product_archive_url', null);
    }

    /**
     * Logs messages to the debug log if WP_DEBUG_LOG is enabled.
     *
     * @param string $label Descriptive label for the log entry.
     * @param mixed $data Data to log.
     */
    private static function log(string $label, $data) {
        if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
            error_log($label . ': ' . print_r($data, true));
        }
    }
}
