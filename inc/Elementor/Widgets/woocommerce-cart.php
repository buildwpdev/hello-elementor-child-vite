<?php

namespace BuildWp\WpChildTheme\Elementor\Widgets;

use ElementorPro\Modules\Woocommerce\Widgets\Menu_Cart;
use ElementorPro\Modules\Woocommerce\Module;
use ElementorPro\Plugin;
use WC;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Ensure Elementor Pro and the required WooCommerce class are loaded
if (!class_exists('ElementorPro\Modules\Woocommerce\Widgets\Menu_Cart')) {
    return;
}

class WooCommerce_Menu_Cart extends Menu_Cart {

    public function get_name(): string {
        return 'woocommerce-cart-widget';
    }

    public function get_title(): string {
        return esc_html__('WooCommerce Menu Cart (Custom)', 'elementor-custom-woo');
    }

    public function get_icon(): string {
        return 'eicon-cart-medium';
    }

    public function get_categories(): array {
        return ['ej-stone']; // Assign to your custom category
    }

    /**
     * Override render method to customize cart output
     */
    protected function render(): void {
        if (!class_exists('WooCommerce')) {
            echo '<p>' . esc_html__('WooCommerce is not active.', 'elementor-custom-woo') . '</p>';
            return;
        }

        $settings = $this->get_settings_for_display();
        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_subtotal = WC()->cart->get_cart_subtotal();

        $data_settings = json_encode([
            "automatically_open_cart" => "yes",
            "cart_type" => "side-cart",
            "open_cart" => "click"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        ?>
        <div class="elementor-element elementor-element-449f212 toggle-icon--cart-medium 
            elementor-menu-cart--items-indicator-bubble 
            elementor-menu-cart--show-subtotal-yes 
            elementor-menu-cart--cart-type-side-cart 
            elementor-menu-cart--show-remove-button-yes 
            elementor-widget elementor-widget-woocommerce-menu-cart"
            data-id="449f212" data-element_type="widget"
            data-settings='<?php echo esc_attr($data_settings); ?>'
            data-widget_type="woocommerce-menu-cart.default">

            <div class="elementor-widget-container">
                <div class="elementor-menu-cart__wrapper">
                    <div class="elementor-menu-cart__toggle_wrapper">
                        <div class="elementor-menu-cart__container elementor-lightbox" aria-hidden="true">
                            <div class="elementor-menu-cart__main" aria-hidden="false">
                                <div class="elementor-menu-cart__close-button"></div>
                                <div 
                                    id="menu-cart-tray-wrapper"
                                    data-wc_cart_count="<?php echo($cart_count); ?>"
                                    data-wc_cart_subtotal="<?php echo($cart_count);?>"
                                >

                                <div class="mb-4 max-w-sm w-full mx-auto">
                                        <div class="animate-pulse flex space-x-4">
                                            <div class="bg-slate-200 h-14 w-14"></div>
                                            <div class="flex-1 space-y-6 py-1">
                                                <div class="h-2 bg-slate-200 rounded"></div>
                                                <div class="space-y-3">
                                                    <div class="grid grid-cols-3 gap-4">
                                                        <div class="h-2 bg-slate-200 rounded col-span-2"></div>
                                                        <div class="h-2 bg-slate-200 rounded col-span-1"></div>
                                                    </div>
                                                    <div class="h-2 bg-slate-200 rounded"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 max-w-sm w-full mx-auto">
                                        <div class="animate-pulse flex space-x-4">
                                            <div class="bg-slate-200 h-14 w-14"></div>
                                            <div class="flex-1 space-y-6 py-1">
                                                <div class="h-2 bg-slate-200 rounded"></div>
                                                <div class="space-y-3">
                                                    <div class="grid grid-cols-3 gap-4">
                                                        <div class="h-2 bg-slate-200 rounded col-span-2"></div>
                                                        <div class="h-2 bg-slate-200 rounded col-span-1"></div>
                                                    </div>
                                                    <div class="h-2 bg-slate-200 rounded"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php 
                                    get_template_part('template-parts/utilities/spinner', null, [
                                        'height' => 14,
                                        'width' => 14,
                                    ]);
                                ?>


                                
                                    <?php if($cart_count):?>

                   
                                    <?php else:?>
                                        <?php

                                        // wp-content/themes/build-wp/template-parts/utilities/spinner.php

                                        $height = $args['height'] ?? 12;
                                        $width = $args['width'] ?? 12;

                                        ?>

                                        <div class="flex items-center justify-center">
                                            <div
                                            class="inline-block h-<?php echo($height);?> w-<?php echo($width);?> animate-spin rounded-full border-4 border-solid border-current border-e-transparent align-[-0.125em] text-surface motion-reduce:animate-[spin_1.5s_linear_infinite] dark:text-white"
                                            role="status">
                                            <span
                                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]"
                                                >Loading...</span
                                            >
                                            </div>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                        <?php 
                         get_template_part('template-parts/menu-cart/button', null, [
                            'cart_count' => $cart_count,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
