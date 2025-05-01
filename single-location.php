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

                    <!-- Location Intro Blurb -->
                    <div class="location-intro mb-4">
                        <p>Welcome to our <?php echo get_the_title(); ?> location. Our state-of-the-art facility offers a wide range of plastic surgery and aesthetic procedures with a team of experienced surgeons dedicated to helping you achieve your aesthetic goals.</p>
                    </div>

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
                        <?php endif; ?>

                        <!-- Google Maps Link -->
                        <?php $location_maps_link = get_field('location_maps_link'); ?>
                        <?php if ($location_maps_link): ?>
                            <div class="location-directions mt-3">
                                <a href="<?php echo esc_url($location_maps_link); ?>" class="mia-button mia-button-gold" target="_blank">
                                    <i class="fa-solid fa-map-location-dot"></i> Get Directions
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Consultation Form -->
                <div class="col-lg-6">
                    <div class="card consultation-card shadow-sm h-100">
                        <div class="card-body p-4">
                            <h3 class="h5 mb-3 text-center">Schedule a Consultation</h3>
                            <div class="location-form-container">
                                <?php gravity_form(1, false, false, false, '', true); ?>
                            </div>
                        </div>
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
                <div class="col-12">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="location-content">
                            <?php the_content(); ?>
                        </div>
                    <?php endwhile; ?>

                    <div class="row mt-5">
                        <!-- Business Hours Section -->
                        <?php if(have_rows('business_hours')): ?>
                        <div class="col-md-6 mb-5">
                            <div class="business-hours-section">
                                <h2 class="mb-3">Center Hours</h2>
                                <div class="business-hours-container">
                                    <?php while(have_rows('business_hours')): the_row();
                                        $day = get_sub_field('day');
                                        $hours = get_sub_field('hours');
                                    ?>
                                        <div class="business-hours-row">
                                            <div class="business-hours-day"><?php echo esc_html($day); ?></div>
                                            <div class="business-hours-time"><?php echo esc_html($hours); ?></div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Video Section -->
                        <div class="col-md-6 mb-5">
                            <?php
                            $video_details = get_field('video_details'); // Get the group field
                            $video_id = isset($video_details['video_id']) ? $video_details['video_id'] : ''; // Get the video ID subfield

                            if ($video_id):
                                $youtube_embed_url = 'https://www.youtube.com/embed/' . esc_attr($video_id);
                            ?>
                                <div class="ratio ratio-16x9 shadow rounded overflow-hidden">
                                    <iframe
                                        src="<?php echo esc_url($youtube_embed_url); ?>"
                                        title="Location Video"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                    ></iframe>
                                </div>
                            <?php endif; ?>
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
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
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
                        <div class="col mb-4">
                            <div class="surgeon-card card border-0 shadow-sm h-100">
                                <div class="text-center">
                                    <div class="surgeon-image-container">
                                        <?php
                                        $surgeon_headshot_id = get_field('surgeon_headshot');
                                        if($surgeon_headshot_id && is_numeric($surgeon_headshot_id)) : ?>
                                            <img src="<?php echo esc_url(wp_get_attachment_image_url($surgeon_headshot_id, 'medium')); ?>"
                                                 class="surgeon-headshot-circular" alt="<?php the_title(); ?> Headshot" />
                                        <?php elseif (has_post_thumbnail()): ?>
                                            <img src="<?php the_post_thumbnail_url(); ?>" class="surgeon-headshot-circular" alt="<?php the_title(); ?>" />
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <h3 class="h4"><?php the_title(); ?></h3>
                                        <p class="surgeon-title">Plastic Surgeon</p>
                                        <a href="<?php the_permalink(); ?>" class="mia-button mia-button-white">View Profile <i class="fa-solid fa-arrow-right"></i></a>
                                    </div>
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
