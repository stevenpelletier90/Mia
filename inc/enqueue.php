<?php
/**
 * Optimized Asset Management for Mia Aesthetics Theme
 *
 * Handles all script and style enqueueing with caching‑friendly filename‑hash
 * versioning, conditional loading, and performance optimizations tailored for
 * WP Engine + WP Rocket.
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ---------------------------------------------------------------------------
 * Constants
 * ---------------------------------------------------------------------------
 */
if ( ! defined( 'MIA_ASSET_HASH_LEN' ) ) {
    // Length of the MD5 hash used for cache‑busting (e.g. main.1a2b3c4d.js)
    define( 'MIA_ASSET_HASH_LEN', 8 );
}

/**
 * Register a style or script with automatic file‑hash versioning.
 *
 * @param string $type   Either 'style' or 'script'.
 * @param string $handle WordPress handle.
 * @param string $path   File path relative to /assets (leading slash allowed).
 * @param array  $deps   Optional dependencies.
 * @param bool   $footer Load script in footer (scripts only).
 */
function mia_register_asset( $type, $handle, $path, $deps = [], $footer = true ) {
    $base_uri = trailingslashit( get_template_directory_uri() ) . 'assets';
    $base_dir = trailingslashit( get_template_directory() )     . 'assets';

    $src  = $base_uri . $path;
    $file = wp_normalize_path( $base_dir . $path );

    // Log missing files during development but fail silently in production.
    if ( WP_DEBUG && ! file_exists( $file ) ) {
        error_log( "[mia_register_asset] Missing {$type} asset: {$file}" );
    }

    // Use file modification time for versioning—lighter than computing an MD5 hash on every request.
    $ver = file_exists( $file ) ? filemtime( $file ) : null;

    if ( $type === 'style' ) {
        wp_register_style( $handle, $src, $deps, $ver );
    } else {
        wp_register_script( $handle, $src, $deps, $ver, $footer );
        wp_script_add_data( $handle, 'strategy', 'defer' ); // Non‑critical JS → defer
    }
}

/**
 * ---------------------------------------------------------------------------
 * Context mappings (single source of truth for CSS/JS filenames)
 * ---------------------------------------------------------------------------
 */
function mia_get_template_mappings() {
    return [
        // Page Templates (available for selection)
        'page-blank-canvas'           => ['css' => 'page-blank-canvas.css',           'js' => 'page-blank-canvas.js'],
        'page-hero-canvas'            => ['css' => 'page-hero-canvas.css',            'js' => 'page-hero-canvas.js'],
        'page-hero-canvas-no-bc'      => ['css' => 'page-hero-canvas-no-bc.css',      'js' => 'page-hero-canvas-no-bc.js'],
        'page-no-bc'                  => ['css' => 'page-no-bc.css',                  'js' => 'page-no-bc.js'],
        'page-before-after-by-doctor' => ['css' => 'page-before-after-by-doctor.css', 'js' => 'page-before-after-by-doctor.js'],
        'page-case-category'          => ['css' => 'page-case-category.css',          'js' => 'page-case-category.js'],
        'page-treatment-layout'       => ['css' => 'page-treatment-layout.css',       'js' => 'page-treatment-layout.js'],
        'page-condition-layout'       => ['css' => 'page-condition-layout.css',       'js' => 'page-condition-layout.js'],
        
        // Core WordPress Templates
        'front-page'      => ['css' => 'front-page.css',    'js' => 'front-page.js'],
        '404'             => ['css' => '404.css',           'js' => '404.js'],
        'search'          => ['css' => 'search.css',        'js' => 'search.js'],
        'case-category'   => ['css' => 'case-category.css', 'js' => 'case-category.js'],
        'category'        => ['css' => 'category.css',      'js' => 'category.js'],
        'archive'         => ['css' => 'archive.css',       'js' => 'archive.js'],
        'single-post'     => ['css' => 'single-post.css',   'js' => 'single-post.js'],
        'page'            => ['css' => 'page.css',          'js' => 'page.js'],
        
        // Default Single Templates (fallbacks for fixed post types)
        'single-case'        => ['css' => 'single-case.css',        'js' => 'single-case.js'],
        'single-condition'   => ['css' => 'page-condition-layout.css','js' => 'page-condition-layout.js'],
        'single-location'    => ['css' => 'single-location.css',    'js' => 'single-location.js'],
        'single-surgeon'     => ['css' => 'single-surgeon.css',     'js' => 'single-surgeon.js'],
        'single-special'     => ['css' => 'single-special.css',     'js' => 'single-special.js'],
        
        // Archive Templates
        'archive-case'        => ['css' => 'archive-case.css',        'js' => 'archive-case.js'],
        'archive-condition'   => ['css' => 'archive-condition.css',   'js' => 'archive-condition.js'],
        'archive-fat-transfer'=> ['css' => 'page-condition-layout.css','js' => 'page-condition-layout.js'],
        'archive-location'    => ['css' => 'archive-location.css',    'js' => 'archive-location.js'],
        'archive-non-surgical'=> ['css' => 'archive-non-surgical.css','js' => 'archive-non-surgical.js'],
        'archive-procedure'   => ['css' => 'archive-procedure.css',   'js' => 'archive-procedure.js'],
        'archive-special'     => ['css' => 'archive-special.css',     'js' => 'archive-special.js'],
        'archive-surgeon'     => ['css' => 'archive-surgeon.css',     'js' => 'archive-surgeon.js'],
        
        // Home & Index
        'home'               => ['css' => 'home.css',          'js' => 'home.js'],
        'index'              => ['css' => 'index.css',         'js' => 'index.js'],
    ];
}

/**
 * Detect the current template for asset loading.
 * Priority: Selected Template > Default Template > Fallback
 *
 * @return string|null Template key for asset mapping.
 */
function mia_detect_template_key() {
    // 1. Check for user-selected template (highest priority)
    if ( is_singular() || is_page() ) {
        $selected_template = get_page_template_slug();
        if ( $selected_template ) {
            $template_key = str_replace( '.php', '', $selected_template );
            if ( array_key_exists( $template_key, mia_get_template_mappings() ) ) {
                return $template_key;
            }
        }
    }
    
    // 2. WordPress core pages
    if ( is_front_page() )                return 'front-page';
    if ( is_404() )                       return '404';
    if ( is_search() )                    return 'search';
    if ( is_tax( 'case-category' ) )      return 'case-category';
    if ( is_category() )                  return 'category';
    
    // 3. Check for blog/posts page BEFORE generic archive check
    // is_home() is true for the posts page when set in Settings > Reading
    if ( is_home() && ! is_front_page() ) {
        // This is the blog posts page - use archive template
        return 'archive';
    }
    
    // 4. Archive pages
    if ( is_archive() && get_post_type() === 'post' ) return 'archive';
    
    if ( is_post_type_archive() ) {
        $post_type = get_post_type() ?: get_query_var( 'post_type' );
        $archive_template = 'archive-' . $post_type;
        if ( array_key_exists( $archive_template, mia_get_template_mappings() ) ) {
            return $archive_template;
        }
        return 'archive'; // Fallback to generic archive
    }
    
    // 5. Single posts/pages
    if ( is_singular( 'post' ) )          return 'single-post';
    if ( is_page() )                      return 'page';
    
    // 6. Custom post type singles (fallback to default templates)
    if ( is_singular() ) {
        $post_type = get_post_type();
        $single_template = 'single-' . $post_type;
        if ( array_key_exists( $single_template, mia_get_template_mappings() ) ) {
            return $single_template;
        }
    }
    
    // 7. Final fallback
    return 'index';
}

/**
 * ---------------------------------------------------------------------------
 * Main enqueue callback
 * ---------------------------------------------------------------------------
 */
function mia_enqueue_assets() {
    // ------------------------ Critical/global assets -----------------------
    mia_register_asset( 'style', 'mia-fonts',     '/css/fonts.css' );
    mia_register_asset( 'style', 'mia-bootstrap', '/bootstrap/css/bootstrap.min.css', [ 'mia-fonts' ] );
    mia_register_asset( 'style', 'mia-base',      '/css/base.css', [ 'mia-bootstrap' ] );

    mia_register_asset( 'style', 'mia-fontawesome', '/fontawesome/css/all.min.css', [ 'mia-base' ] );
    mia_register_asset( 'style', 'mia-header',       '/css/header.css', [ 'mia-base', 'mia-bootstrap' ] );
    mia_register_asset( 'style', 'mia-footer',       '/css/footer.css', [ 'mia-base', 'mia-bootstrap' ] );

    mia_register_asset( 'script', 'mia-bootstrap', '/bootstrap/js/bootstrap.bundle.min.js' ); // no jQuery
    mia_register_asset( 'script', 'mia-header',    '/js/header.js', [ 'mia-bootstrap' ] );

    // ------------------------ Template-specific assets ---------------------
    $template_key = mia_detect_template_key();
    $templates    = mia_get_template_mappings();


    if ( $template_key && isset( $templates[ $template_key ] ) ) {
        $template = $templates[ $template_key ];

        if ( ! empty( $template['css'] ) ) {
            $css_deps = [ 'mia-base', 'mia-header', 'mia-footer' ];
            
            // Add hero section CSS dependency for front page
            if ( $template_key === 'front-page' ) {
                mia_register_asset( 'style', 'mia-hero-section', '/css/hero-section.css', [ 'mia-base', 'mia-bootstrap' ] );
                $css_deps[] = 'mia-hero-section';
            }
            
            mia_register_asset( 'style', 'mia-' . $template_key, '/css/' . $template['css'], $css_deps );
        }

        if ( ! empty( $template['js'] ) ) {
            mia_register_asset( 'script', 'mia-' . $template_key, '/js/' . $template['js'], [ 'mia-bootstrap' ] );
        }
    } elseif ( WP_DEBUG && $template_key ) {
        // Log when template detection succeeds but no mapping exists
        error_log( "[mia_enqueue_assets] Template key '{$template_key}' detected but no mapping found in template mappings." );
    } elseif ( WP_DEBUG && ! $template_key ) {
        // Log when template detection fails completely
        error_log( "[mia_enqueue_assets] Template detection failed - no template key detected for current request." );
    }

    // ------------------------ Enqueue registered ---------------------------
    foreach ( wp_styles()->registered as $h => $_ ) {
        if ( str_starts_with( $h, 'mia-' ) || in_array( $h, [ 'mia-fonts', 'mia-bootstrap', 'mia-base', 'mia-fontawesome' ], true ) ) {
            wp_enqueue_style( $h );
        }
    }
    foreach ( wp_scripts()->registered as $h => $_ ) {
        if ( str_starts_with( $h, 'mia-' ) ) {
            wp_enqueue_script( $h );
        }
    }

    // Attach runtime configuration
    mia_attach_config();
}
add_action( 'wp_enqueue_scripts', 'mia_enqueue_assets' );

/**
 * Localise runtime configuration to the primary script.
 */
function mia_attach_config() {
    $primary = mia_get_primary_script_handle();
    if ( ! $primary || ! wp_script_is( $primary, 'registered' ) ) {
        return;
    }

    wp_localize_script( $primary, 'mia_config', [
        'theme_url' => get_template_directory_uri(),
        'site_url'  => home_url(),
        'is_mobile' => wp_is_mobile(),
        'debug'     => WP_DEBUG,
        'ajax_url'  => admin_url( 'admin-ajax.php' ),
        'nonce'     => wp_create_nonce( 'mia_ajax' ),
    ] );
}

/**
 * Determine the primary bundle for localisation.
 *
 * @return string Handle of the primary script.
 */
function mia_get_primary_script_handle() {
    $template_key = mia_detect_template_key();

    if ( $template_key && wp_script_is( 'mia-' . $template_key, 'registered' ) ) {
        return 'mia-' . $template_key;
    }

    if ( wp_script_is( 'mia-header', 'registered' ) ) {
        return 'mia-header';
    }

    return 'mia-bootstrap';
}

/**
 * Add dns‑prefetch resource hints for external domains.
 */
function mia_resource_hints( $hints, $relation_type ) {
    if ( 'dns-prefetch' === $relation_type ) {
        $hints[] = '//fonts.googleapis.com';
        $hints[] = '//www.google-analytics.com';
        // $hints[] = '//cdn.jsdelivr.net'; // Uncomment if Font Awesome served via CDN
    }
    return $hints;
}
add_filter( 'wp_resource_hints', 'mia_resource_hints', 10, 2 );


?>