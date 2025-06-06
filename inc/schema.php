<?php
/**
 * Schema and Structured Data for Mia Aesthetics Theme
 * 
 * Generates schema.org structured data for better SEO and rich snippets.
 * Works alongside Yoast SEO to provide comprehensive schema markup.
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main schema output function
 */
function mia_output_schema() {
    // Only add our custom schema if Yoast is active
    // This ensures we complement Yoast, not compete with it
    if (!defined('WPSEO_VERSION')) {
        return; // Exit if Yoast is not active
    }
    
    // Get schema based on current context
    $schema = mia_get_context_schema();
    
    if (!empty($schema)) {
        mia_render_schema($schema, 'mia-context-schema');
    }
    
    // Add video schema if applicable
    mia_output_video_schema();
    
    // Add FAQ schema if applicable
    mia_output_faq_schema();
    
    // Add organization schema on homepage only if Yoast isn't handling it
    if (is_front_page() && !mia_yoast_handles_organization_schema()) {
        mia_output_organization_schema();
    }
}
add_action('wp_head', 'mia_output_schema', 20);

/**
 * Check if Yoast is handling organization schema
 */
function mia_yoast_handles_organization_schema() {
    // Check if Yoast is outputting organization schema
    if (class_exists('WPSEO_Schema_Context') && class_exists('WPSEO_Schema_Organization')) {
        return true;
    }
    
    // For older versions of Yoast
    if (function_exists('wpseo_json_ld_output') && get_option('wpseo_titles')) {
        $options = get_option('wpseo_titles');
        if (!empty($options['company_or_person']) && $options['company_or_person'] === 'company') {
            return true;
        }
    }
    
    return false;
}

/**
 * Get schema based on current context
 */
function mia_get_context_schema() {
    $post_type = get_post_type();
    
    switch ($post_type) {
        case 'procedure':
            return mia_get_procedure_schema();
            
        case 'surgeon':
            return mia_get_surgeon_schema();
            
        case 'location':
            return mia_get_location_schema();
            
        case 'condition':
            return mia_get_condition_schema();
            
        case 'special':
            return mia_get_special_schema();
            
        case 'non-surgical':
            return mia_get_non_surgical_schema();
            
        default:
            return null;
    }
}

/**
 * Get procedure schema
 */
function mia_get_procedure_schema() {
    if (!is_singular('procedure')) {
        return null;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'MedicalProcedure',
        'name' => get_the_title(),
        'description' => wp_strip_all_tags(get_the_excerpt()),
        'url' => get_permalink()
    ];
    
    // Add procedure type if available
    if ($procedure_type = get_field('procedure_type')) {
        $schema['procedureType'] = $procedure_type;
    }
    
    // Add body location if available
    if ($body_location = get_field('body_location')) {
        $schema['bodyLocation'] = $body_location;
    }
    
    // Add typical duration
    if ($duration = get_field('procedure_duration')) {
        $schema['typicalDuration'] = $duration;
    }
    
    // Add recovery time
    if ($recovery = get_field('recovery_time')) {
        $schema['followup'] = $recovery;
    }
    
    return $schema;
}

/**
 * Get surgeon schema
 */
function mia_get_surgeon_schema() {
    if (!is_singular('surgeon')) {
        return null;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Physician',
        'name' => get_the_title(),
        'description' => wp_strip_all_tags(get_the_excerpt()),
        'url' => get_permalink()
    ];
    
    // Add image
    if (has_post_thumbnail()) {
        $schema['image'] = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    
    // Add contact information
    if ($phone = get_field('phone_number')) {
        $schema['telephone'] = $phone;
    }
    
    if ($email = get_field('email')) {
        $schema['email'] = $email;
    }
    
    // Add address
    $address = mia_get_address_schema();
    if (!empty($address)) {
        $schema['address'] = $address;
    }
    
    // Add medical specialty
    if ($specialty = get_field('medical_specialty')) {
        $schema['medicalSpecialty'] = $specialty;
    }
    
    // Add credentials
    if ($credentials = get_field('credentials')) {
        $schema['hasCredential'] = $credentials;
    }
    
    // Add affiliated location
    if ($location_id = get_field('surgeon_location')) {
        $schema['workLocation'] = mia_get_location_reference($location_id);
    }
    
    // Add organization membership
    $schema['memberOf'] = mia_get_organization_reference();
    
    return $schema;
}

/**
 * Get location schema
 */
function mia_get_location_schema() {
    if (!is_singular('location')) {
        return null;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => ['MedicalClinic', 'MedicalBusiness', 'LocalBusiness'],
        'name' => get_the_title(),
        'description' => wp_strip_all_tags(get_the_excerpt()),
        'url' => get_permalink()
    ];
    
    // Add image
    if (has_post_thumbnail()) {
        $schema['image'] = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    
    // Add contact information
    $phone = get_field('phone_number') ?: get_field('location_phone');
    if ($phone) {
        $schema['telephone'] = $phone;
    }
    
    // Add address
    $address = mia_get_address_schema();
    if (!empty($address)) {
        $schema['address'] = $address;
    }
    
    // Add business details
    $schema['priceRange'] = get_field('price_range') ?: '$$$';
    $schema['currenciesAccepted'] = 'USD';
    $schema['paymentAccepted'] = get_field('payment_methods') ?: 'Cash, Credit Card, Financing Available';
    
    // Add opening hours
    if ($hours = mia_get_opening_hours_schema()) {
        $schema['openingHoursSpecification'] = $hours;
    }
    
    // Add services
    if ($services = mia_get_services_schema()) {
        $schema['availableService'] = $services;
    }
    
    // Add geo coordinates
    if ($geo = mia_get_geo_schema()) {
        $schema['geo'] = $geo;
    }
    
    // Add accessibility
    if ($accessibility = get_field('accessibility_features')) {
        $schema['amenityFeature'] = mia_format_amenity_features($accessibility);
    }
    
    // Add parent organization
    $schema['parentOrganization'] = mia_get_organization_reference();
    
    return $schema;
}

/**
 * Get condition schema
 */
function mia_get_condition_schema() {
    if (!is_singular('condition')) {
        return null;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'MedicalCondition',
        'name' => get_the_title(),
        'description' => wp_strip_all_tags(get_the_excerpt()),
        'url' => get_permalink()
    ];
    
    // Add associated anatomy
    if ($anatomy = get_field('associated_anatomy')) {
        $schema['associatedAnatomy'] = [
            '@type' => 'AnatomicalStructure',
            'name' => $anatomy
        ];
    }
    
    // Add possible treatments
    if ($treatments = get_field('possible_treatments')) {
        $schema['possibleTreatment'] = mia_format_treatments($treatments);
    }
    
    // Add symptoms
    if ($symptoms = get_field('symptoms')) {
        $schema['signOrSymptom'] = mia_format_symptoms($symptoms);
    }
    
    return $schema;
}

/**
 * Get special/offer schema
 */
function mia_get_special_schema() {
    if (!is_singular('special')) {
        return null;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Offer',
        'name' => get_the_title(),
        'description' => wp_strip_all_tags(get_the_excerpt()),
        'url' => get_permalink()
    ];
    
    // Add price information
    if ($price = get_field('special_price')) {
        $schema['price'] = $price;
        $schema['priceCurrency'] = 'USD';
    }
    
    // Add validity dates
    if ($start_date = get_field('special_start_date')) {
        $schema['validFrom'] = $start_date;
    }
    
    if ($end_date = get_field('special_end_date')) {
        $schema['validThrough'] = $end_date;
    }
    
    // Add availability
    $schema['availability'] = 'https://schema.org/InStock';
    
    // Add eligible regions
    if ($locations = get_field('eligible_locations')) {
        $schema['eligibleRegion'] = mia_format_eligible_regions($locations);
    }
    
    // Add terms
    if ($terms = get_field('terms_conditions')) {
        $schema['termsOfService'] = $terms;
    }
    
    return $schema;
}

/**
 * Get non-surgical schema
 */
function mia_get_non_surgical_schema() {
    if (!is_singular('non-surgical')) {
        return null;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'MedicalProcedure',
        'name' => get_the_title(),
        'description' => wp_strip_all_tags(get_the_excerpt()),
        'url' => get_permalink()
    ];
    
    // Add procedure type
    $schema['procedureType'] = 'Non-Surgical';
    
    // Add body location if available
    if ($body_location = get_field('body_location')) {
        $schema['bodyLocation'] = $body_location;
    }
    
    // Add typical duration
    if ($duration = get_field('procedure_duration')) {
        $schema['typicalDuration'] = $duration;
    }
    
    // Add recovery time
    if ($recovery = get_field('recovery_time')) {
        $schema['followup'] = $recovery;
    }
    
    // Add treatment benefits if available
    if ($benefits = get_field('treatment_benefits')) {
        $schema['expectedBenefit'] = $benefits;
    }
    
    // Add contraindications if available
    if ($contraindications = get_field('contraindications')) {
        $schema['contraindication'] = $contraindications;
    }
    
    return $schema;
}

/**
 * Output video schema
 */
function mia_output_video_schema() {
    $video_data = mia_get_video_field();
    
    if (empty($video_data) || empty($video_data['url'])) {
        return;
    }
    
    $video_details = get_video_details($video_data['url']);
    
    if (!$video_details) {
        return;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'VideoObject',
        'name' => $video_data['title'] ?: get_the_title() . ' Video',
        'description' => $video_data['description'] ?: wp_strip_all_tags(get_the_excerpt()),
        'contentUrl' => $video_details['url'],
        'embedUrl' => $video_details['embed_url'],
        'uploadDate' => get_the_date('c')
    ];
    
    // Add thumbnail
    if (!empty($video_data['thumbnail'])) {
        $schema['thumbnailUrl'] = is_array($video_data['thumbnail']) 
            ? $video_data['thumbnail']['url'] 
            : $video_data['thumbnail'];
    }
    
    // Add duration if available
    if (!empty($video_details['duration'])) {
        $schema['duration'] = $video_details['duration'];
    }
    
    mia_render_schema($schema, 'mia-video-schema');
}

/**
 * Output FAQ schema
 */
function mia_output_faq_schema() {
    $faq_section = get_field('faq_section');
    
    if (empty($faq_section) || empty($faq_section['faqs'])) {
        return;
    }
    
    $faq_items = [];
    
    foreach ($faq_section['faqs'] as $faq) {
        if (!empty($faq['question']) && !empty($faq['answer'])) {
            $faq_items[] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => wp_strip_all_tags($faq['answer'])
                ]
            ];
        }
    }
    
    if (!empty($faq_items)) {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faq_items
        ];
        
        mia_render_schema($schema, 'mia-faq-schema');
    }
}

/**
 * Output organization schema
 */
function mia_output_organization_schema() {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'MedicalOrganization',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => mia_get_logo_url()
        ]
    ];
    
    // Add description
    $schema['description'] = get_bloginfo('description');
    
    // Add contact information from options
    if ($phone = get_field('company_phone', 'option')) {
        $schema['telephone'] = $phone;
    }
    
    if ($email = get_field('company_email', 'option')) {
        $schema['email'] = $email;
    }
    
    // Add main address from options
    $address = mia_get_company_address_schema();
    if (!empty($address)) {
        $schema['address'] = $address;
    }
    
    // Add social profiles
    if ($social = mia_get_social_profiles_schema()) {
        $schema['sameAs'] = $social;
    }
    
    // Add medical specialties
    if ($specialties = get_field('company_specialties', 'option')) {
        $schema['medicalSpecialty'] = $specialties;
    }
    
    // Add aggregate rating if available
    if ($rating = mia_get_aggregate_rating_schema()) {
        $schema['aggregateRating'] = $rating;
    }
    
    mia_render_schema($schema, 'mia-organization-schema');
}

/**
 * Helper Functions
 */

/**
 * Get address schema from ACF fields
 */
function mia_get_address_schema($post_id = null) {
    $address = [
        '@type' => 'PostalAddress',
        'addressCountry' => 'US'
    ];
    
    // Try multiple field names for compatibility
    $street = get_field('street_address', $post_id) ?: get_field('address', $post_id);
    if ($street) {
        $address['streetAddress'] = $street;
    }
    
    if ($city = get_field('city', $post_id)) {
        $address['addressLocality'] = $city;
    }
    
    if ($state = get_field('state', $post_id)) {
        $address['addressRegion'] = mia_get_state_abbr($state);
    }
    
    if ($zip = get_field('zip_code', $post_id)) {
        $address['postalCode'] = $zip;
    }
    
    // Only return if we have at least one address component
    if (count($address) > 2) {
        return $address;
    }
    
    return null;
}

/**
 * Get company address schema from options
 */
function mia_get_company_address_schema() {
    $address = [
        '@type' => 'PostalAddress',
        'addressCountry' => 'US'
    ];
    
    if ($street = get_field('company_street_address', 'option')) {
        $address['streetAddress'] = $street;
    }
    
    if ($city = get_field('company_city', 'option')) {
        $address['addressLocality'] = $city;
    }
    
    if ($state = get_field('company_state', 'option')) {
        $address['addressRegion'] = mia_get_state_abbr($state);
    }
    
    if ($zip = get_field('company_zip', 'option')) {
        $address['postalCode'] = $zip;
    }
    
    // Only return if we have address components
    if (count($address) > 2) {
        return $address;
    }
    
    return null;
}

/**
 * Get organization reference
 */
function mia_get_organization_reference() {
    return [
        '@type' => 'MedicalOrganization',
        'name' => get_bloginfo('name'),
        'url' => home_url()
    ];
}

/**
 * Get location reference
 */
function mia_get_location_reference($location_id) {
    if (!$location_id) {
        return null;
    }
    
    return [
        '@type' => ['MedicalClinic', 'MedicalBusiness'],
        'name' => get_the_title($location_id),
        'url' => get_permalink($location_id)
    ];
}

/**
 * Get opening hours schema
 */
function mia_get_opening_hours_schema($post_id = null) {
    $hours_spec = get_field('opening_hours_specification', $post_id);
    
    if (empty($hours_spec) || !is_array($hours_spec)) {
        return null;
    }
    
    $hours = [];
    
    foreach ($hours_spec as $spec) {
        if (!empty($spec['day_of_week']) && !empty($spec['opens']) && !empty($spec['closes'])) {
            $hours[] = [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => $spec['day_of_week'],
                'opens' => $spec['opens'],
                'closes' => $spec['closes']
            ];
        }
    }
    
    return !empty($hours) ? $hours : null;
}

/**
 * Get services schema
 */
function mia_get_services_schema($post_id = null) {
    $services = get_field('available_services', $post_id);
    
    if (empty($services) || !is_array($services)) {
        return null;
    }
    
    $service_schema = [];
    
    foreach ($services as $service) {
        if (!empty($service['name'])) {
            $item = [
                '@type' => 'MedicalProcedure',
                'name' => $service['name']
            ];
            
            if (!empty($service['description'])) {
                $item['description'] = $service['description'];
            }
            
            $service_schema[] = $item;
        }
    }
    
    return !empty($service_schema) ? $service_schema : null;
}

/**
 * Get geo coordinates schema
 */
function mia_get_geo_schema($post_id = null) {
    $latitude = get_field('latitude', $post_id);
    $longitude = get_field('longitude', $post_id);
    
    if ($latitude && $longitude) {
        return [
            '@type' => 'GeoCoordinates',
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
    }
    
    return null;
}

/**
 * Get social profiles schema
 */
function mia_get_social_profiles_schema() {
    $profiles = [];
    
    $social_fields = [
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'linkedin_url',
        'tiktok_url'
    ];
    
    foreach ($social_fields as $field) {
        $url = get_field($field, 'option');
        if (!empty($url)) {
            $profiles[] = $url;
        }
    }
    
    return !empty($profiles) ? $profiles : null;
}

/**
 * Get aggregate rating schema
 */
function mia_get_aggregate_rating_schema() {
    $rating = get_field('company_rating', 'option');
    $count = get_field('company_rating_count', 'option');
    
    if (!empty($rating) && !empty($count)) {
        return [
            '@type' => 'AggregateRating',
            'ratingValue' => $rating,
            'ratingCount' => $count,
            'bestRating' => '5',
            'worstRating' => '1'
        ];
    }
    
    return null;
}

/**
 * Format amenity features
 */
function mia_format_amenity_features($features) {
    if (!is_array($features)) {
        return null;
    }
    
    $formatted = [];
    
    foreach ($features as $feature) {
        if (!empty($feature['name'])) {
            $formatted[] = [
                '@type' => 'LocationFeatureSpecification',
                'name' => $feature['name'],
                'value' => true
            ];
        }
    }
    
    return !empty($formatted) ? $formatted : null;
}

/**
 * Format treatments
 */
function mia_format_treatments($treatments) {
    if (!is_array($treatments)) {
        return null;
    }
    
    $formatted = [];
    
    foreach ($treatments as $treatment) {
        if (!empty($treatment)) {
            $formatted[] = [
                '@type' => 'MedicalProcedure',
                'name' => $treatment
            ];
        }
    }
    
    return !empty($formatted) ? $formatted : null;
}

/**
 * Format symptoms
 */
function mia_format_symptoms($symptoms) {
    if (!is_array($symptoms)) {
        return null;
    }
    
    $formatted = [];
    
    foreach ($symptoms as $symptom) {
        if (!empty($symptom)) {
            $formatted[] = [
                '@type' => 'MedicalSymptom',
                'name' => $symptom
            ];
        }
    }
    
    return !empty($formatted) ? $formatted : null;
}

/**
 * Format eligible regions
 */
function mia_format_eligible_regions($locations) {
    if (!is_array($locations)) {
        return null;
    }
    
    $formatted = [];
    
    foreach ($locations as $location_id) {
        $location = get_post($location_id);
        if ($location) {
            $formatted[] = [
                '@type' => 'Place',
                'name' => $location->post_title
            ];
        }
    }
    
    return !empty($formatted) ? $formatted : null;
}

/**
 * Render schema JSON-LD
 */
function mia_render_schema($schema, $class = 'mia-schema') {
    if (empty($schema)) {
        return;
    }
    
    // Filter out empty values
    $schema = mia_filter_schema($schema);
    
    if (empty($schema)) {
        return;
    }
    
    echo '<script type="application/ld+json" class="' . esc_attr($class) . '">';
    echo wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT);
    echo '</script>' . "\n";
}

/**
 * Recursively filter empty values from schema
 */
function mia_filter_schema($data) {
    if (!is_array($data)) {
        return $data;
    }
    
    $filtered = [];
    
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $value = mia_filter_schema($value);
        }
        
        if ($value !== '' && $value !== null && (!is_array($value) || !empty($value))) {
            $filtered[$key] = $value;
        }
    }
    
    return $filtered;
}
