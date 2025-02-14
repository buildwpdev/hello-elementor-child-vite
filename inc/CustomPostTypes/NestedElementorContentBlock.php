<?php

// wp-content/themes/ej-stone-co/wp/CustomPostTypes/NestedElementorContentBlock.php

namespace EjStoneCo\WpChildTheme\CustomPostTypes;

use EjStoneCo\WpChildTheme\Helpers\Registerable;

class NestedElementorContentBlock implements Registerable {
    public function register(): void {
        add_action('init', [$this, 'register_cpt']);
    }

    public function register_cpt(): void {
        register_post_type('necb', [
            'labels' => [
                'name' => __('Nested Elementor Content Blocks'),
                'singular_name' => __('Nested Elementor Content Block'),
            ],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true, 
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'rewrite' => ['slug' => 'Nested Elementor Content Blocks'],
            'show_in_menu'       => false, 
        ]);
    }
}
