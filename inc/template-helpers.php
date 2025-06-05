<?php
/**
 * Template Helper Functions
 * 
 * Handles template utilities, formatting helpers, and UI components
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get the site logo URL properly
 * 
 * @param bool $fallback Whether to use fallback if custom logo not set
 * @return string|false Logo URL or false if not found
 */
function mia_get_logo_url($fallback = true) {
    // Try to get custom logo first
    $custom_logo_id = get_theme_mod('custom_logo');
    
    if ($custom_logo_id) {
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
        if ($logo_url) {
            return $logo_url;
        }
    }
    
    // Fallback to known logo if enabled
    if ($fallback) {
        $upload_dir = wp_upload_dir();
        $logo_path = $upload_dir['basedir'] . '/2024/11/miaaesthetics-logo.svg';
        
        if (file_exists($logo_path)) {
            return $upload_dir['baseurl'] . '/2024/11/miaaesthetics-logo.svg';
        }
    }
    
    return false;
}

/**
 * Output the site logo with proper attributes
 * 
 * @param array $args Logo arguments (height, width, class, etc.)
 */
function mia_the_logo($args = []) {
    $defaults = [
        'height' => '50',
        'width' => '200',
        'class' => 'd-inline-block',
        'alt' => 'Mia Aesthetics Logo',
        'fetchpriority' => false,
        'loading' => false
    ];
    
    $args = wp_parse_args($args, $defaults);
    $logo_url = mia_get_logo_url();
    
    if (!$logo_url) {
        // Fallback to site name if no logo found
        echo '<span class="navbar-brand-text">' . esc_html(get_bloginfo('name')) . '</span>';
        return;
    }
    
    $attributes = [
        'src="' . esc_url($logo_url) . '"',
        'alt="' . esc_attr($args['alt']) . '"',
        'height="' . esc_attr($args['height']) . '"',
        'width="' . esc_attr($args['width']) . '"',
        'class="' . esc_attr($args['class']) . '"'
    ];
    
    if ($args['fetchpriority']) {
        $attributes[] = 'fetchpriority="high"';
    }
    
    if ($args['loading']) {
        $attributes[] = 'loading="' . esc_attr($args['loading']) . '"';
    }
    
    echo '<img ' . implode(' ', $attributes) . ' />';
}

/**
 * Get image URL by filename from uploads directory
 * 
 * @param string $filename The filename to search for
 * @param string $subdir Optional subdirectory (e.g., '2025/04')
 * @return string|false Image URL or false if not found
 */
function mia_get_image_url_by_filename($filename, $subdir = '') {
    $upload_dir = wp_upload_dir();
    
    if ($subdir) {
        $file_path = $upload_dir['basedir'] . '/' . trim($subdir, '/') . '/' . $filename;
        
        if (file_exists($file_path)) {
            return $upload_dir['baseurl'] . '/' . trim($subdir, '/') . '/' . $filename;
        }
    }
    
    // Try to find the image by attachment
    $args = [
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => 1,
        'meta_query' => [
            [
                'key' => '_wp_attached_file',
                'value' => $filename,
                'compare' => 'LIKE'
            ]
        ]
    ];
    
    $attachments = get_posts($args);
    
    if (!empty($attachments)) {
        return wp_get_attachment_url($attachments[0]->ID);
    }
    
    return false;
}

/**
 * Get responsive image with srcset for a filename
 * 
 * @param string $filename Base filename
 * @param string $subdir Subdirectory in uploads
 * @param array $sizes Array of sizes [width => height]
 * @return array|false Array with src, srcset or false if not found
 */
function mia_get_responsive_image_data($filename, $subdir = '', $sizes = []) {
    // Try to find by attachment first
    $args = [
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => 1,
        'meta_query' => [
            [
                'key' => '_wp_attached_file',
                'value' => $filename,
                'compare' => 'LIKE'
            ]
        ]
    ];
    
    $attachments = get_posts($args);
    
    if (!empty($attachments)) {
        $attachment_id = $attachments[0]->ID;
        return [
            'src' => wp_get_attachment_url($attachment_id),
            'srcset' => wp_get_attachment_image_srcset($attachment_id, 'full'),
            'sizes' => wp_get_attachment_image_sizes($attachment_id, 'full')
        ];
    }
    
    // Fallback to manual construction if attachment not found
    $upload_dir = wp_upload_dir();
    $base_url = $upload_dir['baseurl'];
    
    if ($subdir) {
        $base_url .= '/' . trim($subdir, '/');
    }
    
    $base_filename = pathinfo($filename, PATHINFO_FILENAME);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    
    $srcset_parts = [];
    
    // Add the original image
    $original_url = $base_url . '/' . $filename;
    $srcset_parts[] = $original_url . ' 1920w'; // Assume original is 1920w
    
    // Add responsive sizes if they exist
    $common_sizes = [
        '300x169' => '300w',
        '768x432' => '768w', 
        '1024x576' => '1024w',
        '1536x864' => '1536w'
    ];
    
    foreach ($common_sizes as $size => $width) {
        $sized_filename = $base_filename . '-' . $size . '.' . $extension;
        $sized_path = $upload_dir['basedir'] . ($subdir ? '/' . trim($subdir, '/') : '') . '/' . $sized_filename;
        
        if (file_exists($sized_path)) {
            $srcset_parts[] = $base_url . '/' . $sized_filename . ' ' . $width;
        }
    }
    
    if (!empty($srcset_parts)) {
        return [
            'src' => $original_url,
            'srcset' => implode(', ', $srcset_parts),
            'sizes' => '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw'
        ];
    }
    
    return false;
}

/**
 * Format "City, ST ZIP" using state-abbreviation helper.
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
