<?php
/**
 * The front page template file
 */
get_header(); ?>
<main id="primary" class="site-main">
<section class="hero-section">
  <div class="hero-wrapper">
<div class="row no-gutters">
  <!-- Left Column: Carousel -->
  <div class="col-md-8">
    <div class="content-box box-carousel position-relative">
      <div id="specialsCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#specialsCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#specialsCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        </div>
        <div class="carousel-inner">
<div class="carousel-item active">
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
<div class="carousel-item">
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
        <button class="carousel-control-prev" type="button" data-bs-target="#specialsCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#specialsCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Right Column: Stacked Boxes (on desktop) / Side by Side (on mobile) -->
  <div class="col-md-4 d-flex flex-column flex-sm-column flex-xs-row">
    <!-- Top Box: Before & After -->
    <div class="content-box box-top position-relative">
      <div class="img-container">
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
      <div class="content-overlay p-2 p-md-4 d-flex flex-column justify-content-end align-items-center text-center">
        <h4 class="responsive-heading">Before & After</h4>
        <div class="button-wrapper">
          <a href="#gallery" class="btn btn-outline-light btn-sm">View Gallery</a>
        </div>
      </div>
    </div>

    <!-- Bottom Box: Financing -->
    <div class="content-box box-bottom position-relative">
      <div class="img-container">
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
      <div class="content-overlay p-2 p-md-4 d-flex flex-column justify-content-end align-items-center text-center">
        <h4 class="responsive-heading">Financing</h4>
        <div class="button-wrapper">
          <a href="#financing" class="btn btn-outline-light btn-sm">Learn More</a>
        </div>
      </div>
    </div>
  </div>
</div>

  </div>
</section>

<section class="about-section py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 about-content mb-4 mb-lg-0">
        <h2 class="section-heading mb-3">About Us</h2>
        <h3 class="section-subheading mb-4">Professional Care You Can Trust</h3>
        <p class="about-text">
          Test Text
        </p>
        <div class="mt-4">
          <a href="#learn-more" class="btn btn-primary">Learn More</a>
        </div>
      </div>
      <div class="col-lg-6 about-video">
        <div class="video-gallery">
          <!-- Main featured video container -->
          <div class="featured-video mb-3">
            <div class="ratio ratio-16x9 rounded shadow overflow-hidden">
              <iframe id="featured-video-player" src="https://www.youtube.com/embed/OxigXlYTqH8" title="Featured Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
          </div>
          
          <!-- Video thumbnails -->
          <div class="video-thumbnails">
            <div class="row g-2">
              <!-- Video 1 -->
              <div class="col-3">
                <div class="video-thumb active" data-video-id="OxigXlYTqH8">
                  <img src="https://img.youtube.com/vi/OxigXlYTqH8/mqdefault.jpg" alt="Video Thumbnail" class="img-fluid rounded">
                  <div class="overlay d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-play"></i>
                  </div>
                </div>
              </div>
              
              <!-- Video 2 -->
              <div class="col-3">
                <div class="video-thumb" data-video-id="sb8Kapy8mzU">
                  <img src="https://img.youtube.com/vi/sb8Kapy8mzU/mqdefault.jpg" alt="Video Thumbnail" class="img-fluid rounded">
                  <div class="overlay d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-play"></i>
                  </div>
                </div>
              </div>
              
              <!-- Video 3 -->
              <div class="col-3">
                <div class="video-thumb" data-video-id="4-B_ISCne28">
                  <img src="https://img.youtube.com/vi/4-B_ISCne28/mqdefault.jpg" alt="Video Thumbnail" class="img-fluid rounded">
                  <div class="overlay d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-play"></i>
                  </div>
                </div>
              </div>
              
              <!-- Video 4 -->
              <div class="col-3">
                <div class="video-thumb" data-video-id="ykz9Z8Kh3Yo">
                  <img src="https://img.youtube.com/vi/ykz9Z8Kh3Yo/mqdefault.jpg" alt="Video Thumbnail" class="img-fluid rounded">
                  <div class="overlay d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-play"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Stats Section with Counting Numbers -->
<section class="stats-section py-5 bg-light">
  <div class="container">
    <div class="row text-center mb-4">
      <div class="col-12">
        <h2 class="section-heading">Board Certified Excellence</h2>
        <p class="lead">Our commitment to quality and professional standards</p>
      </div>
    </div>
    <div class="row text-center">
      <!-- Year Founded -->
      <div class="col-md-4 mb-3 mb-md-0">
        <div class="stats-item">
          <div class="stats-number" data-count="2018">
            <span class="display-4 fw-bold text-primary">2018</span>
          </div>
          <h3 class="stats-title mt-2 mt-md-3">Year Founded</h3>
          <p class="mb-2 mb-md-3">Serving our community with excellence since day one</p>
        </div>
      </div>
      
      <!-- Number 26 -->
      <div class="col-md-4 mb-3 mb-md-0">
        <div class="stats-item">
          <div class="stats-number" data-count="26">
            <span class="display-4 fw-bold text-primary">26</span>
          </div>
          <h3 class="stats-title mt-2 mt-md-3">Board Certified Specialists</h3>
          <p class="mb-2 mb-md-3">Expert professionals dedicated to your care</p>
        </div>
      </div>
      
      <!-- Locations -->
      <div class="col-md-4">
        <div class="stats-item">
          <div class="stats-number" data-count="12">
            <span class="display-4 fw-bold text-primary">12</span>
          </div>
          <h3 class="stats-title mt-2 mt-md-3">Locations</h3>
          <p class="mb-2 mb-md-3">Conveniently serving patients across the region</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Parallax Section with Video Background -->
<section class="parallax-section">
  <div class="parallax-video-bg">
    <video autoplay muted loop playsinline class="parallax-video">
      <source src="/wp-content/uploads/2025/04/beach-movie.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </div>
</section>

<!-- Overlapping Card Section -->
<section class="overlap-section">
  <div class="container">
    <div class="row">
      <div class="col-12 d-flex align-items-center">
        <div class="overlap-card position-relative w-100">
          <div class="row">
            <div class="col-12 text-center mb-4">
              <h3 class="mb-2 text-primary">How Payments Work</h3>
              <h4 class="text-gold">at Mia Aesthetics</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
              <div class="payment-card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                  <div class="payment-step-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <h5 class="mb-0">1</h5>
                  </div>
                  <h5 class="card-title">Research Surgeons</h5>
                  <ul class="list-unstyled text-start small">
                    <li class="mb-2"><i class="fa-solid fa-check me-2"></i>Choose from over 25 experienced surgeons</li>
                    <li class="mb-2"><i class="fa-solid fa-check me-2"></i>Review surgeon bios on our website</li>
                    <li><i class="fa-solid fa-check me-2"></i>Browse our before and after photos</li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
              <div class="payment-card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                  <div class="payment-step-icon">
                    <i class="fa-solid fa-tag"></i>
                    <h5 class="mb-0">2</h5>
                  </div>
                  <h5 class="card-title">Check Specials</h5>
                  <ul class="list-unstyled text-start small">
                    <li class="mb-2"><i class="fa-solid fa-check me-2"></i>Contact a sales coordinator for specials</li>
                    <li class="mb-2"><i class="fa-solid fa-check me-2"></i>New surgeons often offer reduced rates</li>
                    <li><i class="fa-solid fa-check me-2"></i>Keep an eye on seasonal specials</li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4 mb-md-0">
              <div class="payment-card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                  <div class="payment-step-icon">
                    <i class="fa-solid fa-lock"></i>
                    <h5 class="mb-0">3</h5>
                  </div>
                  <h5 class="card-title">Lock in Your Price</h5>
                  <ul class="list-unstyled text-start small">
                    <li class="mb-2"><i class="fa-solid fa-check me-2"></i>Call us to secure a special price</li>
                    <li class="mb-2"><i class="fa-solid fa-check me-2"></i>Booking fee required to lock in price</li>
                    <li><i class="fa-solid fa-check me-2"></i>Price valid for 12 months</li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="payment-card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                  <div class="payment-step-icon">
                    <i class="fa-solid fa-credit-card"></i>
                    <h5 class="mb-0">4</h5>
                  </div>
                  <h5 class="card-title">Complete Payments</h5>
                  <ul class="list-unstyled text-start small">
                    <li class="mb-2"><i class="fa-solid fa-check me-2"></i>Pay at your own pace</li>
                    <li class="mb-2"><i class="fa-solid fa-check me-2"></i>Contact your patient concierge</li>
                    <li><i class="fa-solid fa-check me-2"></i>Ask for contract extensions if needed</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Procedures Tabbed Section -->
<section class="procedures-section py-5">
  <div class="container">
    <div class="row mb-5 text-center">
      <div class="col-12">
        <h2 class="section-heading">Our Procedures</h2>
        <p class="lead">Discover the perfect procedure for your aesthetic goals</p>
      </div>
    </div>

    <!-- Procedure Navigation Tabs -->
    <div class="row">
      <div class="col-12">
        <!-- Mobile Dropdown (shown only on mobile) -->
        <div class="procedure-dropdown">
          <select id="procedureDropdown" aria-label="Select a procedure category">
            <option value="body-content" selected>Body</option>
            <option value="breast-content">Breast</option>
            <option value="face-content">Face</option>
            <option value="nonsurgical-content">Non-Surgical</option>
            <option value="men-content">Men</option>
          </select>
        </div>
        
        <!-- Desktop Tabs (hidden on mobile) -->
        <div class="procedure-tabs-container position-relative">
          <!-- Tab navigation arrows positioned outside the tabs list -->
          <button class="procedure-nav-arrow prev-arrow" type="button" aria-label="Previous procedures" tabindex="0">
            <i class="fa-solid fa-chevron-left"></i>
          </button>
          
          <div class="tabs-wrapper">
            <ul class="nav nav-tabs procedure-tabs justify-content-center" id="procedureTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="body-tab" data-bs-toggle="tab" data-bs-target="#body-content" type="button" role="tab" aria-controls="body-content" aria-selected="true">
                  Body
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="breast-tab" data-bs-toggle="tab" data-bs-target="#breast-content" type="button" role="tab" aria-controls="breast-content" aria-selected="false">
                  Breast
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="face-tab" data-bs-toggle="tab" data-bs-target="#face-content" type="button" role="tab" aria-controls="face-content" aria-selected="false">
                  Face
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="nonsurgical-tab" data-bs-toggle="tab" data-bs-target="#nonsurgical-content" type="button" role="tab" aria-controls="nonsurgical-content" aria-selected="false">
                  Non-Surgical
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="men-tab" data-bs-toggle="tab" data-bs-target="#men-content" type="button" role="tab" aria-controls="men-content" aria-selected="false">
                  Men
                </button>
              </li>
            </ul>
          </div>
          
          <button class="procedure-nav-arrow next-arrow" type="button" aria-label="Next procedures" tabindex="0">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Procedure Content -->
    <div class="tab-content mt-4" id="procedureTabsContent">
      <!-- Body Content -->
      <div class="tab-pane fade show active" id="body-content" role="tabpanel" aria-labelledby="body-tab">
        <div class="row align-items-center">
          <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
            <?php 
            $body_img_id = attachment_url_to_postid('/wp-content/uploads/2025/04/body-home.jpg');
            if ($body_img_id) {
              echo wp_get_attachment_image($body_img_id, 'large', false, array(
                'class' => 'img-fluid rounded shadow',
                'alt' => 'Body Procedures',
                'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px'
              ));
            } else {
              // Fallback if image ID can't be found
              echo '<img src="/wp-content/uploads/2025/04/body-home.jpg" alt="Body Procedures" class="img-fluid rounded shadow">';
            }
            ?>
          </div>
          <div class="col-lg-6 order-lg-1">
            <h3 class="procedure-title mb-3">Body Contouring</h3>
            <h4 class="section-subheading mb-4">Sculpt Your Ideal Figure</h4>
            <p class="mb-4">Our body contouring procedures help you achieve the silhouette you desire. Whether you're looking to remove excess fat, tighten loose skin, or enhance your curves, our specialists can help you reach your aesthetic goals.</p>
            
            <div class="procedure-links mb-4">
              <div class="row">
                <div class="col-md-6">
                  <a href="#tummy-tuck" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Awake Lipo
                  </a>
                  <a href="/cosmetic-plastic-surgery/body/brazilian-butt-lift-bbl/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Brazilian Butt Lift (BBL)
                  </a>
                  <a href="/cosmetic-plastic-surgery/body/lipo-360/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Lipo 360
                  </a>
                </div>
                <div class="col-md-6">
                  <a href="/cosmetic-plastic-surgery/body/mommy-makeover/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Mommy Makeover
                  </a>
                  <a href="/cosmetic-plastic-surgery/body/tummy-tuck/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Tummy Tuck
                  </a>
                  <a href="#thigh-lift" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Arm Lift
                  </a>
                </div>
              </div>
            </div>
            
            <a href="#body-procedures" class="btn btn-primary">View All Body Procedures</a>
          </div>
        </div>
      </div>
      
      <!-- Breast Content -->
      <div class="tab-pane fade" id="breast-content" role="tabpanel" aria-labelledby="breast-tab">
        <div class="row align-items-center">
          <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
            <?php 
            $breast_img_id = attachment_url_to_postid('/wp-content/uploads/2025/04/breast-home.jpg');
            if ($breast_img_id) {
              echo wp_get_attachment_image($breast_img_id, 'large', false, array(
                'class' => 'img-fluid rounded shadow',
                'alt' => 'Breast Procedures',
                'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px'
              ));
            } else {
              // Fallback if image ID can't be found
              echo '<img src="/wp-content/uploads/2025/04/breast-home.jpg" alt="Breast Procedures" class="img-fluid rounded shadow">';
            }
            ?>
          </div>
          <div class="col-lg-6 order-lg-1">
            <h3 class="procedure-title mb-3">Breast Enhancement</h3>
            <h4 class="section-subheading mb-4">Achieve Your Desired Look</h4>
            <p class="mb-4">Our breast procedures are designed to enhance, reduce, or lift your breasts to achieve your desired appearance. Our board-certified surgeons use the latest techniques to deliver natural-looking results with minimal scarring.</p>
            
            <div class="procedure-links mb-4">
              <div class="row">
                <div class="col-md-6">
                  <a href="#breast-augmentation" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Breast Augmentation
                  </a>
                  <a href="#breast-lift" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Breast Lift
                  </a>
                  <a href="#breast-reduction" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Breast Reduction
                  </a>
                </div>
                <div class="col-md-6">
                  <a href="#breast-revision" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Breast Implant Revision
                  </a>
                  <a href="#breast-reconstruction" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Capsulectomy
                  </a>
                  <a href="#breast-implants" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Breast Implant Options
                  </a>
                </div>
              </div>
            </div>
            
            <a href="#breast-procedures" class="btn btn-primary">View All Breast Procedures</a>
          </div>
        </div>
      </div>
      
      <!-- Face Content -->
      <div class="tab-pane fade" id="face-content" role="tabpanel" aria-labelledby="face-tab">
        <div class="row align-items-center">
          <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
            <?php 
            $face_img_id = attachment_url_to_postid('/wp-content/uploads/2025/04/face-2-home.jpg');
            if ($face_img_id) {
              echo wp_get_attachment_image($face_img_id, 'large', false, array(
                'class' => 'img-fluid rounded shadow',
                'alt' => 'Facial Procedures',
                'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px'
              ));
            } else {
              // Fallback if image ID can't be found
              echo '<img src="/wp-content/uploads/2025/04/face-2-home.jpg" alt="Facial Procedures" class="img-fluid rounded shadow">';
            }
            ?>
          </div>
          <div class="col-lg-6 order-lg-1">
            <h3 class="procedure-title mb-3">Facial Rejuvenation</h3>
            <h4 class="section-subheading mb-4">Enhance Your Natural Beauty</h4>
            <p class="mb-4">Our facial procedures are designed to enhance your natural features and restore youthful appearance. From facelifts to rhinoplasty, our board-certified surgeons use the latest techniques to deliver exceptional results.</p>
            
            <div class="procedure-links mb-4">
              <div class="row">
                <div class="col-md-6">
                  <a href="#facelift" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Brow Lift
                  </a>
                  <a href="#rhinoplasty" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Buccal Fat Removal
                  </a>
                  <a href="#eyelid-surgery" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Eyelid Lift
                  </a>
                </div>
                <div class="col-md-6">
                  <a href="#brow-lift" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Facelift
                  </a>
                  <a href="#neck-lift" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Mini Facelift
                  </a>
                  <a href="#facial-implants" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Neck Lift
                  </a>
                </div>
              </div>
            </div>
            
            <a href="#facial-procedures" class="btn btn-primary">View All Facial Procedures</a>
          </div>
        </div>
      </div>
      
      <!-- Non-Surgical Content -->
      <div class="tab-pane fade" id="nonsurgical-content" role="tabpanel" aria-labelledby="nonsurgical-tab">
        <div class="row align-items-center">
          <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
            <?php 
            $nonsurg_img_id = attachment_url_to_postid('/wp-content/uploads/2025/04/face-home.jpg');
            if ($nonsurg_img_id) {
              echo wp_get_attachment_image($nonsurg_img_id, 'large', false, array(
                'class' => 'img-fluid rounded shadow',
                'alt' => 'Non-Surgical Procedures',
                'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px'
              ));
            } else {
              // Fallback if image ID can't be found
              echo '<img src="/wp-content/uploads/2025/04/face-home.jpg" alt="Non-Surgical Procedures" class="img-fluid rounded shadow">';
            }
            ?>
          </div>
          <div class="col-lg-6 order-lg-1">
            <h3 class="procedure-title mb-3">Non-Surgical Treatments</h3>
            <h4 class="section-subheading mb-4">Rejuvenate Without Surgery</h4>
            <p class="mb-4">Our non-surgical treatments offer remarkable results with minimal downtime. From injectables to laser therapies, we provide a range of options to address your concerns without the need for surgery.</p>
            
            <div class="procedure-links mb-4">
              <div class="row">
                <div class="col-md-6">
                  <a href="#botox" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Botox
                  </a>
                  <a href="#fillers" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Dermal Fillers
                  </a>
               
                </div>
                <div class="col-md-6">
                <a href="#laser-resurfacing" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> J-Plasma
                  </a>
                  <a href="#chemical-peels" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Skinny Shot
                  </a>
                </div>
              </div>
            </div>
            
            <a href="#non-surgical" class="btn btn-primary">View All Non-Surgical Treatments</a>
          </div>
        </div>
      </div>
      
      <!-- Men Content -->
      <div class="tab-pane fade" id="men-content" role="tabpanel" aria-labelledby="men-tab">
        <div class="row align-items-center">
          <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
            <?php 
            $men_img_id = attachment_url_to_postid('/wp-content/uploads/2025/04/men-home.jpg');
            if ($men_img_id) {
              echo wp_get_attachment_image($men_img_id, 'large', false, array(
                'class' => 'img-fluid rounded shadow',
                'alt' => 'Men\'s Procedures',
                'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px'
              ));
            } else {
              // Fallback if image ID can't be found
              echo '<img src="/wp-content/uploads/2025/04/men-home.jpg" alt="Men\'s Procedures" class="img-fluid rounded shadow">';
            }
            ?>
          </div>
          <div class="col-lg-6 order-lg-1">
            <h3 class="procedure-title mb-3">Men's Procedures</h3>
            <h4 class="section-subheading mb-4">Tailored Solutions for Men</h4>
            <p class="mb-4">Our men's procedures are specifically designed to address the unique concerns and aesthetic goals of our male patients. From body contouring to facial rejuvenation, we offer a range of treatments to help you look and feel your best.</p>
            
            <div class="procedure-links mb-4">
              <div class="row">
                <div class="col-md-6">
                  <a href="#male-breast-reduction" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Male Brazilian Butt Lift (BBL)
                  </a>
                  <a href="#male-liposuction" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Gynecomastia Surgery
                  </a>
                 
                </div>
                <div class="col-md-6">
                <a href="#male-tummy-tuck" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Male Liposuction
                  </a>
                  <a href="#male-facelift" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Male Tummy Tuck
                  </a>
               
                </div>
              </div>
            </div>
            
            <a href="#men-procedures" class="btn btn-primary">View All Men's Procedures</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Two Column CTA Section -->
<section class="cta-section py-5">
  <div class="container">
    <div class="row g-4">
      <!-- Locations CTA -->
      <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="info-card">
          <div class="card-image">
            <?php 
            $locations_img_id = attachment_url_to_postid('/wp-content/uploads/2025/04/locations-home.jpg');
            if ($locations_img_id) {
              echo wp_get_attachment_image($locations_img_id, 'large', false, array(
                'class' => 'img-fluid',
                'alt' => 'Our Locations',
                'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px'
              ));
            } else {
              // Fallback if image ID can't be found
              echo '<img src="/wp-content/uploads/2025/04/locations-home.jpg" alt="Our Locations" class="img-fluid">';
            }
            ?>
          </div>
          <div class="card-content text-center">
            <h3 class="card-title">Find a Location</h3>
            <p class="card-text">With 12 convenient locations across the region, quality care is never far away. Find the MIA Aesthetics clinic nearest to you.</p>
            <a href="#locations" class="btn btn-primary">VIEW ALL LOCATIONS</a>
          </div>
        </div>
      </div>
      
      <!-- Surgeons CTA -->
      <div class="col-lg-6">
        <div class="info-card">
          <div class="card-image">
            <?php 
            $surgeons_img_id = attachment_url_to_postid('/wp-content/uploads/2025/04/surgeons-home.jpg');
            if ($surgeons_img_id) {
              echo wp_get_attachment_image($surgeons_img_id, 'large', false, array(
                'class' => 'img-fluid',
                'alt' => 'Our Surgeons',
                'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px'
              ));
            } else {
              // Fallback if image ID can't be found
              echo '<img src="/wp-content/uploads/2025/04/surgeons-home.jpg" alt="Our Surgeons" class="img-fluid">';
            }
            ?>
          </div>
          <div class="card-content text-center">
            <h3 class="card-title">Meet Our Surgeons</h3>
            <p class="card-text">Our team of 26 board-certified specialists brings expertise and artistry to every procedure. Learn about their qualifications and specialties.</p>
            <a href="#surgeons" class="btn btn-primary">MEET THE TEAM</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Foundation Section with Fixed Background -->
<section class="foundation-section">
  <div class="foundation-bg" style="background-image: url('/wp-content/uploads/2025/04/miaf6.jpg');"></div>
  <div class="container position-relative">
    <div class="row">
      <div class="col-12">
        <div class="foundation-content text-center">
          <h2 class="section-heading text-white mb-2">The Mia Aesthetics Foundation</h2>
          <h3 class="section-subheading text-gold mb-4">Gives Back</h3>
          <p class="text-white mb-4">We're committed to making a positive impact in our communities through charitable initiatives, education, and outreach programs that help those in need.</p>
          <a href="/mia-foundation/" class="btn btn-primary">Learn More</a>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
// Wait for the page to be fully loaded
window.addEventListener('load', function() {
  // Function to initialize the tab navigation
  function initTabNavigation() {
    // Select elements using vanilla JS
    const tabsContainer = document.querySelector('.procedure-tabs-container');
    const tabsWrapper = document.querySelector('.tabs-wrapper');
    const tabsList = document.querySelector('.procedure-tabs');
    const prevArrow = document.querySelector('.prev-arrow');
    const nextArrow = document.querySelector('.next-arrow');
    const tabLinks = document.querySelectorAll('.procedure-tabs .nav-link');
    
    // Only run if all necessary elements exist
    if (tabsList && prevArrow && nextArrow && tabLinks.length) {
      
      // Force the arrows to be visible and clickable
      prevArrow.style.display = 'flex';
      nextArrow.style.display = 'flex';
      prevArrow.style.zIndex = '9999';
      nextArrow.style.zIndex = '9999';
      prevArrow.style.pointerEvents = 'auto';
      nextArrow.style.pointerEvents = 'auto';
      
      // Flag to prevent multiple clicks
      let isNavigating = false;
      
      // --- Direct click handlers for arrows ---
      function handlePrevArrowClick(e) {
        // Prevent multiple rapid clicks
        if (isNavigating) {
          return;
        }
        
        e.preventDefault(); // Prevent any default behavior
        e.stopPropagation(); // Stop event bubbling
        
        if (prevArrow.classList.contains('disabled')) {
          return;
        }
        
        const activeTab = document.querySelector('.procedure-tabs .nav-link.active');
        if (!activeTab) {
          return;
        }
        
        const activeTabItem = activeTab.parentElement;
        const prevTabItem = activeTabItem.previousElementSibling;
        
        if (prevTabItem) {
          const prevTabLink = prevTabItem.querySelector('.nav-link');
          
          // Set flag to prevent multiple clicks
          isNavigating = true;
          
          // Use Bootstrap's Tab API directly
          try {
            const tab = new bootstrap.Tab(prevTabLink);
            tab.show();
            
            // Reset flag after a short delay
            setTimeout(function() {
              isNavigating = false;
            }, 300);
          } catch (error) {
            // Fallback to direct click
            prevTabLink.click();
            
            // Reset flag after a short delay
            setTimeout(function() {
              isNavigating = false;
            }, 300);
          }
        }
      }
      
      function handleNextArrowClick(e) {
        // Prevent multiple rapid clicks
        if (isNavigating) {
          return;
        }
        
        e.preventDefault(); // Prevent any default behavior
        e.stopPropagation(); // Stop event bubbling
        
        if (nextArrow.classList.contains('disabled')) {
          return;
        }
        
        const activeTab = document.querySelector('.procedure-tabs .nav-link.active');
        if (!activeTab) {
          return;
        }
        
        const activeTabItem = activeTab.parentElement;
        const nextTabItem = activeTabItem.nextElementSibling;
        
        if (nextTabItem) {
          const nextTabLink = nextTabItem.querySelector('.nav-link');
          
          // Set flag to prevent multiple clicks
          isNavigating = true;
          
          // Use Bootstrap's Tab API directly
          try {
            const tab = new bootstrap.Tab(nextTabLink);
            tab.show();
            
            // Reset flag after a short delay
            setTimeout(function() {
              isNavigating = false;
            }, 300);
          } catch (error) {
            // Fallback to direct click
            nextTabLink.click();
            
            // Reset flag after a short delay
            setTimeout(function() {
              isNavigating = false;
            }, 300);
          }
        }
      }
      
      // Remove all existing event listeners
      prevArrow.replaceWith(prevArrow.cloneNode(true));
      nextArrow.replaceWith(nextArrow.cloneNode(true));
      
      // Get the new arrow elements after cloning
      const newPrevArrow = document.querySelector('.prev-arrow');
      const newNextArrow = document.querySelector('.next-arrow');
      
      // Add single click event listeners to the new elements
      newPrevArrow.addEventListener('click', handlePrevArrowClick);
      newNextArrow.addEventListener('click', handleNextArrowClick);
      
      // --- Arrow State Update Logic ---
      function updateArrowStates() {
        // First, enable both arrows by default
        prevArrow.classList.remove('disabled');
        nextArrow.classList.remove('disabled');
        
        // Then only disable based on active tab position
        const activeTab = document.querySelector('.procedure-tabs .nav-link.active');
        if (activeTab) {
          const activeTabItem = activeTab.parentElement;
          const allTabs = Array.from(tabsList.querySelectorAll('.nav-item'));
          const activeIndex = allTabs.indexOf(activeTabItem);
          
          // Only disable prev arrow if we're on the first tab
          if (activeIndex === 0) {
            prevArrow.classList.add('disabled');
          }
          
          // Only disable next arrow if we're on the last tab
          if (activeIndex === allTabs.length - 1) {
            nextArrow.classList.add('disabled');
          }
        }
      }
      
      // --- Ensure Active Tab is Visible ---
      function scrollActiveTabIntoView() {
        const activeTab = document.querySelector('.procedure-tabs .nav-link.active');
        if (!activeTab) {
          return;
        }
        
        const activeTabItem = activeTab.parentElement;
        const tabsRect = tabsList.getBoundingClientRect();
        const activeTabRect = activeTabItem.getBoundingClientRect();
        
        // Check if tab is out of view on the left
        if (activeTabRect.left < tabsRect.left) {
          tabsList.scrollLeft = tabsList.scrollLeft + (activeTabRect.left - tabsRect.left) - 10;
        }
        // Check if tab is out of view on the right
        else if (activeTabRect.right > tabsRect.right) {
          tabsList.scrollLeft = tabsList.scrollLeft + (activeTabRect.right - tabsRect.right) + 10;
        }
      }
      
      // --- Event Listeners ---
      
      // Update arrows when scrolled
      tabsList.addEventListener('scroll', function() {
        updateArrowStates();
      });
      
      // Handle tab changes
      tabLinks.forEach(function(tabLink) {
        tabLink.addEventListener('shown.bs.tab', function() {
          // Wait a tiny bit for the DOM to update
          setTimeout(function() {
            scrollActiveTabIntoView();
            updateArrowStates();
          }, 10);
        });
      });
      
      // Initialize arrow states and scroll position
      updateArrowStates();
      scrollActiveTabIntoView();
      
      // Handle window resize
      window.addEventListener('resize', function() {
        updateArrowStates();
        scrollActiveTabIntoView();
      });
    }
  }
  
  // Initialize immediately
  initTabNavigation();
  
  // Also initialize after a short delay to ensure everything is loaded
  setTimeout(initTabNavigation, 500);
  
  // And again after a longer delay just to be sure
  setTimeout(initTabNavigation, 2000);
});
</script>

<!-- Video Gallery Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Get all video thumbnails
  const videoThumbs = document.querySelectorAll('.video-thumb');
  const featuredVideoPlayer = document.getElementById('featured-video-player');
  
  // Add click event to each thumbnail
  videoThumbs.forEach(thumb => {
    thumb.addEventListener('click', function() {
      // Get video ID from data attribute
      const videoId = this.getAttribute('data-video-id');
      
      // Update the featured video src
      featuredVideoPlayer.src = `https://www.youtube.com/embed/${videoId}`;
      
      // Remove active class from all thumbnails
      videoThumbs.forEach(t => t.classList.remove('active'));
      
      // Add active class to clicked thumbnail
      this.classList.add('active');
    });
  });
  
  // Procedure Dropdown for Mobile
  const procedureDropdown = document.getElementById('procedureDropdown');
  if (procedureDropdown) {
    procedureDropdown.addEventListener('change', function() {
      // Get the selected tab content ID
      const selectedTabId = this.value;
      
      // Hide all tab panes
      const tabPanes = document.querySelectorAll('.tab-pane');
      tabPanes.forEach(pane => {
        pane.classList.remove('show', 'active');
      });
      
      // Show the selected tab pane
      const selectedPane = document.getElementById(selectedTabId);
      if (selectedPane) {
        selectedPane.classList.add('show', 'active');
      }
      
      // Update the desktop tabs to match (for when switching back to desktop view)
      const tabLinks = document.querySelectorAll('.procedure-tabs .nav-link');
      tabLinks.forEach(link => {
        link.classList.remove('active');
        link.setAttribute('aria-selected', 'false');
        
        // If this tab corresponds to the selected dropdown option
        if (link.getAttribute('data-bs-target') === '#' + selectedTabId) {
          link.classList.add('active');
          link.setAttribute('aria-selected', 'true');
        }
      });
    });
  }
});
</script>
</main>
<?php get_footer(); ?>
