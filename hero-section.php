<?php
/**
 * Hero Section Template
 * 
 * Contains the complete hero section with carousel and sidebar boxes.
 * Separated from front-page.php for easier editing and debugging.
 */
?>

<section class="hero-section">
  <div class="hero-container">
    <!-- Main Carousel -->
    <div class="hero-carousel">
      <div class="ratio ratio-16x9 position-relative">
        <div id="specialsCarousel" class="carousel slide w-100 h-100" data-bs-ride="carousel">
          <!-- Carousel Indicators -->
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#specialsCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#specialsCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
          </div>
          
          <!-- Carousel Content -->
          <div class="carousel-inner">
            <!-- First Slide -->
            <div class="carousel-item active w-100 h-100">
              <?php 
              // Use ACF image field with WordPress responsive image functionality
              $banner1_id = get_field('banner_1'); // Get image ID from ACF field
              if ($banner1_id) {
                echo wp_get_attachment_image($banner1_id, 'large', false, array(
                  'class' => 'd-block w-100',
                  'alt' => 'Promotional Banner 1',
                  'loading' => 'eager',
                  'fetchpriority' => 'high',
                  'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 75vw, 1024px'
                ));
              } else {
                // Fallback if ACF field is empty
                echo '<img src="/wp-content/uploads/2025/04/Banner-GENERIC.jpg" class="d-block w-100" alt="Promotional Banner 1" loading="eager" fetchpriority="high"/>';
              }
              ?>
            </div>
            
            <!-- Second Slide -->
            <div class="carousel-item w-100 h-100">
              <?php 
              // Use ACF image field with WordPress responsive image functionality
              $banner2_id = get_field('banner_2'); // Get image ID from ACF field
              if ($banner2_id) {
                echo wp_get_attachment_image($banner2_id, 'large', false, array(
                  'class' => 'd-block w-100',
                  'alt' => 'Promotional Banner 2',
                  'loading' => 'eager',
                  'fetchpriority' => 'high',
                  'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 75vw, 1024px'
                ));
              } else {
                // Fallback if ACF field is empty
                echo '<img src="/wp-content/uploads/2025/04/non-surgical-home-banner.jpg" class="d-block w-100" alt="Promotional Banner 2" loading="eager" fetchpriority="high"/>';
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Hero Sidebar -->
    <div class="hero-sidebar">
      <!-- Before & After Box -->
      <div class="hero-box hero-box-top">
        <div class="hero-box-content">
          <div class="hero-box-image">
            <?php 
            $before_after_img_id = get_field('ba_image'); // Get image ID from ACF field
            if ($before_after_img_id) {
              echo wp_get_attachment_image($before_after_img_id, 'large', false, array(
                'class' => 'content-image',
                'alt' => 'Before and After Background',
                'sizes' => '(max-width: 767px) 50vw, (max-width: 991px) 33vw, 400px'
              ));
            } else {
              // Fallback if ACF field is empty
              echo '<img src="/wp-content/uploads/2025/04/before-after-banner.jpg" alt="Before and After Background" class="content-image" />';
            }
            ?>
          </div>
          <div class="hero-box-overlay">
            <h4 class="hero-box-title">Before & After</h4>
            <div class="hero-box-button">
              <a href="/before-after/" class="mia-button" data-variant="hero" role="button">View Gallery <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
      </div>

      <!-- Financing Box -->
      <div class="hero-box hero-box-bottom">
        <div class="hero-box-content">
          <div class="hero-box-image">
            <?php 
            $financing_img_id = get_field('financing_image'); // Get image ID from ACF field
            if ($financing_img_id) {
              echo wp_get_attachment_image($financing_img_id, 'large', false, array(
                'class' => 'content-image',
                'alt' => 'Financing Options Background',
                'sizes' => '(max-width: 767px) 50vw, (max-width: 991px) 33vw, 400px'
              ));
            } else {
              // Fallback if ACF field is empty
              echo '<img src="/wp-content/uploads/2025/04/financing-home-banner.jpg" alt="Financing Options Background" class="content-image" />';
            }
            ?>
          </div>
          <div class="hero-box-overlay">
            <h4 class="hero-box-title">Financing</h4>        
            <div class="hero-box-button">
              <a href="/financing/" class="mia-button" data-variant="hero" role="button" aria-label="Explore Financing Options">View Options <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
