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
            // Check for video_id (YouTube ID only) - matches schema structure
            if (!empty($val['video_id'])) {
                $video_id = sanitize_text_field($val['video_id']);
                
                // Validate YouTube ID format (11 characters, alphanumeric with - and _)
                if (!preg_match('/^[a-zA-Z0-9_-]{11}$/', $video_id)) {
                    continue; // Invalid YouTube ID format
                }
                
                $video_url = 'https://www.youtube.com/watch?v=' . $video_id;
                
                // Handle video_thumbnail which could be an attachment ID or array
                $thumbnail = '';
                if (!empty($val['video_thumbnail'])) {
                    if (is_numeric($val['video_thumbnail'])) {
                        // It's an attachment ID
                        $thumbnail_url = wp_get_attachment_image_url($val['video_thumbnail'], 'full');
                        if ($thumbnail_url) {
                            $thumbnail = $thumbnail_url;
                        }
                    } elseif (is_array($val['video_thumbnail']) && !empty($val['video_thumbnail']['url'])) {
                        // It's an ACF image array
                        $thumbnail = $val['video_thumbnail']['url'];
                    }
                }
                
                // Always fall back to YouTube thumbnail if no custom thumbnail
                if (empty($thumbnail)) {
                    $thumbnail = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
                }
                
                return [
                    'url'         => esc_url_raw($video_url),
                    'title'       => sanitize_text_field($val['video_title'] ?? ''),
                    'description' => sanitize_textarea_field($val['video_description'] ?? ''),
                    'thumbnail'   => esc_url_raw($thumbnail),
                    'video_id'    => $video_id // Include the ID for direct use
                ];
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