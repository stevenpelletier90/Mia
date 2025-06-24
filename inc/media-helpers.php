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
 * Extract video details from URL (YouTube, Vimeo, MP4, fallback)
 *
 * @param string $video_url URL to video
 * @return array|false Video data array or false if invalid/empty
 */
function get_video_details($video_url) {
    if (empty($video_url) || !is_string($video_url)) {
        return false;
    }

    // Trim and validate URL
    $video_url = trim($video_url);
    if (!filter_var($video_url, FILTER_VALIDATE_URL)) {
        return false;
    }

    // Object-cache layer - avoid re-processing the same URL
    $cache_key = 'video_details_' . md5($video_url);
    $cached    = wp_cache_get($cache_key);
    if ($cached !== false) {
        return $cached;
    }

    $video_data = array(
        'url' => '',
        'embed_url' => '',
        'type' => 'unknown',
        'duration' => ''
    );

    // Try oEmbed first (handles YouTube, Shorts, playlists, Vimeo, etc.)
    $oembed_html = wp_oembed_get($video_url);
    if ($oembed_html) {
        $video_data['url'] = esc_url_raw($video_url);
        $video_data['embed_url'] = esc_url_raw($video_url);
        $video_data['type'] = 'oembed';
        wp_cache_set($cache_key, $video_data, '', DAY_IN_SECONDS);
        return $video_data;
    }

    // Fallback: Check if YouTube URL
    if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $video_url, $matches) ||
        preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $video_url, $matches)) {
        $video_id = sanitize_text_field($matches[1]);
        $video_data['url'] = esc_url_raw('https://www.youtube.com/watch?v=' . $video_id);
        $video_data['embed_url'] = esc_url_raw('https://www.youtube.com/embed/' . $video_id);
        $video_data['type'] = 'youtube';
        wp_cache_set($cache_key, $video_data, '', DAY_IN_SECONDS);
        return $video_data;
    }

    // Fallback: Check if Vimeo URL
    if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $video_url, $matches)) {
        $video_id = absint($matches[1]);
        if ($video_id > 0) {
            $video_data['url'] = esc_url_raw('https://vimeo.com/' . $video_id);
            $video_data['embed_url'] = esc_url_raw('https://player.vimeo.com/video/' . $video_id);
            $video_data['type'] = 'vimeo';
            wp_cache_set($cache_key, $video_data, '', DAY_IN_SECONDS);
            return $video_data;
        }
    }

    // Fallback: Check if direct file URL (mp4)
    $parsed_url = parse_url($video_url);
    if ($parsed_url && isset($parsed_url['path'])) {
        $extension = pathinfo($parsed_url['path'], PATHINFO_EXTENSION);
        if (strtolower($extension) === 'mp4') {
            $video_data['url'] = esc_url_raw($video_url);
            $video_data['embed_url'] = esc_url_raw($video_url);
            $video_data['type'] = 'mp4';
            wp_cache_set($cache_key, $video_data, '', DAY_IN_SECONDS);
            return $video_data;
        }
    }

    // Fallback: If it's a valid URL but type is unknown
    $video_data['url'] = esc_url_raw($video_url);
    $video_data['embed_url'] = esc_url_raw($video_url);
    wp_cache_set($cache_key, $video_data, '', DAY_IN_SECONDS);
    return $video_data;
}

/**
 * Locate and normalise video data stored in various ACF fields.
 *
 * Looks for 'video_details', 'featured_video' or 'video' field groups/fields
 * and returns a unified array:
 *   ['url','title','description','thumbnail'] – empty array if none found.
 *
 * @param int|null $post_id Post ID or current post if null.
 * @return array|null
 */
function mia_get_video_field($post_id = null) {
    if ($post_id === null) {
        $post_id = get_the_ID();
    }
    
    // Validate post ID
    if (!$post_id || !is_numeric($post_id) || get_post_status($post_id) === false) {
        return null;
    }

    $candidates = ['video_details', 'featured_video', 'video'];
    foreach ($candidates as $field) {
        // Add error handling for ACF field lookup
        if (!function_exists('get_field')) {
            error_log('[mia_get_video_field] ACF get_field() function not available');
            return null;
        }
        
        $val = get_field($field, $post_id);
        if (empty($val)) {
            continue;
        }

        // Case 1: ACF repeater/group with explicit keys
        if (is_array($val)) {
            if (!empty($val['video_url'])) {
                $video_url = is_array($val['video_url']) ? $val['video_url']['url'] : $val['video_url'];
                if (is_string($video_url) && filter_var($video_url, FILTER_VALIDATE_URL)) {
                    return [
                        'url'         => esc_url_raw($video_url),
                        'title'       => sanitize_text_field($val['video_title'] ?? ''),
                        'description' => sanitize_textarea_field($val['video_description'] ?? ''),
                        'thumbnail'   => is_string($val['video_thumbnail'] ?? '') ? esc_url_raw($val['video_thumbnail']) : '',
                    ];
                }
            }
            // Generic link array
            if (!empty($val['url']) && is_string($val['url']) && filter_var($val['url'], FILTER_VALIDATE_URL)) {
                return [
                    'url'         => esc_url_raw($val['url']),
                    'title'       => sanitize_text_field($val['title'] ?? ''),
                    'description' => sanitize_textarea_field($val['description'] ?? ''),
                    'thumbnail'   => is_string($val['thumbnail'] ?? '') ? esc_url_raw($val['thumbnail']) : '',
                ];
            }
        }

        // Case 2: Simple URL string
        if (is_string($val) && filter_var($val, FILTER_VALIDATE_URL)) {
            return [
                'url'         => esc_url_raw($val),
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
 *
 * @param mixed $img Image ID, URL, or array
 * @param string $label Image label (before/after)
 * @return string HTML img tag
 */
function mia_before_after_img($img, $label) {
    // Validate and sanitize inputs
    if (!is_string($label) || empty(trim($label))) {
        return ''; // Return empty string for invalid label
    }
    $safe_label = esc_attr(trim($label));
    
    // Handle empty/null image
    if (empty($img)) {
        $src = 'https://placehold.co/600x450';
        return "<img src='" . esc_url($src) . "' class='img-fluid w-100 object-fit-cover' alt='" . $safe_label . " placeholder' loading='lazy'>";
    }

    // Handle numeric ID (attachment ID)
    if (is_numeric($img)) {
        $id = (int) $img;
        if ($id > 0 && wp_attachment_is_image($id)) {
            return wp_get_attachment_image(
                $id,
                'gallery-small',
                false,
                [
                    'class'   => 'img-fluid w-100 object-fit-cover',
                    'alt'     => $safe_label . ' surgery image',
                    'loading' => 'lazy'
                ]
            );
        }
        // Invalid attachment ID - return placeholder
        $src = 'https://placehold.co/600x450';
        return "<img src='" . esc_url($src) . "' class='img-fluid w-100 object-fit-cover' alt='" . $safe_label . " placeholder' loading='lazy'>";
    }
    
    // Handle array (ACF image field)
    if (is_array($img) && !empty($img['url'])) {
        $src = $img['url'];
        if (!filter_var($src, FILTER_VALIDATE_URL)) {
            // Invalid URL in array - return placeholder
            $src = 'https://placehold.co/600x450';
            return "<img src='" . esc_url($src) . "' class='img-fluid w-100 object-fit-cover' alt='" . $safe_label . " placeholder' loading='lazy'>";
        }
        return "<img src='" . esc_url($src) . "' class='img-fluid w-100 object-fit-cover' alt='" . $safe_label . " surgery image' loading='lazy'>";
    }
    
    // Handle string URL
    if (is_string($img)) {
        if (!filter_var($img, FILTER_VALIDATE_URL)) {
            // Try to find attachment by URL with error handling
            $id = attachment_url_to_postid($img);
            if ($id && is_numeric($id) && $id > 0 && wp_attachment_is_image($id)) {
                return wp_get_attachment_image(
                    $id,
                    'gallery-small',
                    false,
                    [
                        'class'   => 'img-fluid w-100 object-fit-cover',
                        'alt'     => $safe_label . ' surgery image',
                        'loading' => 'lazy'
                    ]
                );
            }
            // Invalid URL string - return placeholder
            $src = 'https://placehold.co/600x450';
            return "<img src='" . esc_url($src) . "' class='img-fluid w-100 object-fit-cover' alt='" . $safe_label . " placeholder' loading='lazy'>";
        }
        return "<img src='" . esc_url($img) . "' class='img-fluid w-100 object-fit-cover' alt='" . $safe_label . " surgery image' loading='lazy'>";
    }
    
    // Fallback for unsupported types - return placeholder
    $src = 'https://placehold.co/600x450';
    return "<img src='" . esc_url($src) . "' class='img-fluid w-100 object-fit-cover' alt='" . $safe_label . " placeholder' loading='lazy'>";
}