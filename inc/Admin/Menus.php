<?php

// web/app/themes/ej-stone-co/wp/Admin/Menus.php

namespace EjStoneCo\WpChildTheme\Admin;

use EjStoneCo\WpChildTheme\Helpers\Registerable;

class Menus implements Registerable {
    public function register(): void {
        add_action('admin_menu', [$this, 'add_menus']);
    }

    public function add_menus(): void {
        // Define CPTs for the submenu
        $cpts = [
            'glossary' => 'Glossary',
            'video' => 'Videos',
            'necb' => 'Elementor Blocks',
            'timeline_item' => 'Timeline Items',
        ];

        // Create the parent menu (does NOT create a page)
        add_menu_page(
            __('Theme CPTs', 'text-domain'),  // Menu title
            __('Theme CPTs', 'text-domain'),  // Page title
            'manage_options',                 // Capability
            'edit.php?post_type=' . array_key_first($cpts), // Redirects to first CPT
            '',
            'dashicons-admin-page',           // Icon
            -9999                                // Position
        );

        // Add submenus for each CPT
        foreach ($cpts as $post_type => $label) {
            add_submenu_page(
                'edit.php?post_type=' . array_key_first($cpts), // Parent menu without dummy page
                __($label, 'text-domain'),                      // Page title
                __($label, 'text-domain'),                      // Menu title
                'manage_options',                               // Capability
                "edit.php?post_type={$post_type}"              // Target CPT admin page
            );
        }
    }
}
