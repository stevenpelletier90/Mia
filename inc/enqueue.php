<?php
/**
 * Enqueue helpers and script/style loading for Mia Aesthetics theme.
 * 
 * JavaScript Loading Strategy:
 * - main.js: Global script loaded on most pages (common functionality)
 * - home.js: Replaces main.js on the front page
 * - condition.js: Loaded WITH main.js on condition pages
 * - surgeon.js: Loaded WITH main.js on surgeon pages
 * - gallery.js: Loaded WITH main.js on gallery pages
 * - video.js: Additional script for surgeon/location pages
 * 
 * CSS Loading Strategy:
 * - _base.css: Global styles loaded on all pages
 * - _header.css, _footer.css: Global components
 * - Context-specific CSS based on page type/template
 */

/**
 * Helper: Enqueue a local stylesheet with automatic cache-busting version.
 * 
 * @param string $handle   Unique handle for the stylesheet
 * @param string $relative Relative path from theme directory
 * @param array  $deps     Dependencies for this stylesheet
 * @return bool            True if enqueued successfully
 */
function mia_enqueue_local_style( $handle, $relative, $deps = [] ) {
    $file_path = get_template_directory() . $relative;

    if ( file_exists( $file_path ) ) {
        wp_enqueue_style(
            $handle,
            get_template_directory_uri() . $relative,
            $deps,
            filemtime( $file_path )
        );
        return true;
    }
    return false;
}

/**
 * Helper: Enqueue a local script with automatic cache-busting version.
 * 
 * @param string $handle     Unique handle for the script
 * @param string $relative   Relative path from theme directory
 * @param array  $deps       Dependencies for this script
 * @param bool   $in_footer  Whether to load in footer
 * @return bool              True if enqueued successfully
 */
function mia_enqueue_local_script( $handle, $relative, $deps = [], $in_footer = true ) {
    $file_path = get_template_directory() . $relative;

    if ( file_exists( $file_path ) ) {
        wp_enqueue_script(
            $handle,
            get_template_directory_uri() . $relative,
            $deps,
            filemtime( $file_path ),
            $in_footer
        );
        return true;
    }
    return false;
}

/**
 * Get the appropriate CSS file and handle for the current context.
 * 
 * @return array|null Array with [css_file, handle] or null
 */
function mia_get_context_styles() {
    // Canvas templates always use page CSS
    $current_template = get_page_template_slug();
    if ( in_array( $current_template, ['page-blank-canvas.php', 'page-hero-canvas.php'] ) ) {
        return ['/_page.css', 'mia-page'];
    }

    // Front page
    if ( is_front_page() ) {
        return ['/_home.css', 'mia-home'];
    }

    // Error pages
    if ( is_404() ) {
        return ['/_404.css', 'mia-404'];
    }

    // Search results
    if ( is_search() ) {
        return ['/_search.css', 'mia-search'];
    }

    // Taxonomies
    if ( is_tax() ) {
        return ['/_taxonomies.css', 'mia-taxonomies'];
    }

    // Blog archive
    if ( is_home() || ( is_archive() && get_post_type() === 'post' ) ) {
        return ['/_archive.css', 'mia-post-archive'];
    }

    // Single blog post
    if ( is_singular( 'post' ) ) {
        return ['/_single.css', 'mia-post-single'];
    }

    // Post type archives (check before static pages)
    if ( is_post_type_archive() ) {
        $post_type = get_post_type() ?: get_query_var( 'post_type' );
        if ( $post_type ) {
            return ["/_{$post_type}-archive.css", "mia-{$post_type}-archive"];
        }
    }

    // Static pages
    if ( is_page() ) {
        return ['/_page.css', 'mia-page'];
    }

    // Single custom post types
    if ( is_singular() ) {
        $post_type = get_post_type();
        
        // Skip standard post types (already handled above)
        if ( ! $post_type || in_array( $post_type, ['post', 'page'], true ) ) {
            return null;
        }

        // Special handling for procedure hierarchies
        if ( $post_type === 'procedure' ) {
            $ancestors = get_post_ancestors( get_queried_object() );
            
            // 2nd-level procedures (grandchildren) use condition styles
            if ( count( $ancestors ) === 2 ) {
                return ['/_condition.css', 'mia-condition-single'];
            }
        }

        // Default single post type styles
        return ["/_{$post_type}.css", "mia-{$post_type}-single"];
    }

    return null;
}

/**
 * Get specialized JavaScript files for specific contexts.
 * Returns null if only the global main.js should be loaded.
 * 
 * @return array|null Array with [js_file, handle] or null
 */
function mia_get_context_scripts() {
    // Single condition pages
    if ( is_singular( 'condition' ) ) {
        return ['condition.js', 'mia-condition-script'];
    }

    // Single procedure pages (2nd-level only)
    if ( is_singular( 'procedure' ) ) {
        $ancestors = get_post_ancestors( get_queried_object() );
        
        // 2nd-level procedures (grandchildren) use condition scripts
        if ( count( $ancestors ) === 2 ) {
            return ['condition.js', 'mia-condition-script'];
        }
    }

    // Single surgeon pages
    if ( is_singular( 'surgeon' ) ) {
        return ['surgeon.js', 'mia-surgeon-script'];
    }

    // Front page
    if ( is_front_page() ) {
        return ['home.js', 'mia-home-script'];
    }

    // Gallery pages (if gallery.js isn't already loaded by template)
    if ( is_page() && has_shortcode( get_post()->post_content, 'gallery' ) ) {
        return ['gallery.js', 'mia-gallery-script'];
    }

    // Return null to indicate no specialized script needed
    return null;
}

/**
 * Main enqueue function for theme scripts and styles.
 */
function mia_aesthetics_enqueue_scripts() {
    // Base styles (loaded in order)
    mia_enqueue_base_styles();
    
    // Template-specific styles
    mia_enqueue_template_styles();
    
    // Context-specific styles
    mia_enqueue_context_styles();
    
    // Scripts
    mia_enqueue_scripts();
}
add_action( 'wp_enqueue_scripts', 'mia_aesthetics_enqueue_scripts' );

/**
 * Enqueue base styles that load on every page.
 */
function mia_enqueue_base_styles() {
    // Local fonts (before other styles)
    mia_enqueue_local_style( 'mia-fonts', '/assets/css/_fonts.css' );
    
    // Third-party styles
    mia_enqueue_local_style( 'font-awesome', '/assets/fontawesome/css/all.min.css' );
    mia_enqueue_local_style( 'normalize', '/assets/normalize/normalize.css' );
    mia_enqueue_local_style( 'bootstrap-css', '/assets/bootstrap/css/bootstrap.min.css' );
    
    // Theme base styles
    mia_enqueue_local_style( 'mia-base', '/assets/css/_base.css' );
    
    // Global components (depend on base)
    mia_enqueue_local_style( 'mia-header', '/assets/css/_header.css', ['mia-base'] );
    mia_enqueue_local_style( 'mia-footer', '/assets/css/_footer.css', ['mia-base'] );
}

/**
 * Enqueue template-specific styles.
 */
function mia_enqueue_template_styles() {
    // Gallery assets for before-after template
    if ( is_page_template( 'page-before-after-by-doctor.php' ) ) {
        mia_enqueue_local_style( 
            'mia-gallery-css', 
            '/assets/css/_gallery.css', 
            ['bootstrap-css'] 
        );
    }
    
    // Hero styles for front page
    if ( is_front_page() ) {
        mia_enqueue_local_style( 'mia-hero', '/assets/css/_hero.css', ['mia-base'] );
    }
}

/**
 * Enqueue context-specific styles based on current page/post type.
 */
function mia_enqueue_context_styles() {
    $context_styles = mia_get_context_styles();
    
    if ( $context_styles ) {
        list( $css_file, $handle ) = $context_styles;
        mia_enqueue_local_style( $handle, '/assets/css' . $css_file, ['mia-base'] );
    }
}

/**
 * Enqueue all JavaScript files.
 */
function mia_enqueue_scripts() {
    // Bootstrap (depends on jQuery)
    mia_enqueue_local_script( 
        'bootstrap-js', 
        '/assets/bootstrap/js/bootstrap.bundle.min.js', 
        ['jquery'] 
    );
    
    // Global main.js - loads on all pages except where specialized scripts are used
    $load_main = true;
    $specialized_scripts = mia_get_context_scripts();
    
    // Check if we have a specialized script for this context
    if ( $specialized_scripts ) {
        list( $js_file, $handle ) = $specialized_scripts;
        
        // Some pages get both main.js AND specialized scripts
        $pages_with_both = ['condition.js', 'surgeon.js', 'gallery.js'];
        
        if ( ! in_array( $js_file, $pages_with_both ) ) {
            $load_main = false;
        }
        
        // Enqueue the specialized script
        mia_enqueue_local_script( 
            $handle, 
            '/assets/js/' . $js_file, 
            ['bootstrap-js', 'jquery'] 
        );
    }
    
    // Enqueue main.js if needed
    if ( $load_main ) {
        mia_enqueue_local_script( 
            'mia-main-script', 
            '/assets/js/main.js', 
            ['bootstrap-js', 'jquery'] 
        );
    }
    
    // Template-specific scripts
    if ( is_page_template( 'page-before-after-by-doctor.php' ) ) {
        mia_enqueue_local_script( 
            'mia-gallery-js', 
            '/assets/js/gallery.js', 
            ['bootstrap-js', 'jquery', 'mia-main-script'] 
        );
    }
    
    // Video script for specific post types (in addition to other scripts)
    if ( is_singular( ['surgeon', 'location'] ) ) {
        mia_enqueue_local_script( 
            'mia-video-script', 
            '/assets/js/video.js', 
            ['bootstrap-js', 'jquery'] 
        );
    }
}
