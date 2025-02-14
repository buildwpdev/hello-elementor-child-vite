<?php

// wp-content/themes/build-wp/wp/PostsAsTaxonomies/Pages.php

namespace BuildWp\WpChildTheme\PostsAsTaxonomies;

class Pages {
    public function register() {
        add_action('init', [$this, 'register_pages_taxonomy']);
        add_action('admin_init', [$this, 'convert_pages_to_terms']);
    }

    /**
     * Registers a custom taxonomy where pages act as categories for posts.
     */
    public function register_pages_taxonomy() {
        $args = [
            'label' => __('Page Categories', 'build-wp'),
            'rewrite' => ['slug' => 'page-category'],
            'hierarchical' => true, // Enables parent-child structure
            'show_admin_column' => true, // Show taxonomy in the post edit screen
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'query_var' => true
        ];

        register_taxonomy('page_category', ['post', 'product'], $args);
    }

    /**
     * Converts pages into selectable taxonomy terms.
     */
    public function convert_pages_to_terms() {
        $pages = get_pages();

        foreach ($pages as $page) {
            if (!term_exists($page->post_title, 'page_category')) {
                wp_insert_term($page->post_title, 'page_category', [
                    'description' => get_permalink($page->ID),
                    'slug' => $page->post_name
                ]);
            }
        }
    }
}
