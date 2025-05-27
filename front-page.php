<?php
/**
 * The front page template file
 */
get_header(); ?>
<main id="primary" class="site-main">
<section class="hero-section">
  <div class="hero-wrapper">
<div class="row g-0">
  <!-- Left Column: Carousel -->
  <div class="col-md-8">
    <div class="ratio ratio-16x9 box-carousel position-relative">
      <div id="specialsCarousel" class="carousel slide w-100 h-100" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#specialsCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#specialsCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        </div>
        <div class="carousel-inner">
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
        <!-- Navigation is handled by the carousel indicators above -->
      </div>
    </div>
  </div>

  <!-- Right Column: Stacked Boxes (on desktop) / Side by Side (on mobile) -->
  <div class="col-md-4 d-flex flex-column flex-sm-column flex-xs-row">
    <!-- Top Box: Before & After -->
    <div class="box-top position-relative">
      <div class="ratio ratio-16x9">
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
      <div class="content-overlay p-2 p-md-4 d-flex flex-column justify-content-end align-items-center text-center">        <h4 class="responsive-heading">Before & After</h4>
        <div class="button-wrapper">
          <a href="/before-after/" class="mia-button" data-variant="hero" role="button">View Gallery <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>
    </div>

    <!-- Bottom Box: Financing -->
    <div class="box-bottom position-relative">
      <div class="ratio ratio-16x9">
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
        <h4 class="responsive-heading">Financing</h4>        <div class="button-wrapper">
          <a href="/financing/" class="mia-button" data-variant="hero" role="button" aria-label="Explore Financing Options">View Options <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>

  </div>
</section>

<!-- About Section with Video Carousel -->
<section class="about-section py-5">
  <div class="container">
    <div class="row align-items-center">
      <!-- Left Column: About Content -->
      <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="about-content">
          <p class="tagline mb-2">Professional Care You Can Trust</p>
          <h1 class="mb-4">About Mia Aesthetics</h1>
          <p class="mb-4">Our mission at Mia Aesthetics is to deliver the highest quality of plastic surgery at affordable prices, demonstrating that beauty and cost-effectiveness can coexist harmoniously.</p>
        </div>
      </div>
      
      <!-- Right Column: Video Carousel -->
      <div class="col-lg-6">
        <div class="video-carousel-container">
          <!-- Featured Video -->
          <div class="featured-video mb-3">
            <div class="ratio ratio-16x9" id="featured-video-container">
              <iframe id="featured-video-iframe" src="https://www.youtube.com/embed/OxigXlYTqH8" title="Featured Video" allowfullscreen></iframe>
            </div>
          </div>
          
          <!-- Thumbnail Carousel - All in one row -->
          <div class="row g-3" id="video-thumbnails-container">
            <div class="col-3 video-thumbnail-wrapper" data-video-id="OxigXlYTqH8">
              <div class="video-thumbnail ratio ratio-16x9" data-video-id="OxigXlYTqH8" role="button" tabindex="0" aria-label="Play featured video">
                <img src="https://img.youtube.com/vi/OxigXlYTqH8/mqdefault.jpg" alt="Video thumbnail" class="img-fluid w-100 h-100 object-fit-cover">
                <div class="video-play-overlay">
                  <i class="fa-solid fa-play"></i>
                </div>
              </div>
            </div>
            <div class="col-3 video-thumbnail-wrapper" data-video-id="sb8Kapy8mzU">
              <div class="video-thumbnail ratio ratio-16x9" data-video-id="sb8Kapy8mzU" role="button" tabindex="0" aria-label="Play video 2">
                <img src="https://img.youtube.com/vi/sb8Kapy8mzU/mqdefault.jpg" alt="Video thumbnail" class="img-fluid w-100 h-100 object-fit-cover">
                <div class="video-play-overlay">
                  <i class="fa-solid fa-play"></i>
                </div>
              </div>
            </div>
            <div class="col-3 video-thumbnail-wrapper" data-video-id="4-B_ISCne28">
              <div class="video-thumbnail ratio ratio-16x9" data-video-id="4-B_ISCne28" role="button" tabindex="0" aria-label="Play video 3">
                <img src="https://img.youtube.com/vi/4-B_ISCne28/mqdefault.jpg" alt="Video thumbnail" class="img-fluid w-100 h-100 object-fit-cover">
                <div class="video-play-overlay">
                  <i class="fa-solid fa-play"></i>
                </div>
              </div>
            </div>
            <div class="col-3 video-thumbnail-wrapper" data-video-id="ykz9Z8Kh3Yo">
              <div class="video-thumbnail ratio ratio-16x9" data-video-id="ykz9Z8Kh3Yo" role="button" tabindex="0" aria-label="Play video 4">
                <img src="https://img.youtube.com/vi/ykz9Z8Kh3Yo/mqdefault.jpg" alt="Video thumbnail" class="img-fluid w-100 h-100 object-fit-cover">
                <div class="video-play-overlay">
                  <i class="fa-solid fa-play"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Video Interaction Script -->
      <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Get all video thumbnails and their wrappers
        const videoThumbnails = document.querySelectorAll('.video-thumbnail');
        const thumbnailWrappers = document.querySelectorAll('.video-thumbnail-wrapper');
        const featuredVideoIframe = document.getElementById('featured-video-iframe');
        const thumbnailsContainer = document.getElementById('video-thumbnails-container');
        
        // Function to update the featured video and manage thumbnails
        function updateFeaturedVideo(videoId) {
          // Update the featured video iframe src
          featuredVideoIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
          
          // Show all thumbnail wrappers first
          thumbnailWrappers.forEach(wrapper => {
            wrapper.classList.remove('d-none');
            wrapper.classList.remove('selected-video');
          });
          
          // Mark the selected video with a class instead of hiding it
          thumbnailWrappers.forEach(wrapper => {
            if (wrapper.getAttribute('data-video-id') === videoId) {
              wrapper.classList.add('selected-video');
            }
          });
          
          // Update active state for thumbnails
          videoThumbnails.forEach(thumb => {
            thumb.classList.remove('active');
            if (thumb.getAttribute('data-video-id') === videoId) {
              thumb.classList.add('active');
            }
          });
        }
        
        // Set initial state - mark the default featured video
        updateFeaturedVideo('OxigXlYTqH8');
        
        // Add click event to each thumbnail
        videoThumbnails.forEach(thumbnail => {
          thumbnail.addEventListener('click', function() {
            // Get the video ID from the thumbnail
            const videoId = this.getAttribute('data-video-id');
            
            // Update the featured video and manage thumbnails
            updateFeaturedVideo(videoId);
          });
          
          // Add keyboard accessibility
          thumbnail.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              this.click();
            }
          });
        });
      });
      </script>
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-4 py-md-5">
  <div class="container">
    <!-- Top Content -->
    <div class="row mb-4 mb-md-5">
      <!-- Left Column: Tagline and Heading -->
      <div class="col-lg-6 mb-3 mb-lg-0">
        <div class="stats-heading">
          <p class="tagline mb-1 mb-md-2">Our Commitment</p>
          <h2 class="stats-heading-title">Trusted Surgical Excellence</h2>
        </div>
      </div>
      
      <!-- Right Column: Paragraph and Buttons -->
      <div class="col-lg-6">
        <div class="stats-content">          <p class="mb-3 mb-md-4 fs-6"> Delivering life-changing results with expert care at every step. Our team of highly skilled specialists, years of experience, and thousands of satisfied patients set us apart in the industry.</p>
          <div class="d-flex">
            <a href="/locations/" class="mia-button me-3" data-variant="black" role="button">Our Locations</a>
            <a href="/surgeons/" class="mia-button" data-variant="black" role="button">Our Surgeons</a>
          </div>
        </div>
      </div>
    </div>
    
<!-- Stats Row -->
<div class="row row-cols-2 row-cols-md-4 g-4 mt-4 mt-md-5">
  <!-- Stat Item 1: Founded Year -->
  <div class="col">
    <div class="position-relative ps-3 ps-md-4 mb-4">
      <div class="position-absolute start-0 top-0 stat-line-gold"></div>
      <h3 class="display-5 fw-bold text-white mb-2 lh-1" data-count="2018">0</h3>
      <p class="text-white opacity-75 mb-0 fs-6">Year Founded</p>
    </div>
  </div>
  
  <!-- Stat Item 2: Number of Surgeons (Dynamic) -->
  <div class="col">
    <div class="position-relative ps-3 ps-md-4 mb-4">
      <div class="position-absolute start-0 top-0 stat-line-gold"></div>
      <h3 class="display-5 fw-bold text-white mb-2 lh-1" data-count="<?php
        // Count top-level surgeon posts
        $surgeon_count = new WP_Query(array(
          'post_type' => 'surgeon',
          'post_parent' => 0,
          'posts_per_page' => -1,
          'fields' => 'ids'
        ));
        echo $surgeon_count->post_count ? $surgeon_count->post_count : '27';
        ?>">0</h3>
      <p class="text-white opacity-75 mb-0 fs-6">Expert Surgeons</p>
    </div>
  </div>
  
  <!-- Stat Item 3: Number of Locations (Dynamic) -->
  <div class="col">
    <div class="position-relative ps-3 ps-md-4 mb-4">
      <div class="position-absolute start-0 top-0 stat-line-gold"></div>
      <h3 class="display-5 fw-bold text-white mb-2 lh-1" data-count="<?php
        // Count top-level location posts
        $location_count = new WP_Query(array(
          'post_type' => 'location',
          'post_parent' => 0,
          'posts_per_page' => -1,
          'fields' => 'ids'
        ));
        echo $location_count->post_count ? $location_count->post_count : '13';
        ?>">0</h3>
      <p class="text-white opacity-75 mb-0 fs-6">Clinic Locations</p>
    </div>
  </div>
  
  <!-- Stat Item 4: Total Patients -->
  <div class="col">
    <div class="position-relative ps-3 ps-md-4 mb-4">
      <div class="position-absolute start-0 top-0 stat-line-gold"></div>
      <h3 class="display-5 fw-bold text-white mb-2 lh-1" data-count="150" data-suffix=",000 +">0</h3>
      <p class="text-white opacity-75 mb-0 fs-6">Satisfied Patients</p>
    </div>
  </div>
</div>
  </div>
</section>

<!-- Stats Count-Up Animation Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Get all stat number elements - updated selector for Bootstrap structure
  const statNumbers = document.querySelectorAll('.display-5[data-count]');
  
  // Function to animate counting up
  function animateCountUp(element, target, duration, steps, suffix = '') {
    // Start from 0
    let start = 0;
    let currentStep = 0;
    
    // Get the element's parent to identify the patients counter and year
    const parentText = element.closest('.position-relative').querySelector('p').textContent;
    const isPatients = parentText.includes('Patient');
    const isYear = parentText.includes('Year');
    
    // Calculate the value increment per step
    const valueIncrement = target / steps;
    
    // Set initial text to prevent wrapping on two lines
    if (isPatients) {
      element.textContent = "0";
      element.style.minWidth = "8ch"; // Reserve more space for "150,000+"
    }
    
    const timer = setInterval(() => {
      currentStep++;
      
      // If we've reached the final step, set the final value and clear the interval
      if (currentStep >= steps) {
        clearInterval(timer);
        
        // Format as "150,000+" for patients counter
        if (isPatients) {
          element.textContent = "150,000+";
          // Keep the min-width to prevent layout shifts
        } else if (isYear) {
          // Don't add commas to years
          element.textContent = target + suffix;
        } else {
          element.textContent = target.toLocaleString('en-US') + suffix;
        }
      } else {
        // Otherwise, update with the current count based on step
        start = valueIncrement * currentStep;
        const currentValue = Math.floor(start);
        
        // Format differently based on the type of counter
        if (isPatients) {
          if (currentValue >= target) {
            element.textContent = "150,000+";
          } else {
            element.textContent = currentValue.toLocaleString('en-US') + suffix;
          }
        } else if (isYear) {
          // Don't add commas to years
          element.textContent = currentValue + suffix;
        } else {
          element.textContent = currentValue.toLocaleString('en-US') + suffix;
        }
      }
    }, duration / steps);
  }
  
  // Create an Intersection Observer
  const observer = new IntersectionObserver((entries) => {
    // If any stat number is in the viewport, animate all of them
    if (entries.some(entry => entry.isIntersecting)) {
      // Fixed duration for all animations
      const duration = 2000; // 2 seconds for all numbers
      const steps = 50; // Number of steps in the animation
      
      // Start animation for all stat numbers
      statNumbers.forEach(statNumber => {
        const target = parseInt(statNumber.getAttribute('data-count'), 10);
        const suffix = statNumber.getAttribute('data-suffix') || '';
        
        // Start the animation
        animateCountUp(statNumber, target, duration, steps, suffix);
        
        // Unobserve all elements so the animation only happens once
        observer.unobserve(statNumber);
      });
    }
  }, { threshold: 0.5 }); // Trigger when at least 50% of the element is visible
  
  // Observe each stat number element
  statNumbers.forEach(statNumber => {
    observer.observe(statNumber);
  });
});
</script>


<!-- How Payments Work Section -->
<section class="how-payments-work-section py-5">
  <div class="container-fluid">
    <div class="row align-items-stretch">
      <!-- Left Column: Carousel -->
      <div class="col-xl-6 d-flex">
        <div id="paymentsCarousel" class="carousel slide carousel-fade flex-fill" data-bs-ride="carousel">
          <div class="carousel-inner h-100">
            <div class="carousel-item active h-100">
              <img src="/wp-content/uploads/2025/05/or-view.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Operating Room View">
            </div>
            <div class="carousel-item h-100">
              <img src="/wp-content/uploads/2025/05/mia-clinic.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Mia Clinic">
            </div>
            <div class="carousel-item h-100">
              <img src="/wp-content/uploads/2025/05/surgery-1.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Surgery Room">
            </div>
            <div class="carousel-item h-100">
              <img src="/wp-content/uploads/2025/05/mia-staff-miami.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Mia Staff Miami">
            </div>
          </div>
        </div>
      </div>
      
      <!-- Right Column: Content -->
      <div class="col-xl-6 d-flex align-items-center">
        <div class="payments-content w-100 ps-lg-5">
          <div class="mb-5">
            <h2 class="section-heading mb-2 fs-1">How Payments Work</h2>
            <h3 class="section-subheading mb-4 fs-4">at Mia Aesthetics</h3>
          </div>
          <div class="row row-cols-1 row-cols-sm-2 g-4">
            <div class="col">
              <div class="payment-step-card h-100">
                <h4 class="mb-3">1. Research Surgeons</h4>
                <ul class="mb-0">
                  <li>Choose from over 25 experienced surgeons</li>
                  <li>Review surgeon bios on our website</li>
                  <li>Browse our before and after photos</li>
                </ul>
              </div>
            </div>
            <div class="col">
              <div class="payment-step-card h-100">
                <h4 class="mb-3">2. Check Specials</h4>
                <ul class="mb-0">
                  <li>Contact a sales coordinator for specials</li>
                  <li>New surgeons often offer reduced rates</li>
                  <li>Keep an eye on seasonal specials</li>
                </ul>
              </div>
            </div>
            <div class="col">
              <div class="payment-step-card h-100">
                <h4 class="mb-3">3. Lock in Your Price</h4>
                <ul class="mb-0">
                  <li>Call us to secure a special price</li>
                  <li>Booking fee required to lock in price</li>
                  <li>Price valid for 12 months</li>
                </ul>
              </div>
            </div>
            <div class="col">
              <div class="payment-step-card h-100">
                <h4 class="mb-3">4. Complete Payments</h4>
                <ul class="mb-0">
                  <li>Pay at your own pace</li>
                  <li>Contact your patient concierge</li>
                  <li>Ask for contract extensions if needed</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section Divider -->
<div class="section-divider my-5"></div>

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
          <div class="col-12 col-xl-6 order-xl-2 mb-4 mb-xl-0">
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
          <div class="col-12 col-xl-6 order-xl-1">
            <h3 class="procedure-title mb-3">Body Contouring</h3>
            <p class="section-subheading mb-4">Sculpt Your Ideal Figure</p>
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
              <a href="#body-procedures" class="procedure-link">
                <i class="fa-solid fa-arrow-right"></i> View All Body Procedures
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Breast Content -->
      <div class="tab-pane fade" id="breast-content" role="tabpanel" aria-labelledby="breast-tab">
        <div class="row align-items-center">
          <div class="col-12 col-xl-6 order-xl-2 mb-4 mb-xl-0">
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
          <div class="col-12 col-xl-6 order-xl-1">
            <h3 class="procedure-title mb-3">Breast Enhancement</h3>
            <p class="section-subheading mb-4">Achieve Your Desired Look</p>
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
              <a href="#breast-procedures" class="procedure-link">
                <i class="fa-solid fa-arrow-right"></i> View All Breast Procedures
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Face Content -->
      <div class="tab-pane fade" id="face-content" role="tabpanel" aria-labelledby="face-tab">
        <div class="row align-items-center">
          <div class="col-12 col-xl-6 order-xl-2 mb-4 mb-xl-0">
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
          <div class="col-12 col-xl-6 order-xl-1">
            <h3 class="procedure-title mb-3">Facial Rejuvenation</h3>
            <p class="section-subheading mb-4">Enhance Your Natural Beauty</p>
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
              <a href="#facial-procedures" class="procedure-link">
                <i class="fa-solid fa-arrow-right"></i> View All Facial Procedures
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Non-Surgical Content -->
      <div class="tab-pane fade" id="nonsurgical-content" role="tabpanel" aria-labelledby="nonsurgical-tab">
        <div class="row align-items-center">
          <div class="col-12 col-xl-6 order-xl-2 mb-4 mb-xl-0">
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
          <div class="col-12 col-xl-6 order-xl-1">
            <h3 class="procedure-title mb-3">Non-Surgical Treatments</h3>
            <p class="section-subheading mb-4">Rejuvenate Without Surgery</p>
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
              <a href="#non-surgical" class="procedure-link">
                <i class="fa-solid fa-arrow-right"></i> View All Non-Surgical Treatments
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Men Content -->
      <div class="tab-pane fade" id="men-content" role="tabpanel" aria-labelledby="men-tab">
        <div class="row align-items-center">
          <div class="col-12 col-xl-6 order-xl-2 mb-4 mb-xl-0">
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
          <div class="col-12 col-xl-6 order-xl-1">
            <h3 class="procedure-title mb-3">Men's Procedures</h3>
            <p class="section-subheading mb-4">Tailored Solutions for Men</p>
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
              <a href="#men-procedures" class="procedure-link">
                <i class="fa-solid fa-arrow-right"></i> View All Men's Procedures
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Foundation Section with Fixed Background (Desktop) / Regular Image (Mobile) -->
<section class="foundation-section">
  <!-- Background image for desktop -->
  <div class="foundation-bg d-none d-md-block" style="background-image: url('/wp-content/uploads/2025/04/miaf6.jpg');"></div>
  
  <!-- Regular image for mobile -->
  <div class="foundation-mobile-img d-md-none">
    <img src="/wp-content/uploads/2025/04/miaf6.jpg" alt="Mia Aesthetics Foundation" class="img-fluid w-100">
  </div>
  
  <div class="container position-relative">
    <div class="row">
      <div class="col-12">
        <div class="foundation-content text-center">
          <h2 class="section-heading text-white mb-2">The Mia Aesthetics Foundation</h2>
          <h3 class="section-subheading mb-4">Gives Back</h3>          <p class="text-white mb-4">We're committed to making a positive impact in our communities through charitable initiatives, education, and outreach programs that help those in need.</p>
          <a href="/mia-foundation/" class="mia-button" data-variant="black" role="button" aria-label="Discover Mia Aesthetics Foundation charitable work"> See Our Impact <i class="fa-solid fa-arrow-right"></i></a>
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
    if (tabsList && tabLinks.length) {
      // Handle tab changes to ensure active tab is visible
      tabLinks.forEach(function(tabLink) {
        tabLink.addEventListener('shown.bs.tab', function() {
          // Scroll the active tab into view using the native scroll-snap
          const activeTab = document.querySelector('.procedure-tabs .nav-link.active');
          if (activeTab) {
            const activeTabItem = activeTab.parentElement;
            // Prevent unwanted vertical scroll by only scrolling horizontally
            if (tabsList && typeof tabsList.scrollLeft !== 'undefined') {
              const tabRect = activeTabItem.getBoundingClientRect();
              const listRect = tabsList.getBoundingClientRect();
              // Only scroll if tab is out of horizontal view
              if (tabRect.left < listRect.left || tabRect.right > listRect.right) {
                activeTabItem.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center', scrollMode: 'if-needed' });
              }
            }
          }
        });
      });
      
      // Initialize - make sure active tab is visible
      const activeTab = document.querySelector('.procedure-tabs .nav-link.active');
      if (activeTab) {
        const activeTabItem = activeTab.parentElement;
        // Prevent unwanted vertical scroll by only scrolling horizontally
        if (tabsList && typeof tabsList.scrollLeft !== 'undefined') {
          const tabRect = activeTabItem.getBoundingClientRect();
          const listRect = tabsList.getBoundingClientRect();
          // Only scroll if tab is out of horizontal view
          if (tabRect.left < listRect.left || tabRect.right > listRect.right) {
            activeTabItem.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center', scrollMode: 'if-needed' });
          }
        }
      }
      
      // Optional: Add arrow navigation for accessibility
      if (prevArrow && nextArrow) {
        // Simple arrow navigation that works with the native scroll behavior
        prevArrow.addEventListener('click', function() {
          tabsList.scrollBy({ left: -100, behavior: 'smooth' });
        });
        
        nextArrow.addEventListener('click', function() {
          tabsList.scrollBy({ left: 100, behavior: 'smooth' });
        });
      }
    }
  }
  
  // Initialize immediately
  initTabNavigation();
  
  // Also initialize after a short delay to ensure everything is loaded
  setTimeout(initTabNavigation, 500);
});
</script>

<!-- Procedure Dropdown for Mobile Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Procedure Dropdown for Mobile
  const procedureDropdown = document.getElementById('procedureDropdown');
  
  // Function to show the selected tab content
  function showSelectedTabContent(selectedTabId) {
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
  }
  
  // Initialize the dropdown change event
  if (procedureDropdown) {
    // Set initial state - ensure the first tab content is visible
    const initialTabId = procedureDropdown.value;
    showSelectedTabContent(initialTabId);
    
    // Handle dropdown change event
    procedureDropdown.addEventListener('change', function() {
      const selectedTabId = this.value;
      showSelectedTabContent(selectedTabId);
    });
    
    // Force a change event on page load to ensure content is visible
    const event = new Event('change');
    procedureDropdown.dispatchEvent(event);
  }
  
  // Also handle tab clicks for desktop view
  const tabLinks = document.querySelectorAll('.procedure-tabs .nav-link');
  tabLinks.forEach(link => {
    link.addEventListener('click', function() {
      const targetId = this.getAttribute('data-bs-target').substring(1); // Remove the # character
      
      // Update the dropdown value to match (for when switching to mobile view)
      if (procedureDropdown) {
        procedureDropdown.value = targetId;
      }
    });
  });
});
</script>

</main>
<?php get_footer(); ?>
