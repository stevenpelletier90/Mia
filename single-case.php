<?php
/**
 * Template for displaying single case studies
 *
 * @package Mia_Aesthetics
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
    <section class="case-images py-4 bg-light">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <?php 
                $before_photo = get_field('before_photo');
                $after_photo = get_field('after_photo');
                
                if ($before_photo) : ?>
                <div class="col-6 col-md-5 col-lg-4">
                    <div class="position-relative case-image-container">
                        <img src="<?php echo esc_url($before_photo['sizes']['medium_large']); ?>" 
                             class="img-fluid rounded cursor-pointer" 
                             alt="Before Treatment - <?php the_title(); ?>"
                             data-bs-toggle="modal" 
                             data-bs-target="#imageModal" 
                             data-bs-image="<?php echo esc_url($before_photo['url']); ?>"
                             data-bs-title="Before Treatment">
                        <span class="before-label">Before</span>
                    </div>
                </div>
                <?php endif; 
                
                if ($after_photo) : ?>
                <div class="col-6 col-md-5 col-lg-4">
                    <div class="position-relative case-image-container">
                        <img src="<?php echo esc_url($after_photo['sizes']['medium_large']); ?>" 
                             class="img-fluid rounded cursor-pointer" 
                             alt="After Treatment - <?php the_title(); ?>"
                             data-bs-toggle="modal" 
                             data-bs-target="#imageModal" 
                             data-bs-image="<?php echo esc_url($after_photo['url']); ?>"
                             data-bs-title="After Treatment">
                        <span class="after-label">After</span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <article class="py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content Column -->
                <div class="col-xl-7 col-lg-12">
                    <?php 
                    // Get case information
                    $case_info = get_field('case_information');
                    if ($case_info) : 
                        $height = !empty($case_info['height']);
                        $weight = !empty($case_info['weight']);
                        $bmi = !empty($case_info['bmi']);
                    ?>
                    <!-- Patient Information -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">Patient Information</h2>
                        <div class="row g-3">
                            <?php if (!$height && !$weight && !$bmi) : ?>
                                <div class="col-12">
                                    <div class="alert alert-info text-center" role="status" aria-live="polite">
                                        Protected for Patient Privacy
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php if ($height) : ?>
                                <div class="col-4 col-md-4">
                                    <div class="patient-info-card">
                                        <h5 class="h6">Height</h5>
                                        <p class="mb-0"><?php echo esc_html($case_info['height']); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($weight) : ?>
                                <div class="col-4 col-md-4">
                                    <div class="patient-info-card">
                                        <h5 class="h6">Weight</h5>
                                        <p class="mb-0"><?php echo esc_html($case_info['weight']); ?> lbs</p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($bmi) : ?>
                                <div class="col-4 col-md-4">
                                    <div class="patient-info-card">
                                        <h5 class="h6">BMI</h5>
                                        <p class="mb-0"><?php echo esc_html($case_info['bmi']); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </section>
                    
                    <!-- Surgeon and Location Information -->
                    <section class="mb-5">
                        <div class="row g-3">
                            <?php 
                            // Get surgeon information
                            if (!empty($case_info['performed_by_surgeon'])) :
                                $surgeon = $case_info['performed_by_surgeon'];
                            ?>
                            <div class="col-6">
                                <h2 class="h4 mb-3">Performed by</h2>
                                <a href="<?php echo get_permalink($surgeon); ?>" class="case-surgeon-arrow-link">
                                    <?php 
                                    // Check if there's a specific ACF field for last name
                                    $last_name = get_field('last_name', $surgeon);
                                    
                                    // If there's no specific field, extract from title
                                    if (empty($last_name)) {
                                        $surgeon_name = get_the_title($surgeon);
                                        $name_parts = explode(' ', $surgeon_name);
                                        
                                        // Get the last part of the name (handles multiple words)
                                        if (count($name_parts) > 1) {
                                            // If the last part contains a comma (e.g., "Smith, MD"), get the part before the comma
                                            $last_part = end($name_parts);
                                            if (strpos($last_part, ',') !== false) {
                                                $last_name = explode(',', $last_part)[0];
                                            } else {
                                                // Check if second-to-last part might be the last name (in case of "John Smith MD")
                                                $second_to_last = $name_parts[count($name_parts) - 2];
                                                if (strpos($last_part, 'MD') !== false || strpos($last_part, 'DO') !== false) {
                                                    $last_name = $second_to_last;
                                                } else {
                                                    $last_name = $last_part;
                                                }
                                            }
                                        } else {
                                            $last_name = $surgeon_name;
                                        }
                                    }
                                    
                                    // Remove any commas from the last name
                                    $last_name = trim(str_replace(',', '', $last_name));
                                    echo 'Dr. ' . $last_name;
                                    ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php 
                            // Get location information
                            if (!empty($case_info['performed_at_location'])) :
                                $location = $case_info['performed_at_location'];
                            ?>
                            <div class="col-6">
                                <h2 class="h4 mb-3">Location</h2>
                                <a href="<?php echo get_permalink($location); ?>" class="case-location-arrow-link">
                                    <?php echo get_the_title($location); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </section>
                    <?php endif; ?>

                    <?php if (get_the_content()) : ?>
                    <!-- Patient Background -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">Patient Background</h2>
                        <div class="case-background">
                            <?php the_content(); ?>
                        </div>
                    </section>
                    <?php endif; ?>
                    
                    <?php 
                    // Treatment & Recovery Links
                    if (have_rows('treatment_recovery_links')) : 
                    ?>
                    <section class="mt-5">
                        <h2 class="h4 mb-3">Treatment & Recovery Resources</h2>
                        <div class="list-group treatment-recovery-links">
                            <?php while (have_rows('treatment_recovery_links')) : the_row(); 
                                $linked_page = get_sub_field('link_to_page');
                                
                                if ($linked_page) :
                            ?>
                                <a href="<?php echo get_permalink($linked_page); ?>" class="list-group-item list-group-item-action">
                                    <?php echo get_the_title($linked_page); ?>
                                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                </a>
                            <?php 
                                endif;
                            endwhile; 
                            ?>
                        </div>
                    </section>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="col-xl-5 col-lg-12">
                    <!-- Contact Card with Gravity Form -->
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
                        <?php 
                        $carousel_index = 0;
                        if ($before_photo) : ?>
                        <div class="carousel-item<?php echo $carousel_index === 0 ? ' active' : ''; ?>">
                            <img src="<?php echo esc_url($before_photo['url']); ?>" class="d-block w-100" alt="Before Treatment">
                        </div>
                        <?php 
                        $carousel_index++;
                        endif; 
                        
                        if ($after_photo) : ?>
                        <div class="carousel-item<?php echo $carousel_index === 0 ? ' active' : ''; ?>">
                            <img src="<?php echo esc_url($after_photo['url']); ?>" class="d-block w-100" alt="After Treatment">
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($before_photo && $after_photo) : ?>
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

<!-- Modal Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the modal element
    const imageModal = document.getElementById('imageModal');
    
    if (imageModal) {
        // Handle modal show event
        imageModal.addEventListener('show.bs.modal', function(event) {
            // Get the button that triggered the modal
            const button = event.relatedTarget;
            
            // Determine which image was clicked (before or after)
            const clickedTitle = button.getAttribute('data-bs-title');
            const carousel = bootstrap.Carousel.getInstance(document.getElementById('caseCarousel'));
            
            if (carousel) {
                if (clickedTitle === 'Before Treatment') {
                    carousel.to(0);
                } else if (clickedTitle === 'After Treatment') {
                    carousel.to(1);
                }
            }
        });
    }
    
    // Make all images with data-bs-toggle="modal" clickable
    const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#imageModal"]');
    modalTriggers.forEach(trigger => {
        trigger.style.cursor = 'pointer';
    });
});
</script>

<?php get_footer(); ?>
