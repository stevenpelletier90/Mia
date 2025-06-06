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
        'mia-normalize' => '/normalize/normalize.css',
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
    if (in_array($context['type'], ['archive', 'single'])) {
        $dependencies[] = 'mia-bootstrap';
    }
    
    mia_enqueue_style(
        'mia-' . $context['handle'],
        $style_path,
        $dependencies,
        $version
    );
    
    // Special case: Hero styles for front page
    if (is_front_page()) {
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
        ['jquery'],
        $theme_version,
        true
    );
    
    // Get context for conditional script loading
    $context = mia_get_current_context();
    $load_main = true;
    
    // Context-specific scripts
    if ($context) {
        switch ($context['type']) {
            case 'home':
                // Home page uses its own script instead of main.js
                mia_enqueue_script(
                    'mia-home',
                    $assets_url . '/js/home.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                $load_main = false;
                break;
                
            case 'condition':
            case 'condition-child':
                // Condition pages load both main.js and condition.js
                mia_enqueue_script(
                    'mia-condition',
                    $assets_url . '/js/condition.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'surgeon':
                // Surgeon pages load both main.js and surgeon.js
                mia_enqueue_script(
                    'mia-surgeon',
                    $assets_url . '/js/surgeon.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
                
            case 'gallery':
                // Gallery pages load both main.js and gallery.js
                mia_enqueue_script(
                    'mia-gallery',
                    $assets_url . '/js/gallery.js',
                    ['mia-bootstrap', 'jquery'],
                    $theme_version,
                    true
                );
                break;
        }
    }
    
    // Load main.js on most pages
    if ($load_main) {
        mia_enqueue_script(
            'mia-main',
            $assets_url . '/js/main.js',
            ['mia-bootstrap', 'jquery'],
            $theme_version,
            true
        );
    }
    
    // Video script for surgeon and location pages
    if (is_singular(['surgeon', 'location'])) {
        mia_enqueue_script(
            'mia-video',
            $assets_url . '/js/video.js',
            ['mia-bootstrap', 'jquery'],
            $theme_version,
            true
        );
    }
    
    // Front page specific script
    if (is_front_page()) {
        mia_enqueue_script(
            'mia-front-page',
            $assets_url . '/js/front-page.js',
            ['jquery'],
            $theme_version,
            true
        );
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
    // Add AJAX URL for scripts that need it
    wp_localize_script('jquery', 'mia_ajax', [
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mia_ajax_nonce')
    ]);
    
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
                error_log("Mia Theme: Style file not found - {$file_path}");
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
                error_log("Mia Theme: Script file not found - {$file_path}");
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
    // Special templates
    if (is_page_template('page-blank-canvas.php') || is_page_template('page-hero-canvas.php')) {
        return ['type' => 'page', 'file' => 'page.css', 'handle' => 'page'];
    }
    
    if (is_page_template('page-before-after-by-doctor.php')) {
        return ['type' => 'gallery', 'file' => 'gallery.css', 'handle' => 'gallery'];
    }
    
    // Front page
    if (is_front_page()) {
        return ['type' => 'home', 'file' => 'home.css', 'handle' => 'home'];
    }
    
    // Error pages
    if (is_404()) {
        return ['type' => '404', 'file' => '404.css', 'handle' => '404'];
    }
    
    // Search
    if (is_search()) {
        return ['type' => 'search', 'file' => 'search.css', 'handle' => 'search'];
    }
    
    // Taxonomies
    if (is_tax()) {
        return ['type' => 'taxonomy', 'file' => 'taxonomies.css', 'handle' => 'taxonomies'];
    }
    
    // Blog
    if (is_home() || (is_archive() && get_post_type() === 'post')) {
        return ['type' => 'archive', 'file' => 'archive.css', 'handle' => 'archive'];
    }
    
    if (is_singular('post')) {
        return ['type' => 'single', 'file' => 'single.css', 'handle' => 'single'];
    }
    
    // Custom post type archives
    if (is_post_type_archive()) {
        $post_type = get_post_type() ?: get_query_var('post_type');
        if ($post_type) {
            return [
                'type' => 'archive',
                'file' => $post_type . '-archive.css',
                'handle' => $post_type . '-archive'
            ];
        }
    }
    
    // Pages
    if (is_page()) {
        // Check for gallery shortcode
        global $post;
        if ($post && has_shortcode($post->post_content, 'gallery')) {
            return ['type' => 'gallery', 'file' => 'gallery.css', 'handle' => 'gallery'];
        }
        return ['type' => 'page', 'file' => 'page.css', 'handle' => 'page'];
    }
    
    // Custom post type singles
    if (is_singular()) {
        $post_type = get_post_type();
        
        // Special handling for procedures
        if ($post_type === 'procedure') {
            $ancestors = get_post_ancestors(get_queried_object());
            if (count($ancestors) === 2) {
                return ['type' => 'condition-child', 'file' => 'condition.css', 'handle' => 'condition'];
            }
        }
        
        // Special handling for conditions
        if ($post_type === 'condition') {
            return ['type' => 'condition', 'file' => 'condition.css', 'handle' => 'condition'];
        }
        
        // Special handling for surgeons
        if ($post_type === 'surgeon') {
            return ['type' => 'surgeon', 'file' => 'surgeon.css', 'handle' => 'surgeon'];
        }
        
        // Default single post type
        if ($post_type && !in_array($post_type, ['post', 'page'])) {
            return [
                'type' => 'single',
                'file' => $post_type . '.css',
                'handle' => $post_type
            ];
        }
    }
    
    return null;
}

/**
 * Get the primary script handle for the current page
 */
function mia_get_primary_script_handle() {
    if (is_front_page()) {
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
    $no_defer = ['jquery', 'jquery-core', 'jquery-migrate'];
    
    if (in_array($handle, $no_defer)) {
        return $tag;
    }
    
    // Add defer attribute to non-critical scripts
    if (strpos($tag, 'defer') === false) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'mia_defer_scripts', 10, 3);
