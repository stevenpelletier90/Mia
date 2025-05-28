<?php
/**
 * Cache Helper Functions
 * 
 * Handles caching utilities and cache management
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Clear cached data when posts are updated
 */
if (!function_exists('clear_mia_caches')) {
    function clear_mia_caches($post_id) {
        $post_type = get_post_type($post_id);
        
        if ($post_type === 'location') {
            wp_cache_delete('mia_header_locations');
            wp_cache_delete('mia_footer_locations');
            delete_transient('mia_header_locations');
            delete_transient('mia_footer_locations');
        }
        
        if ($post_type === 'surgeon') {
            wp_cache_delete('mia_header_surgeons');
            wp_cache_delete('mia_footer_surgeons');
            delete_transient('mia_header_surgeons');
            delete_transient('mia_footer_surgeons');
        }
    }
    add_action('save_post', 'clear_mia_caches');
    add_action('delete_post', 'clear_mia_caches');
}
?>