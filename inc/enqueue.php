<?php
/**
 * Optimized Asset Management for Mia Aesthetics Theme
 *
 * Handles all script and style enqueueing with caching‑friendly filename‑hash
 * versioning, conditional loading, and performance optimizations tailored for
 * WP Engine + WP Rocket.
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
function mia_get_context_mappings() {
    return [
        // Special templates
        'page-blank-canvas'           => ['type' => 'page', 'css' => '_page-blank-canvas.css',           'js' => 'page-blank-canvas.js'],
        'page-hero-canvas'            => ['type' => 'page', 'css' => '_page-hero-canvas.css',            'js' => 'page-hero-canvas.js'],
        'page-before-after-by-doctor' => ['type' => 'page', 'css' => '_page-before-after-by-doctor.css', 'js' => 'page-before-after-by-doctor.js'],
        'page-case-category'          => ['type' => 'page', 'css' => '_page-case-category.css',          'js' => 'page-case-category.js'],

        // Core
        'home'         => ['type' => 'home',         'css' => '_front-page.css', 'js' => 'front-page.js'],
        '404'          => ['type' => '404',          'css' => '_404.css',        'js' => '404.js'],
        'search'       => ['type' => 'search',       'css' => '_search.css',     'js' => 'search.js'],
        'case-category'=> ['type' => 'case-category','css' => '_case-category.css','js' => 'case-category.js'],
        'category'     => ['type' => 'category',     'css' => '_category.css',   'js' => 'category.js'],
        'archive'      => ['type' => 'archive',      'css' => '_archive.css',    'js' => 'archive.js'],
        'post'         => ['type' => 'post',         'css' => '_post.css',       'js' => 'single-post.js'],
        'page'         => ['type' => 'page',         'css' => '_page.css',       'js' => 'page.js'],

        // Custom post types
        'procedure'        => ['type' => 'procedure',        'css' => '_procedure.css',        'js' => 'single-procedure.js'],
        'procedure-archive'=> ['type' => 'procedure-archive','css' => '_archive-procedure.css','js' => 'archive-procedure.js'],
        'condition'        => ['type' => 'condition',        'css' => '_condition.css',        'js' => 'single-condition.js'],
        'condition-child'  => ['type' => 'condition-child',  'css' => '_condition.css',        'js' => 'single-condition.js'],
        'condition-archive'=> ['type' => 'condition-archive','css' => '_archive-condition.css','js' => 'archive-condition.js'],
        'fat-transfer'     => ['type' => 'fat-transfer',     'css' => '_fat-transfer.css',     'js' => 'single-fat-transfer.js'],
        'surgeon'          => ['type' => 'surgeon',          'css' => '_surgeon.css',          'js' => 'single-surgeon.js'],
        'surgeon-archive'  => ['type' => 'surgeon-archive',  'css' => '_archive-surgeon.css',  'js' => 'archive-surgeon.js'],
        'case'             => ['type' => 'case',             'css' => '_case.css',             'js' => 'single-case.js'],
        'case-archive'     => ['type' => 'case-archive',     'css' => '_archive-case.css',     'js' => 'archive-case.js'],
        'location'         => ['type' => 'location',         'css' => '_location.css',         'js' => 'single-location.js'],
        'location-archive' => ['type' => 'location-archive', 'css' => '_archive-location.css', 'js' => 'archive-location.js'],
        'non-surgical'     => ['type' => 'non-surgical',     'css' => '_non-surgical.css',     'js' => 'single-non-surgical.js'],
        'non-surgical-archive' => ['type' => 'non-surgical-archive', 'css' => '_archive-non-surgical.css', 'js' => 'archive-non-surgical.js'],
        'special'          => ['type' => 'special',          'css' => '_special.css',          'js' => 'single-special.js'],
        'special-archive'  => ['type' => 'special-archive',  'css' => '_archive-special.css',  'js' => 'archive-special.js'],
        'fat-transfer-archive' => ['type' => 'fat-transfer-archive', 'css' => '_archive-fat-transfer.css', 'js' => 'archive-fat-transfer.js'],
    ];
}

/**
 * Detect the current context key for asset mapping.
 *
 * @return string|null Mapping key or null when not found.
 */
function mia_detect_context_key() {
    // Template → mapping key look‑up
    $template_map = [
        'page-blank-canvas.php'           => 'page-blank-canvas',
        'page-hero-canvas.php'            => 'page-hero-canvas',
        'page-before-after-by-doctor.php' => 'page-before-after-by-doctor',
        'page-case-category.php'          => 'page-case-category',
    ];
    foreach ( $template_map as $tpl => $key ) {
        if ( is_page_template( $tpl ) ) {
            return $key;
        }
    }

    if ( is_front_page() )                                  return 'home';
    if ( is_404() )                                         return '404';
    if ( is_search() )                                      return 'search';
    if ( is_tax( 'case-category' ) )                        return 'case-category';
    if ( is_category() )                                    return 'category';
    if ( is_home() || ( is_archive() && get_post_type() === 'post' ) ) return 'archive';
    if ( is_singular( 'post' ) )                            return 'post';

    // CPT archive
    if ( is_post_type_archive() ) {
        $pt = get_post_type() ?: get_query_var( 'post_type' );
        if ( $pt === 'fat-transfer' ) return 'fat-transfer-archive';
        if ( $pt === 'case' )        return 'case-archive';
        if ( $pt === 'procedure' )   return 'procedure-archive';
        if ( $pt === 'location' )    return 'location-archive';
        if ( $pt === 'surgeon' )     return 'surgeon-archive';
        if ( $pt === 'condition' )   return 'condition-archive';
        if ( $pt === 'non-surgical' ) return 'non-surgical-archive';
        if ( $pt === 'special' )     return 'special-archive';
        if ( $pt )                   return 'archive'; // Generic archive assets
    }

    // Page
    if ( is_page() ) return 'page';

    // CPT single
    if ( is_singular() ) {
        $pt = get_post_type();
        // Check for custom template selection via page template dropdown
        if ( $pt === 'procedure' ) {
            $template = get_page_template_slug();
            if ( $template === 'single-condition.php' ) {
                return 'condition';
            }
            return 'procedure';
        }
        if ( array_key_exists( $pt, mia_get_context_mappings() ) ) {
            return $pt;
        }
    }

    return null;
}

/**
 * ---------------------------------------------------------------------------
 * Main enqueue callback
 * ---------------------------------------------------------------------------
 */
function mia_enqueue_assets() {
    // ------------------------ Critical/global assets -----------------------
    mia_register_asset( 'style', 'mia-fonts',     '/css/_fonts.css' );
    mia_register_asset( 'style', 'mia-bootstrap', '/bootstrap/css/bootstrap.min.css', [ 'mia-fonts' ] );
    mia_register_asset( 'style', 'mia-base',      '/css/_base.css', [ 'mia-bootstrap' ] );

    mia_register_asset( 'style', 'mia-fontawesome', '/fontawesome/css/all.min.css', [ 'mia-base' ] );
    mia_register_asset( 'style', 'mia-header',       '/css/_header.css', [ 'mia-base', 'mia-bootstrap' ] );
    mia_register_asset( 'style', 'mia-footer',       '/css/_footer.css', [ 'mia-base', 'mia-bootstrap' ] );

    mia_register_asset( 'script', 'mia-bootstrap', '/bootstrap/js/bootstrap.bundle.min.js' ); // no jQuery
    mia_register_asset( 'script', 'mia-header',    '/js/header.js', [ 'mia-bootstrap' ] );

    // ------------------------ Context assets -------------------------------
    $key      = mia_detect_context_key();
    $mappings = mia_get_context_mappings();

    if ( $key && isset( $mappings[ $key ] ) ) {
        $map = $mappings[ $key ];

        if ( ! empty( $map['css'] ) ) {
            mia_register_asset( 'style', 'mia-' . $key, '/css/' . $map['css'], [ 'mia-base', 'mia-header', 'mia-footer' ] );
        }

        if ( ! empty( $map['js'] ) ) {
            mia_register_asset( 'script', 'mia-' . $key, '/js/' . $map['js'], [ 'mia-bootstrap' ] );
        }
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
    $key = mia_detect_context_key();

    if ( $key && wp_script_is( 'mia-' . $key, 'registered' ) ) {
        return 'mia-' . $key;
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
