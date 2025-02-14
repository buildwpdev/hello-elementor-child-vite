<?php

// wp-content/themes/build-wp/wp/Elementor/TemplateConditions/RegisterConditions.php

namespace BuildWp\WpChildTheme\Elementor\TemplateConditions;

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

class RegisterConditions {
    public function register() {
        add_action('elementor/theme/register_conditions', [$this, 'register_elementor_conditions']);
    }

    /**
     * Register custom conditions in Elementor
     */
    public function register_elementor_conditions($conditions_manager) {
        require_once(__DIR__ . '/IsCollection.php');

        // Ensure we have access to Elementor's conditions
        if (method_exists($conditions_manager, 'get_condition')) {
            $singular_condition = $conditions_manager->get_condition('singular');
            if ($singular_condition) {
                $singular_condition->register_sub_condition(new IsCollection());
            }
        }
    }
}
