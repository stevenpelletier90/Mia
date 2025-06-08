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
        // Add page-attributes support for template selection
        $cpt->supports[] = 'page-attributes';
        register_post_type('case', (array) $cpt);
    }

    /* 2. Update taxonomy rewrite to use "before-after" base */
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
 * Fix case category term links to use the /before-after/ structure
 */
add_filter('term_link', function($termlink, $term, $taxonomy) {
    if ($taxonomy === 'case-category') {
        // Replace /case-category/ with /before-after/
        $termlink = str_replace('/case-category/', '/before-after/', $termlink);
    }
    return $termlink;
}, 10, 3);

/**
 * Add rewrite rules for hierarchical categories and case posts
 */
add_action('init', function() {
    // Hierarchical case posts (3 levels): /before-after/parent/child/post-slug/
    add_rewrite_rule(
        '^before-after/([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?case=$matches[3]',
        'top'
    );
    
    // Regular case posts (2 levels): /before-after/category/post-slug/
    add_rewrite_rule(
        '^before-after/([^/]+)/([^/]+)/?$',
        'index.php?case=$matches[2]',
        'top'
    );
    
    // Hierarchical case category archives: /before-after/parent/child/
    add_rewrite_rule(
        '^before-after/([^/]+)/([^/]+)/?$',
        'index.php?case-category=$matches[2]',
        'bottom'
    );
    
    // Case category archives (top level): /before-after/category-name/
    add_rewrite_rule(
        '^before-after/([^/]+)/?$',
        'index.php?case-category=$matches[1]',
        'bottom'
    );
}, 25);

/**
 * Handle conflicts between case posts and category archives
 */
add_action('template_redirect', function() {
    global $wp_query;
    
    // Check if this is a case post with a custom template selected
    if (is_single() && get_post_type() === 'case') {
        global $post;
        $page_template = get_post_meta($post->ID, '_wp_page_template', true);
        if ($page_template && $page_template !== 'default') {
            // Override template with the selected custom template
            include(locate_template($page_template));
            exit;
        }
    }
    
    // If we're trying to load a case post but it's 404 or showing wrong content
    if (is_single() && get_post_type() === 'case' && is_404()) {
        // Case post rewrite rule worked, nothing to do
        return;
    }
    
    // No automatic redirects - let rewrite rules handle priority
    
    // Handle uncategorized case posts
    if (is_404() && preg_match('#^/before-after/([^/]+)/?$#', $_SERVER['REQUEST_URI'], $matches)) {
        $slug = $matches[1];
        
        // Check if this slug is a case post (not a category)
        $case_post = get_page_by_path($slug, OBJECT, 'case');
        if ($case_post && $case_post->post_status === 'publish') {
            // Set up the query and global post properly
            global $post;
            $post = $case_post;
            setup_postdata($post);
            
            $wp_query->init();
            $wp_query->query = array('case' => $slug);
            $wp_query->query_vars = array('case' => $slug);
            $wp_query->queried_object = $case_post;
            $wp_query->queried_object_id = $case_post->ID;
            $wp_query->is_single = true;
            $wp_query->is_singular = true;
            $wp_query->is_404 = false;
            $wp_query->post = $case_post;
            $wp_query->posts = array($case_post);
            $wp_query->post_count = 1;
            $wp_query->found_posts = 1;
            status_header(200);
            
            // Check if a custom page template is assigned
            $page_template = get_post_meta($case_post->ID, '_wp_page_template', true);
            if ($page_template && $page_template !== 'default') {
                // Use the custom template
                include(locate_template($page_template));
            } else {
                // Use default case template
                include(get_query_template('single-case'));
            }
            exit;
        }
    }
});

/**
 * Replace %case_category% with the first assigned term's slug.
 */
add_filter('post_type_link', function($link, $post) {

    if ($post->post_type !== 'case') {
        return $link;
    }

    $terms = wp_get_object_terms($post->ID, 'case-category');
    if (!empty($terms) && !is_wp_error($terms)) {
        $term = $terms[0]; // Get the first assigned term
        
        // Build hierarchical path
        $hierarchy = [];
        $current_term = $term;
        
        // Walk up the hierarchy to build the full path
        while ($current_term) {
            array_unshift($hierarchy, $current_term->slug);
            $current_term = $current_term->parent ? get_term($current_term->parent, 'case-category') : null;
        }
        
        $hierarchical_path = implode('/', $hierarchy);
        return str_replace('%case-category%', $hierarchical_path, $link);
    }

    // For uncategorized cases, use simplified structure: /before-after/post-slug/
    return str_replace('before-after/%case-category%/', 'before-after/', $link);

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
 * Initialize Yoast breadcrumb customizations after WordPress is fully loaded
 */
add_action('init', function() {
    // Only add these filters if Yoast SEO is active
    if (!function_exists('yoast_breadcrumb')) {
        return;
    }
    
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
                // Build proper URL manually to ensure /before-after/ structure
                $category_url = home_url('/before-after/' . $category->slug . '/');
                $new_links[] = [
                    'url'  => $category_url,
                    'text' => $category->name,
                ];
                $inserted = true;
            }
        }
        
        return $new_links;
    });

    /**
     * Customize Yoast breadcrumbs for case category archives and fix URLs
     */
    add_filter('wpseo_breadcrumb_links', function($links) {
        // Fix any case-category URLs in breadcrumbs
        foreach ($links as &$link) {
            if (isset($link['url']) && strpos($link['url'], '/case-category/') !== false) {
                // Convert old case-category URLs to before-after URLs
                $link['url'] = str_replace('/case-category/', '/before-after/', $link['url']);
            }
            
            // Update main archive link text
            if (isset($link['url']) && strpos($link['url'], '/before-after/') !== false) {
                // If this is the main archive link, rename it
                if ($link['url'] === get_post_type_archive_link('case')) {
                    $link['text'] = 'Before & After';
                }
            }
        }
        
        return $links;
    }, 20);
}, 20);