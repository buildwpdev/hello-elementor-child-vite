<?php

// wp-content/themes/build-wp/template-parts/menu-cart/tray.php

$cart_count = $args['cart_count'] ?? null;
$cart_subtotal = $args['cart_subtotal'] ?? null;

?>



        <div class="widget_shopping_cart_content">
    <?php if (!($cart_count > 0)): ?>
            <div class="woocommerce-mini-cart__empty-message">
                <?php esc_html_e('No products in the cart.', 'elementor-custom-woo'); ?>
            </div>
    <?php else:?>
            <div class="elementor-menu-cart__products woocommerce-mini-cart cart woocommerce-cart-form__contents">
                <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item): ?>
                    <?php
                        $product = $cart_item['data'];
                        $product_name = $product->get_name();
                        $product_price = WC()->cart->get_product_price($product);
                        $product_permalink = $product->get_permalink();
                    ?>
                    <!-- <li class="woocommerce-mini-cart-item">
                        <a href="<?php echo esc_url($product_permalink); ?>">
                            <?php echo esc_html($product_name); ?>
                        </a>
                        <span class="woocommerce-Price-amount amount">
                            <?php echo wp_kses_post($product_price); ?>
                        </span>
                    </li> -->
                    <?php 
                    get_template_part('template-parts/menu-cart/cart-item', null, [
                        'cart_item' => $cart_item,
                        'cart_item_key' => $cart_item_key,
                        'product' => $product,
                        'product_name' => $product_name,
                        'product_price' => $product_price,
                        'product_permalink' => $product_permalink, 

                    ]);
                    ?>
                <?php endforeach; ?>
            </div>

            <div class="elementor-menu-cart__subtotal">
                <strong><?php esc_html_e('Subtotal:', 'elementor-custom-woo'); ?></strong>
                <?php echo wp_kses_post($cart_subtotal); ?>
            </div>
            <div class="elementor-menu-cart__footer-buttons">
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="elementor-button elementor-button--view-cart elementor-size-md">
                    <span class="elementor-button-text">
                        <?php esc_html_e('View Cart', 'elementor-custom-woo'); ?>
                    </span>
                </a>
                <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="elementor-button elementor-button--checkout elementor-size-md">
                    <span class="elementor-button-text">
                        <?php esc_html_e('Checkout', 'elementor-custom-woo'); ?>
                    </span>
                </a>
            </div>
    <?php endif;?>
        </div>

