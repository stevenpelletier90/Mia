<?php
/**
 * Query Modifications and Filters for Mia Aesthetics Theme
 * 
 * Handles all query modifications, custom filters, and archive behaviors.
 * Centralizes query logic for better maintainability and performance.
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modify main queries for custom post type archives
 */
function mia_modify_archive_queries($query) {
    // Only modify main queries on the frontend
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    
    // Location archive modifications
    if (is_post_type_archive('location')) {
        $query->set('posts_per_page', -1);      // Show all locations
        $query->set('post_parent', 0);          // Only top-level locations
        $query->set('orderby', 'title');        // Alphabetical order
        $query->set('order', 'ASC');
    }
    
    // Surgeon archive modifications
    elseif (is_post_type_archive('surgeon')) {
        $query->set('posts_per_page', -1);      // Show all surgeons
        $query->set('orderby', 'menu_order');   // Manual order
        $query->set('order', 'ASC');
    }
    
    // Procedure archive modifications
    elseif (is_post_type_archive('procedure')) {
        $query->set('posts_per_page', -1);      // Show all procedures
        $query->set('post_parent', 0);          // Only top-level procedures
        $query->set('orderby', 'menu_order title'); // Manual order, then alphabetical
        $query->set('order', 'ASC');
    }
    
    // Case archive modifications
    elseif (is_post_type_archive('case')) {
        $query->set('posts_per_page', -1);      // Show all cases
        $query->set('orderby', 'date');         // Most recent first
        $query->set('order', 'DESC');
    }
    
    // Condition archive modifications
    elseif (is_post_type_archive('condition')) {
        $query->set('posts_per_page', -1);      // Show all conditions
        $query->set('orderby', 'title');        // Alphabetical order
        $query->set('order', 'ASC');
    }
    
    // Special archive modifications
    elseif (is_post_type_archive('special')) {
        $query->set('posts_per_page', 6);       // Paginate specials
        $query->set('orderby', 'menu_order date'); // Manual order, then date
        $query->set('order', 'DESC');
        
        // Only show active specials
        $query->set('meta_query', [
            'relation' => 'OR',
            [
                'key' => 'special_end_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE'
            ],
            [
                'key' => 'special_end_date',
                'compare' => 'NOT EXISTS'
            ]
        ]);
    }
    
    // Non-surgical archive modifications
    elseif (is_post_type_archive('non-surgical')) {
        $query->set('posts_per_page', -1);      // Show all non-surgical
        $query->set('orderby', 'menu_order title'); // Manual order, then alphabetical
        $query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'mia_modify_archive_queries');

/**
 * Modify taxonomy archive queries
 */
function mia_modify_taxonomy_queries($query) {
    // Only modify main queries on the frontend
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    
    // Case category taxonomy
    if (is_tax('case-category')) {
        $query->set('posts_per_page', -1);      // Show all cases
        $query->set('orderby', 'date');         // Most recent first
        $query->set('order', 'DESC');
    }
}
add_action('pre_get_posts', 'mia_modify_taxonomy_queries');

/**
 * Ensure correct body classes for custom post type archives
 */
function mia_archive_body_classes($classes) {
    // Get all registered post types
    $post_types = get_post_types(['public' => true], 'names');
    
    foreach ($post_types as $post_type) {
        if (is_post_type_archive($post_type)) {
            $class_name = 'post-type-archive-' . $post_type;
            if (!in_array($class_name, $classes)) {
                $classes[] = $class_name;
            }
        }
    }
    
    // Add specific classes for single post types
    if (is_singular()) {
        $post_type = get_post_type();
        if ($post_type) {
            $class_name = 'single-type-' . $post_type;
            if (!in_array($class_name, $classes)) {
                $classes[] = $class_name;
            }
        }
    }
    
    return $classes;
}
add_filter('body_class', 'mia_archive_body_classes', 999);

/**
 * Customize excerpt length based on context
 */
function mia_custom_excerpt_length($length) {
    // Shorter excerpts for archive pages
    if (is_archive() || is_home()) {
        return 20;
    }
    
    // Even shorter for search results
    if (is_search()) {
        return 15;
    }
    
    // Default length for other contexts
    return 30;
}
add_filter('excerpt_length', 'mia_custom_excerpt_length');

/**
 * Customize excerpt more text
 */
function mia_excerpt_more($more) {
    // Don't add "more" text in admin
    if (is_admin()) {
        return $more;
    }
    
    return '...';
}
add_filter('excerpt_more', 'mia_excerpt_more');

/**
 * Remove protected/private prefixes from titles
 */
function mia_remove_title_prefixes($title) {
    // Remove "Protected: " prefix
    $title = str_replace('Protected: ', '', $title);
    
    // Remove "Private: " prefix
    $title = str_replace('Private: ', '', $title);
    
    return $title;
}
add_filter('the_title', 'mia_remove_title_prefixes');

/**
 * Modify search query to include custom post types
 */
function mia_search_filter($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        // Include relevant post types in search
        $searchable_types = [
            'post',
            'page',
            'procedure',
            'condition',
            'surgeon',
            'location',
            'non-surgical'
        ];
        
        $query->set('post_type', $searchable_types);
        
        // Limit search results
        $query->set('posts_per_page', 20);
    }
}
add_action('pre_get_posts', 'mia_search_filter');

/**
 * Exclude certain pages from search results
 */
function mia_exclude_pages_from_search($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        // Get IDs of pages to exclude
        $exclude_slugs = [
            'thank-you',
            'privacy-policy',
            'terms-of-service',
            'sitemap'
        ];
        
        $exclude_ids = [];
        foreach ($exclude_slugs as $slug) {
            $page = get_page_by_path($slug);
            if ($page) {
                $exclude_ids[] = $page->ID;
            }
        }
        
        if (!empty($exclude_ids)) {
            $query->set('post__not_in', $exclude_ids);
        }
    }
}
add_action('pre_get_posts', 'mia_exclude_pages_from_search');

/**
 * Add custom query vars
 */
function mia_add_query_vars($vars) {
    // Add custom query variables if needed
    $vars[] = 'surgeon_location';
    $vars[] = 'procedure_type';
    
    return $vars;
}
add_filter('query_vars', 'mia_add_query_vars');

/**
 * Modify queries based on custom query vars
 */
function mia_handle_custom_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Filter surgeons by location
        if (get_query_var('surgeon_location')) {
            $location_id = intval(get_query_var('surgeon_location'));
            if ($location_id) {
                $query->set('meta_query', [
                    [
                        'key' => 'surgeon_location',
                        'value' => $location_id,
                        'compare' => '='
                    ]
                ]);
            }
        }
        
        // Filter procedures by type
        if (get_query_var('procedure_type')) {
            $procedure_type = sanitize_text_field(get_query_var('procedure_type'));
            if ($procedure_type) {
                $query->set('meta_query', [
                    [
                        'key' => 'procedure_type',
                        'value' => $procedure_type,
                        'compare' => '='
                    ]
                ]);
            }
        }
    }
}
add_action('pre_get_posts', 'mia_handle_custom_queries');

/**
 * Optimize queries by removing unnecessary joins
 */
function mia_optimize_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Remove unnecessary meta queries on archives
        if (is_post_type_archive() && !$query->get('meta_query')) {
            $query->set('update_post_meta_cache', false);
        }
        
        // Remove term cache updates if not needed
        if (!is_tax() && !is_category() && !is_tag()) {
            $query->set('update_post_term_cache', false);
        }
    }
}
add_action('pre_get_posts', 'mia_optimize_queries', 999);

/**
 * Add pagination support for custom queries
 */
function mia_pagination_rewrite_rules() {
    // Add rewrite rules for custom post type pagination
    $post_types = ['special'];
    
    foreach ($post_types as $post_type) {
        add_rewrite_rule(
            $post_type . '/page/([0-9]+)/?$',
            'index.php?post_type=' . $post_type . '&paged=$matches[1]',
            'top'
        );
    }
}
add_action('init', 'mia_pagination_rewrite_rules');

/**
 * Fix pagination on custom post type archives
 */
function mia_fix_pagination($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_post_type_archive(['special'])) {
            if (get_query_var('paged')) {
                $query->set('paged', get_query_var('paged'));
            } elseif (get_query_var('page')) {
                $query->set('paged', get_query_var('page'));
            }
        }
    }
}
add_action('pre_get_posts', 'mia_fix_pagination');

/**
 * Get all non-surgical procedures grouped by category
 * Optimized single query instead of multiple individual queries
 */
function mia_get_non_surgical_by_category() {
    static $cached_results = null;
    
    if ($cached_results !== null) {
        return $cached_results;
    }
    
    $all_procedures = new WP_Query([
        'post_type' => 'non-surgical',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order title',
        'order' => 'ASC',
        'meta_query' => [
            [
                'key' => 'procedure_category',
                'compare' => 'EXISTS'
            ]
        ],
        'update_post_meta_cache' => true,
        'update_post_term_cache' => false
    ]);
    
    $grouped = [
        'injectable' => [],
        'skin' => [],
        'body' => [],
        'wellness' => []
    ];
    
    $counts = [
        'injectable' => 0,
        'skin' => 0,
        'body' => 0,
        'wellness' => 0
    ];
    
    if ($all_procedures->have_posts()) {
        while ($all_procedures->have_posts()) {
            $all_procedures->the_post();
            
            $categories = get_field('procedure_category');
            if (is_array($categories)) {
                foreach ($categories as $category) {
                    if (isset($grouped[$category])) {
                        $grouped[$category][] = get_post();
                        $counts[$category]++;
                    }
                }
            } elseif (is_string($categories) && isset($grouped[$categories])) {
                $grouped[$categories][] = get_post();
                $counts[$categories]++;
            }
        }
    }
    
    wp_reset_postdata();
    
    $cached_results = [
        'grouped' => $grouped,
        'counts' => $counts
    ];
    
    return $cached_results;
}
