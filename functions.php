<?php
/**
 * @package Mia_Aesthetics
 */

add_theme_support( 'post-thumbnails' );

// Add custom image sizes for responsive hero images
add_image_size( 'hero-mobile', 640, 400, true );    // Mobile hero images
add_image_size( 'hero-tablet', 1024, 600, true );   // Tablet hero images  
add_image_size( 'hero-desktop', 1920, 800, true );  // Desktop hero images

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
        // Add CSS to prevent lazy loading plugins from hiding hero images
        echo '<style>
        .mia-hero-image {
            opacity: 1 !important;
            visibility: visible !important;
        }
        .mia-hero-image[data-lazy-src] {
            opacity: 1 !important;
        }
        </style>';
        
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
 * US state → abbreviation lookup table (static).
 * Keeping as a constant avoids recreating the array on every call.
 */
if ( ! defined( 'MIA_STATE_ABBREVIATIONS' ) ) {
    define(
        'MIA_STATE_ABBREVIATIONS',
        [
            'Alabama' => 'AL',  'Alaska' => 'AK', 'Arizona' => 'AZ', 'Arkansas' => 'AR', 'California' => 'CA',
            'Colorado' => 'CO', 'Connecticut' => 'CT', 'Delaware' => 'DE', 'Florida' => 'FL', 'Georgia' => 'GA',
            'Hawaii' => 'HI',  'Idaho' => 'ID', 'Illinois' => 'IL', 'Indiana' => 'IN', 'Iowa' => 'IA',
            'Kansas' => 'KS',  'Kentucky' => 'KY', 'Louisiana' => 'LA', 'Maine' => 'ME', 'Maryland' => 'MD',
            'Massachusetts' => 'MA', 'Michigan' => 'MI', 'Minnesota' => 'MN', 'Mississippi' => 'MS', 'Missouri' => 'MO',
            'Montana' => 'MT', 'Nebraska' => 'NE', 'Nevada' => 'NV', 'New Hampshire' => 'NH', 'New Jersey' => 'NJ',
            'New Mexico' => 'NM', 'New York' => 'NY', 'North Carolina' => 'NC', 'North Dakota' => 'ND', 'Ohio' => 'OH',
            'Oklahoma' => 'OK', 'Oregon' => 'OR', 'Pennsylvania' => 'PA', 'Rhode Island' => 'RI', 'South Carolina' => 'SC',
            'South Dakota' => 'SD', 'Tennessee' => 'TN', 'Texas' => 'TX', 'Utah' => 'UT', 'Vermont' => 'VT',
            'Virginia' => 'VA', 'Washington' => 'WA', 'West Virginia' => 'WV', 'Wisconsin' => 'WI', 'Wyoming' => 'WY',
            'District of Columbia' => 'DC'
        ]
    );
}

/**
 * Helper: enqueue a local stylesheet with automatic cache-busting version.
 *
 * @param string $handle      WP style handle.
 * @param string $relative    Path relative to the theme root (must start with '/').
 * @param array  $deps        Optional dependencies.
 *
 * @return bool True if style was enqueued, false otherwise.
 */
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

/**
 * Helper: enqueue a local script with automatic cache-busting version.
 *
 * @param string  $handle      WP script handle.
 * @param string  $relative    Path relative to the theme root (must start with '/').
 * @param array   $deps        Optional dependencies.
 * @param boolean $in_footer   Load script in footer. Default true.
 *
 * @return bool True if script was enqueued, false otherwise.
 */
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

function mia_aesthetics_enqueue_scripts() {
    $theme_path = get_template_directory();
    $theme_uri = get_template_directory_uri();
    $css_path = $theme_path . '/assets/css';
    $css_uri = $theme_uri . '/assets/css';

    // Enqueue local fonts first (before other styles)
    mia_enqueue_local_style( 'mia-fonts', '/assets/css/fonts.css' );

    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css',
        array(),
        '6.7.2'
    );

    wp_enqueue_style(
        'normalize',
        'https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css',
        array(),
        '8.0.1'
    );

    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css',
        array(),
        '5.3.6'
    );

    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js',
        array(),
        '5.3.6',
        true
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


/**
 * Helper: Get US state abbreviation from full name
 * 
 * Used in navigation menus to display location state abbreviations
 * 
 * @param string $state Full state name
 * @return string State abbreviation or original string if not found
 */
function mia_get_state_abbr($state) {
    if ( function_exists( 'WP_State::abbr' ) ) {
        $abbr = WP_State::abbr( $state );
        return $abbr ? $abbr : $state;
    }
    // Fallback: constant map for legacy support
    if ( defined( 'MIA_STATE_ABBREVIATIONS' ) && isset( MIA_STATE_ABBREVIATIONS[ $state ] ) ) {
        return MIA_STATE_ABBREVIATIONS[ $state ];
    }
    return $state;
}

/**
 * Format “City, ST ZIP” using state-abbreviation helper.
 *
 * Any empty parts are skipped and separators added only when needed.
 *
 * @param string $city
 * @param string $state Full state name or abbreviation
 * @param string $zip
 * @return string
 */
function mia_format_city_state_zip( $city = '', $state = '', $zip = '' ) {
    $parts = [];

    if ( $city ) {
        $parts[] = trim( $city );
    }

    if ( $state ) {
        $parts[] = mia_get_state_abbr( trim( $state ) );
    }

    $line = '';
    if ( ! empty( $parts ) ) {
        $line = implode( ', ', $parts );
    }

    if ( $zip ) {
        $line = $line ? $line . ' ' . trim( $zip ) : trim( $zip );
    }

    return $line;
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

    // --- simple object-cache layer --------------------------------------------------
    // Avoid re-processing the same video URL on every page load.
    $cache_key = 'video_details_' . md5($video_url);
    $cached    = wp_cache_get($cache_key);
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

    // Fallback: Check if direct file URL (mp4) - simple check
    if (filter_var($video_url, FILTER_VALIDATE_URL) && pathinfo(parse_url($video_url, PHP_URL_PATH), PATHINFO_EXTENSION) === 'mp4') {
        $video_data['url'] = $video_url;
        $video_data['embed_url'] = $video_url; // Direct link for embed
        $video_data['type'] = 'mp4';
        wp_cache_set($cache_key, $video_data, '', DAY_IN_SECONDS);
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
 * Adds specific Schema markup for custom post types, videos, and FAQs.
 * Assumes Yoast SEO is active and handles Organization, WebPage, WebSite schema.
 */
function mia_add_schema() {
    // Don't run if Yoast Schema is disabled or not active (optional check)
    // if (!class_exists('WPSEO_Frontend') || !WPSEO_Frontend::get_instance()->options['wpseo_json_ld_output']) {
    //     return;
    // }

    $post_type = get_post_type();
    $schema = null; // Initialize schema as null, only output if populated

    switch ($post_type) {
        case 'procedure':
            if (is_singular('procedure')) {
                $schema = [
                    '@context'    => 'https://schema.org',
                    '@type'       => 'MedicalProcedure',
                    'name'        => get_the_title(),
                    'description' => wp_strip_all_tags(get_the_excerpt()),
                    'url'         => get_permalink()
                ];
                if ($procedure_type = get_field('procedure_type')) {
                    $schema['procedureType'] = $procedure_type;
                }
                 // Consider adding 'bodyLocation', 'indication', 'outcome' if available in fields
            }
            break;

        case 'surgeon':
            if (is_singular('surgeon')) {
                $schema = [
                    '@context'      => 'https://schema.org',
                    '@type'         => 'Physician',
                    'name'          => get_the_title(),
                    'description'   => wp_strip_all_tags(get_the_excerpt()),
                    'url'           => get_permalink(),
                    'address'       => [
                        '@type'          => 'PostalAddress',
                        'addressCountry' => 'US'
                    ],
                    'telephone'     => get_field('phone_number') ?: '',
                    'priceRange'    => get_field('price_range') ?: '$-$$$',
                    'image'         => ''
                ];

                $street = get_field('street_address') ?: get_field('address');
                if ($street) $schema['address']['streetAddress'] = $street;
                if ($city = get_field('city')) $schema['address']['addressLocality'] = $city;
                if ($state = get_field('state')) $schema['address']['addressRegion'] = $state;
                if ($zip = get_field('zip_code')) $schema['address']['postalCode'] = $zip;

                if (has_post_thumbnail()) {
                    $schema['image'] = get_the_post_thumbnail_url(get_the_ID(), 'full');
                } elseif ($image_field = get_field('image')) {
                    $schema['image'] = is_array($image_field) ? $image_field['url'] : $image_field;
                } else {
                    $schema['image'] = get_template_directory_uri() . '/assets/images/default-doctor.jpg';
                }

                if ($specialty = get_field('medical_specialty')) {
                    $schema['medicalSpecialty'] = $specialty;
                }

                $location_id = get_field('surgeon_location');
                if ($location_id && is_numeric($location_id)) {
                    $work_location = [
                        '@type' => ['MedicalClinic', 'MedicalBusiness', 'LocalBusiness'],
                        'name' => get_the_title($location_id),
                        'url' => get_permalink($location_id),
                         'address' => [
                            '@type' => 'PostalAddress',
                            'addressCountry' => 'US'
                        ],
                        'telephone' => get_field('phone_number', $location_id) ?: (get_field('location_phone', $location_id) ?: ''),
                        'image' => ''
                    ];
                    $loc_street = get_field('street_address', $location_id) ?: get_field('location_address', $location_id);
                    if ($loc_street) $work_location['address']['streetAddress'] = $loc_street;
                    if ($loc_city = get_field('city', $location_id)) $work_location['address']['addressLocality'] = $loc_city;
                    if ($loc_state = get_field('state', $location_id)) $work_location['address']['addressRegion'] = $loc_state;
                    if ($loc_zip = get_field('zip_code', $location_id)) $work_location['address']['postalCode'] = $loc_zip;

                    if (has_post_thumbnail($location_id)) {
                        $work_location['image'] = get_the_post_thumbnail_url($location_id, 'full');
                    } else {
                         $work_location['image'] = get_template_directory_uri() . '/assets/images/default-location.jpg';
                    }
                    $schema['workLocation'] = $work_location;
                }

                $schema['memberOf'] = [
                    '@type' => 'MedicalOrganization',
                    'name' => get_bloginfo('name'),
                    'url' => home_url(),
                    'telephone' => get_field('company_phone', 'option') ?: '',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => '123 Main Street',
                        'addressLocality' => 'Miami',
                        'addressRegion' => 'FL',
                        'postalCode' => '33101',
                        'addressCountry' => 'US'
                    ]
                ];

            }
            break;

case 'location':
     if (is_singular('location')) {
        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => ['MedicalClinic', 'MedicalBusiness', 'LocalBusiness'],
            'name'        => get_the_title(),
            'description' => wp_strip_all_tags(get_the_excerpt()),
            'url'         => get_permalink(),
            'address'     => [
                '@type'          => 'PostalAddress',
                'addressCountry' => 'US'
            ],
            'telephone'   => '',
            'priceRange'  => get_field('price_range') ?: '$-$$$',
            'image'       => '',
            'currenciesAccepted' => 'USD',
            'paymentAccepted' => get_field('payment_methods') ?: 'Cash, Credit Card, Insurance',
            'medicalSpecialty' => get_field('medical_specialty') ?: []
        ];

        $street = get_field('street_address') ?: get_field('location_address');
        if ($street) $schema['address']['streetAddress'] = $street;
        if ($city = get_field('city')) $schema['address']['addressLocality'] = $city;
        if ($state = get_field('state')) $schema['address']['addressRegion'] = $state;
        if ($zip = get_field('zip_code')) $schema['address']['postalCode'] = $zip;

        $schema['telephone'] = get_field('phone_number') ?: (get_field('location_phone') ?: '');

        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(get_the_ID(), 'full');
        } elseif ($image_field = get_field('image')) {
            $schema['image'] = is_array($image_field) ? $image_field['url'] : $image_field;
        } else {
            $schema['image'] = get_template_directory_uri() . '/assets/images/default-location.jpg';
        }

        if ($hours = get_field('opening_hours')) {
            $schema['openingHours'] = $hours;
        } elseif ($hours_array = get_field('hours_of_operation')) {
        }

        $hours_spec = get_field('opening_hours_specification');
        if (!empty($hours_spec) && is_array($hours_spec)) {
            $schema['openingHoursSpecification'] = [];
            foreach ($hours_spec as $spec) {
                if (!empty($spec['day_of_week']) && !empty($spec['opens']) && !empty($spec['closes'])) {
                    $schema['openingHoursSpecification'][] = [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => $spec['day_of_week'],
                        'opens' => $spec['opens'],
                        'closes' => $spec['closes']
                    ];
                }
            }
        }

        $services = get_field('available_services');
        if (!empty($services) && is_array($services)) {
            $schema['availableService'] = [];
            foreach ($services as $service) {
                if (!empty($service['name'])) {
                    $service_schema = [
                        '@type' => 'MedicalProcedure',
                        'name' => $service['name']
                    ];
                    
                    if (!empty($service['description'])) {
                        $service_schema['description'] = $service['description'];
                    }
                    
                    $schema['availableService'][] = $service_schema;
                }
            }
        }

        $specialties = get_field('medical_specialties');
        if (!empty($specialties) && is_array($specialties)) {
            $schema['medicalSpecialty'] = $specialties;
        } elseif ($single_specialty = get_field('medical_specialty')) {
            $schema['medicalSpecialty'] = $single_specialty;
        }

        $latitude = get_field('latitude');
        $longitude = get_field('longitude');
        if ($latitude && $longitude) {
            $schema['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $latitude,
                'longitude' => $longitude
            ];
        }
        
        $map_url = get_field('map_url');
        if (!empty($map_url)) {
            $schema['hasMap'] = $map_url;
        }
        
        $accepting_patients = get_field('accepting_new_patients');
        if (isset($accepting_patients)) {
            $schema['isAcceptingNewPatients'] = (bool)$accepting_patients;
        }
        
        $network_ids = get_field('health_plan_network_ids');
        if (!empty($network_ids) && is_array($network_ids)) {
            $schema['healthPlanNetworkId'] = $network_ids;
        } elseif (!empty($network_ids) && is_string($network_ids)) {
            $schema['healthPlanNetworkId'] = $network_ids;
        }
        
        $accessibility = get_field('accessibility_features');
        if (!empty($accessibility) && is_array($accessibility)) {
            $schema['amenityFeature'] = [];
            foreach ($accessibility as $feature) {
                if (!empty($feature['name'])) {
                    $schema['amenityFeature'][] = [
                        '@type' => 'LocationFeatureSpecification',
                        'name' => $feature['name'],
                        'value' => true
                    ];
                }
            }
        }

        $schema['parentOrganization'] = [
            '@type' => 'MedicalOrganization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => '123 Main Street',
                'addressLocality' => 'Miami',
                'addressRegion' => 'FL',
                'postalCode' => '33101',
                'addressCountry' => 'US'
            ]
        ];
     }
            break;

        case 'condition':
             if (is_singular('condition')) {
                $schema = [
                    '@context'    => 'https://schema.org',
                    '@type'       => 'MedicalCondition',
                    'name'        => get_the_title(),
                    'description' => wp_strip_all_tags(get_the_excerpt()),
                    'url'         => get_permalink()
                    // Consider adding 'associatedAnatomy', 'cause', 'differentialDiagnosis', 'possibleTreatment', 'riskFactor', 'signOrSymptom' if fields exist
                ];
             }
            break;

        // REMOVED: case 'page': - Let Yoast handle standard WebPage schema.
        // REMOVED: default: (including is_front_page()) - Let Yoast handle Organization schema.
    }

    // --- Output the main schema block IF one was generated ---
    if (!empty($schema)) {
        echo '<script type="application/ld+json" class="mia-custom-schema">'; // Add class for debugging
        // Filter out empty values recursively before encoding
        $schema_filtered = array_filter( $schema, function( $value ) {
            return $value !== '' && $value !== null && (!is_array($value) || !empty(array_filter($value)));
        });
        echo esc_html( wp_json_encode( $schema_filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT ) );
        echo '</script>';
    }


    $video_details_field = null;
    $possible_video_fields = ['video_details', 'featured_video', 'video'];
    foreach ($possible_video_fields as $field_name) {
        $video_details_field = get_field($field_name);
        if (!empty($video_details_field)) {
            if (is_array($video_details_field) && !empty($video_details_field['video_url'])) {
                 break;
            } elseif (is_string($video_details_field) && filter_var($video_details_field, FILTER_VALIDATE_URL)) {
                 $video_details_field = [
                    'video_url'         => $video_details_field,
                    'video_title'       => '',
                    'video_description' => '',
                    'video_thumbnail'   => ''
                 ];
                 break;
            } else {
                 $video_details_field = null;
            }
        }
    }

    if (!empty($video_details_field) && !empty($video_details_field['video_url'])) {
        $video_url = is_array($video_details_field['video_url']) ? $video_details_field['video_url']['url'] : $video_details_field['video_url'];
        $video_data = get_video_details($video_url);

        if ($video_data) {
            $video_schema = [
                '@context'    => 'https://schema.org',
                '@type'       => 'VideoObject',
                'name'        => !empty($video_details_field['video_title']) ? $video_details_field['video_title'] : get_the_title(),
                'description' => !empty($video_details_field['video_description']) ? wp_strip_all_tags($video_details_field['video_description']) : wp_strip_all_tags(get_the_excerpt()),
                'uploadDate'  => get_the_date('c'),
                'contentUrl'  => $video_data['url'],
                'embedUrl'    => $video_data['embed_url'],
                'thumbnailUrl'=> ''
            ];

            if (!empty($video_details_field['video_thumbnail'])) {
                $thumb = $video_details_field['video_thumbnail'];
                $video_schema['thumbnailUrl'] = is_array($thumb) ? $thumb['url'] : $thumb;
            } elseif (has_post_thumbnail()) {
                $video_schema['thumbnailUrl'] = get_the_post_thumbnail_url(get_the_ID(), 'full');
            }

             if (!empty($video_schema['name']) && !empty($video_schema['contentUrl'])) {
                 echo '<script type="application/ld+json" class="mia-video-schema">';
                 $video_schema_filtered = array_filter($video_schema, function($value) { return $value !== ''; });
                 echo esc_html( wp_json_encode( $video_schema_filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT ) );
                 echo '</script>';
             }
        }
    }


    $faq_section = get_field('faq_section');
    if (!empty($faq_section) && !empty($faq_section['faqs']) && is_array($faq_section['faqs'])) {
        $faq_schema = [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => []
        ];

        foreach ($faq_section['faqs'] as $faq_item) {
            if (!empty($faq_item['question']) && !empty($faq_item['answer'])) {
                $faq_schema['mainEntity'][] = [
                    '@type'          => 'Question',
                    'name'           => wp_strip_all_tags($faq_item['question']),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => wp_kses_post($faq_item['answer'])
                    ]
                ];
            }
        }

        if (!empty($faq_schema['mainEntity'])) {
            echo '<script type="application/ld+json" class="mia-faq-schema">';
            echo esc_html( wp_json_encode( $faq_schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT ) );
            echo '</script>';
        }
    }
}
add_action('wp_head', 'mia_add_schema', 20); // Add schema later in the head, Yoast defaults usually run at 10



/**
 * Add organization schema with address information for the homepage
 * This adds address details that the free Yoast SEO doesn't provide
 */
function mia_add_organization_address_schema() {
    // Only add this on the homepage/front page
    if (!is_front_page() && !is_home()) {
        return;
    }
    
    $org_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'MedicalOrganization',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => '123 Main Street',
            'addressLocality' => 'Miami',
            'addressRegion' => 'FL',
            'postalCode' => '33101',
            'addressCountry' => 'US'
        ],
        'telephone' => get_field('company_phone', 'option') ?: '',
        'email' => get_field('company_email', 'option') ?: '',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => get_field('company_logo', 'option') ?: get_template_directory_uri() . '/assets/images/logo.png'
        ],
        'medicalSpecialty' => get_field('company_specialties', 'option') ?: [],
        'currenciesAccepted' => 'USD',
        'paymentAccepted' => get_field('company_payment_methods', 'option') ?: 'Cash, Credit Card, Insurance',
        'priceRange' => get_field('company_price_range', 'option') ?: '$-$$$'
    ];
    
    $social_profiles = get_field('social_profiles', 'option');
    if (!empty($social_profiles) && is_array($social_profiles)) {
        $org_schema['sameAs'] = [];
        foreach ($social_profiles as $profile) {
            if (!empty($profile['url'])) {
                $org_schema['sameAs'][] = $profile['url'];
            }
        }
    }
    
    $hours = get_field('company_hours', 'option');
    if (!empty($hours)) {
        $org_schema['openingHours'] = $hours;
    }
    
    $locations = get_field('company_locations', 'option');
    if (!empty($locations) && is_array($locations)) {
        $org_schema['location'] = [];
        foreach ($locations as $location) {
            if (!empty($location['name'])) {
                $location_schema = [
                    '@type' => ['MedicalClinic', 'MedicalBusiness', 'LocalBusiness'],
                    'name' => $location['name'],
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressCountry' => 'US'
                    ]
                ];
                
                if (!empty($location['address'])) {
                    $location_schema['address']['streetAddress'] = $location['address'];
                }
                if (!empty($location['city'])) {
                    $location_schema['address']['addressLocality'] = $location['city'];
                }
                if (!empty($location['state'])) {
                    $location_schema['address']['addressRegion'] = $location['state'];
                }
                if (!empty($location['zip'])) {
                    $location_schema['address']['postalCode'] = $location['zip'];
                }
                
                if (!empty($location['phone'])) {
                    $location_schema['telephone'] = $location['phone'];
                }
                
                if (!empty($location['url'])) {
                    $location_schema['url'] = $location['url'];
                }
                
                $org_schema['location'][] = $location_schema;
            }
        }
    }
    
    $rating = get_field('company_rating', 'option');
    $rating_count = get_field('company_rating_count', 'option');
    if (!empty($rating) && !empty($rating_count)) {
        $org_schema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => $rating,
            'ratingCount' => $rating_count
        ];
    }
    
    $org_schema_filtered = array_filter($org_schema, function($value) {
        return $value !== '' && $value !== null && (!is_array($value) || !empty(array_filter($value)));
    });
    
    echo '<script type="application/ld+json" class="yoast-schema-graph-address">';
    echo esc_html( wp_json_encode( $org_schema_filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
    echo '</script>';
}
add_action('wp_head', 'mia_add_organization_address_schema', 11); // Run right after Yoast



/**
 * Displays FAQs using Bootstrap Accordion if ACF field 'faq_section' exists.
 * Uses the data populated by ACF.
 * Returns the HTML string or empty string.
 */
function display_page_faqs($show_heading = true) {
    $faq_section = get_field('faq_section'); // Field group name

    // Ensure the section and the repeater field exist and have data
    if (empty($faq_section) || empty($faq_section['faqs']) || !is_array($faq_section['faqs'])) {
        return ''; // Return empty string if no valid FAQ data
    }

    $faqs = $faq_section['faqs']; // The repeater field name
    $accordion_id = 'faq-accordion-' . get_the_ID(); // Unique ID for the accordion

    ob_start(); // Start output buffering
    ?>
    <section class="faqs-section my-5" <?php if($show_heading) { echo 'aria-labelledby="faq-heading-' . get_the_ID() . '"'; } ?>>
        <?php if ($show_heading): 
            $section_title = !empty($faq_section['title']) ? $faq_section['title'] : __('Frequently Asked Questions', 'mia-aesthetics');
        ?>
            <h2 id="faq-heading-<?php echo get_the_ID(); ?>" class="mb-4"><?php echo esc_html($section_title); ?></h2>

            <?php
            if (!empty($faq_section['description'])): ?>
                <div class="faq-description mb-4">
                    <?php echo wp_kses_post($faq_section['description']); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!empty($faqs)): ?>
        <div class="accordion" id="<?php echo esc_attr($accordion_id); ?>">
            <?php foreach ($faqs as $index => $faq):
                if (empty($faq['question']) || empty($faq['answer'])) continue;

                $q = $faq['question'];
                $a = $faq['answer'];
                $item_id = 'faq-' . get_the_ID() . '-' . $index;
                $heading_id = 'heading-' . $item_id;
                $collapse_id = 'collapse-' . $item_id;
                $is_first = ($index === 0);
            ?>
                <div class="accordion-item">
                    <h3 class="accordion-header" id="<?php echo esc_attr($heading_id); ?>">
                        <button
                            class="accordion-button <?php echo $is_first ? '' : 'collapsed'; ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#<?php echo esc_attr($collapse_id); ?>"
                            aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo esc_attr($collapse_id); ?>"
                        >
                            <?php echo esc_html($q); ?>
                        </button>
                    </h3>
                    <div
                        id="<?php echo esc_attr($collapse_id); ?>"
                        class="accordion-collapse collapse <?php echo $is_first ? 'show' : ''; ?>"
                        aria-labelledby="<?php echo esc_attr($heading_id); ?>"
                    >
                        <div class="accordion-body">
                            <?php echo wp_kses_post($a); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>
    <?php
    return ob_get_clean(); // Return buffered content
}


/**
 * Modify the main query for the Location archive page.
 * - Show all locations (no pagination).
 * - Only show top-level locations (no children).
 * - Sort alphabetically by title.
 */
function mia_modify_location_archive_query( $query ) {
    // Check if it's the main query, on the frontend, and the location archive page
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'location' ) ) {
        // Show all posts
        $query->set( 'posts_per_page', -1 );
        // Only show top-level posts (pages with no parent)
        $query->set( 'post_parent', 0 );
        // Sort alphabetically by title
        $query->set( 'orderby', 'title' );
        $query->set( 'order', 'ASC' );
    }
}
add_action( 'pre_get_posts', 'mia_modify_location_archive_query' );


/**
 * Modify the main query for the Surgeon archive page.
 * - Show all surgeons (no pagination).
 * - Sort by menu_order (post order) which can be manually set in the admin.
 */
function mia_modify_surgeon_archive_query( $query ) {
    // Check if it's the main query, on the frontend, and the surgeon archive page
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'surgeon' ) ) {
        // Show all posts
        $query->set( 'posts_per_page', -1 );
        
        // Sort by menu_order (post order)
        $query->set( 'orderby', 'menu_order' );
        $query->set( 'order', 'ASC' );
    }
}
add_action( 'pre_get_posts', 'mia_modify_surgeon_archive_query' );


/**
 * Ensure the correct body class is added for archive pages
 * This is a failsafe to make sure the post-type-archive classes are added
 */
function mia_ensure_archive_body_class($classes) {
    if (is_post_type_archive('surgeon')) {
        if (!in_array('post-type-archive-surgeon', $classes)) {
            $classes[] = 'post-type-archive-surgeon';
        }
    }
    
    if (is_post_type_archive('location')) {
        if (!in_array('post-type-archive-location', $classes)) {
            $classes[] = 'post-type-archive-location';
        }
    }
    
    return $classes;
}
add_filter('body_class', 'mia_ensure_archive_body_class', 999); // Run late to ensure it's not overridden


/**
 * Completely disable comments functionality
 */
function mia_disable_comments() {
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);
    
    add_filter('comments_array', '__return_empty_array', 10, 2);
    
    add_action('admin_menu', function() {
        remove_menu_page('edit-comments.php');
    });
    
    add_action('wp_before_admin_bar_render', function() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    });
    
    add_action('admin_init', function() {
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    });
    
    add_action('admin_init', function() {
        global $pagenow;
        
        if ($pagenow === 'edit-comments.php') {
            wp_redirect(admin_url());
            exit;
        }
    });
    
    add_action('admin_init', function() {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    });
    
    add_filter('rest_endpoints', function($endpoints) {
        if (isset($endpoints['/wp/v2/comments'])) {
            unset($endpoints['/wp/v2/comments']);
        }
        if (isset($endpoints['/wp/v2/comments/(?P<id>[\\d]+)'])) {
            unset($endpoints['/wp/v2/comments/(?P<id>[\\d]+)']);
        }
        return $endpoints;
    });
}
add_action('init', 'mia_disable_comments');

/**
 * Custom excerpt length for archive pages
 * Cuts the default excerpt length by half
 */
function mia_custom_excerpt_length($length) {
    return intval($length / 2);
}
add_filter('excerpt_length', 'mia_custom_excerpt_length');

/**
 * Remove category from the excerpt (if it's added automatically)
 */
function mia_trim_excerpt($excerpt) {
    // This is a simple function to ensure categories aren't included in excerpt
    // Modify as needed based on how categories are displayed in your theme
    return $excerpt;
}
add_filter('get_the_excerpt', 'mia_trim_excerpt');

/**
 * Use single-condition.php for 2nd-level children of "procedure"
 */
add_filter('single_template', function($template) {
    if (is_singular('procedure')) {
        $post = get_queried_object();
        $ancestors = get_post_ancestors($post);
        
        if (count($ancestors) === 2) {
            $alt = locate_template('single-condition.php');
            if ($alt) {
                return $alt;
            }
        }
    }
    
    return $template;
});

/**
 * Menu data structure to eliminate duplication
 */
function get_mia_menu_structure() {
    return [
        'procedures' => [
            'title' => 'Procedures',
            'url' => home_url('/cosmetic-plastic-surgery/'),
            'sections' => [
                'body' => [
                    'title' => 'Body',
                    'url' => home_url('/cosmetic-plastic-surgery/body/'),
                    'items' => [
                        ['title' => 'Mia Waist Corset™', 'slug' => 'mia-corset'],
                        ['title' => 'Awake Lipo', 'slug' => 'awake-liposuction'],
                        ['title' => 'Body Lift', 'slug' => 'circumferential-body-lift'],
                        ['title' => 'Brazilian Butt Lift (BBL)', 'slug' => 'brazilian-butt-lift-bbl'],
                        ['title' => 'Lipo 360', 'slug' => 'lipo-360'],
                        ['title' => 'Liposuction', 'slug' => 'liposuction'],
                        ['title' => 'Tummy Tuck', 'slug' => 'tummy-tuck'],
                        ['title' => 'Mommy Makeover', 'slug' => 'mommy-makeover'],
                        ['title' => 'Arm Lift', 'slug' => 'arm-lift'],
                        ['title' => 'Thigh Lift', 'slug' => 'thigh-lift'],
                        ['title' => 'Vaginal Rejuvenation', 'slug' => 'labiaplasty-labia-reduction-vaginal-rejuvenation'],
                    ]
                ],
                'breast' => [
                    'title' => 'Breast',
                    'url' => home_url('/cosmetic-plastic-surgery/breast/'),
                    'items' => [
                        ['title' => 'Breast Augmentation', 'slug' => 'augmentation-implants'],
                        ['title' => 'Breast Reduction', 'slug' => 'reduction'],
                        ['title' => 'Breast Lift', 'slug' => 'lift'],
                        ['title' => 'Breast Implant Revision', 'slug' => 'implant-revision-surgery'],
                    ]
                ],
                'face' => [
                    'title' => 'Face',
                    'url' => home_url('/cosmetic-plastic-surgery/face/'),
                    'items' => [
                        ['title' => 'Brow Lift', 'slug' => 'brow-lift'],
                        ['title' => 'Buccal Fat Removal', 'slug' => 'buccal-cheek-fat-removal'],
                        ['title' => 'Blepharoplasty', 'slug' => 'eyelid-lift-blepharoplasty'],
                        ['title' => 'Chin Lipo', 'slug' => 'chin-lipo'],
                        ['title' => 'Facelift', 'slug' => 'facelift'],
                        ['title' => 'Mini Facelift', 'slug' => 'mini-facelift'],
                        ['title' => 'Neck Lift', 'slug' => 'neck-lift'],
                        ['title' => 'Otoplasty', 'slug' => 'ear-pinning-otoplasty'],
                        ['title' => 'Rhinoplasty', 'slug' => 'nose-job-rhinoplasty'],
                    ]
                ],
                'men' => [
                    'title' => 'Men',
                    'url' => home_url('#'),
                    'items' => [
                        ['title' => 'Male BBL', 'slug' => 'male-bbl', 'parent' => 'body'],
                        ['title' => 'Male Breast Procedures', 'slug' => 'male-breast-procedures', 'parent' => 'breast'],
                        ['title' => 'Male Liposuction', 'slug' => 'male-liposuction', 'parent' => 'body'],
                        ['title' => 'Male Tummy Tuck', 'slug' => 'male-tummy-tuck', 'parent' => 'body'],
                    ]
                ]
            ]
        ]
    ];
}

/**
 * Render menu for both desktop and mobile
 */
function render_mia_menu($type = 'desktop') {
    $menu = get_mia_menu_structure();
    $is_mobile = $type === 'mobile';
    
    foreach ($menu as $key => $section) {
        if ($key === 'procedures') {
            render_procedures_menu($section, $is_mobile);
        }
        // Add other menu sections here
    }
}

/**
 * Render procedures dropdown
 */
function render_procedures_menu($procedures, $is_mobile = false) {
    $dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
    ?>
    <li class="nav-item dropdown <?php echo $dropdown_class; ?>">
        <a class="nav-link dropdown-toggle" href="<?php echo esc_url($procedures['url']); ?>" 
           role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo esc_html($procedures['title']); ?>
        </a>
        <?php if ($is_mobile): ?>
            <?php render_mobile_procedures_menu($procedures); ?>
        <?php else: ?>
            <?php render_desktop_procedures_menu($procedures); ?>
        <?php endif; ?>
    </li>
    <?php
}

/**
 * Render desktop procedures mega menu
 */
function render_desktop_procedures_menu($procedures) {
    ?>
    <div class="dropdown-menu mega-menu w-100 p-3">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <a class="mega-menu-title" href="<?php echo esc_url($procedures['url']); ?>">View All Procedures <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="row">
                <?php foreach ($procedures['sections'] as $section_key => $section): ?>
                    <div class="col-md-3 mb-3">
                        <h6 class="dropdown-header">
                            <a href="<?php echo esc_url($section['url']); ?>" class="text-dark fw-bold text-decoration-none"><?php echo esc_html($section['title']); ?></a>
                        </h6>
                        <ul class="list-unstyled">
                            <?php foreach ($section['items'] as $item): ?>
                                <?php 
                                $parent_path = isset($item['parent']) ? $procedures['sections'][$item['parent']]['url'] : $section['url'];
                                $item_url = rtrim($parent_path, '/') . '/' . $item['slug'] . '/';
                                ?>
                                <li><a class="dropdown-item py-1" href="<?php echo esc_url($item_url); ?>"><?php echo esc_html($item['title']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render mobile procedures dropdown menu
 */
function render_mobile_procedures_menu($procedures) {
    ?>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo esc_url($procedures['url']); ?>">View All Procedures</a></li>
        <?php foreach ($procedures['sections'] as $section_key => $section): ?>
            <!-- Section Header -->
            <li><a class="dropdown-item fw-bold" href="<?php echo esc_url($section['url']); ?>"><?php echo esc_html($section['title']); ?></a></li>
            <!-- Section Items -->
            <?php foreach ($section['items'] as $item): ?>
                <?php 
                $parent_path = isset($item['parent']) ? $procedures['sections'][$item['parent']]['url'] : $section['url'];
                $item_url = rtrim($parent_path, '/') . '/' . $item['slug'] . '/';
                ?>
                <li><a class="dropdown-item" href="<?php echo esc_url($item_url); ?>"><?php echo esc_html($item['title']); ?></a></li>
            <?php endforeach; ?>
            <?php if ($section_key !== array_key_last($procedures['sections'])): ?>
                <li><hr class="dropdown-divider"></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <?php
}

/**
 * Cache location queries
 */
function get_cached_locations() {
    $cache_key = 'mia_header_locations';
    $locations = wp_cache_get($cache_key);
    
    if (false === $locations) {
        $args = [
            'post_type' => 'location',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_parent' => 0
        ];
        
        $query = new WP_Query($args);
        $locations = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $locations[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'state' => get_field('state')
                ];
            }
            wp_reset_postdata();
        }
        
        wp_cache_set($cache_key, $locations, '', 3600); // Cache for 1 hour
    }
    
    return $locations;
}

/**
 * Cache surgeon queries
 */
function get_cached_surgeons() {
    $cache_key = 'mia_header_surgeons';
    $surgeons = wp_cache_get($cache_key);
    
    if (false === $surgeons) {
        $args = [
            'post_type' => 'surgeon',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ];
        
        $query = new WP_Query($args);
        $surgeons = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $surgeon_name = get_the_title();
                $name_parts = explode(' ', $surgeon_name);
                $last_name = isset($name_parts[1]) ? $name_parts[1] : $surgeon_name;
                
                $surgeons[] = [
                    'id' => get_the_ID(),
                    'name' => $surgeon_name,
                    'url' => get_permalink(),
                    'last_name' => $last_name
                ];
            }
            wp_reset_postdata();
            
            // Sort surgeons by last name
            usort($surgeons, function($a, $b) {
                return strcasecmp($a['last_name'], $b['last_name']);
            });
        }
        
        wp_cache_set($cache_key, $surgeons, '', 3600); // Cache for 1 hour
    }
    
    return $surgeons;
}

/**
 * Render locations menu for both desktop and mobile
 */
function render_locations_menu($is_mobile = false) {
    $locations = get_cached_locations();
    $dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
    ?>
    <li class="nav-item dropdown <?php echo $dropdown_class; ?>">
        <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/locations/')); ?>" 
           role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Locations
        </a>
        <?php if ($is_mobile): ?>
            <?php render_mobile_locations_menu($locations); ?>
        <?php else: ?>
            <?php render_desktop_locations_menu($locations); ?>
        <?php endif; ?>
    </li>
    <?php
}

/**
 * Render desktop locations mega menu
 */
function render_desktop_locations_menu($locations) {
    ?>
    <div class="dropdown-menu mega-menu w-100 p-3">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <a class="mega-menu-title" href="<?php echo esc_url(home_url('/locations/')); ?>">View All Locations <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="row">
                <?php
                if (!empty($locations)) :
                    $total_locations = count($locations);
                    $locations_per_column = ceil($total_locations / 4);
                    $location_count = 0;
                    $column_count = 0;
                    
                    echo '<div class="col-md-3 mb-3"><ul class="list-unstyled">';
                    foreach ($locations as $location) :
                        $display_city = trim(str_ireplace('Mia Aesthetics', '', $location['title']));
                        $abbr = mia_get_state_abbr($location['state']);
                        $menu_label = $location['state'] ? $display_city . ', ' . $abbr : $display_city;
                        
                        echo '<li><a class="dropdown-item py-1" href="' . esc_url($location['url']) . '">' . esc_html($menu_label) . '</a></li>';
                        $location_count++;
                        
                        if ($location_count % $locations_per_column === 0 && $location_count < $total_locations) {
                            $column_count++;
                            echo '</ul></div><div class="col-md-3 mb-3"><ul class="list-unstyled">';
                        }
                    endforeach;
                    echo '</ul></div>';
                    
                    while ($column_count < 3) {
                        $column_count++;
                        echo '<div class="col-md-3 mb-3"></div>';
                    }
                else:
                    echo '<div class="col-12"><p>No locations found. <a href="' . esc_url(home_url('/locations/')) . '">View our locations page</a> for more information.</p></div>';
                endif;
                ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render mobile locations dropdown menu
 */
function render_mobile_locations_menu($locations) {
    ?>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/locations/')); ?>">View All Locations</a></li>
        <?php
        if (!empty($locations)) :
            foreach ($locations as $location) :
                $display_city = trim(str_ireplace('Mia Aesthetics', '', $location['title']));
                $abbr = mia_get_state_abbr($location['state']);
                $menu_label = $location['state'] ? $display_city . ', ' . $abbr : $display_city;
                ?>
                <li><a class="dropdown-item" href="<?php echo esc_url($location['url']); ?>"><?php echo esc_html($menu_label); ?></a></li>
                <?php
            endforeach;
        else:
            ?>
            <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/locations/')); ?>">View Our Locations</a></li>
            <?php
        endif;
        ?>
    </ul>
    <?php
}

/**
 * Render surgeons menu for both desktop and mobile
 */
function render_surgeons_menu($is_mobile = false) {
    $surgeons = get_cached_surgeons();
    $dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
    ?>
    <li class="nav-item dropdown <?php echo $dropdown_class; ?>">
        <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>" 
           role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Surgeons
        </a>
        <?php if ($is_mobile): ?>
            <?php render_mobile_surgeons_menu($surgeons); ?>
        <?php else: ?>
            <?php render_desktop_surgeons_menu($surgeons); ?>
        <?php endif; ?>
    </li>
    <?php
}

/**
 * Render desktop surgeons mega menu
 */
function render_desktop_surgeons_menu($surgeons) {
    ?>
    <div class="dropdown-menu mega-menu w-100 p-3">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <a class="mega-menu-title" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>">View All Surgeons <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="row">
                <?php
                if (!empty($surgeons)) :
                    $total_surgeons = count($surgeons);
                    $surgeons_per_column = ceil($total_surgeons / 4);
                    $surgeon_count = 0;
                    $column_count = 0;
                    
                    echo '<div class="col-md-3 mb-3"><ul class="list-unstyled">';
                    foreach ($surgeons as $surgeon) :
                        echo '<li><a class="dropdown-item py-1" href="' . esc_url($surgeon['url']) . '">' . esc_html($surgeon['name']) . '</a></li>';
                        $surgeon_count++;
                        
                        if ($surgeon_count % $surgeons_per_column === 0 && $surgeon_count < $total_surgeons) {
                            $column_count++;
                            echo '</ul></div><div class="col-md-3 mb-3"><ul class="list-unstyled">';
                        }
                    endforeach;
                    echo '</ul></div>';
                    
                    while ($column_count < 3) {
                        $column_count++;
                        echo '<div class="col-md-3 mb-3"></div>';
                    }
                else:
                    echo '<div class="col-12"><p>No surgeons found. <a href="' . esc_url(home_url('/plastic-surgeons/')) . '">View our surgeons page</a> for more information.</p></div>';
                endif;
                ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render mobile surgeons dropdown menu
 */
function render_mobile_surgeons_menu($surgeons) {
    ?>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>">View All Surgeons</a></li>
        <?php
        if (!empty($surgeons)) :
            foreach ($surgeons as $surgeon) :
                ?>
                <li><a class="dropdown-item" href="<?php echo esc_url($surgeon['url']); ?>"><?php echo esc_html($surgeon['name']); ?></a></li>
                <?php
            endforeach;
        else:
            ?>
            <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>">View Our Surgeons</a></li>
            <?php
        endif;
        ?>
    </ul>
    <?php
}

/**
 * Render Before & After menu for both desktop and mobile
 */
function render_before_after_menu($is_mobile = false) {
    $dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
    ?>
    <li class="nav-item dropdown <?php echo $dropdown_class; ?>">
        <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/before-after/')); ?>" 
           role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Before & After
        </a>
        <?php if ($is_mobile): ?>
            <?php render_mobile_before_after_menu(); ?>
        <?php else: ?>
            <?php render_desktop_before_after_menu(); ?>
        <?php endif; ?>
    </li>
    <?php
}

/**
 * Render desktop Before & After mega menu
 */
function render_desktop_before_after_menu() {
    ?>
    <div class="dropdown-menu mega-menu w-100 p-3">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-3">
                    <a class="mega-menu-title" href="<?php echo esc_url(home_url('/before-after/')); ?>">View All Before & After <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6 class="dropdown-header">
                        <span class="text-dark fw-bold">By Procedure</span>
                    </h6>
                    <ul class="list-unstyled">
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/arm/')); ?>">Arm Lift</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/bbl/')); ?>">Brazilian Butt Lift (BBL)</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/breast-augmentation/')); ?>">Breast Augmentation</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/breast-lift/')); ?>">Breast Lift</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/breast-reduction/')); ?>">Breast Reduction</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/lipo-360/')); ?>">Lipo 360</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/mommy-makeover/')); ?>">Mommy Makeover</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/tummy-tuck/')); ?>">Tummy Tuck</a></li>
                    </ul>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="dropdown-header">
                        <span class="text-dark fw-bold">By Category</span>
                    </h6>
                    <ul class="list-unstyled">
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/before-after-by-doctor/')); ?>">Results by Surgeon</a></li>
                        <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/before-after/patient-journeys/')); ?>">Patient Videos</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render mobile Before & After dropdown menu
 */
function render_mobile_before_after_menu() {
    ?>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/')); ?>">View All Before & After</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/arm/')); ?>">Arm Lift</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/bbl/')); ?>">Brazilian Butt Lift (BBL)</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/breast-augmentation/')); ?>">Breast Augmentation</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/breast-lift/')); ?>">Breast Lift</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/breast-reduction/')); ?>">Breast Reduction</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/lipo-360/')); ?>">Lipo 360</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/mommy-makeover/')); ?>">Mommy Makeover</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/tummy-tuck/')); ?>">Tummy Tuck</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/patient-journeys/')); ?>">Patient Videos</a></li>
        <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/before-after/before-after-by-doctor/')); ?>">Results by Surgeon</a></li>
    </ul>
    <?php
}

/**
 * Clear cached data when posts are updated
 */
function clear_mia_caches($post_id) {
    $post_type = get_post_type($post_id);
    
    if ($post_type === 'location') {
        wp_cache_delete('mia_header_locations');
        wp_cache_delete('mia_footer_locations');
    }
    
    if ($post_type === 'surgeon') {
        wp_cache_delete('mia_header_surgeons');
        wp_cache_delete('mia_footer_surgeons');
    }
}
add_action('save_post', 'clear_mia_caches');
add_action('delete_post', 'clear_mia_caches');

/**
 * Generate consistent button HTML with data attributes
 */
function mia_button($text, $url = '#', $variant = 'primary', $icon = '', $attributes = []) {
    $default_attributes = [
        'class' => 'mia-button',
        'data-variant' => $variant,
        'role' => 'button'
    ];
    
    $attributes = array_merge($default_attributes, $attributes);
    
    $attr_string = '';
    foreach ($attributes as $key => $value) {
        $attr_string .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
    }
    
    $icon_html = $icon ? ' <i class="' . esc_attr($icon) . '"></i>' : '';
    
    if ($url === '#' || empty($url)) {
        return '<button' . $attr_string . '>' . esc_html($text) . $icon_html . '</button>';
    } else {
        return '<a href="' . esc_url($url) . '"' . $attr_string . '>' . esc_html($text) . $icon_html . '</a>';
    }
}

?>
