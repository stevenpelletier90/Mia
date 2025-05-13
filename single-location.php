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

    <header class="location-header py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="mb-3"><?php echo get_the_title(); ?></h1>
                    <div class="location-intro mb-4">
                        <p>Welcome to our <?php 
                        $location_title = get_the_title();
                        $location_title = str_replace('Mia Aesthetics', '', $location_title);
                        echo trim($location_title); 
                        ?> location. Our state-of-the-art facility offers a wide range of plastic surgery and aesthetic procedures with a team of experienced surgeons dedicated to helping you achieve your aesthetic goals.</p>
                    </div>
                    <div class="location-info mb-4">
<?php
                        $street_address = get_field('street_address');
                        $city = get_field('city');
                        $state = get_field('state');
                        $zip_code = get_field('zip_code');
                        if ($street_address || $city || $state || $zip_code):
                    ?>
                            <div class="location-detail mb-2">
                                <div class="d-flex flex-column">
                                    <?php if ($street_address): ?>
                                        <span><?php echo esc_html($street_address); ?></span>
                                    <?php endif; ?>
                                    <?php if ($city || $state || $zip_code): ?>
                                        <span>
                                            <?php
                                            $city_state_zip = '';
                                            if ($city) {
                                                $city_state_zip .= esc_html($city);
                                            }
                                            if ($state) {
                                                if ($city_state_zip !== '') {
                                                    $city_state_zip .= ', ';
                                                }
                                                $city_state_zip .= esc_html($state);
                                            }
                                            if ($zip_code) {
                                                if ($city_state_zip !== '') {
                                                    $city_state_zip .= ' ';
                                                }
                                                $city_state_zip .= esc_html($zip_code);
                                            }
                                            echo $city_state_zip;
                                            ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php $phone_number = get_field('phone_number'); ?>
                        <?php if ($phone_number): ?>
                            <div class="location-detail mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-phone location-icon"></i>
                                    <a href="tel:<?php echo esc_attr($phone_number); ?>" class="location-phone">
                                        <?php echo esc_html($phone_number); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php $hours_operation = get_field('hours_operation'); ?>
                        <?php if ($hours_operation): ?>
                            <div class="location-detail mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock location-icon"></i>
                                    <span><?php echo esc_html($hours_operation); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php $location_maps_link = get_field('location_maps_link'); ?>
                        <?php if ($location_maps_link): ?>
                            <div class="location-directions mt-3">
                                <a href="<?php echo esc_url($location_maps_link); ?>" class="location-map-link" target="_blank">
                                    <i class="fas fa-map-marker-alt location-icon"></i> Get Directions
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-6 ps-lg-5">
                    <?php
                    $video_details = get_field('video_details');
                    $video_id = isset($video_details['video_id']) ? $video_details['video_id'] : '';
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
    </header>

    <article class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="location-content">
                            <?php the_content(); ?>
                        </div>
                    <?php endwhile; ?>

                    <div class="location-details py-4">
                        <div class="row gx-5">
                            <div class="col-md-7">
                                <div class="location-cta-links mb-4">
                                    <div class="row row-cols-1 row-cols-sm-2 g-3">
                                        <div class="col">
                                            <a href="/contact" class="btn btn-outline-primary d-flex justify-content-between align-items-center w-100">
                                                Contact Us <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <?php $phone_number = get_field('phone_number'); ?>
                                            <?php if ($phone_number): ?>
                                            <a href="tel:<?php echo esc_attr($phone_number); ?>" class="btn btn-outline-primary d-flex justify-content-between align-items-center w-100">
                                                Call Now <i class="fas fa-arrow-right"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col">
                                            <?php $location_maps_link = get_field('location_maps_link'); ?>
                                            <?php if ($location_maps_link): ?>
                                            <a href="<?php echo esc_url($location_maps_link); ?>" class="btn btn-outline-primary d-flex justify-content-between align-items-center w-100" target="_blank">
                                                Get Directions <i class="fas fa-arrow-right"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col">
                                            <a href="/procedures" class="btn btn-outline-primary d-flex justify-content-between align-items-center w-100">
                                                View Procedures <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <?php if(have_rows('business_hours')): ?>
                                <h3 class="mb-3 h5">Hours of Operation</h3>
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
                                <?php endif; ?>
                            </div>

                            <div class="col-md-5">
                                <div class="card consultation-card shadow-sm h-100">
                                    <div class="card-body p-4">
                                        <h2 class="h5 mb-3 text-center">Schedule a Consultation</h2>
                                        <div class="location-form-container">
                                            <?php gravity_form(1, false, false, false, '', true); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

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

    <section class="py-5">
        <div class="container">
            <div class="faq-container">
                <?php echo display_page_faqs(); ?>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
