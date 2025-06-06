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
 * Enhanced with WP Rocket and Imagify compatibility
 */
if (!function_exists('clear_mia_caches')) {
    function clear_mia_caches($post_id) {
        // Skip revisions and autosaves
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }
        
        $post_type = get_post_type($post_id);
        $cache_cleared = false;
        
        // Clear location-related caches
        if ($post_type === 'location') {
            wp_cache_delete('mia_header_locations');
            wp_cache_delete('mia_footer_locations');
            delete_transient('mia_header_locations');
            delete_transient('mia_footer_locations');
            $cache_cleared = true;
        }
        
        // Clear surgeon-related caches
        if ($post_type === 'surgeon') {
            wp_cache_delete('mia_header_surgeons');
            wp_cache_delete('mia_footer_surgeons');
            delete_transient('mia_header_surgeons');
            delete_transient('mia_footer_surgeons');
            
            // Clear surgeon by location caches
            $locations = get_posts([
                'post_type' => 'location',
                'posts_per_page' => -1,
                'fields' => 'ids'
            ]);
            foreach ($locations as $location_id) {
                wp_cache_delete('mia_footer_surgeons_location_' . $location_id);
                delete_transient('mia_footer_surgeons_location_' . $location_id);
            }
            $cache_cleared = true;
        }
        
        // Clear procedure-related caches
        if ($post_type === 'procedure') {
            wp_cache_delete('mia_header_procedures');
            delete_transient('mia_header_procedures');
            $cache_cleared = true;
        }
        
        // Clear condition-related caches
        if ($post_type === 'condition') {
            wp_cache_delete('mia_header_conditions');
            delete_transient('mia_header_conditions');
            $cache_cleared = true;
        }
        
        // Clear non-surgical-related caches
        if ($post_type === 'non-surgical') {
            wp_cache_delete('mia_header_non_surgical');
            delete_transient('mia_header_non_surgical');
            $cache_cleared = true;
        }
        
        // Clear case-related caches
        if ($post_type === 'case') {
            wp_cache_delete('mia_recent_cases');
            delete_transient('mia_recent_cases');
            $cache_cleared = true;
        }
        
        // Clear special-related caches
        if ($post_type === 'special') {
            wp_cache_delete('mia_active_specials');
            delete_transient('mia_active_specials');
            $cache_cleared = true;
        }
        
        // Clear site statistics cache for any content type
        if (in_array($post_type, ['surgeon', 'location', 'procedure', 'case'])) {
            delete_transient('mia_site_stats');
            delete_transient("mia_count_{$post_type}_" . md5(serialize(['post_parent' => 0])));
            delete_transient("mia_count_{$post_type}_" . md5(serialize([])));
            $cache_cleared = true;
        }
        
        // If any cache was cleared, trigger WP Rocket cache clearing
        if ($cache_cleared) {
            mia_clear_wp_rocket_cache($post_id);
            mia_clear_imagify_cache($post_id);
        }
    }
    add_action('save_post', 'clear_mia_caches');
    add_action('delete_post', 'clear_mia_caches');
    add_action('wp_trash_post', 'clear_mia_caches');
    add_action('untrash_post', 'clear_mia_caches');
}

/**
 * Clear WP Rocket cache when content changes
 */
function mia_clear_wp_rocket_cache($post_id = null) {
    // Check if WP Rocket is active
    if (!function_exists('rocket_clean_domain')) {
        return;
    }
    
    // Clear specific post cache
    if ($post_id) {
        $post_url = get_permalink($post_id);
        if ($post_url && function_exists('rocket_clean_files')) {
            rocket_clean_files($post_url);
        }
    }
    
    // Clear homepage cache for menu changes
    if (function_exists('rocket_clean_home')) {
        rocket_clean_home();
    }
    
    // Clear archive pages if relevant post types
    $post_type = $post_id ? get_post_type($post_id) : null;
    if ($post_type && function_exists('rocket_clean_post_type')) {
        $archive_url = get_post_type_archive_link($post_type);
        if ($archive_url) {
            rocket_clean_files($archive_url);
        }
    }
}

/**
 * Clear Imagify cache when images are updated
 */
function mia_clear_imagify_cache($post_id = null) {
    // Check if Imagify is active
    if (!class_exists('Imagify_Plugin')) {
        return;
    }
    
    // Clear attachment optimizations if this is an attachment
    if ($post_id && wp_attachment_is_image($post_id)) {
        if (function_exists('imagify_delete_backup_file')) {
            // This allows Imagify to re-optimize if needed
            delete_post_meta($post_id, '_imagify_optimization_level');
            delete_post_meta($post_id, '_imagify_data');
            delete_post_meta($post_id, '_imagify_status');
        }
    }
}

/**
 * Clear all menu-related caches
 * Useful for manual cache clearing or when menu structure changes
 */
function mia_clear_all_menu_caches() {
    $cache_keys = [
        'mia_header_locations',
        'mia_footer_locations', 
        'mia_header_surgeons',
        'mia_footer_surgeons',
        'mia_header_procedures',
        'mia_header_conditions',
        'mia_header_non_surgical',
        'mia_recent_cases',
        'mia_active_specials'
    ];
    
    foreach ($cache_keys as $key) {
        wp_cache_delete($key);
        delete_transient($key);
    }
    
    // Clear surgeon by location caches
    $locations = get_posts([
        'post_type' => 'location',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]);
    foreach ($locations as $location_id) {
        wp_cache_delete('mia_footer_surgeons_location_' . $location_id);
        delete_transient('mia_footer_surgeons_location_' . $location_id);
    }
    
    // Clear WP Rocket cache
    if (function_exists('rocket_clean_domain')) {
        rocket_clean_domain();
    }
}

/**
 * Add admin action to manually clear caches
 */
function mia_add_cache_clear_admin_action() {
    if (current_user_can('manage_options') && isset($_GET['mia_clear_cache'])) {
        mia_clear_all_menu_caches();
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>Mia theme caches cleared successfully!</p></div>';
        });
    }
}
add_action('admin_init', 'mia_add_cache_clear_admin_action');

/**
 * Add cache clear link to admin bar
 */
function mia_add_admin_bar_cache_clear($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $wp_admin_bar->add_node([
        'id'    => 'mia-clear-cache',
        'title' => 'Clear Mia Cache',
        'href'  => add_query_arg('mia_clear_cache', '1', admin_url()),
        'meta'  => [
            'title' => 'Clear all Mia theme caches'
        ]
    ]);
}
add_action('admin_bar_menu', 'mia_add_admin_bar_cache_clear', 999);

/**
 * Clear caches when ACF fields are updated
 */
function mia_clear_cache_on_acf_update($value, $post_id, $field) {
    // Clear caches when location or surgeon fields are updated
    if (is_numeric($post_id)) {
        $post_type = get_post_type($post_id);
        if (in_array($post_type, ['location', 'surgeon', 'procedure', 'condition', 'non-surgical'])) {
            clear_mia_caches($post_id);
        }
    }
    return $value;
}
add_filter('acf/update_value', 'mia_clear_cache_on_acf_update', 10, 3);

/**
 * WP Rocket compatibility - exclude dynamic content from caching
 */
function mia_wp_rocket_exclude_dynamic_content() {
    if (!function_exists('rocket_clean_domain')) {
        return;
    }
    
    // Exclude AJAX requests from caching
    add_filter('rocket_cache_reject_uri', function($uris) {
        $exclude_uris = [
            '/wp-admin/admin-ajax.php',
            '(.*)preview=true(.*)',
            '(.*)wp-comments-post.php(.*)',
            '(.*)mia_clear_cache(.*)'
        ];
        return array_merge($uris, $exclude_uris);
    });
    
    // Exclude certain query parameters from caching
    add_filter('rocket_cache_query_strings', function($query_strings) {
        $exclude_params = [
            'surgeon_location',
            'procedure_type',
            'mia_clear_cache'
        ];
        return array_merge($query_strings, $exclude_params);
    });
}
add_action('init', 'mia_wp_rocket_exclude_dynamic_content');

/**
 * Get cached post count for performance
 */
function mia_get_cached_post_count($post_type, $args = []) {
    $cache_key = "mia_count_{$post_type}_" . md5(serialize($args));
    $count = get_transient($cache_key);
    
    if (false === $count) {
        $default_args = [
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ];
        
        $query_args = wp_parse_args($args, $default_args);
        $query = new WP_Query($query_args);
        $count = $query->post_count;
        
        // Cache for 2 hours
        set_transient($cache_key, $count, 2 * HOUR_IN_SECONDS);
        wp_reset_postdata();
    }
    
    return $count;
}

/**
 * Get site statistics with caching
 */
function mia_get_site_stats() {
    $cache_key = 'mia_site_stats';
    $stats = get_transient($cache_key);
    
    if (false === $stats) {
        $stats = [
            'surgeons' => mia_get_cached_post_count('surgeon', ['post_parent' => 0]) ?: 27,
            'locations' => mia_get_cached_post_count('location', ['post_parent' => 0]) ?: 13,
            'procedures' => mia_get_cached_post_count('procedure') ?: 50,
            'cases' => mia_get_cached_post_count('case') ?: 1000,
        ];
        
        // Cache for 2 hours
        set_transient($cache_key, $stats, 2 * HOUR_IN_SECONDS);
    }
    
    return $stats;
}
?>