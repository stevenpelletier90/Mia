<?php
/**
 * Media Helper Functions
 * 
 * Handles image sizes, lazy loading, video processing, and media-related utilities
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom image sizes for responsive hero images
 */
add_image_size( 'hero-mobile', 640, 400, true );    // Mobile hero images
add_image_size( 'hero-tablet', 1024, 600, true );   // Tablet hero images  
add_image_size( 'hero-desktop', 1920, 800, true );  // Desktop hero images

/**
 * Add custom image sizes for before/after gallery images
 */
add_image_size( 'gallery-small', 300, 225, true );   // Small gallery images (mobile)
add_image_size( 'gallery-medium', 450, 338, true );  // Medium gallery images (tablet)
add_image_size( 'gallery-large', 600, 450, true );   // Large gallery images (desktop)

/**
 * Exclude hero images from lazy loading to optimize LCP
 */
function mia_exclude_hero_from_lazy_loading($attr, $attachment, $size) {
    // Check if this is a hero image size
    if (in_array($size, ['hero-mobile', 'hero-tablet', 'hero-desktop'])) {
        // Add loading="eager" to prevent lazy loading
        $attr['loading'] = 'eager';
        // Remove any lazy loading classes that might be added by plugins
        if (isset($attr['class'])) {
            $attr['class'] = str_replace(['lazy', 'lazyload'], '', $attr['class']);
            $attr['class'] = trim(preg_replace('/\s+/', ' ', $attr['class']));
        }
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'mia_exclude_hero_from_lazy_loading', 10, 3);

/**
 * Disable lazy loading for hero images in procedure templates
 */
function mia_disable_lazy_loading_for_hero($value, $image, $context) {
    // Check if we're on a procedure page and this might be the hero image
    if (is_singular('procedure') && $context === 'the_content') {
        // Check if the image has hero-related classes or is positioned absolutely
        if (strpos($image, 'position-absolute') !== false || 
            strpos($image, 'hero-') !== false ||
            strpos($image, 'procedure background') !== false) {
            // Remove lazy loading attributes
            $image = str_replace(['loading="lazy"', 'data-lazy-src', 'data-lazy-srcset', 'data-lazy-sizes'], 
                               ['loading="eager"', 'src', 'srcset', 'sizes'], $image);
            // Add fetchpriority if not present
            if (strpos($image, 'fetchpriority') === false) {
                $image = str_replace('<img ', '<img fetchpriority="high" ', $image);
            }
        }
    }
    return $value;
}
add_filter('wp_lazy_loading_enabled', 'mia_disable_lazy_loading_for_hero', 10, 3);

/**
 * Completely disable lazy loading for hero images with specific class
 */
function mia_disable_lazy_loading_for_hero_class($attr, $attachment, $size) {
    // If this is a hero image or has the mia-hero-image class, disable lazy loading
    if (isset($attr['class']) && strpos($attr['class'], 'mia-hero-image') !== false) {
        $attr['loading'] = 'eager';
        // Remove lazy loading classes
        $attr['class'] = str_replace(['lazy', 'lazyload'], '', $attr['class']);
        $attr['class'] = trim(preg_replace('/\s+/', ' ', $attr['class']));
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'mia_disable_lazy_loading_for_hero_class', 20, 3);

/**
 * Prevent lazy loading plugins from affecting hero images
 */
function mia_prevent_hero_lazy_loading() {
    if (is_singular('procedure')) {
        // Add JavaScript to force load hero images immediately
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var heroImages = document.querySelectorAll(".mia-hero-image");
            heroImages.forEach(function(img) {
                // Force immediate loading
                if (img.dataset.lazySrc) {
                    img.src = img.dataset.lazySrc;
                    img.removeAttribute("data-lazy-src");
                }
                if (img.dataset.lazySrcset) {
                    img.srcset = img.dataset.lazySrcset;
                    img.removeAttribute("data-lazy-srcset");
                }
                if (img.dataset.lazySizes) {
                    img.sizes = img.dataset.lazySizes;
                    img.removeAttribute("data-lazy-sizes");
                }
                // Remove lazy loading classes
                img.classList.remove("lazy", "lazyload", "lazyloading");
                img.classList.add("lazyloaded");
            });
        });
        </script>';
    }
}
add_action('wp_head', 'mia_prevent_hero_lazy_loading', 999);

/**
 * Extract video details from URL (YouTube, Vimeo, MP4, fallback)
 *
 * @param string $video_url URL to video
 * @return array|false Video data array or false if invalid/empty
 */
function get_video_details($video_url) {
    if (empty($video_url) || !is_string($video_url)) {
        return false;
    }

    // --- simple object-cache layer --------------------------------------------------
    // Avoid re-processing the same video URL on every page load.
    $cache_key = 'video_details_' . md5($video_url);
    $cached    = get_transient($cache_key);
    if ($cached !== false) {
        return $cached; // short-circuit if we already have the parsed data
    }

    $video_data = array(
        'url' => '',
        'embed_url' => '',
        'type' => 'unknown', // Default type
        'duration' => '' // Duration is harder to get reliably without API calls
    );

    // Trim whitespace
    $video_url = trim($video_url);

    // Try oEmbed first (handles YouTube, Shorts, playlists, Vimeo, etc.)
    $oembed_html = wp_oembed_get($video_url);
    if ($oembed_html) {
        $video_data['url'] = $video_url;
        $video_data['embed_url'] = $video_url;
        $video_data['type'] = 'oembed';
        set_transient($cache_key, $video_data, DAY_IN_SECONDS);
        return $video_data;
    }

    // Fallback: Check if YouTube URL
    if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $video_url, $matches) ||
        preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $video_url, $matches)) {
        $video_id = $matches[1];
        $video_data['url'] = 'https://www.youtube.com/watch?v=' . $video_id;
        $video_data['embed_url'] = 'https://www.youtube.com/embed/' . $video_id;
        $video_data['type'] = 'youtube';
        set_transient($cache_key, $video_data, DAY_IN_SECONDS);
        return $video_data;
    }

    // Fallback: Check if Vimeo URL
    if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $video_url, $matches)) {
        $video_id = $matches[1];
        $video_data['url'] = 'https://vimeo.com/' . $video_id;
        $video_data['embed_url'] = 'https://player.vimeo.com/video/' . $video_id;
        $video_data['type'] = 'vimeo';
        set_transient($cache_key, $video_data, DAY_IN_SECONDS);
        return $video_data;
    }

    // Fallback: Check if direct file URL (mp4) - simple check
    if (filter_var($video_url, FILTER_VALIDATE_URL) && pathinfo(parse_url($video_url, PHP_URL_PATH), PATHINFO_EXTENSION) === 'mp4') {
        $video_data['url'] = $video_url;
        $video_data['embed_url'] = $video_url; // Direct link for embed
        $video_data['type'] = 'mp4';
        set_transient($cache_key, $video_data, DAY_IN_SECONDS);
        return $video_data;
    }

    // Fallback: If it's a valid URL but type is unknown, return it
    if (filter_var($video_url, FILTER_VALIDATE_URL)) {
        $video_data['url'] = $video_url;
        $video_data['embed_url'] = $video_url; // Use direct URL as fallback embed
        // $video_data['type'] remains 'unknown'
        wp_cache_set($cache_key, $video_data, '', DAY_IN_SECONDS);
        return $video_data;
    }

    // Return false if not a valid or recognized video URL
    return false;
}

/**
 * Locate and normalise video data stored in various ACF fields.
 *
 * Looks for 'video_details', 'featured_video' or 'video' field groups/fields
 * and returns a unified array:
 *   ['url','title','description','thumbnail']  – empty array if none found.
 *
 * @param int|null $post_id Post ID or current post if null.
 * @return array|null
 */
function mia_get_video_field( $post_id = null ) {
    if ( $post_id === null ) {
        $post_id = get_the_ID();
    }

    $candidates = [ 'video_details', 'featured_video', 'video' ];
    foreach ( $candidates as $field ) {
        $val = get_field( $field, $post_id );
        if ( empty( $val ) ) {
            continue;
        }

        // Case 1: ACF repeater/group with explicit keys (video_url etc.)
        if ( is_array( $val ) ) {
            if ( ! empty( $val['video_url'] ) ) {
                return [
                    'url'         => is_array( $val['video_url'] ) ? $val['video_url']['url'] : $val['video_url'],
                    'title'       => $val['video_title']       ?? '',
                    'description' => $val['video_description'] ?? '',
                    'thumbnail'   => $val['video_thumbnail']   ?? '',
                ];
            }
            // Generic link array { url, title, description, thumbnail }
            if ( ! empty( $val['url'] ) ) {
                return [
                    'url'         => $val['url'],
                    'title'       => $val['title']       ?? '',
                    'description' => $val['description'] ?? '',
                    'thumbnail'   => $val['thumbnail']   ?? '',
                ];
            }
        }

        // Case 2: Simple URL string
        if ( is_string( $val ) && filter_var( $val, FILTER_VALIDATE_URL ) ) {
            return [
                'url'         => $val,
                'title'       => '',
                'description' => '',
                'thumbnail'   => '',
            ];
        }
    }

    return null;
}

/**
 * Helper function for before/after gallery images
 * Handles both image IDs and URLs, with fallback to placeholder
 */
function mia_before_after_img( $img, $label ) {
    if ( ! $img ) {
        $src = 'https://placehold.co/600x450';
        return "<img src='{$src}' class='img-fluid w-100 object-fit-cover' alt='{$label} placeholder' loading='lazy'>";
    }

    $id  = is_numeric( $img ) ? $img : attachment_url_to_postid( $img );
    
    if ( $id ) {
        return wp_get_attachment_image(
            $id,
            'gallery-small',
            false,
            [
                'class'   => 'img-fluid w-100 object-fit-cover',
                'alt'     => "{$label} surgery image",
                'loading' => 'lazy'
            ]
        );
    }
    
    // Fallback for direct URLs that couldn't be converted to attachment ID
    $src = is_array( $img ) ? $img['url'] : $img;
    return "<img src='" . esc_url( $src ) . "' class='img-fluid w-100 object-fit-cover' alt='{$label} surgery image' loading='lazy'>";
}
?>
