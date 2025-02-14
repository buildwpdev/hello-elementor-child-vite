<?php
defined( 'ABSPATH' ) || exit;

global $product;

if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
    return;
}

// Predefine variables to optimize function calls
$product_id       = $product->get_id();
$product_title    = get_the_title();
$product_permalink = get_permalink( $product_id );
$product_image    = get_the_post_thumbnail_url( $product_id, 'large' );
$product_price    = $product->get_price_html();
$product_rating   = wc_get_rating_html( $product->get_average_rating() );

?>
<li <?php wc_product_class( 'product-listing relative group', $product ); ?>>
    <div class="relative">
        <a href="<?php echo esc_url( $product_permalink ); ?>" class="product-listing-image">
            <img src="<?php echo esc_url( $product_image ); ?>" 
                 alt="<?php echo esc_attr( $product_title ); ?>" 
                 class="h-96 w-full object-cover transition-opacity duration-300 group-hover:opacity-75 sm:aspect-[2/3] sm:h-auto">
        </a>

        <!-- Overlay (Darkens on Hover, Content Appears) -->
        <div class="product-listing-overlay">
            <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-36 bg-gradient-to-t from-black opacity-50 transition-opacity duration-300 group-hover:opacity-75"></div>
            
            <!-- Hidden by default, appears on hover -->
            <div class="relative opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                <?php woocommerce_template_loop_add_to_cart(); ?>
            </div>
        </div>
    </div>

    <div class="relative mt-4">
        <a href="<?php echo esc_url( $product_permalink ); ?>">
            <h5 class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-gray-700 dark:hover:text-gray-300 transition">
                <?php echo esc_html( $product_title ); ?>
            </h5>
        </a>

        <p class="relative text-lg font-semibold text-gray-900 dark:text-gray-100">
            <?php echo $product_price; ?>
        </p>

        <div class="woocommerce-product-rating mt-1 text-sm text-gray-500 dark:text-gray-400">
            <?php echo $product_rating; ?>
        </div>
    </div>
</li>
