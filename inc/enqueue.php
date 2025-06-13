<?php
/**
 * Asset Management for Mia Aesthetics Theme
 * 
 * Handles all script and style enqueueing with optimized loading strategies.
 * Includes automatic cache-busting, conditional loading, and performance optimizations.
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main enqueue function - coordinates all asset loading
 */
function mia_enqueue_assets() {
    // Load styles in proper order
    mia_enqueue_styles();
    
    // Load scripts with dependencies
    mia_enqueue_scripts();
    
    // Add inline scripts for runtime configuration
    mia_add_inline_scripts();
}
add_action('wp_enqueue_scripts', 'mia_enqueue_assets');


/**
 * Enqueue all stylesheets with proper dependencies and loading order
 */
function mia_enqueue_styles() {
    $theme_version = wp_get_theme()->get('Version');
    $assets_url = get_template_directory_uri() . '/assets';
    
    // Critical CSS - Load with high priority
    $critical_styles = [
        'mia-fonts' => '/css/_fonts.css',
        'mia-bootstrap' => '/bootstrap/css/bootstrap.min.css',
        'mia-base' => '/css/_base.css'
    ];
    
    foreach ($critical_styles as $handle => $path) {
        mia_enqueue_style($handle, $assets_url . $path, [], $theme_version);
    }
    
    // Font Awesome - Load after critical styles
    mia_enqueue_style(
        'mia-fontawesome', 
        $assets_url . '/fontawesome/css/all.min.css',
        ['mia-base'],
        $theme_version
    );
    
    // Global components - Load after base styles
    mia_enqueue_style(
        'mia-header',
        $assets_url . '/css/_header.css',
        ['mia-base', 'mia-bootstrap'],
        $theme_version
    );
    
    mia_enqueue_style(
        'mia-footer',
        $assets_url . '/css/_footer.css',
        ['mia-base', 'mia-bootstrap'],
        $theme_version
    );
    
    // Context-specific styles - Load conditionally
    mia_enqueue_context_styles($assets_url, $theme_version);
}

/**
 * Enqueue context-specific styles based on current page/template
 */
function mia_enqueue_context_styles($assets_url, $version) {
    $context = mia_get_current_context();
    
    if (!$context) {
        return;
    }
    
    $style_path = $assets_url . '/css/_' . $context['file'];
    $dependencies = ['mia-base', 'mia-header', 'mia-footer'];
    
    // Add specific dependencies based on context
    if (in_array($context['type'], ['archive', 'single'], true)) {
        $dependencies[] = 'mia-bootstrap';
    }
    
    mia_enqueue_style(
        'mia-' . $context['handle'],
        $style_path,
        $dependencies,
        $version
    );
    
    
}

/**
 * Enqueue all JavaScript files with proper dependencies
 */
function mia_enqueue_scripts() {
    $theme_version = wp_get_theme()->get('Version');
    $assets_url = get_template_directory_uri() . '/assets';
    
    // Bootstrap Bundle (includes Popper.js)
    mia_enqueue_script(
        'mia-bootstrap',
        $assets_url . '/bootstrap/js/bootstrap.bundle.min.js',
        [],
        $theme_version,
        true
    );
    
    // Get context for conditional script loading
    $context = mia_get_current_context();
    
    // Context-specific scripts
    if ($context) {
        switch ($context['type']) {
            case 'home':
                // Home page uses its own script instead of main.js
                mia_enqueue_script(
                    'mia-front-page',
                    $assets_url . '/js/front-page.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'condition':
            case 'condition-child':
                // Condition pages and second child of procedures load condition.js
                mia_enqueue_script(
                    'mia-single-condition',
                    $assets_url . '/js/single-condition.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'fat-transfer':
                // Fat-transfer pages use their own specific JS
                mia_enqueue_script(
                    'mia-single-fat-transfer',
                    $assets_url . '/js/single-fat-transfer.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'surgeon':
                // Surgeon pages load surgeon.js
                mia_enqueue_script(
                    'mia-single-surgeon',
                    $assets_url . '/js/single-surgeon.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'page-before-after-by-doctor':
            case 'page-blank-canvas':
            case 'page-case-category':
            case 'page-hero-canvas':
                // Page template scripts
                mia_enqueue_script(
                    'mia-' . $context['handle'],
                    $assets_url . '/js/' . $context['handle'] . '.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'case':
                // Single “case” pages need extra modal/carousel sync logic
                mia_enqueue_script(
                    'mia-single-case',
                    $assets_url . '/js/single-case.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'location':
                // Location pages load location.js
                mia_enqueue_script(
                    'mia-single-location',
                    $assets_url . '/js/single-location.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'non-surgical':
                // Non-surgical pages load non-surgical.js
                mia_enqueue_script(
                    'mia-single-non-surgical',
                    $assets_url . '/js/single-non-surgical.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'special':
                // Special pages load special.js
                mia_enqueue_script(
                    'mia-single-special',
                    $assets_url . '/js/single-special.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'procedure':
                // Procedure pages load procedure.js
                mia_enqueue_script(
                    'mia-single-procedure',
                    $assets_url . '/js/single-procedure.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'post':
                // Single post pages load post.js
                mia_enqueue_script(
                    'mia-single-post',
                    $assets_url . '/js/single-post.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'page':
                // Regular pages load page.js
                mia_enqueue_script(
                    'mia-page',
                    $assets_url . '/js/page.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'archive':
                // Archive pages load archive.js
                mia_enqueue_script(
                    'mia-archive',
                    $assets_url . '/js/archive.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'search':
                // Search pages load search.js
                mia_enqueue_script(
                    'mia-search',
                    $assets_url . '/js/search.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case '404':
                // 404 pages load 404.js
                mia_enqueue_script(
                    'mia-404',
                    $assets_url . '/js/404.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'case-category':
                // Case category pages load case-category.js
                mia_enqueue_script(
                    'mia-case-category',
                    $assets_url . '/js/case-category.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'category':
                // Category pages load category.js
                mia_enqueue_script(
                    'mia-category',
                    $assets_url . '/js/category.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
        }
    }
    
    /* -------------------------------------------------------------
     * Archive-specific scripts for all post type archives
     * ------------------------------------------------------------- */
    if (is_post_type_archive()) {
        $post_type = get_post_type() ?: get_query_var('post_type');
        if ($post_type) {
            mia_enqueue_script(
                'mia-archive-' . $post_type,
                $assets_url . '/js/archive-' . $post_type . '.js',
                ['mia-bootstrap', 'jquery', 'wp-util', 'underscore'],
                $theme_version,
                true
            );
        }
    }

    
    
    
    // Header script for mobile CTA functionality
    mia_enqueue_script(
        'mia-header',
        $assets_url . '/js/header.js',
        ['mia-bootstrap'],
        $theme_version,
        true
    );
}

/**
 * Add inline scripts for runtime configuration
 */
function mia_add_inline_scripts() {
    // Add AJAX URL for scripts that need it (only if jQuery is enqueued)
    if (wp_script_is('jquery', 'enqueued')) {
        wp_localize_script('jquery', 'mia_ajax', [
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mia_ajax_nonce')
        ]);
    }
    
    // Add theme configuration
    $theme_config = [
        'theme_url' => get_template_directory_uri(),
        'site_url' => home_url(),
        'is_mobile' => wp_is_mobile(),
        'debug' => WP_DEBUG
    ];
    
    wp_localize_script(
        mia_get_primary_script_handle(),
        'mia_config',
        $theme_config
    );
}

/**
 * Helper: Enqueue a style with automatic versioning
 */
function mia_enqueue_style($handle, $src, $deps = [], $ver = false) {
    // Check if file exists for local files
    if (strpos($src, get_template_directory_uri()) === 0) {
        $file_path = str_replace(
            get_template_directory_uri(),
            get_template_directory(),
            $src
        );
        
        if (!file_exists($file_path)) {
            if (WP_DEBUG) {
                error_log("[mia_enqueue_style] Style file not found - {$file_path}");
            }
            return false;
        }
        
        // Use file modification time for cache busting
        if ($ver === false) {
            $ver = filemtime($file_path);
        }
    }
    
    wp_enqueue_style($handle, $src, $deps, $ver);
    return true;
}

/**
 * Helper: Enqueue a script with automatic versioning
 */
function mia_enqueue_script($handle, $src, $deps = [], $ver = false, $in_footer = true) {
    // Check if file exists for local files
    if (strpos($src, get_template_directory_uri()) === 0) {
        $file_path = str_replace(
            get_template_directory_uri(),
            get_template_directory(),
            $src
        );
        
        if (!file_exists($file_path)) {
            if (WP_DEBUG) {
                error_log("[mia_enqueue_script] Script file not found - {$file_path}");
            }
            return false;
        }
        
        // Use file modification time for cache busting
        if ($ver === false) {
            $ver = filemtime($file_path);
        }
    }
    
    wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
    return true;
}

/**
 * Get the current page context for conditional asset loading
 */
function mia_get_current_context() {
    // Static cache to prevent multiple computations per request
    static $ctx = null;
    if ($ctx !== null) {
        return $ctx;
    }
    
    // Special templates
    if (is_page_template('page-blank-canvas.php')) {
        $ctx = ['type' => 'page', 'file' => '_page-blank-canvas.css', 'handle' => 'page-blank-canvas'];
        return $ctx;
    }

    if (is_page_template('page-hero-canvas.php')) {
        $ctx = ['type' => 'page', 'file' => '_page-hero-canvas.css', 'handle' => 'page-hero-canvas'];
        return $ctx;
    }

    if (is_page_template('page-before-after-by-doctor.php')) {
        $ctx = ['type' => 'page', 'file' => '_page-before-after-by-doctor.css', 'handle' => 'page-before-after-by-doctor'];
        return $ctx;
    }

    if (is_page_template('page-case-category.php')) {
        $ctx = ['type' => 'page', 'file' => '_page-case-category.css', 'handle' => 'page-case-category'];
        return $ctx;
    }
    
    // Front page
    if (is_front_page()) {
        $ctx = ['type' => 'home', 'file' => '_front-page.css', 'handle' => 'front-page'];
        return $ctx;
    }
    
    // Error pages
    if (is_404()) {
        $ctx = ['type' => '404', 'file' => '_404.css', 'handle' => '404'];
        return $ctx;
    }
    
    // Search
    if (is_search()) {
        $ctx = ['type' => 'search', 'file' => '_search.css', 'handle' => 'search'];
        return $ctx;
    }
    
    // Specific taxonomy handling
    if (is_tax('case-category')) {
        $ctx = ['type' => 'case-category', 'file' => '_case-category.css', 'handle' => 'case-category'];
        return $ctx;
    }


    // Category pages
    if (is_category()) {
        $ctx = ['type' => 'category', 'file' => '_category.css', 'handle' => 'category'];
        return $ctx;
    }
    
    // Blog
    if (is_home() || (is_archive() && get_post_type() === 'post')) {
        $ctx = ['type' => 'archive', 'file' => '_archive.css', 'handle' => 'archive'];
        return $ctx;
    }
    
    if (is_singular('post')) {
        $ctx = ['type' => 'post', 'file' => '_post.css', 'handle' => 'post'];
        return $ctx;
    }
    
    // Custom post type archives
    if (is_post_type_archive()) {
        $post_type = get_post_type() ?: get_query_var('post_type');
        if ($post_type) {
            // Special handling for fat-transfer archive - use same CSS as single
            if ($post_type === 'fat-transfer') {
                $ctx = ['type' => 'fat-transfer', 'file' => '_fat-transfer.css', 'handle' => 'fat-transfer'];
                return $ctx;
            }
            
        }
    }
    
    // Pages
    if (is_page()) {
        $ctx = ['type' => 'page', 'file' => '_page.css', 'handle' => 'page'];
        return $ctx;
    }
    
    // Custom post type singles
    if (is_singular()) {
        $post_type = get_post_type();
        
        // Special handling for procedures
        if ($post_type === 'procedure') {
            $ancestors = get_post_ancestors(get_queried_object());
            if (count($ancestors) === 2) {
                $ctx = ['type' => 'condition-child', 'file' => '_condition.css', 'handle' => 'condition'];
                return $ctx;
            } else {
                $ctx = ['type' => 'procedure', 'file' => '_procedure.css', 'handle' => 'procedure'];
                return $ctx;
            }
        }
        
        // Special handling for conditions
        if ($post_type === 'condition') {
            $ctx = ['type' => 'condition', 'file' => '_condition.css', 'handle' => 'condition'];
            return $ctx;
        }
        
        // Special handling for fat-transfer
        if ($post_type === 'fat-transfer') {
            $ctx = ['type' => 'fat-transfer', 'file' => '_fat-transfer.css', 'handle' => 'fat-transfer'];
            return $ctx;
        }
        
        // Special handling for surgeons
        if ($post_type === 'surgeon') {
            $ctx = ['type' => 'surgeon', 'file' => '_surgeon.css', 'handle' => 'surgeon'];
            return $ctx;
        }
        
        // Special handling for cases
        if ($post_type === 'case') {
            $ctx = ['type' => 'case', 'file' => '_case.css', 'handle' => 'case'];
            return $ctx;
        }
        
        // Special handling for locations
        if ($post_type === 'location') {
            $ctx = ['type' => 'location', 'file' => '_location.css', 'handle' => 'location'];
            return $ctx;
        }
        
        // Special handling for non-surgical
        if ($post_type === 'non-surgical') {
            $ctx = ['type' => 'non-surgical', 'file' => '_non-surgical.css', 'handle' => 'non-surgical'];
            return $ctx;
        }
        
        // Special handling for special
        if ($post_type === 'special') {
            $ctx = ['type' => 'special', 'file' => '_special.css', 'handle' => 'special'];
            return $ctx;
        }
        
    }
    
    // Log unexpected scenarios for debugging
    if (WP_DEBUG) {
        $current_url = $_SERVER['REQUEST_URI'] ?? 'unknown';
        $post_type = get_post_type() ?: 'none';
        error_log("[mia_get_current_context] No context found for URL: {$current_url}, Post Type: {$post_type}");
    }
    
    return null;
}

/**
 * Get the primary script handle for the current page
 */
function mia_get_primary_script_handle() {
    $context = mia_get_current_context();
    
    if ($context && $context['type'] === 'home') {
        return 'mia-front-page';
    }
    
    // Check if context-specific script is enqueued
    if ($context) {
        $script_handle = '';
        
        switch ($context['type']) {
            case 'page-before-after-by-doctor':
            case 'page-blank-canvas': 
            case 'page-case-category':
            case 'page-hero-canvas':
                $script_handle = 'mia-' . $context['handle'];
                break;
            default:
                $script_handle = 'mia-' . $context['type'];
                break;
        }
        
        if ($script_handle && wp_script_is($script_handle, 'enqueued')) {
            return $script_handle;
        }
    }
    
    // Fallback to header script, then jQuery
    if (wp_script_is('mia-header', 'enqueued')) {
        return 'mia-header';
    }
    
    return 'jquery';
}
