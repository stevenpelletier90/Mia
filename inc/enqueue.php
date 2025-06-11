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
    
    // Special case: Hero styles for front page
    if ($context['type'] === 'home') {
        mia_enqueue_style(
            'mia-hero',
            $assets_url . '/css/_hero.css',
            ['mia-home'],
            $version
        );
    }
    
    // Special case: Gallery styles
    if ($context['type'] === 'gallery' || is_page_template('page-before-after-by-doctor.php')) {
        mia_enqueue_style(
            'mia-gallery',
            $assets_url . '/css/_gallery.css',
            ['mia-bootstrap'],
            $version
        );
    }
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
        ['jquery', 'wp-util', 'underscore'],
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
                    'mia-home',
                    $assets_url . '/js/home.js',
                    ['mia-bootstrap', 'jquery', 'wp-util', 'underscore'],
                    $theme_version,
                    true
                );
                break;
                
            case 'condition':
            case 'condition-child':
                // Condition pages load both main.js and condition.js
                mia_enqueue_script(
                    'mia-condition',
                    $assets_url . '/js/condition.js',
                    ['mia-bootstrap', 'jquery', 'wp-util', 'underscore'],
                    $theme_version,
                    true
                );
                break;
                
            case 'fat-transfer':
                // Fat-transfer pages use the same JS as condition pages
                mia_enqueue_script(
                    'mia-condition',
                    $assets_url . '/js/condition.js',
                    ['mia-bootstrap', 'jquery', 'wp-util', 'underscore'],
                    $theme_version,
                    true
                );
                break;
                
            case 'surgeon':
                // Surgeon pages load both main.js and surgeon.js
                mia_enqueue_script(
                    'mia-surgeon',
                    $assets_url . '/js/surgeon.js',
                    ['mia-bootstrap', 'jquery', 'wp-util', 'underscore'],
                    $theme_version,
                    true
                );
                break;
                
            case 'gallery':
                // Gallery pages load both main.js and gallery.js
                mia_enqueue_script(
                    'mia-gallery',
                    $assets_url . '/js/gallery.js',
                    ['mia-bootstrap', 'jquery', 'wp-util', 'underscore'],
                    $theme_version,
                    true
                );
                break;
                
            case 'case':
                // Case pages use main.js with WordPress dependencies
                // No additional case-specific script needed
                break;
        }
    }
    
    // Load main.js on most pages (except home page which has its own script)
    if (!$context || $context['type'] !== 'home') {
        mia_enqueue_script(
            'mia-main',
            $assets_url . '/js/main.js',
            ['mia-bootstrap', 'jquery', 'wp-util', 'underscore'],
            $theme_version,
            true
        );
    }
    
    // Video script for surgeon and location pages
    if (is_singular(['surgeon', 'location'])) {
        mia_enqueue_script(
            'mia-video',
            $assets_url . '/js/video.js',
            ['mia-bootstrap', 'jquery', 'wp-util', 'underscore'],
            $theme_version,
            true
        );
    }
    
    // Front page specific script
    if ($context && $context['type'] === 'home') {
        mia_enqueue_script(
            'mia-front-page',
            $assets_url . '/js/front-page.js',
            ['jquery', 'wp-util', 'underscore'],
            $theme_version,
            true
        );
    }
    
    // Header script for mobile CTA functionality
    mia_enqueue_script(
        'mia-header',
        $assets_url . '/js/header.js',
        ['mia-bootstrap', 'wp-util', 'underscore'],
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
    if (is_page_template('page-blank-canvas.php') || is_page_template('page-hero-canvas.php')) {
        $ctx = ['type' => 'page', 'file' => 'page.css', 'handle' => 'page'];
        return $ctx;
    }
    
    if (is_page_template('page-before-after-by-doctor.php')) {
        $ctx = ['type' => 'gallery', 'file' => 'gallery.css', 'handle' => 'gallery'];
        return $ctx;
    }
    
    // Front page
    if (is_front_page()) {
        $ctx = ['type' => 'home', 'file' => 'home.css', 'handle' => 'home'];
        return $ctx;
    }
    
    // Error pages
    if (is_404()) {
        $ctx = ['type' => '404', 'file' => '404.css', 'handle' => '404'];
        return $ctx;
    }
    
    // Search
    if (is_search()) {
        $ctx = ['type' => 'search', 'file' => 'search.css', 'handle' => 'search'];
        return $ctx;
    }
    
    // Taxonomies
    if (is_tax()) {
        $ctx = ['type' => 'taxonomy', 'file' => 'taxonomies.css', 'handle' => 'taxonomies'];
        return $ctx;
    }
    
    // Blog
    if (is_home() || (is_archive() && get_post_type() === 'post')) {
        $ctx = ['type' => 'archive', 'file' => 'archive.css', 'handle' => 'archive'];
        return $ctx;
    }
    
    if (is_singular('post')) {
        $ctx = ['type' => 'single', 'file' => 'single.css', 'handle' => 'single'];
        return $ctx;
    }
    
    // Custom post type archives
    if (is_post_type_archive()) {
        $post_type = get_post_type() ?: get_query_var('post_type');
        if ($post_type) {
            // Special handling for fat-transfer archive - use same CSS as single
            if ($post_type === 'fat-transfer') {
                $ctx = ['type' => 'fat-transfer', 'file' => 'fat-transfer.css', 'handle' => 'fat-transfer'];
                return $ctx;
            }
            
            // Default archive handling for other post types
            $ctx = [
                'type' => 'archive',
                'file' => $post_type . '-archive.css',
                'handle' => $post_type . '-archive'
            ];
            return $ctx;
        }
    }
    
    // Pages
    if (is_page()) {
        // Check for gallery shortcode
        $post_content = get_post_field('post_content', get_the_ID());
        if ($post_content && has_shortcode($post_content, 'gallery')) {
            $ctx = ['type' => 'gallery', 'file' => 'gallery.css', 'handle' => 'gallery'];
            return $ctx;
        }
        $ctx = ['type' => 'page', 'file' => 'page.css', 'handle' => 'page'];
        return $ctx;
    }
    
    // Custom post type singles
    if (is_singular()) {
        $post_type = get_post_type();
        
        // Special handling for procedures
        if ($post_type === 'procedure') {
            $ancestors = get_post_ancestors(get_queried_object());
            if (count($ancestors) === 2) {
                $ctx = ['type' => 'condition-child', 'file' => 'condition.css', 'handle' => 'condition'];
                return $ctx;
            }
        }
        
        // Special handling for conditions
        if ($post_type === 'condition') {
            $ctx = ['type' => 'condition', 'file' => 'condition.css', 'handle' => 'condition'];
            return $ctx;
        }
        
        // Special handling for fat-transfer
        if ($post_type === 'fat-transfer') {
            $ctx = ['type' => 'fat-transfer', 'file' => 'fat-transfer.css', 'handle' => 'fat-transfer'];
            return $ctx;
        }
        
        // Special handling for surgeons
        if ($post_type === 'surgeon') {
            $ctx = ['type' => 'surgeon', 'file' => 'surgeon.css', 'handle' => 'surgeon'];
            return $ctx;
        }
        
        // Special handling for cases
        if ($post_type === 'case') {
            $ctx = ['type' => 'case', 'file' => 'case.css', 'handle' => 'case'];
            return $ctx;
        }
        
        // Default single post type
        if ($post_type && !in_array($post_type, ['post', 'page'])) {
            $ctx = [
                'type' => 'single',
                'file' => $post_type . '.css',
                'handle' => $post_type
            ];
            return $ctx;
        }
    }
    
    $ctx = null;
    return $ctx;
}

/**
 * Get the primary script handle for the current page
 */
function mia_get_primary_script_handle() {
    $context = mia_get_current_context();
    
    if ($context && $context['type'] === 'home') {
        return 'mia-home';
    }
    
    // Check if main.js is enqueued
    if (wp_script_is('mia-main', 'enqueued')) {
        return 'mia-main';
    }
    
    // Fallback to jQuery
    return 'jquery';
}

/**
 * Preload critical assets for better performance
 */
function mia_preload_critical_assets() {
    $assets_url = get_template_directory_uri() . '/assets';
    
    // Preload critical fonts
    $fonts = [
        'Inter-VariableFont_opsz,wght.woff2',
        'Montserrat-VariableFont_wght.woff2'
    ];
    
    foreach ($fonts as $font) {
        echo '<link rel="preload" href="' . esc_url($assets_url . '/fonts/' . $font) . '" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
    }
    
    // Preload Font Awesome fonts
    echo '<link rel="preload" href="' . esc_url($assets_url . '/fontawesome/webfonts/fa-solid-900.woff2') . '" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
    echo '<link rel="preload" href="' . esc_url($assets_url . '/fontawesome/webfonts/fa-regular-400.woff2') . '" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
    
    // Preload critical CSS
    echo '<link rel="preload" href="' . esc_url($assets_url . '/css/_fonts.css') . '" as="style">' . "\n";
    echo '<link rel="preload" href="' . esc_url($assets_url . '/bootstrap/css/bootstrap.min.css') . '" as="style">' . "\n";
    echo '<link rel="preload" href="' . esc_url($assets_url . '/css/_base.css') . '" as="style">' . "\n";
}
add_action('wp_head', 'mia_preload_critical_assets', 2);

/**
 * Add resource hints for external resources
 */
function mia_resource_hints($hints, $relation_type) {
    if ('dns-prefetch' === $relation_type) {
        // Add any external domains used
        $hints[] = '//fonts.googleapis.com';
        $hints[] = '//www.google-analytics.com';
    }
    
    return $hints;
}
add_filter('wp_resource_hints', 'mia_resource_hints', 10, 2);

/**
 * Defer non-critical JavaScript (frontend only)
 */
function mia_defer_scripts($tag, $handle, $src) {
    // Don't defer scripts in admin area
    if (is_admin()) {
        return $tag;
    }
    
    // Scripts that should not be deferred
    $no_defer = ['jquery', 'jquery-core', 'jquery-migrate', 'wp-util', 'underscore', 'wp-api-request'];
    
    if (in_array($handle, $no_defer)) {
        return $tag;
    }
    
    // Add defer attribute to non-critical scripts (guard against double-insertion)
    if (strpos($tag, ' defer') === false) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'mia_defer_scripts', 10, 3);
