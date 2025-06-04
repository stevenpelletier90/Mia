<?php
/**
 * Hero Section Template
 * 
 * Clean implementation with proper aspect ratios:
 * - Carousel: 1920x1080 (16:9)
 * - Boxes: 1600x900 (16:9) 
 */
?>

<section class="hero-section">
  <div class="hero-container">
    <!-- Carousel Section (1920x1080) -->
    <div class="hero-carousel">
      <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true" data-bs-interval="5000">
        <!-- Indicators -->
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        </div>
        
        <!-- Slides -->
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="/wp-content/uploads/2025/04/image-homepage16-9.jpg" 
                 srcset="/wp-content/uploads/2025/04/image-homepage16-9-300x169.jpg 300w,
                         /wp-content/uploads/2025/04/image-homepage16-9-768x432.jpg 768w,
                         /wp-content/uploads/2025/04/image-homepage16-9-1024x576.jpg 1024w,
                         /wp-content/uploads/2025/04/image-homepage16-9-1536x864.jpg 1536w,
                         /wp-content/uploads/2025/04/image-homepage16-9.jpg 1920w"
                 sizes="(max-width: 480px) 100vw, (max-width: 767px) 100vw, 66vw"
                 width="1920"
                 height="1080"
                 class="d-block w-100" 
                 alt="Slide 1" 
                 fetchpriority="high">
          </div>
          <div class="carousel-item">
            <img src="/wp-content/uploads/2025/04/non-surgical-home-banner.jpg"
                 srcset="/wp-content/uploads/2025/04/non-surgical-home-banner-300x169.jpg 300w,
                         /wp-content/uploads/2025/04/non-surgical-home-banner-768x432.jpg 768w,
                         /wp-content/uploads/2025/04/non-surgical-home-banner-1024x576.jpg 1024w,
                         /wp-content/uploads/2025/04/non-surgical-home-banner-1536x864.jpg 1536w,
                         /wp-content/uploads/2025/04/non-surgical-home-banner.jpg 1920w"
                 sizes="(max-width: 480px) 100vw, (max-width: 767px) 100vw, 66vw"
                 width="1920"
                 height="1080"
                 class="d-block w-100" 
                 alt="Slide 2"
                 loading="lazy">
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar with Two Boxes (1600x900 each) -->
    <div class="hero-sidebar">
      <!-- Before & After Box -->
      <div class="hero-box hero-box-top">
        <img src="/wp-content/uploads/2025/04/before-after-banner.jpg"
             srcset="/wp-content/uploads/2025/04/before-after-banner-300x169.jpg 300w,
                     /wp-content/uploads/2025/04/before-after-banner-768x432.jpg 768w,
                     /wp-content/uploads/2025/04/before-after-banner-1024x576.jpg 1024w,
                     /wp-content/uploads/2025/04/before-after-banner-1536x864.jpg 1536w,
                     /wp-content/uploads/2025/04/before-after-banner.jpg 1600w"
             sizes="(max-width: 480px) 50vw, (max-width: 767px) 50vw, 33vw"
             width="1600"
             height="900"
             class="hero-box-image" 
             alt="Before & After"
             loading="lazy">
        <div class="hero-box-overlay">
          <h4 class="hero-box-title">Before & After</h4>
          <a href="/before-after/" class="mia-button" data-variant="hero">
            View Gallery <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Financing Box -->
      <div class="hero-box hero-box-bottom">
        <img src="/wp-content/uploads/2025/04/financing-home-banner.jpg"
             srcset="/wp-content/uploads/2025/04/financing-home-banner-300x169.jpg 300w,
                     /wp-content/uploads/2025/04/financing-home-banner-768x432.jpg 768w,
                     /wp-content/uploads/2025/04/financing-home-banner-1024x576.jpg 1024w,
                     /wp-content/uploads/2025/04/financing-home-banner-1536x864.jpg 1536w,
                     /wp-content/uploads/2025/04/financing-home-banner.jpg 1600w"
             sizes="(max-width: 480px) 50vw, (max-width: 767px) 50vw, 33vw"
             width="1600"
             height="900"
             class="hero-box-image" 
             alt="Financing"
             loading="lazy">
        <div class="hero-box-overlay">
          <h4 class="hero-box-title">Financing</h4>
          <a href="/financing/" class="mia-button" data-variant="hero">
            View Options <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
