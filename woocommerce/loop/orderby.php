<?php

// wp-content/themes/ej-stone-co/woocommerce/loop/orderby.php

$catalog_orderby_options = apply_filters('woocommerce_catalog_orderby', array(
    'menu_order' => __( 'Default sorting', 'woocommerce' ),
    'popularity' => __( 'Popularity', 'woocommerce' ),
    'rating'     => __( 'Average rating', 'woocommerce' ),
    'date'       => __( 'Latest', 'woocommerce' ),
    'price'      => __( 'Price: low to high', 'woocommerce' ),
    'price-desc' => __( 'Price: high to low', 'woocommerce' ),
));
?>

<form class="woocommerce-ordering" method="get">
    <div class="tw-select relative w-48 sm:w-60 lg:w-72"
        data-options='<?php echo json_encode($catalog_orderby_options, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'
        data-selected="<?php echo esc_attr($orderby); ?>">

        <!-- Hidden Select Field -->
        <select name="orderby" class="sr-only">
            <?php foreach ($catalog_orderby_options as $id => $name) : ?>
                <option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>>
                    <?php echo esc_html($name); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Custom Tailwind Dropdown Button -->
        <button type="button" class="tw-select-button grid w-full cursor-pointer grid-cols-1 rounded-md bg-white py-1.5 pl-3 pr-2 text-left text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
            <span class="tw-select-selected col-start-1 row-start-1 truncate pr-6">Sort by</span>
            <i class="bi bi-chevron-down col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4"></i>
        </button>

        <!-- Empty Dropdown (Items will be inserted dynamically) -->
        <ul class="tw-select-dropdown absolute z-10 -mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm hidden"></ul>
    </div>

    <input type="hidden" name="paged" value="1" />
    <?php wc_query_string_form_fields(null, array('orderby', 'submit', 'paged', 'product-page')); ?>
</form>