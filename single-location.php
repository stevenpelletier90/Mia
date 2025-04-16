<?php
get_header(); 
?>

<main>
    <div class="container">
        <?php
        if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
        }
        ?>
    </div>

    <!-- Hero Section -->
    <header class="location-header py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Mia Aesthetics <?php echo get_the_title(); ?></h1>
                    
                    <!-- Quick Info Bar -->
                    <div class="location-info mb-4">
                        <!-- Address -->
                        <?php $location_address = get_field('location_address'); ?>
                        <?php if ($location_address): ?>
                            <div class="location-detail">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-map-marker-alt location-icon"></i>
                                    <span><?php echo esc_html($location_address); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Phone Number -->
                        <?php $phone_number = get_field('phone_number'); ?>
                        <?php if ($phone_number): ?>
                            <div class="location-detail">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-phone location-icon"></i>
                                    <a href="tel:<?php echo esc_attr($phone_number); ?>" class="location-phone">
                                        <?php echo esc_html($phone_number); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Hours of Operation -->
                        <?php $hours_operation = get_field('hours_operation'); ?>
                        <?php if ($hours_operation): ?>
                            <div class="location-detail">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-clock location-icon"></i>
                                    <span><?php echo esc_html($hours_operation); ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="location-detail">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-clock location-icon"></i>
                                    <span>Mon-Fri: 9am-5pm | Sat: 10am-2pm | Sun: Closed</span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Google Maps Link -->
                        <?php $location_maps_link = get_field('location_maps_link'); ?>
                        <?php if ($location_maps_link): ?>
                            <div class="location-directions mt-3">
                                <a href="<?php echo esc_url($location_maps_link); ?>" class="btn btn-primary" target="_blank">
                                    Get Directions
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Embedded Video -->
                <div class="col-lg-6">
                    <div class="video-container mb-4">
                        <?php 
                        $video_url = get_field('video_url');
                        $video_title = get_field('video_title');
                        
                        if ($video_url):
                            // Convert "watch?v=" to "embed/" if needed
                            if (strpos($video_url, 'watch?v=') !== false) {
                                $video_url = str_replace('watch?v=', 'embed/', $video_url);
                            }
                        ?>
                            <div class="ratio ratio-16x9 shadow rounded overflow-hidden">
                                <iframe 
                                    src="<?php echo esc_url($video_url); ?>" 
                                    title="<?php echo esc_attr($video_title ? $video_title : 'YouTube Video'); ?>" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                ></iframe>
                            </div>
                            <?php if ($video_title): ?>
                                <p class="mt-2 text-center"><strong>Video Title:</strong> <?php echo esc_html($video_title); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-center text-muted">No video available for this location.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <article class="py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content Column - Using WordPress default content -->
                <div class="col-lg-8">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="location-content">
                            <?php the_content(); ?>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Contact Card -->
                    <div class="card consultation-card mb-4 shadow-sm">
                        <div class="card-body p-3">
                            <h3 class="h5 mb-3 text-center">Schedule a Consultation</h3>
                            <?php gravity_form(1, false, false, false, '', true); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <!-- Team Section -->
    <section class="team-section py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">Our <?php echo get_the_title(); ?> Team</h2>
            <div class="row g-4">
                <?php
                $args = array(
                    'post_type' => 'surgeon',
                    'meta_query' => array(
                        array(
                            'key' => 'surgeon_location',
                            'value' => get_the_ID(),
                            'compare' => '='
                        )
                    )
                );
                $surgeons = new WP_Query($args);

                if ($surgeons->have_posts()) :
                    while ($surgeons->have_posts()) : $surgeons->the_post(); ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="surgeon-card card border-0 shadow-sm h-100">
                                <?php if (has_post_thumbnail()): ?>
                                    <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="<?php the_title(); ?>" />
                                <?php endif; ?>
                                <div class="card-body text-center">
                                    <h3 class="h4"><?php the_title(); ?></h3>
                                    <p class="surgeon-title">Plastic Surgeon</p>
                                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary">View Profile</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                endif; ?>
            </div>
        </div>
    </section>

    <!-- FAQ Section - Standalone -->
    <?php
    $faq_section = get_field('faq_section');
    if($faq_section && !empty($faq_section['faqs'])): ?>
    <section class="py-5">
        <div class="container">
            <div class="faq-container">
                <?php echo display_page_faqs(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

<?php get_footer(); ?>