<?php
/**
 * Theme Functions
 *
 * @package Mia_Aesthetics
 */

add_theme_support( 'post-thumbnails' );

// Enqueue Scripts and Styles
function mia_aesthetics_enqueue_scripts() {
    // Theme paths
    $theme_path = get_template_directory();
    $theme_uri = get_template_directory_uri();
    $css_path = $theme_path . '/assets/css';
    $css_uri = $theme_uri . '/assets/css';

    // Third-party CSS
    // Removed Google Fonts enqueue - Fonts are now loaded locally via _base.css

    // Consider removing Font Awesome if not actively used or if SVGs are preferred
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', // Check latest version
        array(),
        '6.7.2' // Update version number if you update the URL
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

    // Bootstrap JS Bundle (includes Popper)
    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js',
        array(), // Bootstrap 5 doesn't require jQuery as a dependency
        '5.3.6',
        true
    );

    // Base Styles - Always Load
    $base_file = '/_base.css';
    if (file_exists($css_path . $base_file)) {
        wp_enqueue_style(
            'mia-base',
            $css_uri . $base_file,
            array('bootstrap-css', 'normalize'), // Dependencies
            filemtime($css_path . $base_file)
        );
    }

    // Component Styles - Always Load (Header/Footer)
    $component_styles = array(
        'header' => '/_header.css',
        'footer' => '/_footer.css'
    );

    foreach ($component_styles as $name => $file) {
        if (file_exists($css_path . $file)) {
            wp_enqueue_style(
                'mia-' . $name,
                $css_uri . $file,
                array('mia-base'), // Depends on base
                filemtime($css_path . $file)
            );
        }
    }

    // --- Load CSS based on current template ---
    
    // 1. Determine the CSS file to load based on current page/post type
    $css_file = null;
    $handle = null;
    
    // Front page
    if (is_front_page()) {
        $css_file = '/_home.css';
        $handle = 'mia-home';
    }
    // 404 page
    elseif (is_404()) {
        $css_file = '/_404.css';
        $handle = 'mia-404';
    }
    // Search page
    elseif (is_search()) {
        $css_file = '/_search.css';
        $handle = 'mia-search';
    }
    // Taxonomy pages
    elseif (is_tax()) {
        $css_file = '/_taxonomies.css';
        $handle = 'mia-taxonomies';
    }
    // Blog index/archive
    elseif (is_home() || (is_archive() && get_post_type() == 'post')) {
        $css_file = '/_archive.css';
        $handle = 'mia-post-archive';
    }
    // Single post
    elseif (is_singular('post')) {
        $css_file = '/_single.css';
        $handle = 'mia-post-single';
    }
    // Regular pages
    elseif (is_page()) {
        $css_file = '/_page.css';
        $handle = 'mia-page';
    }
    // Custom post types - archives
    elseif (is_post_type_archive()) {
        $post_type = get_post_type();
        if (!$post_type) {
            // Get post type from query var if get_post_type() fails
            $post_type = get_query_var('post_type');
        }
        
        if ($post_type) {
            $css_file = '/_' . $post_type . '-archive.css';
            $handle = 'mia-' . $post_type . '-archive';
        }
    }
    // Custom post types - single
    elseif (is_singular()) {
        $post_type = get_post_type();
        
        if ($post_type && $post_type !== 'post' && $post_type !== 'page') {
            $css_file = '/_' . $post_type . '.css';
            $handle = 'mia-' . $post_type . '-single';
        }
    }
    
    // 2. Load the determined CSS file if it exists
    if ($css_file && $handle) {
        if (file_exists($css_path . $css_file)) {
            wp_enqueue_style(
                $handle,
                $css_uri . $css_file,
                array('mia-base'),
                filemtime($css_path . $css_file)
            );
        } else {
            // Log missing CSS file for debugging
            error_log('Mia Aesthetics Theme: CSS file not found: ' . $css_file);
        }
    }
    
    // 3. Page-specific styles based on slug (for regular pages only)
    if (is_page()) {
        $page_slug = get_post_field('post_name', get_post());
        $page_specific_path = '/_' . $page_slug . '.css';
        
        if (file_exists($css_path . $page_specific_path)) {
            wp_enqueue_style(
                'mia-page-' . $page_slug,
                $css_uri . $page_specific_path,
                array('mia-page'), // Depends on general page style
                filemtime($css_path . $page_specific_path)
            );
        }
    }

    // --- JavaScript Loading ---
    
    // Determine which JS file to load
    if (is_singular('condition')) {
        // Load script specific to single Condition pages
        $js_file = $theme_path . '/assets/js/condition.js';
        $handle = 'mia-condition-script';
    } 
    elseif (is_singular('surgeon')) {
        // Load script specific to single Surgeon pages
        $js_file = $theme_path . '/assets/js/surgeon.js';
        $handle = 'mia-surgeon-script';
    } 
    else {
        // Load global script on all other pages
        $js_file = $theme_path . '/assets/js/main.js';
        $handle = 'mia-aesthetics-script';
    }
    
    // Load the determined JS file if it exists
    if (isset($js_file) && file_exists($js_file)) {
        wp_enqueue_script(
            $handle,
            $theme_uri . '/assets/js/' . basename($js_file),
            array('bootstrap-js'),
            filemtime($js_file),
            true // Load in footer
        );
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

    // Check if YouTube URL
    if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $video_url, $matches) ||
        preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $video_url, $matches)) {
        $video_id = $matches[1];
        $video_data['url'] = 'https://www.youtube.com/watch?v=' . $video_id;
        $video_data['embed_url'] = 'https://www.youtube.com/embed/' . $video_id;
        $video_data['type'] = 'youtube';
        return $video_data;
    }

    // Check if Vimeo URL
    if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $video_url, $matches)) {
        $video_id = $matches[1];
        $video_data['url'] = 'https://vimeo.com/' . $video_id;
        $video_data['embed_url'] = 'https://player.vimeo.com/video/' . $video_id;
        $video_data['type'] = 'vimeo';
        return $video_data;
    }

    // Check if direct file URL (mp4) - simple check
    if (filter_var($video_url, FILTER_VALIDATE_URL) && pathinfo(parse_url($video_url, PHP_URL_PATH), PATHINFO_EXTENSION) === 'mp4') {
        $video_data['url'] = $video_url;
        $video_data['embed_url'] = $video_url; // Direct link for embed
        $video_data['type'] = 'mp4';
        return $video_data;
    }

    // If it's a valid URL but type is unknown, return it
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

    // --- Generate Schema for Specific Post Types ---
    switch ($post_type) {
        case 'procedure':
            if (is_singular('procedure')) {
                $schema = [
                    '@context'    => 'https://schema.org',
                    '@type'       => 'MedicalProcedure',
                    'name'        => get_the_title(),
                    'description' => wp_strip_all_tags(get_the_excerpt()), // Use stripped excerpt
                    'url'         => get_permalink() // Add URL
                ];
                if ($procedure_type = get_field('procedure_type')) {
                    $schema['procedureType'] = $procedure_type; // Assuming 'procedure_type' field exists
                }
                 // Consider adding 'bodyLocation', 'indication', 'outcome' if available in fields
            }
            break;

        case 'surgeon':
            if (is_singular('surgeon')) {
                $schema = [
                    '@context'      => 'https://schema.org',
                    '@type'         => 'Physician', // More specific than Person
                    'name'          => get_the_title(),
                    'description'   => wp_strip_all_tags(get_the_excerpt()),
                    'url'           => get_permalink(),
                    'address'       => [ // Address is often required/recommended for Physician
                        '@type'          => 'PostalAddress',
                        'addressCountry' => 'US' // Assume US, make dynamic if needed
                    ],
                    // Required/Recommended Properties (provide fallbacks or ensure fields exist)
                    'telephone'     => get_field('phone_number') ?: '', // Add fallback if field is empty
                    'priceRange'    => get_field('price_range') ?: '$-$$$', // Default price range
                    'image'         => '' // Initialize image
                ];

                // Populate Address fields (using potential variations)
                $street = get_field('street_address') ?: get_field('address');
                if ($street) $schema['address']['streetAddress'] = $street;
                if ($city = get_field('city')) $schema['address']['addressLocality'] = $city;
                if ($state = get_field('state')) $schema['address']['addressRegion'] = $state;
                if ($zip = get_field('zip_code')) $schema['address']['postalCode'] = $zip;

                // Populate Image (Required for Physician, provide fallback)
                if (has_post_thumbnail()) {
                    $schema['image'] = get_the_post_thumbnail_url(get_the_ID(), 'full');
                } elseif ($image_field = get_field('image')) { // Assuming 'image' is an ACF image field
                    $schema['image'] = is_array($image_field) ? $image_field['url'] : $image_field;
                } else {
                    $schema['image'] = get_template_directory_uri() . '/assets/images/default-doctor.jpg'; // Default image
                }

                // Optional but Recommended Properties
                if ($specialty = get_field('medical_specialty')) {
                    $schema['medicalSpecialty'] = $specialty;
                }

                // Work Location (MedicalClinic) - Nested
                $location_id = get_field('surgeon_location'); // Assuming ACF relationship field
                if ($location_id && is_numeric($location_id)) {
                    $work_location = [
                        '@type' => ['MedicalClinic', 'MedicalBusiness', 'LocalBusiness'],
                        'name' => get_the_title($location_id),
                        'url' => get_permalink($location_id),
                         'address' => [
                            '@type' => 'PostalAddress',
                            'addressCountry' => 'US'
                        ],
                        'telephone' => get_field('phone_number', $location_id) ?: (get_field('location_phone', $location_id) ?: ''), // Try multiple phone fields for location
                        'image' => ''
                    ];
                     // Populate location address
                    $loc_street = get_field('street_address', $location_id) ?: get_field('location_address', $location_id);
                    if ($loc_street) $work_location['address']['streetAddress'] = $loc_street;
                    if ($loc_city = get_field('city', $location_id)) $work_location['address']['addressLocality'] = $loc_city;
                    if ($loc_state = get_field('state', $location_id)) $work_location['address']['addressRegion'] = $loc_state;
                    if ($loc_zip = get_field('zip_code', $location_id)) $work_location['address']['postalCode'] = $loc_zip;

                    // Populate location image
                    if (has_post_thumbnail($location_id)) {
                        $work_location['image'] = get_the_post_thumbnail_url($location_id, 'full');
                    } else {
                         $work_location['image'] = get_template_directory_uri() . '/assets/images/default-location.jpg'; // Default location image
                    }
                    $schema['workLocation'] = $work_location;
                }

                // Member Of (Organization) - Nested Reference
                $schema['memberOf'] = [
                    '@type' => 'MedicalOrganization',
                    'name' => get_bloginfo('name'),
                    'url' => home_url(),
                    'telephone' => get_field('company_phone', 'option') ?: '',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => '123 Main Street', // Replace with actual address 
                        'addressLocality' => 'Miami', // Replace with actual city
                        'addressRegion' => 'FL', // Replace with actual state
                        'postalCode' => '33101', // Replace with actual zip code
                        'addressCountry' => 'US'
                    ]
                ];

                 // Note: A better approach uses Yoast filters to link this Physician
                 // to the main Organization node generated by Yoast using '@id'.
            }
            break;

case 'location':
     if (is_singular('location')) {
        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => ['MedicalClinic', 'MedicalBusiness', 'LocalBusiness'], // Complete type hierarchy
            'name'        => get_the_title(),
            'description' => wp_strip_all_tags(get_the_excerpt()),
            'url'         => get_permalink(),
            'address'     => [
                '@type'          => 'PostalAddress',
                'addressCountry' => 'US'
            ],
            'telephone'   => '', // Initialize
            'priceRange'  => get_field('price_range') ?: '$-$$$', // Default price range
            'image'       => '', // Initialize image
            'currenciesAccepted' => 'USD', // Standard currency format
            'paymentAccepted' => get_field('payment_methods') ?: 'Cash, Credit Card, Insurance', // Default payment methods
            'medicalSpecialty' => get_field('medical_specialty') ?: [] // Initialize medical specialty
        ];

        // Populate Address fields
        $street = get_field('street_address') ?: get_field('location_address');
        if ($street) $schema['address']['streetAddress'] = $street;
        if ($city = get_field('city')) $schema['address']['addressLocality'] = $city;
        if ($state = get_field('state')) $schema['address']['addressRegion'] = $state;
        if ($zip = get_field('zip_code')) $schema['address']['postalCode'] = $zip;

        // Populate Telephone (try multiple fields)
        $schema['telephone'] = get_field('phone_number') ?: (get_field('location_phone') ?: '');

        // Populate Image
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(get_the_ID(), 'full');
        } elseif ($image_field = get_field('image')) {
            $schema['image'] = is_array($image_field) ? $image_field['url'] : $image_field;
        } else {
            $schema['image'] = get_template_directory_uri() . '/assets/images/default-location.jpg'; // Default image
        }

        // Opening Hours - Properly formatted for Schema.org
        if ($hours = get_field('opening_hours')) {
            // If the field already returns schema-compatible format (e.g., "Mo-Fr 09:00-17:00")
            $schema['openingHours'] = $hours;
        } elseif ($hours_array = get_field('hours_of_operation')) {
            // If using a more complex field structure, format it properly
            // This is a placeholder for custom formatting logic based on your field structure
            // Example: $schema['openingHours'] = format_hours_for_schema($hours_array);
        }

        // Opening Hours Specification (more detailed alternative to openingHours)
        $hours_spec = get_field('opening_hours_specification');
        if (!empty($hours_spec) && is_array($hours_spec)) {
            $schema['openingHoursSpecification'] = [];
            foreach ($hours_spec as $spec) {
                if (!empty($spec['day_of_week']) && !empty($spec['opens']) && !empty($spec['closes'])) {
                    $schema['openingHoursSpecification'][] = [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => $spec['day_of_week'], // Should be schema.org day format (e.g., "Monday")
                        'opens' => $spec['opens'],           // Should be 24-hour format (e.g., "09:00")
                        'closes' => $spec['closes']          // Should be 24-hour format (e.g., "17:00")
                    ];
                }
            }
        }

        // Available Services (MedicalProcedure, MedicalTest, or MedicalTherapy)
        $services = get_field('available_services');
        if (!empty($services) && is_array($services)) {
            $schema['availableService'] = [];
            foreach ($services as $service) {
                if (!empty($service['name'])) {
                    $service_schema = [
                        '@type' => 'MedicalProcedure', // Or MedicalTest or MedicalTherapy based on service type
                        'name' => $service['name']
                    ];
                    
                    // Add description if available
                    if (!empty($service['description'])) {
                        $service_schema['description'] = $service['description'];
                    }
                    
                    $schema['availableService'][] = $service_schema;
                }
            }
        }

        // Medical Specialty
        $specialties = get_field('medical_specialties');
        if (!empty($specialties) && is_array($specialties)) {
            $schema['medicalSpecialty'] = $specialties; // Array of specialties
        } elseif ($single_specialty = get_field('medical_specialty')) {
            $schema['medicalSpecialty'] = $single_specialty; // Single specialty
        }

        // Geo Coordinates
        $latitude = get_field('latitude');
        $longitude = get_field('longitude');
        if ($latitude && $longitude) {
            $schema['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $latitude,
                'longitude' => $longitude
            ];
        }
        
        // Map URL (if available)
        $map_url = get_field('map_url');
        if (!empty($map_url)) {
            $schema['hasMap'] = $map_url;
        }
        
        // Is accepting new patients (from MedicalOrganization)
        $accepting_patients = get_field('accepting_new_patients');
        if (isset($accepting_patients)) {
            $schema['isAcceptingNewPatients'] = (bool)$accepting_patients;
        }
        
        // Health Plan Network IDs (from MedicalOrganization)
        $network_ids = get_field('health_plan_network_ids');
        if (!empty($network_ids) && is_array($network_ids)) {
            $schema['healthPlanNetworkId'] = $network_ids;
        } elseif (!empty($network_ids) && is_string($network_ids)) {
            $schema['healthPlanNetworkId'] = $network_ids;
        }
        
        // Accessibility features
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

        // Parent Organization Reference
        $schema['parentOrganization'] = [
            '@type' => 'MedicalOrganization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => '123 Main Street', // Replace with actual address
                'addressLocality' => 'Miami', // Replace with actual city
                'addressRegion' => 'FL', // Replace with actual state
                'postalCode' => '33101', // Replace with actual zip code
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
        echo json_encode($schema_filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT);
        echo '</script>';
    }


    // --- Add Video Schema if video details exist ---
    // Try to get video details from various possible field names
    $video_details_field = null;
    $possible_video_fields = ['video_details', 'featured_video', 'video']; // Add other potential field names
    foreach ($possible_video_fields as $field_name) {
        $video_details_field = get_field($field_name);
        if (!empty($video_details_field)) {
            // Check if it's an array (like ACF group) or just a URL string
            if (is_array($video_details_field) && !empty($video_details_field['video_url'])) {
                 // Looks like an ACF group, use it
                 break;
            } elseif (is_string($video_details_field) && filter_var($video_details_field, FILTER_VALIDATE_URL)) {
                 // Looks like a direct URL, structure it like the array
                 $video_details_field = [
                    'video_url'         => $video_details_field,
                    'video_title'       => '', // Fallback title/desc needed
                    'video_description' => '',
                    'video_thumbnail'   => ''
                 ];
                 break;
            } else {
                 $video_details_field = null; // Reset if format isn't recognized
            }
        }
    }

    // Process video data if we found valid details
    if (!empty($video_details_field) && !empty($video_details_field['video_url'])) {
        $video_url = is_array($video_details_field['video_url']) ? $video_details_field['video_url']['url'] : $video_details_field['video_url'];
        $video_data = get_video_details($video_url); // Use the helper function

        if ($video_data) {
            $video_schema = [
                '@context'    => 'https://schema.org',
                '@type'       => 'VideoObject',
                'name'        => !empty($video_details_field['video_title']) ? $video_details_field['video_title'] : get_the_title(),
                'description' => !empty($video_details_field['video_description']) ? wp_strip_all_tags($video_details_field['video_description']) : wp_strip_all_tags(get_the_excerpt()),
                'uploadDate'  => get_the_date('c'), // ISO 8601 format
                'contentUrl'  => $video_data['url'], // Direct URL to the video file/page
                'embedUrl'    => $video_data['embed_url'], // URL for embedding
                'thumbnailUrl'=> '' // Initialize thumbnail
                // 'duration' => $video_data['duration'], // Add if get_video_details can fetch it
            ];

            // Add thumbnail
            if (!empty($video_details_field['video_thumbnail'])) {
                $thumb = $video_details_field['video_thumbnail'];
                $video_schema['thumbnailUrl'] = is_array($thumb) ? $thumb['url'] : $thumb;
            } elseif (has_post_thumbnail()) {
                $video_schema['thumbnailUrl'] = get_the_post_thumbnail_url(get_the_ID(), 'full');
            }

             // Output video schema only if essential fields are present
             if (!empty($video_schema['name']) && !empty($video_schema['contentUrl'])) {
                 echo '<script type="application/ld+json" class="mia-video-schema">';
                 $video_schema_filtered = array_filter($video_schema, function($value) { return $value !== ''; });
                 echo json_encode($video_schema_filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT);
                 echo '</script>';
             }
        }
    }


    // --- Add FAQ Schema if FAQs exist (using ACF repeater/group) ---
    $faq_section = get_field('faq_section'); // Assuming 'faq_section' is the group/flexible content field name
    if (!empty($faq_section) && !empty($faq_section['faqs']) && is_array($faq_section['faqs'])) { // Check if 'faqs' repeater exists and is not empty
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
                        'text'  => wp_kses_post($faq_item['answer']) // Allow basic HTML in answer text
                        // Consider using wp_strip_all_tags() if only plain text is desired/allowed by Google
                    ]
                ];
            }
        }

        // Output FAQ schema only if valid questions/answers were added
        if (!empty($faq_schema['mainEntity'])) {
            echo '<script type="application/ld+json" class="mia-faq-schema">';
            echo json_encode($faq_schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT);
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
            'streetAddress' => '123 Main Street', // Replace with actual address
            'addressLocality' => 'Miami', // Replace with actual city
            'addressRegion' => 'FL', // Replace with actual state
            'postalCode' => '33101', // Replace with actual zip code
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
    
    // Add social media profiles if available
    $social_profiles = get_field('social_profiles', 'option');
    if (!empty($social_profiles) && is_array($social_profiles)) {
        $org_schema['sameAs'] = [];
        foreach ($social_profiles as $profile) {
            if (!empty($profile['url'])) {
                $org_schema['sameAs'][] = $profile['url'];
            }
        }
    }
    
    // Add opening hours if available
    $hours = get_field('company_hours', 'option');
    if (!empty($hours)) {
        $org_schema['openingHours'] = $hours;
    }
    
    // Add locations if available (branch locations)
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
                
                // Add location address if available
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
                
                // Add location phone if available
                if (!empty($location['phone'])) {
                    $location_schema['telephone'] = $location['phone'];
                }
                
                // Add location URL if available
                if (!empty($location['url'])) {
                    $location_schema['url'] = $location['url'];
                }
                
                $org_schema['location'][] = $location_schema;
            }
        }
    }
    
    // Add aggregate rating if available
    $rating = get_field('company_rating', 'option');
    $rating_count = get_field('company_rating_count', 'option');
    if (!empty($rating) && !empty($rating_count)) {
        $org_schema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => $rating,
            'ratingCount' => $rating_count
        ];
    }
    
    // Filter out empty values before encoding
    $org_schema_filtered = array_filter($org_schema, function($value) {
        return $value !== '' && $value !== null && (!is_array($value) || !empty(array_filter($value)));
    });
    
    echo '<script type="application/ld+json" class="yoast-schema-graph-address">';
    echo json_encode($org_schema_filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
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
            // Section Title (Optional)
            $section_title = !empty($faq_section['title']) ? $faq_section['title'] : __('Frequently Asked Questions', 'mia-aesthetics'); // Default title with translation
        ?>
            <h2 id="faq-heading-<?php echo get_the_ID(); ?>" class="mb-4"><?php echo esc_html($section_title); ?></h2>

            <?php // Section Description (Optional)
            if (!empty($faq_section['description'])): ?>
                <div class="faq-description mb-4">
                    <?php echo wp_kses_post($faq_section['description']); // Allows safe HTML ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!empty($faqs)): ?>
        <div class="accordion" id="<?php echo esc_attr($accordion_id); ?>">
            <?php foreach ($faqs as $index => $faq):
                // Ensure both question and answer exist for each item
                if (empty($faq['question']) || empty($faq['answer'])) continue;

                $item_id = 'faq-' . get_the_ID() . '-' . $index;
                $heading_id = 'heading-' . $item_id;
                $collapse_id = 'collapse-' . $item_id;
                $is_first = ($index === 0); // Check if it's the first item
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
                            <?php echo esc_html($faq['question']); ?>
                        </button>
                    </h3>
                    <div
                        id="<?php echo esc_attr($collapse_id); ?>"
                        class="accordion-collapse collapse <?php echo $is_first ? 'show' : ''; ?>"
                        aria-labelledby="<?php echo esc_attr($heading_id); ?>"
                    >
                        <div class="accordion-body">
                            <?php echo wp_kses_post($faq['answer']); // Use wp_kses_post to allow safe HTML formatting ?>
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
// Usage: In your template file (e.g., single-surgeon.php, page.php), you would call:
// echo display_page_faqs();


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
 * Fallback CSS loader for specific templates that might have issues with the main loader
 * This is a safety net to ensure critical CSS files are always loaded
 */
function mia_fallback_css_loader() {
    $theme_uri = get_template_directory_uri();
    $theme_path = get_template_directory();
    $css_path = $theme_path . '/assets/css';
    $css_uri = $theme_uri . '/assets/css';
    
    // Array of critical CSS files to ensure are loaded for specific templates
    $critical_css = array();
    
    // Add surgeon archive CSS if on surgeon archive page
    if (is_post_type_archive('surgeon')) {
        $critical_css['mia-surgeon-archive-fallback'] = '/_surgeon-archive.css';
    }
    
    // Add location archive CSS if on location archive page
    if (is_post_type_archive('location')) {
        $critical_css['mia-location-archive-fallback'] = '/_location-archive.css';
    }
    
    // Add other critical CSS files here as needed
    
    // Load all critical CSS files
    foreach ($critical_css as $handle => $file) {
        if (file_exists($css_path . $file) && !wp_style_is('mia-' . str_replace('-fallback', '', $handle), 'enqueued')) {
            wp_enqueue_style(
                $handle,
                $css_uri . $file,
                array('mia-base'),
                filemtime($css_path . $file)
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'mia_fallback_css_loader', 999); // Run after the main enqueue function

/**
 * Ensure the correct body class is added for archive pages
 * This is a failsafe to make sure the post-type-archive classes are added
 */
function mia_ensure_archive_body_class($classes) {
    if (is_post_type_archive('surgeon')) {
        // Make sure the post-type-archive-surgeon class is added
        if (!in_array('post-type-archive-surgeon', $classes)) {
            $classes[] = 'post-type-archive-surgeon';
        }
    }
    
    if (is_post_type_archive('location')) {
        // Make sure the post-type-archive-location class is added
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
    // Close comments on the front-end
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);
    
    // Hide existing comments
    add_filter('comments_array', '__return_empty_array', 10, 2);
    
    // Remove comments page in menu
    add_action('admin_menu', function() {
        remove_menu_page('edit-comments.php');
    });
    
    // Remove comments from admin bar
    add_action('wp_before_admin_bar_render', function() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    });
    
    // Disable support for comments and trackbacks in post types
    add_action('admin_init', function() {
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    });
    
    // Redirect any user trying to access comments page
    add_action('admin_init', function() {
        global $pagenow;
        
        if ($pagenow === 'edit-comments.php') {
            wp_redirect(admin_url());
            exit;
        }
    });
    
    // Remove comments metabox from dashboard
    add_action('admin_init', function() {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    });
    
    // Disable comments REST API endpoint
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

?>
