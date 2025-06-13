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

    // Trim whitespace
    $video_url = trim($video_url);

    // Try oEmbed first (handles YouTube, Shorts, playlists, Vimeo, etc.)
    $oembed_html = wp_oembed_get($video_url);
    if ($oembed_html) {
        $video_data['url'] = $video_url;
        $video_data['embed_url'] = $video_url;
        $video_data['type'] = 'oembed';
        wp_cache_set($cache_key, $video_data, '', DAY_IN_SECONDS);
        return $video_data;
    }

    // Fallback: Check if YouTube URL
    if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $video_url, $matches) ||
        preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $video_url, $matches)) {
        $video_id = $matches[1];
        $video_data['url'] = 'https://www.youtube.com/watch?v=' . $video_id;
        $video_data['embed_url'] = 'https://www.youtube.com/embed/' . $video_id;
        $video_data['type'] = 'youtube';
        wp_cache_set($cache_key, $video_data, '', DAY_IN_SECONDS);
        return $video_data;
    }

    // Fallback: Check if Vimeo URL
    if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $video_url, $matches)) {
        $video_id = $matches[1];
        $video_data['url'] = 'https://vimeo.com/' . $video_id;
        $video_data['embed_url'] = 'https://player.vimeo.com/video/' . $video_id;
        $video_data['type'] = 'vimeo';
        wp_cache_set($cache_key, $video_data, '', DAY_IN_SECONDS);
        return $video_data;
    }

    // Fallback: Check if direct file URL (mp4)
    if (filter_var($video_url, FILTER_VALIDATE_URL) && pathinfo(parse_url($video_url, PHP_URL_PATH), PATHINFO_EXTENSION) === 'mp4') {
        $video_data['url'] = $video_url;
        $video_data['embed_url'] = $video_url;
        $video_data['type'] = 'mp4';
        wp_cache_set($cache_key, $video_data, '', DAY_IN_SECONDS);
        return $video_data;
    }

    // Fallback: If it's a valid URL but type is unknown
    if (filter_var($video_url, FILTER_VALIDATE_URL)) {
        $video_data['url'] = $video_url;
        $video_data['embed_url'] = $video_url;
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
 *   ['url','title','description','thumbnail'] – empty array if none found.
 *
 * @param int|null $post_id Post ID or current post if null.
 * @return array|null
 */
function mia_get_video_field($post_id = null) {
    if ($post_id === null) {
        $post_id = get_the_ID();
    }

    $candidates = ['video_details', 'featured_video', 'video'];
    foreach ($candidates as $field) {
        $val = get_field($field, $post_id);
        if (empty($val)) {
            continue;
        }

        // Case 1: ACF repeater/group with explicit keys
        if (is_array($val)) {
            if (!empty($val['video_url'])) {
                return [
                    'url'         => is_array($val['video_url']) ? $val['video_url']['url'] : $val['video_url'],
                    'title'       => $val['video_title']       ?? '',
                    'description' => $val['video_description'] ?? '',
                    'thumbnail'   => $val['video_thumbnail']   ?? '',
                ];
            }
            // Generic link array
            if (!empty($val['url'])) {
                return [
                    'url'         => $val['url'],
                    'title'       => $val['title']       ?? '',
                    'description' => $val['description'] ?? '',
                    'thumbnail'   => $val['thumbnail']   ?? '',
                ];
            }
        }

        // Case 2: Simple URL string
        if (is_string($val) && filter_var($val, FILTER_VALIDATE_URL)) {
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
 *
 * @param mixed $img Image ID, URL, or array
 * @param string $label Image label (before/after)
 * @return string HTML img tag
 */
function mia_before_after_img($img, $label) {
    // Sanitize the label parameter
    $safe_label = esc_attr($label);
    
    if (!$img) {
        $src = 'https://placehold.co/600x450';
        return "<img src='" . esc_url($src) . "' class='img-fluid w-100 object-fit-cover' alt='" . $safe_label . " placeholder' loading='lazy'>";
    }

    $id = is_numeric($img) ? $img : attachment_url_to_postid($img);
    
    if ($id) {
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
    
    // Fallback for direct URLs
    $src = is_array($img) ? $img['url'] : $img;
    return "<img src='" . esc_url($src) . "' class='img-fluid w-100 object-fit-cover' alt='" . $safe_label . " surgery image' loading='lazy'>";
}