<?php
/**
 * Menu Helper Functions for Mia Aesthetics theme.
 *
 * Provides rendering logic and caching for navigation menus (procedures, locations, surgeons, before/after).
 * All menu display logic is centralized here for maintainability.
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access to this file.
if (!defined('ABSPATH')) {
    exit;
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
 * Cache surgeons by location queries
 */
function get_cached_surgeons_by_location($location_id) {
    $cache_key = 'mia_footer_surgeons_location_' . $location_id;
    $surgeons = wp_cache_get($cache_key);
    
    if (false === $surgeons) {
        $args = [
            'post_type' => 'surgeon',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'surgeon_location',
                    'value' => $location_id,
                    'compare' => '='
                ]
            ]
        ];
        
        $query = new WP_Query($args);
        $surgeons = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $surgeons[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'url' => get_permalink()
                ];
            }
            wp_reset_postdata();
        }
        
        wp_cache_set($cache_key, $surgeons, '', 3600); // Cache for 1 hour
    }
    
    return $surgeons;
}

/**
 * Render procedures dropdown
 */
function render_procedures_menu($procedures, $is_mobile = false) {
    $dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
    ?>
    <li class="nav-item dropdown <?php echo $dropdown_class; ?>">
        <a class="nav-link dropdown-toggle" href="<?php echo esc_url($procedures['url']); ?>" 
           role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
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
                        <div class="dropdown-header">
                            <a href="<?php echo esc_url($section['url']); ?>" class="text-dark fw-bold text-decoration-none"><?php echo esc_html($section['title']); ?></a>
                        </div>
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
 * Render locations menu for both desktop and mobile
 */
function render_locations_menu($is_mobile = false) {
    $locations = get_cached_locations();
    $dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
    ?>
    <li class="nav-item dropdown <?php echo $dropdown_class; ?>">
        <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/locations/')); ?>" 
           role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
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
           role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
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
           role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
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
                    <div class="dropdown-header">
                        <span class="text-dark fw-bold">By Procedure</span>
                    </div>
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
                    <div class="dropdown-header">
                        <span class="text-dark fw-bold">By Category</span>
                    </div>
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

?>