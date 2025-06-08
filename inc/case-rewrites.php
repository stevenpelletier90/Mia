<?php
/**
 * Case Post Type URL Structure Customization
 * 
 * Fine-tunes the "case" URL structure to become:
 * /before-after/{procedure}/{case-slug}/
 * 
 * ACF's UI can't put a taxonomy placeholder in the slug, so we override 
 * the rewrite args after ACF registers the post-type and add the usual 
 * post_type_link filter.
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fine-tune the "case" URL structure so it becomes
 * /before-after/{procedure}/{case-slug}/
 */
add_action('init', function() {

    /* 1. Update CPT rewrite to include the placeholder */
    $cpt = get_post_type_object('case');
    if ($cpt) {
        $cpt->rewrite = [
            'slug'       => 'before-after/%case-category%', // token
            'with_front' => false,
            'pages'      => true,
            'feeds'      => false,
        ];
        register_post_type('case', (array) $cpt);
    }

    /* 2. Make the taxonomy share the same base "before-after" */
    $tax = get_taxonomy('case-category');
    if ($tax) {
        $tax->rewrite = [
            'slug'         => 'before-after',
            'with_front'   => false,
            'hierarchical' => true,
        ];
        register_taxonomy('case-category', ['case'], (array) $tax);
    }

}, 20); // run *after* ACF's default registration

/**
 * Add custom rewrite rules for case categories
 */
add_action('init', function() {
    // Add rewrite rule for case category archives
    add_rewrite_rule(
        '^before-after/([^/]+)/?$',
        'index.php?case-category=$matches[1]',
        'top'
    );
    
    // Add rewrite rule for hierarchical case categories (if needed)
    add_rewrite_rule(
        '^before-after/([^/]+)/([^/]+)/?$',
        'index.php?case-category=$matches[2]',
        'top'
    );
}, 25);

/**
 * Replace %case_category% with the first assigned term's slug.
 */
add_filter('post_type_link', function($link, $post) {

    if ($post->post_type !== 'case') {
        return $link;
    }

    $terms = wp_get_object_terms($post->ID, 'case-category');
    if (!empty($terms) && !is_wp_error($terms)) {
        return str_replace('%case-category%', $terms[0]->slug, $link);
    }

    // Fallback if no category is set
    return str_replace('%case-category%', 'uncategorised', $link);

}, 10, 2);

/**
 * Prevent pagination on case archive since it's a static landing page
 */
add_action('init', function() {
    // Remove pagination rewrite rules for case archive
    global $wp_rewrite;
    $wp_rewrite->use_trailing_slashes = true;
    
    // Remove the automatic /page/X/ rewrite for case archive
    add_filter('rewrite_rules_array', function($rules) {
        // Remove pagination rules for case archive
        unset($rules['case/page/?([0-9]{1,})/?$']);
        unset($rules['case/page/([0-9]+)/?$']);
        
        return $rules;
    });
}, 25);

/**
 * Redirect any pagination URLs on case archive to the main archive
 */
add_action('template_redirect', function() {
    if (is_post_type_archive('case') && is_paged()) {
        wp_redirect(get_post_type_archive_link('case'), 301);
        exit;
    }
});

/**
 * Customize Yoast breadcrumbs for case posts to include the category
 */
add_filter('wpseo_breadcrumb_links', function($links) {
    global $post;
    
    // Only modify breadcrumbs for case posts
    if (!is_singular('case') || !$post) {
        return $links;
    }
    
    // Get the case category for this post
    $terms = wp_get_object_terms($post->ID, 'case-category');
    if (empty($terms) || is_wp_error($terms)) {
        return $links;
    }
    
    $category = $terms[0]; // Use the first category
    
    // Find where to insert the category (after "Before & After" but before the post title)
    $new_links = [];
    $inserted = false;
    
    foreach ($links as $link) {
        $new_links[] = $link;
        
        // Insert category after the "Before & After" archive link
        if (!$inserted && isset($link['url']) && strpos($link['url'], '/before-after/') !== false && !strpos($link['url'], '/before-after/' . $category->slug)) {
            $new_links[] = [
                'url'  => get_term_link($category),
                'text' => $category->name,
            ];
            $inserted = true;
        }
    }
    
    return $new_links;
});

/**
 * Customize Yoast breadcrumbs for case category archives
 */
add_filter('wpseo_breadcrumb_links', function($links) {
    // Only modify breadcrumbs for case category taxonomy pages
    if (!is_tax('case-category')) {
        return $links;
    }
    
    // Find the archive link and update it to "Before & After"
    foreach ($links as &$link) {
        if (isset($link['url']) && strpos($link['url'], '/before-after/') !== false) {
            // If this is the main archive link, rename it
            if ($link['url'] === get_post_type_archive_link('case')) {
                $link['text'] = 'Before & After';
            }
        }
    }
    
    return $links;
}, 20);