<?php
/**
 * Location Archive Template
 * Displays all locations in alphabetical order with CTA sections
 */

get_header(); ?>

<main>
    <div class="container">
        <?php
        if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
        }
        ?>
    </div>
    
    <!-- Archive Header -->
    <header class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1><?php post_type_archive_title(); ?></h1>
                    <p class="lead">Find a Mia Aesthetics location near you and schedule your consultation today.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Archive Content -->
    <section class="location-archive-section py-5">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="row g-5">
                    <?php while (have_posts()) : the_post(); 
                        // Get location data
                        $location_address = get_field('location_address');
                        $phone_number = get_field('phone_number');
                        $location_maps_link = get_field('location_maps_link');
                    ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="location-card h-100 shadow-sm rounded overflow-hidden">
                                <div class="location-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium_large', ['class' => 'img-fluid w-100']); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="location-content p-4">
                                    <h2 class="h4 mb-3">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                            Mia Aesthetics <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    
                                    <div class="location-details mb-3">
                                        <?php if ($location_address): ?>
                                            <div class="location-detail mb-2">
                                                <div class="d-flex align-items-start">
                                                    <i class="fas fa-map-marker-alt me-2 mt-1 location-icon"></i>
                                                    <span><?php echo esc_html($location_address); ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($phone_number): ?>
                                            <div class="location-detail mb-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-phone me-2 location-icon"></i>
                                                    <a href="tel:<?php echo esc_attr($phone_number); ?>" class="location-phone text-decoration-none">
                                                        <?php echo esc_html($phone_number); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="location-cta-buttons d-flex flex-column gap-2">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-primary d-flex justify-content-between align-items-center">
                                            View Location <i class="fas fa-arrow-right"></i>
                                        </a>
                                        
                                        <?php if ($location_maps_link): ?>
                                            <a href="<?php echo esc_url($location_maps_link); ?>" class="btn btn-outline-primary d-flex justify-content-between align-items-center" target="_blank">
                                                Get Directions <i class="fas fa-map"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($phone_number): ?>
                                            <a href="tel:<?php echo esc_attr($phone_number); ?>" class="btn btn-outline-primary d-flex justify-content-between align-items-center">
                                                Call Now <i class="fas fa-phone"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

            <?php else : ?>
                <div class="row">
                    <div class="col">
                        <div class="alert alert-info">
                            <p class="mb-0">No locations found. Please check back soon for updates.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
