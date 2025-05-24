<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="preload" href="https://use.typekit.net/iej5pyg.css" as="style">
  <link rel="stylesheet" href="https://use.typekit.net/iej5pyg.css">
  <?php wp_head(); ?>
  <!-- Bootstrap JS is already enqueued in functions.php -->
</head>
<body <?php body_class(); ?><?php if (!is_singular('surgeon')): ?> data-bs-spy="scroll" data-bs-target="#surgeon-tabs" data-bs-offset="120"<?php endif; ?>>
<?php wp_body_open(); ?>
<header class="position-sticky top-0 z-1030">
  <nav class="navbar navbar-expand-xl navbar-dark">
    <div class="container-fluid">
      <div class="d-flex flex-wrap align-items-center w-100">
        <!-- Logo -->
        <a class="navbar-brand me-xl-3" href="<?php echo esc_url(home_url('/')); ?>">
          <img 
            src="/wp-content/uploads/2024/11/miaaesthetics-logo.svg" 
            alt="Mia Aesthetics Logo" 
            height="50" 
            width="200" 
            class="d-inline-block" 
            fetchpriority="high" 
          />
        </a>
        <!-- Medium Mobile CTA - Only visible on medium mobile devices -->
        <div class="d-none d-sm-block d-xl-none mx-auto">
          <a href="<?php echo esc_url(home_url('/free-plastic-surgery-consultation/')); ?>" class="header-btn">
            Free Virtual Consultation <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas" aria-controls="navbarOffcanvas" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Offcanvas Container -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel">
          <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title" id="navbarOffcanvasLabel">
              <img 
                src="/wp-content/uploads/2024/11/miaaesthetics-logo.svg" 
                alt="Mia Aesthetics Logo" 
                height="30" 
                width="120" 
                loading="lazy"
              />
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <div class="d-flex flex-column flex-xl-row align-items-start align-items-xl-center w-100">
              <!-- Main Navigation -->
              <ul class="navbar-nav me-xl-auto mb-2 mb-xl-0">
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                </li>
                <!-- Desktop Procedures Mega Menu (only visible on desktop) -->
                <li class="nav-item dropdown position-static d-none d-xl-block">
                  <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/')); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Procedures
                  </a>
                  <div class="dropdown-menu mega-menu w-100 p-3">
                    <div class="container">
                      <div class="row">
                        <div class="col-12 mb-3">
                          <a class="mega-menu-title" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/')); ?>">View All Procedures <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                      </div>
                      <div class="row">
                        <!-- Body Section -->
                        <div class="col-md-3 mb-3">
                          <h6 class="dropdown-header">
                            <a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/')); ?>" class="text-dark fw-bold text-decoration-none">Body</a>
                          </h6>
                          <ul class="list-unstyled">
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/mia-corset/')); ?>">Mia Waist Corset&trade;</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/awake-liposuction/')); ?>">Awake Lipo</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/circumferential-body-lift/')); ?>">Body Lift</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/brazilian-butt-lift-bbl/')); ?>">Brazilian Butt Lift (BBL)</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/lipo-360/')); ?>">Lipo 360</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/liposuction/')); ?>">Liposuction</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/tummy-tuck/')); ?>">Tummy Tuck</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/mommy-makeover/')); ?>">Mommy Makeover</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/arm-lift/')); ?>">Arm Lift</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/thigh-lift/')); ?>">Thigh Lift</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/labiaplasty-labia-reduction-vaginal-rejuvenation/')); ?>">Vaginal Rejuvenation</a></li>
                          </ul>
                        </div>
                        <!-- Breast Section -->
                        <div class="col-md-3 mb-3">
                          <h6 class="dropdown-header">
                            <a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/')); ?>" class="text-dark fw-bold text-decoration-none">Breast</a>
                          </h6>
                          <ul class="list-unstyled">
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/augmentation-implants/')); ?>">Breast Augmentation</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/reduction/')); ?>">Breast Reduction</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/lift/')); ?>">Breast Lift</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/implant-revision-surgery/')); ?>">Breast Implant Revision</a></li>
                          </ul>
                        </div>
                        <!-- Face Section -->
                        <div class="col-md-3 mb-3">
                          <h6 class="dropdown-header">
                            <a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/')); ?>" class="text-dark fw-bold text-decoration-none">Face</a>
                          </h6>
                          <ul class="list-unstyled">
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/brow-lift/')); ?>">Brow Lift</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/buccal-cheek-fat-removal/')); ?>">Buccal Fat Removal</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/eyelid-lift-blepharoplasty/')); ?>">Blepharoplasty</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/chin-lipo/')); ?>">Chin Lipo</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/facelift/')); ?>">Facelift</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/mini-facelift/')); ?>">Mini Facelift</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/neck-lift/')); ?>">Neck Lift</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/ear-pinning-otoplasty/')); ?>">Otoplasty</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/nose-job-rhinoplasty/')); ?>">Rhinoplasty</a></li>
                          </ul>
                        </div>
                        <!-- Men Section -->
                        <div class="col-md-3 mb-3">
                          <h6 class="dropdown-header">
                            <a href="<?php echo esc_url(home_url('#')); ?>" class="text-dark fw-bold text-decoration-none">Men</a>
                          </h6>
                          <ul class="list-unstyled">
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/male-bbl/')); ?>">Male BBL</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/male-breast-procedures/')); ?>">Male Breast Procedures</a></li>                            
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/male-liposuction/')); ?>">Male Liposuction</a></li>
                            <li><a class="dropdown-item py-1" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/male-tummy-tuck/')); ?>">Male Tummy Tuck</a></li>                          
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
                <!-- Mobile Procedures Dropdown (only visible on mobile) -->
                <li class="nav-item dropdown d-xl-none">
                  <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/')); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Procedures
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/')); ?>">View All Procedures</a></li>
                    <!-- Body Section -->
                    <li><a class="dropdown-item fw-bold" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/')); ?>">Body</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/mia-corset/')); ?>">Mia Waist Corset&trade;</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/awake-liposuction/')); ?>">Awake Lipo</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/circumferential-body-lift/')); ?>">Body Lift</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/brazilian-butt-lift-bbl/')); ?>">Brazilian Butt Lift (BBL)</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/lipo-360/')); ?>">Lipo 360</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/liposuction/')); ?>">Liposuction</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/tummy-tuck/')); ?>">Tummy Tuck</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/mommy-makeover/')); ?>">Mommy Makeover</a></li>       
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/arm-lift/')); ?>">Arm Lift</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/thigh-lift/')); ?>">Thigh Lift</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/labiaplasty-labia-reduction-vaginal-rejuvenation/')); ?>">Vaginal Rejuvenation</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <!-- Breast Section -->
                    <li><a class="dropdown-item fw-bold" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/')); ?>">Breast</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/augmentation-implants/')); ?>">Breast Augmentation</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/reduction/')); ?>">Breast Reduction</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/lift/')); ?>">Breast Lift</a></li>                    
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/implant-revision-surgery/')); ?>">Breast Implant Revision</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <!-- Face Section -->
                    <li><a class="dropdown-item fw-bold" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/')); ?>">Face</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/brow-lift/')); ?>">Brow Lift</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/buccal-cheek-fat-removal/')); ?>">Buccal Fat Removal</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/eyelid-lift-blepharoplasty/')); ?>">Blepharoplasty</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/chin-lipo/')); ?>">Chin Lipo</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/facelift/')); ?>">Facelift</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/mini-facelift/')); ?>">Mini Facelift</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/neck-lift/')); ?>">Neck Lift</a></li>              
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/ear-pinning-otoplasty/')); ?>">Otoplasty</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/nose-job-rhinoplasty/')); ?>">Rhinoplasty</a></li>
                    
                    <li><hr class="dropdown-divider"></li>
                    <!-- Men Section -->
                    <li><a class="dropdown-item fw-bold" href="<?php echo esc_url(home_url('#')); ?>">Men</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/male-bbl/')); ?>">Male BBL</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/male-breast-procedures/')); ?>">Male Breast Procedures</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/male-liposuction/')); ?>">Male Liposuction</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/male-tummy-tuck/')); ?>">Male Tummy Tuck</a></li>                
                  </ul>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Non-Surgical
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/non-surgical/j-plasma-skin-tightening/')); ?>">J-Plasma</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/weight-loss/')); ?>">Skinny Shot</a></li>
                  </ul>
                </li>
                <!-- Desktop Locations Mega Menu (only visible on desktop) -->
                <li class="nav-item dropdown position-static d-none d-xl-block">
                  <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/locations/')); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Locations
                  </a>
                  <div class="dropdown-menu mega-menu w-100 p-3">
                    <div class="container">
                      <div class="row">
                        <div class="col-12 mb-3">
                          <a class="mega-menu-title" href="<?php echo esc_url(home_url('/locations/')); ?>">View All Locations <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                      </div>
                      <div class="row">
                        <?php
                        // Query location custom post type - only parent pages
                        $location_args = array(
                          'post_type' => 'location',
                          'posts_per_page' => -1,
                          'orderby' => 'title',
                          'order' => 'ASC',
                          'post_parent' => 0 // Only get parent pages (no children)
                        );
                        $location_query = new WP_Query($location_args);
                        if ($location_query->have_posts()) :
                          // Count locations to distribute them evenly across columns
                          $total_locations = $location_query->post_count;
                          $locations_per_column = ceil($total_locations / 4); // 4 columns like surgeons
                          $location_count = 0;
                          $column_count = 0;
                          // Start the first column
                          echo '<div class="col-md-3 mb-3"><ul class="list-unstyled">';
                          while ($location_query->have_posts()) : $location_query->the_post();
$location_title = get_the_title();
$state = get_field('state');
$location_url = get_permalink();
// Remove "Mia Aesthetics" from the title
$display_city = trim(str_ireplace('Mia Aesthetics', '', $location_title));
// Get state abbreviation using helper
$abbr = mia_get_state_abbr($state);
// Build menu label
$menu_label = $state ? $display_city . ', ' . $abbr : $display_city;
echo '<li><a class="dropdown-item py-1" href="' . esc_url($location_url) . '">' . esc_html($menu_label) . '</a></li>';
                            $location_count++;
                            // Start a new column if needed
                            if ($location_count % $locations_per_column === 0 && $location_count < $total_locations) {
                              $column_count++;
                              echo '</ul></div><div class="col-md-3 mb-3"><ul class="list-unstyled">';
                            }
                          endwhile;
                          // Close the last column
                          echo '</ul></div>';
                          // Fill any remaining columns if needed
                          while ($column_count < 3) { // We need 4 columns total (0-3)
                            $column_count++;
                            echo '<div class="col-md-3 mb-3"></div>';
                          }
                          wp_reset_postdata();
                        else:
                          // Fallback if no locations found
                          echo '<div class="col-12"><p>No locations found. <a href="' . esc_url(home_url('/locations/')) . '">View our locations page</a> for more information.</p></div>';
                        endif;
                        ?>
                      </div>
                    </div>
                  </div>
                </li>
                <!-- Mobile Locations Dropdown (only visible on mobile) -->
                <li class="nav-item dropdown d-xl-none">
                  <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/locations/')); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Locations
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/locations/')); ?>">View All Locations</a></li>
                    <?php
                    // Query location custom post type - only parent pages
                    $location_args = array(
                      'post_type' => 'location',
                      'posts_per_page' => -1,
                      'orderby' => 'title',
                      'order' => 'ASC',
                      'post_parent' => 0 // Only get parent pages (no children)
                    );
                    $location_query = new WP_Query($location_args);
                    if ($location_query->have_posts()) :
                      while ($location_query->have_posts()) : $location_query->the_post();
$location_title = get_the_title();
$state = get_field('state');
$location_url = get_permalink();
$display_city = trim(str_ireplace('Mia Aesthetics', '', $location_title));
$abbr = mia_get_state_abbr($state);
$menu_label = $state ? $display_city . ', ' . $abbr : $display_city;
?>
<li><a class="dropdown-item" href="<?php echo esc_url($location_url); ?>"><?php echo esc_html($menu_label); ?></a></li>
<?php
                      endwhile;
                      wp_reset_postdata();
                    else:
                      // Fallback if no locations found
                      ?>
                      <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/locations/')); ?>">View Our Locations</a></li>
                      <?php
                    endif;
                    ?>
                  </ul>
                </li>
                <!-- Desktop Surgeons Mega Menu (only visible on desktop) -->
                <li class="nav-item dropdown position-static d-none d-xl-block">
                  <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Surgeons
                  </a>
                  <div class="dropdown-menu mega-menu w-100 p-3">
                    <div class="container">
                      <div class="row">
                        <div class="col-12 mb-3">
                          <a class="mega-menu-title" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>">View All Surgeons <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                      </div>
                      <div class="row">
                        <?php
                        // Query surgeons custom post type
                        $surgeon_args = array(
                          'post_type' => 'surgeon',
                          'posts_per_page' => -1,
                          'orderby' => 'title',
                          'order' => 'ASC'
                        );
                        $surgeon_query = new WP_Query($surgeon_args);
                        
                        // Create an array to store surgeons with their last names
                        $surgeons = array();
                        
                        if ($surgeon_query->have_posts()) :
                          while ($surgeon_query->have_posts()) : $surgeon_query->the_post();
                            $surgeon_name = get_the_title();
                            $surgeon_url = get_permalink();
                            $surgeon_id = get_the_ID();
                            
                            // Extract last name (second word in the title)
                            $name_parts = explode(' ', $surgeon_name);
                            $last_name = isset($name_parts[1]) ? $name_parts[1] : $surgeon_name;
                            
                            // Store surgeon data
                            $surgeons[] = array(
                              'id' => $surgeon_id,
                              'name' => $surgeon_name,
                              'url' => $surgeon_url,
                              'last_name' => $last_name
                            );
                          endwhile;
                          
                          // Sort surgeons by last name
                          usort($surgeons, function($a, $b) {
                            return strcasecmp($a['last_name'], $b['last_name']);
                          });
                          
                          // Count surgeons to distribute them evenly across columns
                          $total_surgeons = count($surgeons);
                          $surgeons_per_column = ceil($total_surgeons / 4); // 4 columns
                          $surgeon_count = 0;
                          $column_count = 0;
                          
                          // Start the first column
                          echo '<div class="col-md-3 mb-3"><ul class="list-unstyled">';
                          
                          // Display surgeons in the sorted order
                          foreach ($surgeons as $surgeon) :
                            // Output the surgeon link
                            echo '<li><a class="dropdown-item py-1" href="' . esc_url($surgeon['url']) . '">' . esc_html($surgeon['name']) . '</a></li>';
                            $surgeon_count++;
                            
                            // Start a new column if needed
                            if ($surgeon_count % $surgeons_per_column === 0 && $surgeon_count < $total_surgeons) {
                              $column_count++;
                              echo '</ul></div><div class="col-md-3 mb-3"><ul class="list-unstyled">';
                            }
                          endforeach;
                          // Close the last column
                          echo '</ul></div>';
                          // Fill any remaining columns if needed
                          while ($column_count < 3) { // We need 4 columns total (0-3)
                            $column_count++;
                            echo '<div class="col-md-3 mb-3"></div>';
                          }
                          wp_reset_postdata();
                        else:
                          // Fallback if no surgeons found
                          echo '<div class="col-12"><p>No surgeons found. <a href="' . esc_url(home_url('/plastic-surgeons/')) . '">View our surgeons page</a> for more information.</p></div>';
                        endif;
                        ?>
                      </div>
                    </div>
                  </div>
                </li>
                <!-- Mobile Surgeons Dropdown (only visible on mobile) -->
                <li class="nav-item dropdown d-xl-none">
                  <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Surgeons
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>">View All Surgeons</a></li>
                    <?php
                    // Query surgeons custom post type
                    $surgeon_args = array(
                      'post_type' => 'surgeon',
                      'posts_per_page' => -1,
                      'orderby' => 'title',
                      'order' => 'ASC'
                    );
                    $surgeon_query = new WP_Query($surgeon_args);
                    
                    // Create an array to store surgeons with their last names
                    $surgeons = array();
                    
                    if ($surgeon_query->have_posts()) :
                      while ($surgeon_query->have_posts()) : $surgeon_query->the_post();
                        $surgeon_name = get_the_title();
                        $surgeon_url = get_permalink();
                        
                        // Extract last name (second word in the title)
                        $name_parts = explode(' ', $surgeon_name);
                        $last_name = isset($name_parts[1]) ? $name_parts[1] : $surgeon_name;
                        
                        // Store surgeon data
                        $surgeons[] = array(
                          'name' => $surgeon_name,
                          'url' => $surgeon_url,
                          'last_name' => $last_name
                        );
                      endwhile;
                      
                      // Sort surgeons by last name
                      usort($surgeons, function($a, $b) {
                        return strcasecmp($a['last_name'], $b['last_name']);
                      });
                      
                      // Display surgeons in the sorted order
                      foreach ($surgeons as $surgeon) :
                        ?>
                        <li><a class="dropdown-item" href="<?php echo esc_url($surgeon['url']); ?>"><?php echo esc_html($surgeon['name']); ?></a></li>
                        <?php
                      endforeach;
                      wp_reset_postdata();
                    else:
                      // Fallback if no surgeons found
                      ?>
                      <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>">View Our Surgeons</a></li>
                      <?php
                    endif;
                    ?>
                  </ul>
                </li>
                <!-- Desktop Before & After Mega Menu (only visible on desktop) -->
                <li class="nav-item dropdown position-static d-none d-xl-block">
                  <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/before-after/')); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Before & After
                  </a>
                  <div class="dropdown-menu mega-menu w-100 p-3">
                    <div class="container">
                      <div class="row">
                        <div class="col-12 mb-3">
                          <a class="mega-menu-title" href="<?php echo esc_url(home_url('/before-after/')); ?>">View All Before & After <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                      </div>
                      <div class="row">
                        <!-- Procedure Types -->
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
                        <!-- Other Categories -->
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
                </li>
                <!-- Mobile Before & After Dropdown (only visible on mobile) -->
                <li class="nav-item dropdown d-xl-none">
                  <a class="nav-link dropdown-toggle" href="<?php echo esc_url(home_url('/before-after/')); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Before & After
                  </a>
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
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo esc_url(home_url('/financing/')); ?>">Financing</a>
                </li>
                <!-- Patient Portal Dropdown -->
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Patient Portal
                  </a>
                  <ul class="dropdown-menu">
                    <li>
                      <a class="dropdown-item" href="https://patient.miaaesthetics.com/s/login?ec=302&startURL=%2Fs%2Fhome" target="_blank">
                        Patient Portal Login
                      </a>
                    </li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/web-to-case/')); ?>">Portal Support</a></li>
                  </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://shop.miaaesthetics.com/" target="_blank">Shop</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!-- Right Side Items -->
        <div class="d-none d-xl-block ms-auto">
          <a href="<?php echo esc_url(home_url('/free-plastic-surgery-consultation/')); ?>" class="header-btn desktop-cta">
            Free Virtual Consultation <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </nav>
</header>
<!-- Mobile Floating CTA Button - Only visible on mobile -->
<div id="mobileCta" class="mobile-cta-container">
  <a href="<?php echo esc_url(home_url('/free-plastic-surgery-consultation/')); ?>" class="mobile-consultation-btn">
    Free Virtual Consultation <i class="fa-solid fa-arrow-right"></i>
  </a>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const cta = document.getElementById('mobileCta');
    const oc = document.getElementById('navbarOffcanvas');

    oc.addEventListener('show.bs.offcanvas', () => cta.classList.add('d-none'));   // hide when menu opens
    oc.addEventListener('hidden.bs.offcanvas', () => cta.classList.remove('d-none')); // show again when menu closes
  });
</script>
