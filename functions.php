<?php
/**
 * @package Mia_Aesthetics
 */

add_theme_support( 'post-thumbnails' );

function mia_aesthetics_enqueue_scripts() {
    $theme_path = get_template_directory();
    $theme_uri = get_template_directory_uri();
    $css_path = $theme_path . '/assets/css';
    $css_uri = $theme_uri . '/assets/css';

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
    $base_css_file = $css_path . '/_base.css';
    if ( file_exists( $base_css_file ) ) {
        $ver = filemtime( $base_css_file );
        wp_enqueue_style(
            'mia-base',
            $css_uri . '/_base.css',
            array(),
            $ver
        );
    }

    // 2. Enqueue header/footer (depend on base)
    $header_css_file = $css_path . '/_header.css';
    if ( file_exists( $header_css_file ) ) {
        $ver = filemtime( $header_css_file );
        wp_enqueue_style(
            'mia-header',
            $css_uri . '/_header.css',
            array('mia-base'),
            $ver
        );
    }
    $footer_css_file = $css_path . '/_footer.css';
    if ( file_exists( $footer_css_file ) ) {
        $ver = filemtime( $footer_css_file );
        wp_enqueue_style(
            'mia-footer',
            $css_uri . '/_footer.css',
            array('mia-base'),
            $ver
        );
    }

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
        $page_css_file = $css_path . $page_css;
        if ( file_exists( $page_css_file ) ) {
            $ver = filemtime( $page_css_file );
            wp_enqueue_style(
                $page_handle,
                $css_uri . $page_css,
                array( 'mia-base' ),
                $ver
            );
        }
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
    
    if ( isset( $js_file ) && file_exists( $js_file ) ) {
        $ver = filemtime( $js_file );
        wp_enqueue_script(
            $handle,
            $theme_uri . '/assets/js/' . basename( $js_file ),
            array( 'bootstrap-js' ),
            $ver,
            true
        );
    }
    
    if (is_singular('surgeon') || is_singular('location')) {
        $video_js_file = $theme_path . '/assets/js/video.js';
        if ( file_exists( $video_js_file ) ) {
            $ver = filemtime( $video_js_file );
            wp_enqueue_script(
                'mia-video-script',
                $theme_uri . '/assets/js/video.js',
                array( 'bootstrap-js' ),
                $ver,
                true
            );
        }
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
    // Fallback: manual array for legacy support
    $abbr = array(
        'Alabama' => 'AL', 'Alaska' => 'AK', 'Arizona' => 'AZ', 'Arkansas' => 'AR', 'California' => 'CA',
        'Colorado' => 'CO', 'Connecticut' => 'CT', 'Delaware' => 'DE', 'Florida' => 'FL', 'Georgia' => 'GA',
        'Hawaii' => 'HI', 'Idaho' => 'ID', 'Illinois' => 'IL', 'Indiana' => 'IN', 'Iowa' => 'IA',
        'Kansas' => 'KS', 'Kentucky' => 'KY', 'Louisiana' => 'LA', 'Maine' => 'ME', 'Maryland' => 'MD',
        'Massachusetts' => 'MA', 'Michigan' => 'MI', 'Minnesota' => 'MN', 'Mississippi' => 'MS', 'Missouri' => 'MO',
        'Montana' => 'MT', 'Nebraska' => 'NE', 'Nevada' => 'NV', 'New Hampshire' => 'NH', 'New Jersey' => 'NJ',
        'New Mexico' => 'NM', 'New York' => 'NY', 'North Carolina' => 'NC', 'North Dakota' => 'ND', 'Ohio' => 'OH',
        'Oklahoma' => 'OK', 'Oregon' => 'OR', 'Pennsylvania' => 'PA', 'Rhode Island' => 'RI', 'South Carolina' => 'SC',
        'South Dakota' => 'SD', 'Tennessee' => 'TN', 'Texas' => 'TX', 'Utah' => 'UT', 'Vermont' => 'VT',
        'Virginia' => 'VA', 'Washington' => 'WA', 'West Virginia' => 'WV', 'Wisconsin' => 'WI', 'Wyoming' => 'WY',
        'District of Columbia' => 'DC'
    );
    return isset($abbr[$state]) ? $abbr[$state] : $state;
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
        return $video_data;
    }

    // Fallback: Check if YouTube URL
    if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $video_url, $matches) ||
        preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $video_url, $matches)) {
        $video_id = $matches[1];
        $video_data['url'] = 'https://www.youtube.com/watch?v=' . $video_id;
        $video_data['embed_url'] = 'https://www.youtube.com/embed/' . $video_id;
        $video_data['type'] = 'youtube';
        return $video_data;
    }

    // Fallback: Check if Vimeo URL
    if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $video_url, $matches)) {
        $video_id = $matches[1];
        $video_data['url'] = 'https://vimeo.com/' . $video_id;
        $video_data['embed_url'] = 'https://player.vimeo.com/video/' . $video_id;
        $video_data['type'] = 'vimeo';
        return $video_data;
    }

    // Fallback: Check if direct file URL (mp4) - simple check
    if (filter_var($video_url, FILTER_VALIDATE_URL) && pathinfo(parse_url($video_url, PHP_URL_PATH), PATHINFO_EXTENSION) === 'mp4') {
        $video_data['url'] = $video_url;
        $video_data['embed_url'] = $video_url; // Direct link for embed
        $video_data['type'] = 'mp4';
        return $video_data;
    }

    // Fallback: If it's a valid URL but type is unknown, return it
    if (filter_var($video_url, FILTER_VALIDATE_URL)) {
        $video_data['url'] = $video_url;
        $video_data['embed_url'] = $video_url; // Use direct URL as fallback embed
        // $video_data['type'] remains 'unknown'
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

?>
