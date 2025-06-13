<?php
/**
 * Single non-surgical post template
 * 
 * @package Mia_Aesthetics
 */

get_header(); 

$post = get_queried_object();
$hero_id = get_post_thumbnail_id( $post );
?>
<main>
    <div class="container">
        <?php if ( function_exists( 'yoast_breadcrumb' ) ) : ?>
            <nav aria-label="Breadcrumb" class="breadcrumb-nav">
                <?php yoast_breadcrumb(); ?>
            </nav>
        <?php endif; ?>
    </div>

    <?php while (have_posts()) : the_post(); ?>
        <header class="procedure-header py-5 position-relative overflow-hidden">
            <?php if ($hero_id): ?>
                <picture class="hero-picture">
                    <source media="(max-width: 640px)" 
                        srcset="<?php echo esc_url(wp_get_attachment_image_url($hero_id, 'hero-mobile')); ?>">
                    <source media="(max-width: 1024px)" 
                        srcset="<?php echo esc_url(wp_get_attachment_image_url($hero_id, 'hero-tablet')); ?>">
                    <img src="<?php echo esc_url(wp_get_attachment_image_url($hero_id, 'hero-desktop')); ?>" 
                        alt="<?php echo esc_attr(get_the_title()); ?> non-surgical procedure background"
                        class="hero-bg"
                        loading="eager"
                        fetchpriority="high">
                </picture>
            <?php endif; ?>
            
            <div class="container">
                <div class="row min-vh-50 d-flex align-items-center">
                    <div class="col-lg-7 mb-4 mb-lg-0">
                        <h1><?php the_title(); ?></h1>
                        <?php 
                        $procedure_price = get_field('procedure_price');
                        if ($procedure_price): ?>
                            <div class="pricing-info mt-3">
                                <h2 class="h4 mb-1">Starting Price: <?php echo $procedure_price; ?>*</h2>
                                <small>* Pricing varies by provider</small>
                            </div>
                        <?php endif; ?>
                    </div>
                    
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

        <article class="single-procedure">
            <section class="main-content">
                <?php the_content(); ?>
            </section>
            
            <section class="results-resources-section" aria-labelledby="results-heading" aria-describedby="results-description">
                <div class="container">
                    <div class="row g-4 g-lg-5 align-items-start">
                        <div class="col-lg-7 mb-4 mb-lg-0">
                            <h2 id="results-heading" class="h3 fw-bold mb-4 text-white">Before &amp; After Results</h2>
                            <div id="results-description" class="sr-only">Patient before and after treatment results gallery</div>

                            <div class="row g-4">
                                <?php
                                $before_after_gallery = get_field('before_after_gallery');
                                if ($before_after_gallery): 
                                    foreach (array_slice($before_after_gallery, 0, 2) as $pair): ?>
                                        <div class="col-6">
                                            <figure class="before-after-card h-100 overflow-hidden position-relative">
                                                <span class="badge bg-dark position-absolute top-0 start-0 m-2"><?php echo esc_html(__('Before', 'mia')); ?></span>
                                                <?php echo mia_before_after_img($pair['before_image'], 'Before'); ?>
                                                <figcaption class="small text-muted text-center py-2">
                                                    <?php echo !empty($pair['caption']) ? esc_html($pair['caption']) : 'Actual patient results may vary'; ?>
                                                </figcaption>
                                            </figure>
                                        </div>

                                        <div class="col-6">
                                            <figure class="before-after-card h-100 overflow-hidden position-relative">
                                                <span class="badge text-dark position-absolute top-0 start-0 m-2 badge-after"><?php echo esc_html(__('After', 'mia')); ?></span>
                                                <?php echo mia_before_after_img($pair['after_image'], 'After'); ?>
                                                <figcaption class="small text-muted text-center py-2">
                                                    <?php echo !empty($pair['caption']) ? esc_html($pair['caption']) : 'Actual patient results may vary'; ?>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    <?php endforeach; 
                                else: ?>
                                    <div class="col-6">
                                        <figure class="before-after-card h-100 overflow-hidden position-relative">
                                            <span class="badge bg-dark position-absolute top-0 start-0 m-2"><?php echo esc_html(__('Before', 'mia')); ?></span>
                                            <?php echo mia_before_after_img(null, 'Before'); ?>
                                            <figcaption class="small text-muted text-center py-2">
                                                Actual patient results may vary
                                            </figcaption>
                                        </figure>
                                    </div>

                                    <div class="col-6">
                                        <figure class="before-after-card h-100 overflow-hidden position-relative">
                                            <span class="badge text-dark position-absolute top-0 start-0 m-2 badge-after"><?php echo esc_html(__('After', 'mia')); ?></span>
                                            <?php echo mia_before_after_img(null, 'After'); ?>
                                            <figcaption class="small text-muted text-center py-2">
                                                Actual patient results may vary
                                            </figcaption>
                                        </figure>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="text-center mt-4">
                                <a href="#gallery" class="mia-button" data-variant="gold">
                                    View More Results <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>

                        <aside class="col-lg-5" aria-labelledby="resources-heading">
                            <h2 id="resources-heading" class="h3 fw-bold mb-4 text-white">Additional Resources</h2>

                            <nav class="list-group list-group-flush rounded-3" aria-label="Related resources">
                                <?php
                                $related_procedures = get_field('related_procedures');
                                if ($related_procedures):
                                    $related_ids = array_map(function($p) { return is_object($p) ? $p->ID : (int)$p; }, $related_procedures);
                                    $related_query = new WP_Query([
                                        'post_type' => 'non-surgical',
                                        'post__in' => $related_ids,
                                        'orderby' => 'post__in',
                                        'posts_per_page' => count($related_ids)
                                    ]);
                                    if ($related_query->have_posts()):
                                        while ($related_query->have_posts()): $related_query->the_post(); ?>
                                            <a class="list-group-item list-group-item-action d-flex gap-3 py-3"
                                               href="<?php the_permalink(); ?>">
                                                <i class="fa-solid fa-hand-sparkles fs-4 flex-shrink-0" aria-hidden="true"></i>
                                                <span>
                                                    <strong>Related: <?php the_title(); ?></strong><br>
                                                    <small class="text-muted">Learn about this complementary treatment</small>
                                                </span>
                                            </a>
                                        <?php endwhile;
                                        wp_reset_postdata();
                                    endif;
                                endif; ?>

                                <a class="list-group-item list-group-item-action d-flex gap-3 py-3"
                                   href="/out-of-town-patients/">
                                    <i class="fa-solid fa-plane fs-4 flex-shrink-0" aria-hidden="true"></i>
                                    <span>
                                        <strong>Out‑of‑Town Patients</strong><br>
                                        <small class="text-muted">Travel info & accommodation details</small>
                                    </span>
                                </a>

                                <a class="list-group-item list-group-item-action d-flex gap-3 py-3"
                                   href="/calculate-your-bmi/">
                                    <i class="fa-solid fa-calculator fs-4 flex-shrink-0" aria-hidden="true"></i>
                                    <span>
                                        <strong>BMI Calculator</strong><br>
                                        <small class="text-muted">Calculate your BMI before booking</small>
                                    </span>
                                </a>
                            </nav>
                        </aside>
                    </div>
                </div>
            </section>

            <?php 
            $faq_section = get_field('faq_section');
            if ($faq_section && !empty($faq_section['faqs'])): ?>
                <section class="py-4 py-lg-5" aria-labelledby="faq-heading-<?php echo get_the_ID(); ?>">
                    <div class="container">                        
                        <?php echo display_page_faqs(); ?>                      
                    </div>
                </section>
            <?php endif; ?>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>