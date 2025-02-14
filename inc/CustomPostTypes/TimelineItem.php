<?php

// wp-content/themes/build-wp/src/CustomPostTypes/TimelineItem.php

namespace BuildWp\WpChildTheme\CustomPostTypes;

use BuildWp\WpChildTheme\Helpers\Registerable;

class TimelineItem implements Registerable {
    public function register(): void {
        add_action('init', [$this, 'register_cpt']);
    }

    public function register_cpt(): void {
        register_post_type('timeline_item', [
            'labels' => [
                'name' => __('TimelineItems'),
                'singular_name' => __('TimelineItem'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => 'TimelineItems'],
            'show_in_menu'       => false, 
        ]);
    }
}
