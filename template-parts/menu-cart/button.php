<?php

// wp-content/themes/ej-stone-co/template-parts/menu-cart/button.php

$cart_count = $args['cart_count'] ?? null;

?>


<div class="elementor-menu-cart__toggle elementor-button-wrapper">
    <button id="elementor-menu-cart__toggle_button" 
        class="elementor-menu-cart__toggle_button elementor-button elementor-size-sm"
        aria-expanded="false">
        <!-- <span class="elementor-button-text">
            <span class="woocommerce-Price-amount amount">
                <bdi>
                    <span class="woocommerce-Price-currencySymbol">$</span><?php echo WC()->cart->get_cart_total(); ?>
                </bdi>
            </span>
        </span> -->
        <span class="elementor-button-icon">
            <span class="elementor-button-icon-qty" data-counter="<?php echo esc_attr($cart_count); ?>">
                <?php echo esc_html($cart_count); ?>
            </span>
            <i class="bi bi-bag"></i>
            <span class="elementor-screen-only"><?php esc_html_e('Cart', 'elementor-custom-woo'); ?></span>
        </span>
    </button>
</div>
