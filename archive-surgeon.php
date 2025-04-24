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
            
            <?php 
            // Default WordPress loop will handle the query for this archive
            if (have_posts()) : 
            ?>
                <div class="row"> <?php // Bootstrap row for the grid ?>
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
                        
                        // Get surgeon headshot ID
                        $headshot_id = get_field('surgeon_headshot');
                    ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-4 <?php echo esc_attr($location_class); ?>"> <?php // Bootstrap column classes ?>
                            <div class="card surgeon-card shadow-sm border-0 h-100 text-center"> 
                                <div class="card-body d-flex flex-column align-items-center pt-4"> 
                                    <?php 
                                    // Display headshot if ID exists
                                    if($headshot_id && is_numeric($headshot_id)) : 
                                        echo wp_get_attachment_image(
                                            $headshot_id, 
                                            'thumbnail', // Use thumbnail size for small circular image
                                            false, 
                                            array(
                                                'class' => 'surgeon-archive-headshot rounded-circle mb-3', // Add classes for styling
                                                'alt' => get_the_title() . ' Headshot'
                                            )
                                        ); 
                                    // Fallback to post thumbnail if headshot field is empty but thumbnail exists
                                    elseif (has_post_thumbnail()) : 
                                        the_post_thumbnail('thumbnail', ['class' => 'surgeon-archive-headshot rounded-circle mb-3']);
                                    // Optional: Add a placeholder if no image is available
                                    // else :
                                    //    echo '<img src="https://placeholder.co/150x150" class="surgeon-archive-headshot rounded-circle mb-3" alt="Placeholder">';
                                    endif; 
                                    ?>
                                    
                                    <h2 class="h5 mb-1"> 
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    
                                    <?php if($location_title) : ?>
                                    <p class="text-muted small mb-0"> 
                                        <?php echo esc_html($location_title); ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
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
    
</main>

<?php get_footer(); ?>
