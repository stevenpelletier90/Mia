<?php
/**
 * Enqueue helpers and script/style loading for Mia Aesthetics theme.
 */

// Helper: enqueue a local stylesheet with automatic cache-busting version.
function mia_enqueue_local_style( $handle, $relative, $deps = [] ) {
    $theme_path = get_template_directory();
    $theme_uri  = get_template_directory_uri();
    $file_path  = $theme_path . $relative;

    if ( file_exists( $file_path ) ) {
        wp_enqueue_style(
            $handle,
            $theme_uri . $relative,
            $deps,
            filemtime( $file_path )
        );
        return true;
    }
    return false;
}

// Helper: enqueue a local script with automatic cache-busting version.
function mia_enqueue_local_script( $handle, $relative, $deps = [], $in_footer = true ) {
    $theme_path = get_template_directory();
    $theme_uri  = get_template_directory_uri();
    $file_path  = $theme_path . $relative;

    if ( file_exists( $file_path ) ) {
        wp_enqueue_script(
            $handle,
            $theme_uri . $relative,
            $deps,
            filemtime( $file_path ),
            $in_footer
        );
        return true;
    }
    return false;
}

// Main enqueue function for theme scripts and styles.
function mia_aesthetics_enqueue_scripts() {
    $theme_path = get_template_directory();
    $theme_uri = get_template_directory_uri();
    $css_path = $theme_path . '/assets/css';
    $css_uri = $theme_uri . '/assets/css';

// Enqueue local fonts first (before other styles)
mia_enqueue_local_style( 'mia-fonts', '/assets/css/_fonts.css' );

// Enqueue gallery assets only for the before-after-by-doctor template
if (is_page_template('page-before-after-by-doctor.php')) {
    wp_enqueue_style(
        'mia-gallery-css',
        get_template_directory_uri() . '/assets/css/_gallery.css',
        array('bootstrap-css'), // depends on Bootstrap CSS
        filemtime(get_template_directory() . '/assets/css/_gallery.css')
    );
    wp_enqueue_script(
        'mia-gallery-js',
        get_template_directory_uri() . '/assets/js/gallery.js',
        array('bootstrap-js', 'jquery'), // depends on Bootstrap and jQuery
        filemtime(get_template_directory() . '/assets/js/gallery.js'),
        true
    );
}

    mia_enqueue_local_style( 'font-awesome', '/assets/fontawesome/css/all.min.css' );

    mia_enqueue_local_style(
        'normalize',
        '/assets/normalize/normalize.css'
    );

    mia_enqueue_local_style(
        'bootstrap-css',
        '/assets/bootstrap/css/bootstrap.min.css'
    );

    mia_enqueue_local_script(
        'bootstrap-js',
        '/assets/bootstrap/js/bootstrap.bundle.min.js',
        array('jquery')
    );

    // Unified CSS loading: base, header, footer, and one page-specific CSS
    // 1. Enqueue base first (no dependencies)
    mia_enqueue_local_style( 'mia-base', '/assets/css/_base.css' );

    // 2. Enqueue header/footer (depend on base)
    mia_enqueue_local_style( 'mia-header', '/assets/css/_header.css', array( 'mia-base' ) );
    mia_enqueue_local_style( 'mia-footer', '/assets/css/_footer.css', array( 'mia-base' ) );

    // 3. Determine and enqueue page-specific CSS (depends on base)
    $page_css    = null;
    $page_handle = null;

    switch ( true ) {
        case is_front_page():
            [$page_css, $page_handle] = ['/_home.css', 'mia-home'];
            break;

        case is_404():
            [$page_css, $page_handle] = ['/_404.css', 'mia-404'];
            break;

        case is_search():
            [$page_css, $page_handle] = ['/_search.css', 'mia-search'];
            break;

        case is_tax():
            [$page_css, $page_handle] = ['/_taxonomies.css', 'mia-taxonomies'];
            break;

        case is_home():
        case ( is_archive() && get_post_type() === 'post' ):
            [$page_css, $page_handle] = ['/_archive.css', 'mia-post-archive'];
            break;

        case is_singular( 'post' ):
            [$page_css, $page_handle] = ['/_single.css', 'mia-post-single'];
            break;

        case is_page():
            [$page_css, $page_handle] = ['/_page.css', 'mia-page'];
            break;

        case is_post_type_archive():
            $pt = get_post_type() ?: get_query_var( 'post_type' );
            if ( $pt ) {
                $page_css    = '/_' . $pt . '-archive.css';
                $page_handle = 'mia-' . $pt . '-archive';
            }
            break;

        case is_singular():
            $pt = get_post_type();
            if ( $pt && ! in_array( $pt, [ 'post', 'page' ], true ) ) {
                // Special handling for 2nd-level procedure pages (grandchildren)
                if ($pt === 'procedure') {
                    $post = get_queried_object();
                    $ancestors = get_post_ancestors($post);
                    
                    if (count($ancestors) === 2) {
                        // Use condition CSS for 2nd-level procedure pages
                        $page_css    = '/_condition.css';
                        $page_handle = 'mia-condition-single';
                    } else {
                        // Use procedure CSS for other procedure pages
                        $page_css    = '/_procedure.css';
                        $page_handle = 'mia-procedure-single';
                    }
                } else {
                    $page_css    = '/_' . $pt . '.css';
                    $page_handle = 'mia-' . $pt . '-single';
                }
            }
            break;
    }
    if ( $page_css && $page_handle ) {
        mia_enqueue_local_style( $page_handle, '/assets/css' . $page_css, array( 'mia-base' ) );
    }

    // JS loading
    if (is_singular('condition')) {
        $js_file = $theme_path . '/assets/js/condition.js';
        $handle = 'mia-condition-script';
    } 
    // Check for 2nd-level procedure pages (grandchildren)
    elseif (is_singular('procedure')) {
        $post = get_queried_object();
        $ancestors = get_post_ancestors($post);
        
        if (count($ancestors) === 2) {
            // Use condition.js for 2nd-level procedure pages
            $js_file = $theme_path . '/assets/js/condition.js';
            $handle = 'mia-condition-script';
        } else {
            // Use main.js for other procedure pages
            $js_file = $theme_path . '/assets/js/main.js';
            $handle = 'mia-aesthetics-script';
        }
    }
    elseif (is_singular('surgeon')) {
        $js_file = $theme_path . '/assets/js/surgeon.js';
        $handle = 'mia-surgeon-script';
    } 
    elseif (is_front_page()) {
        $js_file = $theme_path . '/assets/js/home.js';
        $handle = 'mia-home-script';
    }
    else {
        $js_file = $theme_path . '/assets/js/main.js';
        $handle = 'mia-aesthetics-script';
    }
    
    if ( isset( $js_file ) ) {
        mia_enqueue_local_script( $handle, '/assets/js/' . basename( $js_file ), array( 'bootstrap-js' ), true );
    }
    
    if (is_singular('surgeon') || is_singular('location')) {
        $video_js_file = $theme_path . '/assets/js/video.js';
        mia_enqueue_local_script( 'mia-video-script', '/assets/js/video.js', array( 'bootstrap-js' ), true );
    }
}
add_action('wp_enqueue_scripts', 'mia_aesthetics_enqueue_scripts');

// Debug function to track asset loading and template issues
function mia_debug_asset_loading() {
    // Only run on the before-after-by-doctor page
    if (is_page_template('page-before-after-by-doctor.php')) {
        error_log('=== MIA Aesthetics Debug Info ===');
        error_log('Template Detection: Before & After by Doctor template is active');
        
        // Check template file existence
        $template_path = get_template_directory() . '/page-before-after-by-doctor.php';
        error_log('Template file exists: ' . (file_exists($template_path) ? 'Yes' : 'No'));
        
        // Check JSON data file
        $json_path = get_template_directory() . '/assets/data/before-after-gallery.json';
        error_log('Gallery JSON path: ' . $json_path);
        error_log('JSON file exists: ' . (file_exists($json_path) ? 'Yes' : 'No'));
        if (file_exists($json_path)) {
            $json_content = file_get_contents($json_path);
            error_log('JSON content length: ' . strlen($json_content));
            error_log('JSON is valid: ' . (json_decode($json_content) !== null ? 'Yes' : 'No'));
        }
        
        // Check asset loading
        error_log('=== Asset Loading Status ===');
        error_log('Bootstrap CSS: ' . (wp_style_is('bootstrap-css', 'enqueued') ? 'Loaded' : 'Not loaded'));
        error_log('Bootstrap JS: ' . (wp_script_is('bootstrap-js', 'enqueued') ? 'Loaded' : 'Not loaded'));
        error_log('jQuery: ' . (wp_script_is('jquery', 'enqueued') ? 'Loaded' : 'Not loaded'));
        error_log('Gallery CSS: ' . (wp_style_is('mia-gallery-css', 'enqueued') ? 'Loaded' : 'Not loaded'));
        error_log('Gallery JS: ' . (wp_script_is('mia-gallery-js', 'enqueued') ? 'Loaded' : 'Not loaded'));
        
        // Check asset URLs
        error_log('=== Asset URLs ===');
        error_log('Gallery CSS URL: ' . get_template_directory_uri() . '/assets/css/gallery.css');
        error_log('Gallery JS URL: ' . get_template_directory_uri() . '/assets/js/gallery.js');
        
        // Check if files exist
        error_log('=== File Existence ===');
        error_log('Gallery CSS exists: ' . (file_exists(get_template_directory() . '/assets/css/gallery.css') ? 'Yes' : 'No'));
        error_log('Gallery JS exists: ' . (file_exists(get_template_directory() . '/assets/js/gallery.js') ? 'Yes' : 'No'));
        
        error_log('=== End Debug Info ===');
    }
}
add_action('wp_enqueue_scripts', 'mia_debug_asset_loading', 999);
?>
