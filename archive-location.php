<?php
/**
 * Location Archive Template
 * Displays all locations in alphabetical order with CTA sections
 */

get_header();
?>

<main>
<?php mia_breadcrumbs(); ?>

    <!-- Archive Header -->
    <section class="post-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-2"><?php post_type_archive_title(); ?></h1>
                    <p class="lead mb-0">Find a Mia Aesthetics location near you and schedule your consultation today.</p>
                </div>
            </div>
        </div>
    </section>

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
                $location_map = get_field('location_map');
                if ($location_map):
                    $street = $location_map['street_number'] . ' ' . $location_map['street_name'];
                    $city = $location_map['city'];
                    $state = $location_map['state_short'];
                    $zip = $location_map['post_code'];
                ?>
                    <div class="location-detail mb-2">
                        <span>
                            <?php echo esc_html($street); ?>
                            <br>
                            <?php echo esc_html($city . ', ' . $state . ' ' . $zip); ?>
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
            </div>            <div class="location-cta-buttons">
                <a href="<?php the_permalink(); ?>" class="mia-button" data-variant="gold" role="button">
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
