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

get_header(); 

// Get featured image
$featured_img_id = '';
if (have_posts()) {
    the_post();
    $featured_img_id = get_post_thumbnail_id(get_the_ID());
    rewind_posts();
}
?>
<main>
    <div class="container">
        <?php
        if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
        }
        ?>
    </div>

    <?php while (have_posts()) : the_post(); ?>
        <!-- Page Header with Two-Column Layout -->
        <header class="procedure-header py-5 position-relative overflow-hidden">
            <!-- Hero Image -->
            <?php if ($featured_img_id): ?>
                <img src="<?php echo wp_get_attachment_image_url($featured_img_id, 'full'); ?>"
                     alt="<?php echo esc_attr(get_the_title()); ?> procedure background"
                     class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover mia-hero-image"
                     style="z-index: -2;">
            <!-- Gradient Overlay -->
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to right, rgba(0, 0, 0, 0.6), rgba(27, 27, 27, 0.5)); z-index: -1;"></div>
            <?php endif; ?>
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
        <article>
            <!-- Main content without container constraints -->
            <div class="main-content">
                <?php the_content(); ?>
            </div>
            
            <section class="results-resources-section" aria-label="Before and after results with helpful resources">
              <div class="container">
                <div class="row g-4 g-lg-5 align-items-start">
                  <div class="col-lg-7 mb-4 mb-lg-0">
                    <h2 class="h3 fw-bold mb-4 text-white">Before &amp; After Results</h2>

                    <div class="row g-4">
                      <?php
                      // Get before/after images from ACF or similar
                      $before_after_gallery = get_field('before_after_gallery');
                      if ($before_after_gallery): 
                        $count = 0;
                        foreach ($before_after_gallery as $image_pair): 
                          if($count >= 2) break; // Only show 2 images
                      ?>
                        <!-- Single result card -->
                        <div class="col-6">
                          <figure class="before-after-card h-100 overflow-hidden position-relative">
                            <span class="badge bg-dark position-absolute top-0 start-0 m-2">Before</span>

                            <?php if ($image_pair['before_image']): ?>
                                <img src="<?php echo esc_url($image_pair['before_image']); ?>"
                                     class="img-fluid w-100 object-fit-cover"
                                     alt="Patient before surgery">
                            <?php endif; ?>

                            <figcaption class="small text-muted text-center py-2">
                              <?php echo !empty($image_pair['caption']) ? esc_html($image_pair['caption']) : 'Actual patient results may vary'; ?>
                            </figcaption>
                          </figure>
                        </div>

                        <div class="col-6">
                          <figure class="before-after-card h-100 overflow-hidden position-relative">
                            <span class="badge text-dark position-absolute top-0 start-0 m-2 badge-after">After</span>

                            <?php if ($image_pair['after_image']): ?>
                                <img src="<?php echo esc_url($image_pair['after_image']); ?>"
                                     class="img-fluid w-100 object-fit-cover"
                                     alt="Patient after surgery">
                            <?php endif; ?>

                            <figcaption class="small text-muted text-center py-2">
                              <?php echo !empty($image_pair['caption']) ? esc_html($image_pair['caption']) : 'Actual patient results may vary'; ?>
                            </figcaption>
                          </figure>
                        </div>
                        <!-- /card -->
                      <?php 
                        $count++;
                        endforeach; 
                      else: ?>
                        <!-- Placeholder cards when no images are available -->
                        <div class="col-6">
                          <figure class="before-after-card h-100 overflow-hidden position-relative">
                            <span class="badge bg-dark position-absolute top-0 start-0 m-2">Before</span>

                            <img src="https://placehold.co/600x450"
                                 class="img-fluid w-100 object-fit-cover"
                                 alt="Patient before surgery">

                            <figcaption class="small text-muted text-center py-2">
                              Actual patient results may vary
                            </figcaption>
                          </figure>
                        </div>

                        <div class="col-6">
                          <figure class="before-after-card h-100 overflow-hidden position-relative">
                            <span class="badge text-dark position-absolute top-0 start-0 m-2 badge-after">After</span>

                            <img src="https://placehold.co/600x450"
                                 class="img-fluid w-100 object-fit-cover"
                                 alt="Patient after surgery">

                            <figcaption class="small text-muted text-center py-2">
                              Actual patient results may vary
                            </figcaption>
                          </figure>
                        </div>
                      <?php endif; ?>
                    </div>

                    <div class="text-center mt-4">
                      <a href="#gallery" class="mia-button" data-variant="gold">
                        View More Results <i class="fa-solid fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                  <!-- /Before & After -->

                  <!-- ===== Additional Resources ======================================== -->
                  <aside class="col-lg-5">
                    <h2 class="h3 fw-bold mb-4 text-white">Additional Resources</h2>

                    <!-- List group keeps DOM light & accessible -->
                    <nav class="list-group list-group-flush rounded-3">
                      <?php
                      // Related Procedures
                      $related_procedures = get_field('related_procedures');
                      if ($related_procedures): 
                        foreach ($related_procedures as $post): 
                          setup_postdata($post); ?>
                          <a class="list-group-item list-group-item-action d-flex gap-3 py-3"
                             href="<?php the_permalink(); ?>">
                            <i class="fa-solid fa-stethoscope fs-4 flex-shrink-0" aria-hidden="true"></i>
                            <span>
                              <strong>Related: <?php the_title(); ?></strong><br>
                              <small class="text-muted">Learn about this complementary procedure</small>
                            </span>
                          </a>
                        <?php 
                        endforeach; 
                        wp_reset_postdata(); 
                      endif; ?>

                      <a class="list-group-item list-group-item-action d-flex gap-3 py-3"
                         href="/out-of-town">
                        <i class="fa-solid fa-plane fs-4 flex-shrink-0" aria-hidden="true"></i>
                        <span>
                          <strong>Out‑of‑Town Patients</strong><br>
                          <small class="text-muted">Travel info &amp; accommodation details</small>
                        </span>
                      </a>

                      <a class="list-group-item list-group-item-action d-flex gap-3 py-3"
                         href="/bmi-calculator">
                        <i class="fa-solid fa-calculator fs-4 flex-shrink-0" aria-hidden="true"></i>
                        <span>
                          <strong>BMI Calculator</strong><br>
                          <small class="text-muted">Calculate your BMI before booking</small>
                        </span>
                      </a>
                    </nav>
                  </aside>
                  <!-- /Helpful Resources -->

                </div><!-- /.row -->
              </div><!-- /.container‑xxl -->
            </section>

            
            <?php 
            // FAQ Section
            $faq_section = get_field('faq_section');
            if ($faq_section && !empty($faq_section['faqs'])): ?>
            <section class="py-4 py-lg-5">
                <div class="container">                        
                    <?php echo display_page_faqs(); ?>                      
                </div>
            </section>
            <?php endif; ?>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
