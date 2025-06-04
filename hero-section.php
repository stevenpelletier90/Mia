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
      <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        </div>
        
        <!-- Slides -->
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="https://placehold.co/1920x1080/ff69b4/ffffff?text=Slide+1" class="d-block w-100" alt="Slide 1">
          </div>
          <div class="carousel-item">
            <img src="https://placehold.co/1920x1080/00bfff/ffffff?text=Slide+2" class="d-block w-100" alt="Slide 2">
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar with Two Boxes (1600x900 each) -->
    <div class="hero-sidebar">
      <!-- Before & After Box -->
      <div class="hero-box hero-box-top">
        <img src="https://placehold.co/1600x900/32cd32/ffffff?text=Before+%26+After" class="hero-box-image" alt="Before & After">
        <div class="hero-box-overlay">
          <h4 class="hero-box-title">Before & After</h4>
          <a href="/before-after/" class="mia-button" data-variant="hero">
            View Gallery <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Financing Box -->
      <div class="hero-box hero-box-bottom">
        <img src="https://placehold.co/1600x900/ffa500/ffffff?text=Financing" class="hero-box-image" alt="Financing">
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
