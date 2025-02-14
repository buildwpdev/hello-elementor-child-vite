<?php

// wp-content/themes/ej-stone-co/src/CustomPostTypes/Video.php

namespace EjStoneCo\WpChildTheme\CustomPostTypes;

use EjStoneCo\WpChildTheme\Helpers\Registerable;

class Video implements Registerable {
    public function register(): void {
        add_action('init', [$this, 'register_cpt']);
    }

    public function register_cpt(): void {
        register_post_type('video', [
            'labels' => [
                'name' => __('Videos'),
                'singular_name' => __('Video'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => 'Videos'],
            'show_in_menu'       => false, 
        ]);
    }
}
