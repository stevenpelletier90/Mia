<?php
/**
 * Surgeons Archive Template
 * Custom template for displaying all surgeons in a more organized layout
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
                    <p class="lead">Meet our team of board-certified plastic surgeons dedicated to providing exceptional care and results.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Archive Content -->
    <section class="py-5">
        <div class="container">
            <!-- Introduction Text -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <p>Our surgeons are leaders in the field of aesthetic plastic surgery, combining artistry and technical expertise to deliver beautiful, natural-looking results. Each surgeon brings unique specializations and approaches to help you achieve your aesthetic goals.</p>
                </div>
            </div>
            
            <?php if (have_posts()) : ?>
                <!-- Location Filters -->
                <div class="surgeon-filters mb-4">
                    <div class="d-flex flex-wrap justify-content-center">
                        <button class="btn btn-outline-primary m-1 active" data-filter="all">All Locations</button>
                        <?php
                        // Get unique locations
                        $locations = array();
                        $temp_query = $wp_query;
                        while(have_posts()) : the_post();
                            $location = get_field('surgeon_location');
                            if($location && is_numeric($location)) {
                                $location_id = intval($location);
                                $location_title = get_the_title($location_id);
                                if(!isset($locations[$location_id]) && !empty($location_title)) {
                                    $locations[$location_id] = $location_title;
                                }
                            }
                        endwhile;
                        rewind_posts(); // Reset the query
                        
                        // Output location filter buttons
                        foreach($locations as $location_id => $location_title) {
                            echo '<button class="btn btn-outline-primary m-1" data-filter="location-' . esc_attr($location_id) . '">' . esc_html($location_title) . '</button>';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="surgeon-grid">
                    <?php while (have_posts()) : the_post(); 
                        // Get surgeon location if available
                        $location = get_field('surgeon_location');
                        $location_id = '';
                        $location_title = '';
                        $location_class = '';
                        
                        if($location && is_numeric($location)) {
                            $location_id = intval($location);
                            $location_title = get_the_title($location_id);
                            $location_class = 'location-' . $location_id;
                        }
                    ?>
                        <div class="surgeon-item <?php echo esc_attr($location_class); ?>">
                            <div class="card surgeon-card shadow-sm border-0 h-100">
                                <?php if($location_title) : ?>
                                <div class="location-badge">
                                    <span><?php echo esc_html($location_title); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="surgeon-image-container">
                                        <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body d-flex flex-column">
                                    <h2 class="h4 mb-2">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    
                                    <p class="text-muted mb-3">Plastic Surgeon</p>
                                    
                                    <div class="mb-3">
                                        <span class="badge bg-primary me-1 mb-1">Board Certified</span>
                                        <?php 
                                        // Display specialties if they exist
                                        $specialties = get_field('specialties');
                                        if($specialties) {
                                            $specialties_array = explode(',', $specialties);
                                            foreach($specialties_array as $specialty) {
                                                echo '<span class="badge bg-secondary me-1 mb-1">' . trim($specialty) . '</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    
                                    <div class="surgeon-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    
                                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary mt-auto">View Profile</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="row mt-5">
                    <div class="col">
                        <?php the_posts_pagination([
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'class' => 'pagination justify-content-center',
                        ]); ?>
                    </div>
                </div>

            <?php else : ?>
                <div class="row">
                    <div class="col text-center">
                        <p>No surgeons found.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="h3 mb-4">Schedule Your Consultation Today</h2>
                    <p class="mb-4">Take the first step toward achieving your aesthetic goals by scheduling a consultation with one of our expert surgeons.</p>
                    <a href="/consultation" class="btn btn-primary">Book a Consultation</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Script -->
    <script>
    jQuery(document).ready(function($) {
        // Location filter functionality
        $('.surgeon-filters .btn').on('click', function() {
            // Update active button
            $('.surgeon-filters .btn').removeClass('active');
            $(this).addClass('active');
            
            // Get filter value
            var filterValue = $(this).data('filter');
            
            // Show/hide surgeons based on filter
            if (filterValue === 'all') {
                $('.surgeon-item').show();
            } else {
                $('.surgeon-item').hide();
                $('.surgeon-item.' + filterValue).show();
            }
            
            // Animate items
            $('.surgeon-item:visible').each(function(i) {
                var item = $(this);
                setTimeout(function() {
                    item.addClass('animated');
                }, i * 100);
            });
        });
        
        // Initial animation
        $('.surgeon-item').each(function(i) {
            var item = $(this);
            setTimeout(function() {
                item.addClass('animated');
            }, i * 100);
        });
    });
    </script>
</main>

<?php get_footer(); ?>
