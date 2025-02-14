<?php

// wp-content/themes/ej-stone-co/src/CustomPostTypes/Glossary.php

namespace EjStoneCo\WpChildTheme\CustomPostTypes;

use EjStoneCo\WpChildTheme\Helpers\Registerable;

class Glossary implements Registerable {
    public function register(): void {
        add_action('init', [$this, 'register_cpt']);
    }

    public function register_cpt(): void {
        register_post_type('glossary', [
            'labels' => [
                'name' => __('Glossaries'),
                'singular_name' => __('Glossary'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => 'glossary'],
            'show_in_menu'       => false, 
        ]);
    }
}
