<?php
/**
 * Featured Image Column for Admin Lists
 * 
 * Adds a featured image column to post, page, and custom post type admin lists
 * to quickly see which items have featured images set.
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add featured image column to post types
 */
function mia_add_featured_image_columns($columns) {
    // Insert featured image column after title
    $new_columns = [];
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['featured_image'] = '<span class="dashicons dashicons-format-image" title="Featured Image"></span>';
        }
    }
    return $new_columns;
}

/**
 * Display featured image column content
 */
function mia_show_featured_image_column($column, $post_id) {
    if ($column === 'featured_image') {
        if (has_post_thumbnail($post_id)) {
            $thumbnail = get_the_post_thumbnail($post_id, [40, 40], [
                'style' => 'width: 40px; height: 40px; object-fit: cover; border-radius: 4px;'
            ]);
            echo '<span title="Has featured image">' . $thumbnail . '</span>';
        } else {
            echo '<span class="dashicons dashicons-format-image" style="color: #ccc; font-size: 40px;" title="No featured image"></span>';
        }
    }
}

/**
 * Make featured image column sortable
 */
function mia_make_featured_image_column_sortable($columns) {
    $columns['featured_image'] = 'featured_image';
    return $columns;
}

/**
 * Handle sorting by featured image
 */
function mia_sort_by_featured_image($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');
    if ($orderby === 'featured_image') {
        $query->set('meta_key', '_thumbnail_id');
        $query->set('orderby', 'meta_value_num');
        
        // Show posts with featured images first when sorting
        if ($query->get('order') === 'ASC') {
            $query->set('meta_query', [
                'relation' => 'OR',
                [
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS'
                ],
                [
                    'key' => '_thumbnail_id',
                    'compare' => 'NOT EXISTS'
                ]
            ]);
        }
    }
}

/**
 * Set column width for featured image column
 */
function mia_featured_image_column_css() {
    echo '<style>
        .column-featured_image {
            width: 60px;
            text-align: center;
        }
        .column-featured_image .dashicons {
            font-size: 20px;
            width: 20px;
            height: 20px;
        }
    </style>';
}

/**
 * Apply to all relevant post types
 */
function mia_init_featured_image_columns() {
    // Get all public post types
    $post_types = get_post_types(['public' => true], 'names');
    
    // Add featured image support to post types that might not have it
    $supported_types = ['post', 'page', 'case', 'procedure', 'surgeon', 'location', 'special', 'condition', 'non-surgical'];
    
    foreach ($supported_types as $post_type) {
        if (post_type_exists($post_type)) {
            // Add featured image column
            add_filter("manage_{$post_type}_posts_columns", 'mia_add_featured_image_columns');
            add_action("manage_{$post_type}_posts_custom_column", 'mia_show_featured_image_column', 10, 2);
            add_filter("manage_edit-{$post_type}_sortable_columns", 'mia_make_featured_image_column_sortable');
        }
    }
    
    // Add sorting functionality
    add_action('pre_get_posts', 'mia_sort_by_featured_image');
    
    // Add CSS
    add_action('admin_head', 'mia_featured_image_column_css');
}

// Initialize the featured image columns
add_action('admin_init', 'mia_init_featured_image_columns');

/**
 * Add bulk action to set featured images from media library
 */
function mia_add_bulk_featured_image_actions($actions) {
    $actions['set_featured_image'] = 'Set Featured Image';
    $actions['remove_featured_image'] = 'Remove Featured Image';
    return $actions;
}

/**
 * Handle bulk featured image actions
 */
function mia_handle_bulk_featured_image_actions($redirect_url, $action, $post_ids) {
    if ($action === 'remove_featured_image') {
        foreach ($post_ids as $post_id) {
            delete_post_thumbnail($post_id);
        }
        $redirect_url = add_query_arg('featured_images_removed', count($post_ids), $redirect_url);
    }
    
    return $redirect_url;
}

/**
 * Show admin notices for bulk actions
 */
function mia_featured_image_bulk_action_notices() {
    if (!empty($_REQUEST['featured_images_removed'])) {
        $count = intval($_REQUEST['featured_images_removed']);
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p>' . sprintf(_n('Featured image removed from %s post.', 'Featured images removed from %s posts.', $count), $count) . '</p>';
        echo '</div>';
    }
}

// Add bulk actions for all post types
function mia_init_bulk_actions() {
    $post_types = ['post', 'page', 'case', 'procedure', 'surgeon', 'location', 'special', 'condition', 'non-surgical'];
    
    foreach ($post_types as $post_type) {
        if (post_type_exists($post_type)) {
            add_filter("bulk_actions-edit-{$post_type}", 'mia_add_bulk_featured_image_actions');
            add_filter("handle_bulk_actions-edit-{$post_type}", 'mia_handle_bulk_featured_image_actions', 10, 3);
        }
    }
    
    add_action('admin_notices', 'mia_featured_image_bulk_action_notices');
}

add_action('admin_init', 'mia_init_bulk_actions');