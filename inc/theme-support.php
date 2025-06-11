<?php
/**
 * WordPress Theme Support Features
 * 
 * Comprehensive theme support declarations optimized for modern WordPress
 * and compatible with Yoast SEO, WP Rocket, Imagify, and Accessibe.
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add theme support features
 */
function mia_theme_support() {
    // Post thumbnails (already declared but included for completeness)
    add_theme_support('post-thumbnails');
    
    // HTML5 support for better semantic markup (Accessibe foundation)
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
        'navigation-widgets'
    ]);
    
    // Custom logo support
    add_theme_support('custom-logo', [
        'height'               => 100,
        'width'                => 300,
        'flex-height'          => true,
        'flex-width'           => true,
        'header-text'          => ['site-title', 'site-description'],
        'unlink-homepage-logo' => false,
    ]);
    
    // Custom background support
    add_theme_support('custom-background', [
        'default-color'      => 'ffffff',
        'default-attachment' => 'fixed',
        'default-repeat'     => 'no-repeat',
        'default-position-x' => 'center',
        'default-position-y' => 'top',
    ]);
    
    // Custom header support
    add_theme_support('custom-header', [
        'default-image'      => '',
        'width'              => 1920,
        'height'             => 800,
        'flex-height'        => true,
        'flex-width'         => true,
        'header-text'        => false,
        'uploads'            => true,
        'video'              => true,
    ]);
    
    // Responsive embeds (YouTube, Vimeo, etc.)
    add_theme_support('responsive-embeds');
    
    // Wide alignment for block editor
    add_theme_support('align-wide');
    
    // Custom line height control
    add_theme_support('custom-line-height');
    
    // Custom units (rem, em, vh, vw)
    add_theme_support('custom-units');
    
    // Custom spacing control
    add_theme_support('custom-spacing');
    
    // Editor styles for block editor
    add_theme_support('editor-styles');
    
    // Dark editor style for better contrast
    add_theme_support('dark-editor-style');
    
    // Automatic feed links
    add_theme_support('automatic-feed-links');
    
    // Title tag support (let WordPress handle it, not Yoast conflicts)
    add_theme_support('title-tag');
    
    // Block template parts (for full site editing preparation)
    add_theme_support('block-template-parts');
    
    // WP Engine specific optimizations
    if (defined('WPE_APIKEY')) {
        // Optimize for WP Engine's infrastructure
        add_theme_support('wpe-optimized-images');
    }
    
    // WP Rocket compatibility
    if (function_exists('rocket_clean_domain')) {
        // Let WP Rocket handle these optimizations
        add_theme_support('wp-rocket-compatible');
    }
}
add_action('after_setup_theme', 'mia_theme_support');

/**
 * Add editor styles
 */
function mia_add_editor_styles() {
    // Add editor stylesheet for block editor
    add_editor_style([
        'assets/css/_fonts.css',
        'assets/css/_base.css',
        'assets/bootstrap/css/bootstrap.min.css'
    ]);
}
add_action('after_setup_theme', 'mia_add_editor_styles');

/**
 * Custom image sizes for responsive design
 * Optimized for Imagify and WP Engine CDN
 */
function mia_add_image_sizes() {
    // Hero images (already defined in media-helpers.php but ensuring consistency)
    add_image_size('hero-mobile', 640, 400, true);
    add_image_size('hero-tablet', 1024, 600, true);
    add_image_size('hero-desktop', 1920, 800, true);
    
    // Card thumbnails for listings
    add_image_size('card-small', 300, 200, true);
    add_image_size('card-medium', 450, 300, true);
    add_image_size('card-large', 600, 400, true);
    
    // Profile images for surgeons
    add_image_size('profile-small', 150, 150, true);
    add_image_size('profile-medium', 300, 300, true);
    add_image_size('profile-large', 600, 600, true);
    
    // Location images
    add_image_size('location-thumb', 250, 167, true);
    add_image_size('location-featured', 800, 533, true);
    
    // Blog/content images
    add_image_size('content-small', 400, 267, true);
    add_image_size('content-medium', 800, 533, true);
    add_image_size('content-large', 1200, 800, true);
}
add_action('after_setup_theme', 'mia_add_image_sizes');

/**
 * Block editor color palette
 * Matches theme design system
 */
function mia_block_editor_settings() {
    // Custom color palette
    add_theme_support('editor-color-palette', [
        [
            'name'  => 'Gold Primary',
            'slug'  => 'gold-primary',
            'color' => '#c8b273',
        ],
        [
            'name'  => 'Gold Dark',
            'slug'  => 'gold-dark',
            'color' => '#b8a263',
        ],
        [
            'name'  => 'Navy Blue',
            'slug'  => 'navy-blue',
            'color' => '#1a365d',
        ],
        [
            'name'  => 'Dark Gray',
            'slug'  => 'dark-gray',
            'color' => '#2d3748',
        ],
        [
            'name'  => 'Medium Gray',
            'slug'  => 'medium-gray',
            'color' => '#718096',
        ],
        [
            'name'  => 'Light Gray',
            'slug'  => 'light-gray',
            'color' => '#f7fafc',
        ],
        [
            'name'  => 'White',
            'slug'  => 'white',
            'color' => '#ffffff',
        ],
        [
            'name'  => 'Black',
            'slug'  => 'black',
            'color' => '#000000',
        ],
    ]);
    
    // Custom font sizes
    add_theme_support('editor-font-sizes', [
        [
            'name' => 'Small',
            'size' => 14,
            'slug' => 'small'
        ],
        [
            'name' => 'Regular',
            'size' => 16,
            'slug' => 'regular'
        ],
        [
            'name' => 'Large',
            'size' => 20,
            'slug' => 'large'
        ],
        [
            'name' => 'Extra Large',
            'size' => 24,
            'slug' => 'extra-large'
        ],
        [
            'name' => 'Huge',
            'size' => 32,
            'slug' => 'huge'
        ]
    ]);
    
    // Disable custom colors and font sizes if design system should be strict
    // add_theme_support('disable-custom-colors');
    // add_theme_support('disable-custom-font-sizes');
}
add_action('after_setup_theme', 'mia_block_editor_settings');

/**
 * Register navigation menus
 */
function mia_register_menus() {
    register_nav_menus([
        'primary'   => 'Primary Navigation',
        'footer'    => 'Footer Navigation',
        'legal'     => 'Legal Links',
        'social'    => 'Social Media Links',
    ]);
}
add_action('after_setup_theme', 'mia_register_menus');

/**
 * Content width for better responsive embeds
 */
function mia_content_width() {
    $GLOBALS['content_width'] = apply_filters('mia_content_width', 1200);
}
add_action('after_setup_theme', 'mia_content_width', 0);

/**
 * Add theme support for starter content (for new installations)
 */
function mia_starter_content() {
    add_theme_support('starter-content', [
        'widgets' => [
            'footer-1' => [
                'text_business_info',
            ],
            'footer-2' => [
                'text_about',
            ],
            'footer-3' => [
                'text_follow',
            ],
        ],
        'posts' => [
            'home',
            'about' => [
                'thumbnail' => '{{image-logo}}',
            ],
            'blog' => [
                'thumbnail' => '{{image-logo}}',
            ],
        ],
        'theme_mods' => [
            'custom_logo' => '{{image-logo}}',
        ],
        'nav_menus' => [
            'primary' => [
                'name' => 'Primary Navigation',
                'items' => [
                    'link_home',
                    'page_about',
                    'page_blog',
                ],
            ],
        ],
    ]);
}
add_action('after_setup_theme', 'mia_starter_content');

/**
 * Yoast SEO compatibility
 */
function mia_yoast_compatibility() {
    // Ensure Yoast breadcrumbs work properly
    if (function_exists('yoast_breadcrumb')) {
        add_theme_support('yoast-seo-breadcrumbs');
    }
    
    // Let Yoast handle title tags
    if (defined('WPSEO_VERSION')) {
        // Remove theme title tag support if Yoast is active
        remove_theme_support('title-tag');
    }
}
add_action('wp', 'mia_yoast_compatibility');

/**
 * Block patterns support (for future content creation)
 */
function mia_register_block_patterns() {
    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category(
            'mia-aesthetics',
            ['label' => 'Mia Aesthetics']
        );
    }
}
add_action('init', 'mia_register_block_patterns');
