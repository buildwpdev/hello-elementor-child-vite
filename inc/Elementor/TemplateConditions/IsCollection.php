<?php

namespace BuildWp\WpChildTheme\Elementor\TemplateConditions;

use ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base;

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

class IsCollection extends Condition_Base {

    public static function get_parent() {
        return 'singular'; // Makes it a sub-condition under "Singular"
    }

    public static function get_type() {
        return 'is_collection';
    }

    public function get_name() {
        return 'is_collection';
    }

    public function get_label() {
        return __('Is Collection', 'build-wp');
    }

    public function check($args) {
        $post_id = get_the_ID();
        return get_post_meta($post_id, 'is_collection', true) === 'true';
    }
}
