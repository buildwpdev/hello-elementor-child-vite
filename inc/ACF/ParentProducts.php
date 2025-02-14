<?php

// // Hook into ACF's save_post action
// add_action('acf/save_post', 'my_acf_save_post');
// function my_acf_save_post($post_id) {
//     global $wpdb;

//     // Avoid running for auto-saves, revisions, etc.
//     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
//         return;
//     }

//     // Specify the field key or name that contains the parent ID
//     $field_key = 'parent_product_id'; // Replace with your actual ACF field name or key

//     // Get the value of the ACF field
//     $parent_id = get_field($field_key, $post_id);

//     // Ensure the value is a valid integer before proceeding
//     if ($parent_id && is_numeric($parent_id)) {
//         $parent_id = intval($parent_id); // Convert to an integer

//         // Debugging: Log the IDs
//         if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
//             error_log('ACF $post_id: ' . print_r($post_id, true));
//             error_log('ACF $parent_id: ' . print_r($parent_id, true));
//         }

//         // Update the post's parent ID using wpdb
//         $updated = $wpdb->update(
//             $wpdb->posts, // Table name
//             [ 'post_parent' => $parent_id ], // Data to update
//             [ 'ID' => $post_id ], // Where clause
//             [ '%d' ], // Data format
//             [ '%d' ] // Where format
//         );

//         // Debugging: Log the update result
//         if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
//             error_log('Update result: ' . ($updated ? 'Success' : 'Failure'));
//         }

//         // Clean the post cache to reflect changes
//         clean_post_cache($post_id);

//         // Debugging: Check the updated post_parent value
//         $parent_id_db = $wpdb->get_var($wpdb->prepare("SELECT post_parent FROM $wpdb->posts WHERE ID = %d", $post_id));
//         if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
//             error_log('Database post_parent after update: ' . print_r($parent_id_db, true));
//         }
//     }
// }