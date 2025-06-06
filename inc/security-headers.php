<?php
/**
 * Security Headers for Mia Aesthetics Theme
 * 
 * Implements comprehensive security headers including CSP, HSTS, and other protections.
 * Compatible with WP Rocket, Imagify, and common WordPress plugins.
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add security headers to all pages
 */
function mia_add_security_headers() {
    // Don't add headers in admin area to avoid plugin conflicts
    if (is_admin()) {
        return;
    }
    
    // HSTS (HTTP Strict Transport Security) - only if HTTPS
    if (is_ssl()) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }
    
    // X-Content-Type-Options
    header('X-Content-Type-Options: nosniff');
    
    // X-Frame-Options
    header('X-Frame-Options: SAMEORIGIN');
    
    // X-XSS-Protection
    header('X-XSS-Protection: 1; mode=block');
    
    // Referrer Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Permissions Policy (formerly Feature Policy)
    $permissions_policy = [
        'geolocation=()',
        'microphone=()',
        'camera=()',
        'payment=()',
        'usb=()',
        'magnetometer=()',
        'gyroscope=()',
        'speaker=()',
        'vibrate=()',
        'fullscreen=(self)',
        'sync-xhr=()'
    ];
    header('Permissions-Policy: ' . implode(', ', $permissions_policy));
    
    // Content Security Policy
    mia_add_csp_header();
}
add_action('send_headers', 'mia_add_security_headers');

/**
 * Generate and add Content Security Policy header
 */
function mia_add_csp_header() {
    $site_url = parse_url(home_url(), PHP_URL_HOST);
    
    // Base CSP directives
    $csp_directives = [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.youtube.com *.ytimg.com *.vimeo.com *.google.com *.googletagmanager.com *.googleapis.com *.gstatic.com *.facebook.net *.twitter.com *.instagram.com",
        "style-src 'self' 'unsafe-inline' *.googleapis.com *.gstatic.com *.youtube.com *.vimeo.com fonts.googleapis.com",
        "img-src 'self' data: blob: *.youtube.com *.ytimg.com *.vimeo.com *.google.com *.googleusercontent.com *.gravatar.com *.facebook.com *.instagram.com *.twitter.com placehold.co",
        "font-src 'self' data: *.googleapis.com *.gstatic.com",
        "connect-src 'self' *.youtube.com *.vimeo.com *.google.com *.googleapis.com *.facebook.com *.twitter.com *.instagram.com",
        "frame-src 'self' *.youtube.com *.vimeo.com *.google.com *.facebook.com *.twitter.com *.instagram.com",
        "media-src 'self' data: blob: *.youtube.com *.vimeo.com",
        "object-src 'none'",
        "base-uri 'self'",
        "form-action 'self' *.gravityforms.com *.google.com"
    ];
    
    // Add WP Rocket compatibility
    if (function_exists('rocket_clean_domain')) {
        $csp_directives[] = "worker-src 'self' blob:"; // For WP Rocket's service workers
    }
    
    // Add Imagify compatibility
    if (class_exists('Imagify_Plugin')) {
        $csp_directives[] = "worker-src 'self' blob:"; // For Imagify's WebP conversion workers
    }
    
    // Add Google Analytics/Tag Manager if commonly used
    $csp_directives[1] .= " *.analytics.google.com *.googleadservices.com";
    $csp_directives[3] .= " *.analytics.google.com *.googleadservices.com";
    
    $csp = implode('; ', $csp_directives);
    
    // Use Content-Security-Policy-Report-Only in development
    $header_name = (defined('WP_DEBUG') && WP_DEBUG) ? 
        'Content-Security-Policy-Report-Only' : 
        'Content-Security-Policy';
    
    header($header_name . ': ' . $csp);
}

/**
 * Add security headers via .htaccess for better performance
 * This generates .htaccess rules that can be manually added
 */
function mia_generate_htaccess_security_rules() {
    $rules = "
# Security Headers for Mia Aesthetics
<IfModule mod_headers.c>
    # HSTS
    Header always set Strict-Transport-Security \"max-age=31536000; includeSubDomains; preload\" env=HTTPS
    
    # X-Content-Type-Options
    Header always set X-Content-Type-Options \"nosniff\"
    
    # X-Frame-Options
    Header always set X-Frame-Options \"SAMEORIGIN\"
    
    # X-XSS-Protection
    Header always set X-XSS-Protection \"1; mode=block\"
    
    # Referrer Policy
    Header always set Referrer-Policy \"strict-origin-when-cross-origin\"
    
    # Remove Server header
    Header always unset Server
    Header always unset X-Powered-By
</IfModule>

# Prevent access to sensitive files
<FilesMatch \"\\.(htaccess|htpasswd|ini|log|sh|sql|tar|gz)$\">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Prevent access to wp-config.php
<Files wp-config.php>
    Order Allow,Deny
    Deny from all
</Files>
";
    
    return $rules;
}

/**
 * Remove unnecessary headers that leak information
 */
function mia_remove_version_headers() {
    // Remove WordPress version from head
    remove_action('wp_head', 'wp_generator');
    
    // Remove version from RSS feeds
    add_filter('the_generator', '__return_empty_string');
    
    // Remove version from scripts and styles
    add_filter('style_loader_src', 'mia_remove_version_parameter', 15);
    add_filter('script_loader_src', 'mia_remove_version_parameter', 15);
}
add_action('init', 'mia_remove_version_headers');

/**
 * Remove version parameter from static assets
 */
function mia_remove_version_parameter($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

/**
 * Add nonce to inline scripts for CSP compatibility
 */
function mia_add_script_nonce($tag, $handle, $src) {
    // Generate nonce for inline scripts
    static $nonce = null;
    if ($nonce === null) {
        $nonce = wp_create_nonce('script_nonce_' . get_current_user_id());
    }
    
    // Add nonce to inline scripts (those without src)
    if (empty($src) && strpos($tag, 'nonce=') === false) {
        $tag = str_replace('<script', '<script nonce="' . esc_attr($nonce) . '"', $tag);
    }
    
    return $tag;
}
// Uncomment when ready to implement stricter CSP
// add_filter('script_loader_tag', 'mia_add_script_nonce', 10, 3);

/**
 * Security recommendations for WP Rocket users
 */
function mia_wp_rocket_security_recommendations() {
    if (!function_exists('rocket_clean_domain')) {
        return;
    }
    
    // Add security-conscious cache exclusions
    add_filter('rocket_cache_reject_uri', function($uris) {
        $security_uris = [
            '/wp-admin/(.*)',
            '/wp-login.php',
            '/wp-cron.php',
            '/xmlrpc.php',
            '(.*)preview=true(.*)',
            '(.*)wp-comments-post.php(.*)',
            '(.*)wp-admin/admin-ajax.php(.*)'
        ];
        return array_merge($uris, $security_uris);
    });
}
add_action('init', 'mia_wp_rocket_security_recommendations');

/**
 * Add security headers to admin area (limited set)
 */
function mia_add_admin_security_headers() {
    if (!is_admin()) {
        return;
    }
    
    // Minimal headers for admin to avoid plugin conflicts
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    
    // Only add HSTS if HTTPS
    if (is_ssl()) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}
add_action('admin_init', 'mia_add_admin_security_headers');
?>