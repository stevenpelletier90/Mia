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
