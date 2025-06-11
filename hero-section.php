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
            <?php 
            $slide1_data = mia_get_responsive_image_data('image-homepage16-9.jpg', '2025/04');
            if ($slide1_data): ?>
            <img src="<?php echo esc_url($slide1_data['src']); ?>" 
                 srcset="<?php echo esc_attr($slide1_data['srcset']); ?>"
                 sizes="(max-width: 480px) 100vw, (max-width: 767px) 100vw, 66vw"
                 width="1920"
                 height="1080"
                 class="d-block w-100" 
                 alt="Mia Aesthetics homepage banner showcasing plastic surgery procedures" 
                 fetchpriority="high">
            <?php else: ?>
            <div class="carousel-placeholder bg-secondary d-flex align-items-center justify-content-center" style="height: 1080px;">
              <span class="text-white">Slide 1 Image Not Found</span>
            </div>
            <?php endif; ?>
          </div>
          <div class="carousel-item">
            <?php 
            $slide2_data = mia_get_responsive_image_data('non-surgical-home-banner.jpg', '2025/04');
            if ($slide2_data): ?>
            <img src="<?php echo esc_url($slide2_data['src']); ?>"
                 srcset="<?php echo esc_attr($slide2_data['srcset']); ?>"
                 sizes="(max-width: 480px) 100vw, (max-width: 767px) 100vw, 66vw"
                 width="1920"
                 height="1080"
                 class="d-block w-100" 
                 alt="Non-surgical treatments and aesthetic procedures at Mia Aesthetics"
                 loading="lazy">
            <?php else: ?>
            <div class="carousel-placeholder bg-secondary d-flex align-items-center justify-content-center" style="height: 1080px;">
              <span class="text-white">Slide 2 Image Not Found</span>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar with Two Boxes (1600x900 each) -->
    <div class="hero-sidebar">
      <!-- Before & After Box -->
      <div class="hero-box hero-box-top">
        <?php 
        $before_after_data = mia_get_responsive_image_data('before-after-banner.jpg', '2025/04');
        if ($before_after_data): ?>
        <img src="<?php echo esc_url($before_after_data['src']); ?>"
             srcset="<?php echo esc_attr($before_after_data['srcset']); ?>"
             sizes="(max-width: 480px) 50vw, (max-width: 767px) 50vw, 33vw"
             width="1600"
             height="900"
             class="hero-box-image" 
             alt="Before & After"
             loading="lazy">
        <?php else: ?>
        <div class="hero-box-placeholder bg-secondary d-flex align-items-center justify-content-center" style="height: 900px;">
          <span class="text-white">Before & After Image Not Found</span>
        </div>
        <?php endif; ?>
        <div class="hero-box-overlay">
          <div class="hero-box-title">Before & After</div>
          <a href="<?php echo esc_url(home_url('/before-after/')); ?>" class="mia-button" data-variant="hero">
            View Gallery <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>

      <!-- Financing Box -->
      <div class="hero-box hero-box-bottom">
        <?php 
        $financing_data = mia_get_responsive_image_data('financing-home-banner.jpg', '2025/04');
        if ($financing_data): ?>
        <img src="<?php echo esc_url($financing_data['src']); ?>"
             srcset="<?php echo esc_attr($financing_data['srcset']); ?>"
             sizes="(max-width: 480px) 50vw, (max-width: 767px) 50vw, 33vw"
             width="1600"
             height="900"
             class="hero-box-image" 
             alt="Financing"
             loading="lazy">
        <?php else: ?>
        <div class="hero-box-placeholder bg-secondary d-flex align-items-center justify-content-center" style="height: 900px;">
          <span class="text-white">Financing Image Not Found</span>
        </div>
        <?php endif; ?>
        <div class="hero-box-overlay">
          <div class="hero-box-title">Financing</div>
          <a href="<?php echo esc_url(home_url('/financing/')); ?>" class="mia-button" data-variant="hero">
            View Options <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
