<?php

// 

$cart_item_key = $args['cart_item_key'];
$cart_item = $args['cart_item'];
$_product = $args['product'];
$product_permalink = $args['product_permalink'];
$product_name = $args['product_name'];
$product_price = $args['product_price'];

/**
 * wp-content/plugins/elementor-pro/modules/woocommerce/wc-templates/cart/mini-cart.php
 * See:  elementor_pro_render_mini_cart_item
 */

?>



<div class="elementor-menu-cart__product woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

    <div class="elementor-menu-cart__product-image product-thumbnail">
        <?php
        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

        if ( ! $product_permalink ) :
            echo wp_kses_post( $thumbnail );
        else :
            printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
        endif;
        ?>
    </div>

    <div class="elementor-menu-cart__product-name product-name" data-title="<?php echo esc_attr__( 'Product', 'elementor-pro' ); ?>">
        <?php
        if ( ! $product_permalink ) :
            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
        else :
            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
        endif;

        do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

        // Meta data.
        echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
    </div>

    <div class="elementor-menu-cart__product-price product-price" data-title="<?php echo esc_attr__( 'Price', 'elementor-pro' ); ?>">
        <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '<span class="product-quantity">%s &times;</span> %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>

    <div class="elementor-menu-cart__product-remove product-remove">
        <?php foreach ( [ 'elementor_remove_from_cart_button', 'remove_from_cart_button' ] as $class ) {
            echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                '<a href="%s" class="%s" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"></a>',
                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                $class,
                __( 'Remove this item', 'elementor-pro' ),
                esc_attr( $product_id ),
                esc_attr( $cart_item_key ),
                esc_attr( $_product->get_sku() )
            ), $cart_item_key );
        } ?>
    </div>
</div>

