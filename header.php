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
                <!-- Procedures Menu - Desktop (using refactored functions) -->
                <?php 
                $menu_structure = get_mia_menu_structure();
                render_procedures_menu($menu_structure['procedures'], false); 
                ?>
                <!-- Procedures Menu - Mobile (using refactored functions) -->
                <?php render_procedures_menu($menu_structure['procedures'], true); ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Non-Surgical
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/non-surgical/j-plasma-skin-tightening/')); ?>">J-Plasma</a></li>
                    <li><a class="dropdown-item" href="<?php echo esc_url(home_url('/weight-loss/')); ?>">Skinny Shot</a></li>
                  </ul>
                </li>
                <!-- Locations Menu (using refactored functions) -->
                <?php render_locations_menu(false); ?>
                <?php render_locations_menu(true); ?>
                <!-- Surgeons Menu (using refactored functions) -->
                <?php render_surgeons_menu(false); ?>
                <?php render_surgeons_menu(true); ?>
                <!-- Before & After Menu (using refactored functions) -->
                <?php render_before_after_menu(false); ?>
                <?php render_before_after_menu(true); ?>
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
