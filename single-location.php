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
                            <div class="location-detail mb-4">
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

                        <?php
                        // Grouped hours of operation (short format)
                        $short_days = array(
                            'Monday' => 'Mon', 'Tuesday' => 'Tue', 'Wednesday' => 'Wed',
                            'Thursday' => 'Thu', 'Friday' => 'Fri', 'Saturday' => 'Sat', 'Sunday' => 'Sun'
                        );
                        $hours_rows = array();
                        if (have_rows('business_hours')) {
                            while (have_rows('business_hours')): the_row();
                                $day = get_sub_field('day');
                                $hours = get_sub_field('hours');
                                if ($day && $hours) {
                                    $hours_rows[] = array('day' => $day, 'hours' => $hours);
                                }
                            endwhile;
                        }
                        $output = array();
                        $n = count($hours_rows);
                        $i = 0;
                        while ($i < $n) {
                            $start = $i;
                            $current_hours = $hours_rows[$i]['hours'];
                            while (
                                $i + 1 < $n &&
                                $hours_rows[$i + 1]['hours'] === $current_hours
                            ) {
                                $i++;
                            }
                            if ($start == $i) {
                                $label = $short_days[$hours_rows[$start]['day']];
                            } else {
                                $label = $short_days[$hours_rows[$start]['day']] . '–' . $short_days[$hours_rows[$i]['day']];
                            }
                            $output[] = $label . ' ' . $current_hours;
                            $i++;
                        }
                        if (!empty($output)) : ?>
                            <div class="location-detail mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock location-icon"></i>
                                    <span><?php echo implode(' | ', $output); ?></span>
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
                    // Looking at different methods to get the fields
                    
                    // Try method 1: direct field access
                    $video_id = get_field('video_id');
                    $video_url = get_field('video_url');
                    $video_thumbnail = get_field('video_thumbnail');
                    
                    // Try method 2: accessing through the group
                    $video_details = get_field('video_details');
                    if (!empty($video_details)) {
                        if (empty($video_id) && isset($video_details['video_id'])) {
                            $video_id = $video_details['video_id'];
                        }
                        if (empty($video_url) && isset($video_details['video_url'])) {
                            $video_url = $video_details['video_url'];
                        }
                        if (empty($video_thumbnail) && isset($video_details['video_thumbnail'])) {
                            $video_thumbnail = $video_details['video_thumbnail'];
                        }
                    }
                    
                    // Try method 3: try the field group name
                    $featured_video = get_field('featured_video');
                    if (!empty($featured_video)) {
                        if (empty($video_id) && isset($featured_video['video_id'])) {
                            $video_id = $featured_video['video_id'];
                        }
                        if (empty($video_url) && isset($featured_video['video_url'])) {
                            $video_url = $featured_video['video_url'];
                        }
                        if (empty($video_thumbnail) && isset($featured_video['video_thumbnail'])) {
                            $video_thumbnail = $featured_video['video_thumbnail'];
                        }
                    }
                    
                    // Get all fields for debugging
                    $all_fields = get_fields();
                    
                    // Initialize thumbnail URL
                    $thumbnail_url = '';

                    // Function to extract YouTube video ID from various URL formats
                    function get_youtube_video_id_location($youtube_url) {
                        $video_id = '';
                        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
                            $video_id = $matches[1];
                        } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
                            $video_id = $matches[1];
                        } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
                            $video_id = $matches[1];
                        } elseif (preg_match('/youtube\.com\/v\/([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
                            $video_id = $matches[1];
                        } elseif (preg_match('/youtube\.com\/user\/\w+\/\?v=([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
                            $video_id = $matches[1];
                        }
                        return $video_id;
                    }

                    $embed_url = '';
                    if ($video_url) {
                        $video_id = get_youtube_video_id_location($video_url);
                        $embed_url = $video_id ? 'https://www.youtube.com/embed/' . $video_id : $video_url;
                    }

                    // Handle the video_thumbnail which returns an array with all image data
                    if ($video_thumbnail && is_array($video_thumbnail)) {
                        // The URL is directly accessible in the array
                        $thumbnail_url = $video_thumbnail['url'];
                    }
                    ?>

                    <!-- Video container - only show if we have both URL and thumbnail -->
                    <?php if (!empty($video_url) && !empty($thumbnail_url)): ?>
                    <div class="sidebar-section" style="border-radius: 0;">
                        <div class="video-container">
                            <div class="video-thumbnail" data-embed-url="<?php echo esc_url($embed_url); ?>">
                                <img 
                                    src="<?php echo esc_url($thumbnail_url); ?>" 
                                    alt="<?php echo esc_attr(get_the_title()); ?> Video Thumbnail" 
                                    class="img-fluid"
                                    loading="lazy"
                                    width="640"
                                    height="360"
                                />
                                <div class="video-play-button">
                                    <i class="fa-solid fa-play"></i>
                                </div>
                            </div>
                        </div>
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
                                
                            </div>

                            <div class="col-md-5">
                                <div class="card consultation-card h-100">
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
            <?php echo display_page_faqs(); ?>           
        </div>
    </section>

<?php get_footer(); ?>
