<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * This template is used when WordPress cannot find a page or post
 * that matches the requested URL.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary" class="site-main">
    <section class="error-404 not-found py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <!-- 404 Header -->
                    <div class="error-header mb-5">
                        <h1 class="display-1 fw-bold text-primary">404</h1>
                        <h2 class="h3 mb-4">Page Not Found</h2>
                        <p class="lead mb-5">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                    </div>

                    <!-- Search Form -->
                    <div class="search-form mb-5">
                        <h3 class="h5 mb-3">Search Our Website</h3>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Helpful Links -->
                    <div class="helpful-links mb-5">
                        <h3 class="h5 mb-3">You might find these links helpful:</h3>
                        <div class="row g-4 justify-content-center">
                            <!-- Home -->
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-home text-primary fs-3 mb-3"></i>
                                        <h4 class="h6 mb-3">Homepage</h4>
                                        <p class="small mb-3">Return to our homepage</p>
                                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-outline-primary btn-sm">Go Home</a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Procedures -->
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-list-alt text-primary fs-3 mb-3"></i>
                                        <h4 class="h6 mb-3">Procedures</h4>
                                        <p class="small mb-3">Browse our procedures</p>
                                        <a href="<?php echo esc_url(home_url('/procedures/')); ?>" class="btn btn-outline-primary btn-sm">View Procedures</a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact -->
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-envelope text-primary fs-3 mb-3"></i>
                                        <h4 class="h6 mb-3">Contact Us</h4>
                                        <p class="small mb-3">Get in touch with us</p>
                                        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-outline-primary btn-sm">Contact</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="back-button">
                        <button onclick="history.back()" class="btn btn-link text-decoration-none">
                            <i class="fas fa-arrow-left me-2"></i> Go Back to Previous Page
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
