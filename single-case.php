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
    <div class="container">
        <?php if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
        } ?>
    </div>

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

    <!-- Before & After Images Section -->
    <?php if ( $before_photo || $after_photo ) : ?>
    <section class="case-images py-4 bg-light">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <?php if ( $before_photo ) : ?>
                <div class="col-6 col-md-5 col-lg-4">
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
                <div class="col-6 col-md-5 col-lg-4">
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
    </section>
    <?php endif; ?>

    <!-- Main Content -->
    <article class="py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content Column -->
                <div class="col-xl-7 col-lg-12">
                    <?php /* ---------------- Patient Information -------------- */ ?>
                    <?php
                    $has_height_weight_bmi = $height || $weight || $bmi;
                    ?>
                    <section class="mb-5">
                        <h2 class="h4 mb-3">Patient Information</h2>
                        <div class="row g-3">
                            <?php if ( ! $has_height_weight_bmi ) : ?>
                                <div class="col-12">
                                    <div class="alert alert-info text-center" role="status" aria-live="polite">
                                        Protected for Patient Privacy
                                    </div>
                                </div>
                            <?php else : ?>
                                <?php if ( $height ) : ?>
                                <div class="col-4 col-md-4">
                                    <div class="patient-info-card">
                                        <h5 class="h6">Height</h5>
                                        <p class="mb-0"><?php echo esc_html( $height ); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ( $weight ) : ?>
                                <div class="col-4 col-md-4">
                                    <div class="patient-info-card">
                                        <h5 class="h6">Weight</h5>
                                        <p class="mb-0"><?php echo esc_html( $weight ); ?> lbs</p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if ( $bmi ) : ?>
                                <div class="col-4 col-md-4">
                                    <div class="patient-info-card">
                                        <h5 class="h6">BMI</h5>
                                        <p class="mb-0"><?php echo esc_html( $bmi ); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </section>

                    <?php /* ------------- Surgeon & Location ----------------- */ ?>
                    <?php if ( $surgeon || $location ) : ?>
                    <section class="mb-5">
                        <div class="row g-3">
                            <?php if ( $surgeon ) : ?>
                            <div class="col-6">
                                <h2 class="h4 mb-3">Performed by</h2>
                                <a href="<?php echo esc_url( get_permalink( $surgeon ) ); ?>" class="case-surgeon-arrow-link">
                                    <?php
                                    // If a dedicated ACF last_name field exists, prefer it.
                                    $last_name = get_field( 'last_name', $surgeon );
                                    if ( ! $last_name ) {
                                        $name_parts = explode( ' ', get_the_title( $surgeon ) );
                                        $last_name  = preg_replace( '/,.*$/', '', end( $name_parts ) );
                                    }
                                    echo 'Dr. ' . esc_html( $last_name );
                                    ?>
                                </a>
                            </div>
                            <?php endif; ?>

                            <?php if ( $location ) : ?>
                            <div class="col-6">
                                <h2 class="h4 mb-3">Location</h2>
                                <a href="<?php echo esc_url( get_permalink( $location ) ); ?>" class="case-location-arrow-link">
                                    <?php echo esc_html( get_the_title( $location ) ); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </section>
                    <?php endif; ?>

                    <?php /* ------------- Procedure Performed --------------- */ ?>
                    <?php if ( ! empty( $procedure_performed ) ) : ?>
                    <section class="mb-5">
                        <h2 class="h4 mb-3">Procedure<?php echo count( $procedure_performed ) > 1 ? 's' : ''; ?> Performed</h2>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ( (array) $procedure_performed as $procedure_id ) : ?>
                                <li class="mb-1">
                                    <a href="<?php echo esc_url( get_permalink( $procedure_id ) ); ?>" class="case-procedure-link">
                                        <?php echo esc_html( get_the_title( $procedure_id ) ); ?>
                                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                    <?php endif; ?>

                    <?php /* ---------------- Patient Background ------------- */ ?>
                    <?php if ( get_the_content() ) : ?>
                    <section class="mb-5">
                        <h2 class="h4 mb-3">Patient Background</h2>
                        <div class="case-background">
                            <?php the_content(); ?>
                        </div>
                    </section>
                    <?php endif; ?>

                    <?php /* ------------- Treatment & Recovery -------------- */ ?>
                    <?php if ( ! empty( $case_links ) ) : ?>
                    <section class="mt-5">
                        <h2 class="h4 mb-3">Treatment &amp; Recovery Resources</h2>
                        <div class="list-group treatment-recovery-links">
                            <?php foreach ( (array) $case_links as $resource_id ) : ?>
                                <a href="<?php echo esc_url( get_permalink( $resource_id ) ); ?>" class="list-group-item list-group-item-action">
                                    <?php echo esc_html( get_the_title( $resource_id ) ); ?>
                                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="col-xl-5 col-lg-12">
                    <div class="card consultation-card mb-4 shadow-sm">
                        <div class="card-body p-3">
                            <h3 class="h5 mb-3 text-center">Schedule a Consultation</h3>
                            <?php gravity_form( 1, false, false, false, '', true ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <?php /* -------------------- FAQ Section ---------------------- */ ?>
    <?php
    $faq_section = get_field( 'faq_section' );
    if ( $faq_section && ! empty( $faq_section['faqs'] ) ) : ?>
    <section class="py-5">
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
