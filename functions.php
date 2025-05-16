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

    $base_file = '/_base.css';
    if (file_exists($css_path . $base_file)) {
        wp_enqueue_style(
            'mia-base',
            $css_uri . $base_file,
            array('bootstrap-css', 'normalize'),
            filemtime($css_path . $base_file)
        );
    }

    $component_styles = array(
        'header' => '/_header.css',
        'footer' => '/_footer.css'
    );

    foreach ($component_styles as $name => $file) {
        if (file_exists($css_path . $file)) {
            wp_enqueue_style(
                'mia-' . $name,
                $css_uri . $file,
                array('mia-base'),
                filemtime($css_path . $file)
            );
        }
    }

    $css_file = null;
    $handle = null;
    
    if (is_front_page()) {
        $css_file = '/_home.css';
        $handle = 'mia-home';
    } elseif (is_404()) {
        $css_file = '/_404.css';
        $handle = 'mia-404';
    } elseif (is_search()) {
        $css_file = '/_search.css';
        $handle = 'mia-search';
    } elseif (is_tax()) {
        $css_file = '/_taxonomies.css';
        $handle = 'mia-taxonomies';
    } elseif (is_home() || (is_archive() && get_post_type() == 'post')) {
        $css_file = '/_archive.css';
        $handle = 'mia-post-archive';
    } elseif (is_singular('post')) {
        $css_file = '/_single.css';
        $handle = 'mia-post-single';
    } elseif (is_page()) {
        $css_file = '/_page.css';
        $handle = 'mia-page';
    } elseif (is_post_type_archive()) {
        $post_type = get_post_type();
        if (!$post_type) {
            $post_type = get_query_var('post_type');
        }
        
        if ($post_type) {
            $css_file = '/_' . $post_type . '-archive.css';
            $handle = 'mia-' . $post_type . '-archive';
        }
    } elseif (is_singular()) {
        $post_type = get_post_type();
        
        if ($post_type && $post_type !== 'post' && $post_type !== 'page') {
            $css_file = '/_' . $post_type . '.css';
            $handle = 'mia-' . $post_type . '-single';
        }
    }
    
    // Load the determined CSS file
    if ($css_file && $handle) {
        if (file_exists($css_path . $css_file)) {
            wp_enqueue_style(
                $handle,
                $css_uri . $css_file,
                array('mia-base'),
                filemtime($css_path . $css_file)
            );
        } else {
            error_log('Mia Aesthetics Theme: CSS file not found: ' . $css_file);
        }
    }
    
    if (is_page()) {
        $page_slug = get_post_field('post_name', get_post());
        $page_specific_path = '/_' . $page_slug . '.css';
        
        if (file_exists($css_path . $page_specific_path)) {
            wp_enqueue_style(
                'mia-page-' . $page_slug,
                $css_uri . $page_specific_path,
                array('mia-page'),
                filemtime($css_path . $page_specific_path)
            );
        }
    }

    if (is_singular('condition')) {
        $js_file = $theme_path . '/assets/js/condition.js';
        $handle = 'mia-condition-script';
    } 
    elseif (is_singular('surgeon')) {
        $js_file = $theme_path . '/assets/js/surgeon.js';
        $handle = 'mia-surgeon-script';
    } 
    else {
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
            true
        );
    }
    
    if (is_singular('surgeon') || is_singular('location')) {
        $video_js_file = $theme_path . '/assets/js/video.js';
        if (file_exists($video_js_file)) {
            wp_enqueue_script(
                'mia-video-script',
                $theme_uri . '/assets/js/video.js',
                array('bootstrap-js'),
                filemtime($video_js_file),
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
        echo json_encode($schema_filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT);
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
                 echo json_encode($video_schema_filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT);
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
                            <?php echo esc_html($faq['question']); ?>
                        </button>
                    </h3>
                    <div
                        id="<?php echo esc_attr($collapse_id); ?>"
                        class="accordion-collapse collapse <?php echo $is_first ? 'show' : ''; ?>"
                        aria-labelledby="<?php echo esc_attr($heading_id); ?>"
                    >
                        <div class="accordion-body">
                            <?php echo wp_kses_post($faq['answer']); ?>
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
 * Fallback CSS loader to ensure critical CSS files are always loaded
 */
function mia_fallback_css_loader() {
    $theme_uri = get_template_directory_uri();
    $theme_path = get_template_directory();
    $css_path = $theme_path . '/assets/css';
    $css_uri = $theme_uri . '/assets/css';
    
    $critical_css = array();
    
    if (is_post_type_archive('surgeon')) {
        $critical_css['mia-surgeon-archive-fallback'] = '/_surgeon-archive.css';
    }
    
    if (is_post_type_archive('location')) {
        $critical_css['mia-location-archive-fallback'] = '/_location-archive.css';
    }
    
    if (is_singular('surgeon')) {
        $critical_css['mia-surgeon-single-fallback'] = '/_surgeon.css';
    }
    
    if (is_singular('location')) {
        $critical_css['mia-location-single-fallback'] = '/_location.css';
    }
    
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
add_action('wp_enqueue_scripts', 'mia_fallback_css_loader', 999);

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

?>
