<?php
/**
 * Location Archive Template
 * Displays all locations in alphabetical order with CTA sections
 */

get_header();
?>

<main>
    <div class="container">
        <?php if (function_exists('yoast_breadcrumb')) : ?>
            <?php yoast_breadcrumb('<p id="breadcrumbs">', '</p>'); ?>
        <?php endif; ?>
    </div>

    <!-- Archive Header -->
    <header class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1><?php post_type_archive_title(); ?></h1>
                    <p class="lead">
                        Find a Mia Aesthetics location near you and schedule your consultation today.
                    </p>
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
                        $location_address   = get_field('location_address');
                        $phone_number       = get_field('phone_number');
                        $location_maps_link = get_field('location_maps_link');
                    ?>
<div class="col-md-6 col-lg-4">
    <?php
    $bg_image_id = get_field('background_image');
    if ($bg_image_id) {
        $bg_image_url = wp_get_attachment_image_url($bg_image_id, 'medium_large');
    } elseif (has_post_thumbnail()) {
        $bg_image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
    } else {
        $bg_image_url = '';
    }
    ?>
    <div class="location-card">
        <?php if ($bg_image_url): ?>
            <img src="<?php echo esc_url($bg_image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="location-card-img-top w-100">
        <?php endif; ?>
        <div class="location-content p-4">
            <h2 class="h4 mb-3">
                <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark location-title-link">
                    <?php echo get_the_title(); ?>
                </a>
            </h2>

            <div class="location-details mb-3">
                <?php
                $street_address = get_field('street_address');
                $city           = get_field('city');
                $state          = get_field('state');
                $zip_code       = get_field('zip_code');
                if ($street_address || $city || $state || $zip_code) :
                ?>
                    <div class="location-detail mb-2">
                        <span>
                            <?php if ($street_address) : ?>
                                <?php echo esc_html($street_address); ?>
                            <?php endif; ?>
                            <?php if ($city || $state || $zip_code) : ?>
                                <br>
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
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if ($phone_number) : ?>
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

            <div class="location-cta-buttons">
                <a href="<?php the_permalink(); ?>" class="btn mia-button-gold" role="button">
                    View Location <i class="fas fa-arrow-right"></i>
                </a>
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
