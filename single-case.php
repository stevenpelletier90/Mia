<?php
/**
 * Template for displaying single case studies (updated for new ACF group structure)
 *
 * Pulls all sub‑fields from the `case_information` group once and
 * references them locally to minimise DB calls.
 *
 * @package Mia_Aesthetics
 */

global $post;

get_header(); ?>

<main>
<?php mia_breadcrumbs(); ?>

    <?php
    /* ------------------------------------------------------------------
     * Grab everything from the `case_information` ACF group in one go.
     * ------------------------------------------------------------------ */
    $case_info = get_field( 'case_information' );

    $before_photo        = $case_info['before_photo']        ?? null; // Image array or null
    $after_photo         = $case_info['after_photo']         ?? null; // Image array or null
    $height              = $case_info['height']              ?? '';
    $weight              = $case_info['weight']              ?? '';
    $bmi                 = $case_info['bmi']                 ?? '';
    $surgeon             = $case_info['performed_by_surgeon']   ?? null; // Post object (ID)
    $location            = $case_info['performed_at_location']  ?? null; // Post object (ID)
    $procedure_performed = $case_info['procedure_performed'] ?? array(); // Post object(s)
    $case_links          = $case_info['case_links']          ?? array(); // Post object(s)
    ?>

    <!-- Page Header -->
    <section class="post-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1><?php the_title(); ?></h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content - Two Column Layout -->
    <article class="py-5 py-lg-6">
        <div class="container">
            <div class="row g-4 g-lg-5">
                <!-- Before & After Images Column -->
                <div class="col-lg-6">
                    <?php if ( $before_photo || $after_photo ) : ?>
                    <div class="case-images-container">
                        <h2 class="h4 mb-3">Before & After</h2>
                        <div class="row g-3">
                            <?php if ( $before_photo ) : ?>
                            <div class="col-6">
                                <div class="position-relative case-image-container">
                                    <img src="<?php echo esc_url( $before_photo['sizes']['medium_large'] ?? $before_photo['url'] ); ?>"
                                         class="img-fluid rounded cursor-pointer"
                                         alt="Before Treatment – <?php echo esc_attr( get_the_title() ); ?>"
                                         loading="lazy"
                                         data-bs-toggle="modal"
                                         data-bs-target="#imageModal"
                                         data-bs-image="<?php echo esc_url( $before_photo['url'] ); ?>"
                                         data-bs-title="Before Treatment">
                                    <span class="before-label">Before</span>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ( $after_photo ) : ?>
                            <div class="col-6">
                                <div class="position-relative case-image-container">
                                    <img src="<?php echo esc_url( $after_photo['sizes']['medium_large'] ?? $after_photo['url'] ); ?>"
                                         class="img-fluid rounded cursor-pointer"
                                         alt="After Treatment – <?php echo esc_attr( get_the_title() ); ?>"
                                         loading="lazy"
                                         data-bs-toggle="modal"
                                         data-bs-target="#imageModal"
                                         data-bs-image="<?php echo esc_url( $after_photo['url'] ); ?>"
                                         data-bs-title="After Treatment">
                                    <span class="after-label">After</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Patient Information Column -->
                <div class="col-lg-6">
                    <?php /* ---------------- Patient Information -------------- */ ?>
                    <?php
                    $has_height_weight_bmi = $height || $weight || $bmi;
                    $has_surgeon_location = $surgeon || $location;
                    ?>
                    <section class="mb-5">
                        <h2 class="h4 mb-3">Patient Information</h2>
                        <?php if ( ! $has_height_weight_bmi && ! $has_surgeon_location ) : ?>
                            <div class="alert alert-info text-center" role="status" aria-live="polite">
                                Protected for Patient Privacy
                            </div>
                        <?php else : ?>
                            <?php if ( $has_height_weight_bmi ) : ?>
                            <!-- Height, Weight, BMI Row -->
                            <div class="row g-3 mb-3">
                                <?php if ( $height ) : ?>
                                <div class="col-4">
                                    <div class="patient-info-card">
                                        <h5 class="h6">Height</h5>
                                        <p class="mb-0"><?php echo esc_html( $height ); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ( $weight ) : ?>
                                <div class="col-4">
                                    <div class="patient-info-card">
                                        <h5 class="h6">Weight</h5>
                                        <p class="mb-0"><?php echo esc_html( $weight ); ?> lbs</p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ( $bmi ) : ?>
                                <div class="col-4">
                                    <div class="patient-info-card">
                                        <h5 class="h6">BMI</h5>
                                        <p class="mb-0"><?php echo esc_html( $bmi ); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <?php if ( $has_surgeon_location || ! empty( $procedure_performed ) ) : ?>
                            <!-- Surgeon, Location, and Procedure Row -->
                            <div class="row g-3">
                                <?php if ( $surgeon ) : ?>
                                <div class="col-6">
                                    <a href="<?php echo esc_url( get_permalink( $surgeon ) ); ?>" class="patient-info-card patient-info-card-link text-decoration-none">
                                        <h5 class="h6">Performed by</h5>
                                        <p class="mb-0"><?php echo esc_html( get_the_title( $surgeon ) ); ?></p>
                                        <i class="fas fa-chevron-right patient-info-arrow" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <?php endif; ?>

                                <?php if ( $location ) : ?>
                                <div class="col-6">
                                    <a href="<?php echo esc_url( get_permalink( $location ) ); ?>" class="patient-info-card patient-info-card-link text-decoration-none">
                                        <h5 class="h6">Location</h5>
                                        <p class="mb-0"><?php echo esc_html( get_the_title( $location ) ); ?></p>
                                        <i class="fas fa-chevron-right patient-info-arrow" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $procedure_performed ) ) : ?>
                                    <?php foreach ( (array) $procedure_performed as $procedure_id ) : ?>
                                        <div class="col-6">
                                            <a href="<?php echo esc_url( get_permalink( $procedure_id ) ); ?>" class="patient-info-card patient-info-card-link text-decoration-none">
                                                <h5 class="h6">Procedure<?php echo count( $procedure_performed ) > 1 ? 's' : ''; ?></h5>
                                                <p class="mb-0"><?php echo esc_html( get_the_title( $procedure_id ) ); ?></p>
                                                <i class="fas fa-chevron-right patient-info-arrow" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </section>


                </div>
            </div>
        </div>
    </article>

    <?php /* ---------------- Patient Background ------------- */ ?>
    <?php if ( get_the_content() ) : ?>
    <section class="py-4 py-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="h4 mb-4">Patient Background</h2>
                    <div class="case-background">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php /* ------------- Treatment & Recovery -------------- */ ?>
    <?php if ( ! empty( $case_links ) ) : ?>
    <section class="py-4 py-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="h4 mb-4">Treatment &amp; Recovery Resources</h2>
                    <div class="row g-3">
                        <?php foreach ( (array) $case_links as $resource_id ) : ?>
                            <div class="col-6">
                                <a href="<?php echo esc_url( get_permalink( $resource_id ) ); ?>" class="patient-info-card patient-info-card-link text-decoration-none">
                                    <h5 class="h6">Resource</h5>
                                    <p class="mb-0"><?php echo esc_html( get_the_title( $resource_id ) ); ?></p>
                                    <i class="fas fa-chevron-right patient-info-arrow" aria-hidden="true"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php /* ------------- Related Cases -------------- */ ?>
    <?php
    // Get related cases based on case-categories taxonomy
    $current_categories = wp_get_post_terms(get_the_ID(), 'case-categories', ['fields' => 'ids']);
    
    $related_cases = null;
    
    if (!empty($current_categories)) {
        // Get cases with the same categories
        $related_cases = new WP_Query([
            'post_type' => 'case',
            'post__not_in' => [get_the_ID()],
            'posts_per_page' => 4,
            'orderby' => 'rand',
            'tax_query' => [
                [
                    'taxonomy' => 'case-categories',
                    'field' => 'term_id',
                    'terms' => $current_categories,
                    'operator' => 'IN'
                ]
            ]
        ]);
    }

    if (!$related_cases || !$related_cases->have_posts()) {
        // Fallback: get any recent cases if no related ones found
        $related_cases = new WP_Query([
            'post_type' => 'case',
            'post__not_in' => [get_the_ID()],
            'posts_per_page' => 4,
            'orderby' => 'date',
            'order' => 'DESC'
        ]);
    }

    if ($related_cases->have_posts()) : ?>
    <section class="py-4 py-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="h4 mb-4">Related Cases</h2>
                    <div class="row g-3">
                        <?php while ($related_cases->have_posts()) : $related_cases->the_post(); ?>
                            <div class="col-md-6">
                                <div class="related-case-item">
                                    <a href="<?php the_permalink(); ?>" class="related-case-link">
                                        <div class="related-case-number">
                                            <span><?php echo str_pad($related_cases->current_post + 1, 2, '0', STR_PAD_LEFT); ?></span>
                                        </div>
                                        <div class="related-case-content">
                                            <h3 class="related-case-title"><?php the_title(); ?></h3>
                                            <span class="related-case-meta">Case Study</span>
                                        </div>
                                        <div class="related-case-arrow">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php 
    endif;
    wp_reset_postdata();
    ?>

    <?php /* -------------------- FAQ Section ---------------------- */ ?>
    <?php
    $faq_section = get_field( 'faq_section' );
    if ( $faq_section && ! empty( $faq_section['faqs'] ) ) : ?>
    <section class="py-5 py-lg-6">
        <div class="container">
            <div class="faq-container">
                <?php echo display_page_faqs(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<!-- Image Modal with Carousel -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel"><?php the_title(); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="caseCarousel" class="carousel slide carousel-fade" data-bs-ride="false">
                    <div class="carousel-inner">
                        <?php $carousel_index = 0; ?>
                        <?php if ( $before_photo ) : ?>
                        <div class="carousel-item<?php echo $carousel_index === 0 ? ' active' : ''; ?>">
                            <img src="<?php echo esc_url( $before_photo['url'] ); ?>" class="d-block w-100" alt="Before Treatment">
                        </div>
                        <?php $carousel_index++; endif; ?>

                        <?php if ( $after_photo ) : ?>
                        <div class="carousel-item<?php echo $carousel_index === 0 ? ' active' : ''; ?>">
                            <img src="<?php echo esc_url( $after_photo['url'] ); ?>" class="d-block w-100" alt="After Treatment">
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ( $before_photo && $after_photo ) : ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#caseCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#caseCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modal logic moved to assets/js/single-case-modal-sync.js -->

<?php get_footer(); ?>
