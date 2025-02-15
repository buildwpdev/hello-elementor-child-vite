<?php

// wp-content/themes/build-wp/src/Init.php

namespace BuildWp\WpChildTheme;


class Init {
    public static function register_services() {
        $services = [

            // Cleanup
            //Setup\Cleanup::class, // <-- ADDED THIS

            // Theming
            Assets\Enqueue::class, 
            Assets\BreakpointsGenerator::class, 
            PostsAsTaxonomies\Pages::class,
            Shortcodes\Shortcodes::class, 

            // Admin Menus
            Admin\Menus::class, // <-- ADDED THIS


            // CustomPostTypes\Portfolio::class,
            // Taxonomies\Industry::class,
            CustomPostTypes\Glossary::class, 
            CustomPostTypes\Video::class, 
            CustomPostTypes\NestedElementorContentBlock::class, 
            CustomPostTypes\TimelineItem::class, 

            // Filtering
            InformationRetrieval\FacetWP\SearchSync::class,
            InformationRetrieval\AdvancedWooSearch\AdvancedWooSearch::class,

            // // WooCommerce
            // Elementor
            Elementor\TemplateConditions\RegisterConditions::class,
            Elementor\Widgets\Loader::class, 



        ];


        // ✅ Only register WooCommerce services if WooCommerce is active
        if (class_exists('WooCommerce')) {
            error_log("✅ WooCommerce is active. Registering WooCommerce services...");
            
            $services = array_merge($services, [
                WooCommerce\Breadcrumbs::class,
                WooCommerce\DismissibleMessages::class,
            ]);
        } else {
            error_log("❌ WooCommerce is NOT active. Skipping WooCommerce services.");
        }


        //error_log('🔍 Init.php - Registered Services: ' . print_r($services, true));

        foreach ($services as $class) {
            //error_log('🔎 Checking class: ' . $class);
            if (!class_exists($class)) {
                //error_log('❌ MISSING CLASS: ' . $class);
                continue;
            } 
            // else {
            //     error_log('✅ Class exists: ' . $class);
            // }
            (new $class())->register();
        }
        
    }
}