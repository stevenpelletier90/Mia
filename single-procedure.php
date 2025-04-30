<?php
/**
 * The template for displaying single procedure posts
 *
 * This template is used for individual procedure custom post types.
 * It includes specialized sections for procedure details, pricing,
 * consultation form, before & after gallery, and related resources.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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

    <?php while (have_posts()) : the_post(); 
        // Get featured image URL for header background
        $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    ?>
        <!-- Page Header with Two-Column Layout -->
        <header class="procedure-header py-5" <?php if($featured_img_url): ?> style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.6), rgba(27, 27, 27, 0.5)), url('<?php echo esc_url($featured_img_url); ?>')" <?php endif; ?>>
            <div class="container">
                <div class="row min-vh-50 d-flex align-items-center">
                    <!-- Left Column: Title and Price -->
                    <div class="col-lg-7 mb-4 mb-lg-0">
                        <h1><?php the_title(); ?></h1>
                        <?php 
                        $procedure_price = get_field('procedure_price');
                        if ($procedure_price): ?>
                            <div class="pricing-info mt-3">
                                <p class="h4 mb-1">Starting Price: <?php echo $procedure_price; ?>*</p>
                                <small>* Pricing varies by surgeon</small>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Right Column: Consultation Form -->
                    <div class="col-lg-5">
                        <div class="card shadow-sm consultation-card">
                            <div class="card-body p-4">
                                <h3 class="h4 text-center">FREE VIRTUAL CONSULTATION</h3>
                                <?php gravity_form(1, false, false, false, '', true); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <article class="py-5">
            <?php if (has_post_thumbnail()) : ?>
            <div class="container mb-4">
                <div class="row">
                    <div class="col-12 text-center">
                        <?php the_post_thumbnail('large', ['class' => 'img-fluid rounded']); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Main content without container constraints -->
            <div class="main-content">
                <?php the_content(); ?>
            </div>
            
            <!-- Before & After Results Section with Related Resources -->
            <section class="results-resources-section py-5 mt-5 bg-light">
                <div class="container">
                    <div class="row">
                        <!-- Left Column: Before & After Gallery -->
                        <div class="col-lg-7 mb-5 mb-lg-0">
                            <h2 class="section-title mb-4">Before & After Results</h2>
                            <div class="before-after-gallery">
                                <?php
                                // Get before/after images from ACF or similar
                                $before_after_gallery = get_field('before_after_gallery');
                                if ($before_after_gallery): ?>
                                    <div class="row g-4">
                                        <?php 
                                        // Limit to 2 image pairs (removed one row)
                                        $count = 0;
                                        foreach ($before_after_gallery as $image_pair): 
                                            if($count >= 2) break; // Only show 2 images
                                        ?>
                                            <div class="col-md-6">
                                                <div class="before-after-card">
                                                    <div class="before-after-images">
                                                        <img src="<?php echo esc_url($image_pair['before_image']); ?>" alt="Before" class="img-fluid">
                                                        <img src="<?php echo esc_url($image_pair['after_image']); ?>" alt="After" class="img-fluid">
                                                    </div>
                                                    <?php if(!empty($image_pair['caption'])): ?>
                                                        <p class="caption mt-2"><?php echo esc_html($image_pair['caption']); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php 
                                            $count++;
                                        endforeach; 
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <div class="placeholder-gallery">
                                        <div class="row g-4">
                                            <?php for($i = 0; $i < 2; $i++): // Reduced from 4 to 2 images ?>
                                                <div class="col-md-6">
                                                    <div class="before-after-card">
                                                        <div class="before-after-images">
                                                            <img src="https://placehold.co/400x300" alt="Before" class="img-fluid">
                                                            <img src="https://placehold.co/400x300" alt="After" class="img-fluid">
                                                        </div>
                                                        <p class="caption mt-2">Actual patient results may vary</p>
                                                    </div>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="text-center mt-4">
                                    <a href="/before-after/" class="btn btn-primary">View More Results</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column: Related Resources -->
                        <div class="col-lg-5">
                            <h2 class="section-title mb-4">Helpful Resources</h2>
                            <div class="resource-links">
                                <!-- Related Procedures -->
                                <?php
                                $related_procedures = get_field('related_procedures');
                                if ($related_procedures): 
                                    foreach ($related_procedures as $post): 
                                        setup_postdata($post); ?>
                                        <a href="<?php the_permalink(); ?>" class="resource-card card mb-4 shadow-sm text-decoration-none">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="resource-icon me-3">
                                                        <i class="fa-solid fa-scalpel-line-dashed fs-3"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="h5 mb-2">Related: <?php the_title(); ?></h4>
                                                        <p class="mb-0">Learn about this complementary procedure</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    <?php 
                                    endforeach; 
                                    wp_reset_postdata(); 
                                endif; ?>
                                
                                <!-- Out of Town Patients -->
                                <a href="https://miaaesthetics.com/out-of-town-patients/" class="resource-card card mb-4 shadow-sm text-decoration-none">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="resource-icon me-3">
                                                <i class="fa-solid fa-plane-departure fs-3"></i>
                                            </div>
                                            <div>
                                                <h4 class="h5 mb-2">Out of Town Patients</h4>
                                                <p class="mb-0">Travel information and accommodation details</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                
                                <!-- BMI Calculator -->
                                <a href="https://miaaesthetics.com/what-is-a-bmi-and-how-do-you-calculate-it/" class="resource-card card shadow-sm text-decoration-none">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="resource-icon me-3">
                                                <i class="fa-solid fa-calculator fs-3"></i>
                                            </div>
                                            <div>
                                                <h4 class="h5 mb-2">BMI Calculator</h4>
                                                <p class="mb-0">Learn about BMI and calculate yours</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <div class="container">
                <?php 
                // FAQ Section
                $faq_section = get_field('faq_section');
                if ($faq_section && !empty($faq_section['faqs'])): ?>
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="faq-container">
                            <?php echo display_page_faqs(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
