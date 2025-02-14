<?php

// wp-content/themes/build-wp/wp/WooCommerce/DismissableMessages.php

namespace BuildWp\WpChildTheme\WooCommerce;

class DismissibleMessages {
    /**
     * Registers the filters to modify WooCommerce notices.
     */
    public static function register() {
        add_filter('woocommerce_add_message', [self::class, 'addDismissButton'], 10, 1);
        add_filter('woocommerce_add_error', [self::class, 'addDismissButton'], 10, 1);
        add_filter('woocommerce_add_notice', [self::class, 'addDismissButton'], 10, 1);
    }

    /**
     * Adds a dismiss button to WooCommerce notices.
     *
     * @param string $message The original notice message.
     * @return string Modified notice with a dismiss button.
     */
    public static function addDismissButton(string $message): string {
        $dismissButton = '<button class="woocommerce-message-dismiss" aria-label="Dismiss">×</button>';
        return '<div class="woocommerce-notice-content">' . $message . '</div>' . $dismissButton;
    }
}
