<?php
/**
 * @package Mia_Aesthetics
 */

add_theme_support( 'post-thumbnails' );

require_once get_template_directory() . '/inc/state-abbreviations.php';
require_once get_template_directory() . '/inc/disable-comments.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/schema.php';
require_once get_template_directory() . '/inc/menus.php';
require_once get_template_directory() . '/inc/queries.php';

// Add custom image sizes for responsive hero images
add_image_size( 'hero-mobile', 640, 400, true );    // Mobile hero images
add_image_size( 'hero-tablet', 1024, 600, true );   // Tablet hero images  
add_image_size( 'hero-desktop', 1920, 800, true );  // Desktop hero images

// Add custom image sizes for before/after gallery images
add_image_size( 'gallery-small', 300, 225, true );   // Small gallery images (mobile)
add_image_size( 'gallery-medium', 450, 338, true );  // Medium gallery images (tablet)
add_image_size( 'gallery-large', 600, 450, true );   // Large gallery images (desktop)

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

/**
 * Helper function for before/after gallery images
 * Handles both image IDs and URLs, with fallback to placeholder
 */
function mia_before_after_img( $img, string $label ) : string {
    if ( ! $img ) {
        $src = 'https://placehold.co/600x450';
        return "<img src='{$src}' class='img-fluid w-100 object-fit-cover' alt='{$label} placeholder' loading='lazy'>";
    }

    $id  = is_numeric( $img ) ? $img : attachment_url_to_postid( $img );
    
    if ( $id ) {
        return wp_get_attachment_image(
            $id,
            'gallery-small',
            false,
            [
                'class'   => 'img-fluid w-100 object-fit-cover',
                'alt'     => "{$label} surgery image",
                'loading' => 'lazy'
            ]
        );
    }
    
    // Fallback for direct URLs that couldn't be converted to attachment ID
    $src = is_array( $img ) ? $img['url'] : $img;
    return "<img src='" . esc_url( $src ) . "' class='img-fluid w-100 object-fit-cover' alt='{$label} surgery image' loading='lazy'>";
}

?>
