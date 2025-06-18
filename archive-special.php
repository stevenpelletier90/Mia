<?php
/**
 * Archive Template for Specials/Promotions
 * Used as: archive-specials.php
 */
get_header(); ?>
<main>
    <?php if ( function_exists( 'yoast_breadcrumb' ) ) : ?>
        <nav aria-label="Breadcrumb" class="breadcrumb-nav"><span class="visually-hidden">You are here:</span>
            <?php yoast_breadcrumb(); ?>
        </nav>
    <?php endif; ?>
    
    <!-- Specials Archive Header -->
    <section class="post-header py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <h1 class="mb-2">Current Specials & Offers</h1>
                    <p class="lead mb-0">Limited-time promotions on our most popular procedures</p>
                </div>
                <div class="col-12 text-center mt-4">
                    <!-- Call to Action Button -->
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact-us'))); ?>" class="btn btn-primary">
                        Schedule a Consultation
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Filter/Category Navigation (Optional) -->
    <div class="bg-white border-bottom py-3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills special-filters justify-content-center justify-content-md-start">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">All Specials</a>
                        </li>
                        <?php 
                        // Get all terms for your specials taxonomy (if you have one)
                        $terms = get_terms(array(
                            'taxonomy' => 'special_category', // Update with your actual taxonomy name
                            'hide_empty' => true,
                        ));
                        
                        // Loop through terms
                        if (!empty($terms) && !is_wp_error($terms)) {
                            foreach ($terms as $term) {
                                echo '<li class="nav-item">';
                                echo '<a class="nav-link" href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                                echo '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Archive Content -->
    <section class="py-5">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="row g-4">
                    <?php 
                    // Counter to track items in each row
                    $counter = 0;
                    
                    while (have_posts()) : the_post(); 
                        // Custom fields - assuming you'll create these in ACF or similar
                        $regular_price = get_post_meta(get_the_ID(), 'regular_price', true);
                        $special_price = get_post_meta(get_the_ID(), 'special_price', true);
                        $expiration_date = get_post_meta(get_the_ID(), 'expiration_date', true);
                        $discount_percentage = !empty($regular_price) && !empty($special_price) ? 
                            round(100 - (($special_price / $regular_price) * 100)) : '';
                        
                        // Format expiration date if it exists
                        $formatted_date = !empty($expiration_date) ? date('F j, Y', strtotime($expiration_date)) : '';
                        
                        // Every third item (for desktop view)
                        $counter++;
                        $featured_class = ($counter == 1) ? 'special-featured' : '';
                    ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 special-card <?php echo esc_attr($featured_class); ?> shadow-sm">
                                <?php if (!empty($discount_percentage)) : ?>
                                    <div class="special-badge">
                                        <span><?php echo esc_html($discount_percentage); ?>% OFF</span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="card-img-container">
                                        <?php the_post_thumbnail('medium_large', ['class' => 'card-img-top']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h2 class="h4 card-title">
                                        <a href="<?php the_permalink(); ?>" class="stretched-link">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    
                                    <?php if (!empty($regular_price) && !empty($special_price)) : ?>
                                    <div class="special-pricing mb-3">
                                        <span class="regular-price text-decoration-line-through text-muted">$<?php echo esc_html($regular_price); ?></span>
                                        <span class="special-price text-primary h5 ms-2">$<?php echo esc_html($special_price); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-text">
                                        <?php the_excerpt(); ?>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-white border-top-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php if (!empty($formatted_date)) : ?>
                                            <small class="text-muted">Expires: <?php echo esc_html($formatted_date); ?></small>
                                        <?php endif; ?>
                                        <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <!-- Newsletter Signup (Between content and pagination) -->
                <div class="row my-5">
                    <div class="col-12">
                        <div class="card bg-light border-0">
                            <div class="card-body p-4 p-md-5 text-center">
                                <h3>Never Miss a Special Offer</h3>
                                <p class="mb-4">Subscribe to our newsletter to receive exclusive offers and updates</p>
                                <div class="row justify-content-center">
                                    <div class="col-md-8 col-lg-6">
                                        <form class="d-flex flex-column flex-md-row gap-2">
                                            <input type="email" class="form-control" placeholder="Your email address">
                                            <button type="submit" class="btn btn-primary">Subscribe</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col">
                        <?php the_posts_pagination([
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'class' => 'pagination justify-content-center',
                        ]); ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="row">
                    <div class="col text-center py-5">
                        <div class="py-4">
                            <h3>No Current Specials</h3>
                            <p>Check back soon for upcoming promotions and special offers.</p>
                            <a href="<?php echo esc_url(home_url()); ?>" class="btn btn-outline-primary mt-3">Return to Home</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8 text-center">
                    <h3>What Our Patients Are Saying</h3>
                    <p>Hear from patients who have taken advantage of our special offers</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="testimonial-slider">
                        <?php
                        // Query for testimonials - you can replace this with your actual testimonial system
                        $testimonials = new WP_Query(array(
                            'post_type' => 'testimonial',
                            'posts_per_page' => 3,
                        ));
                        
                        if ($testimonials->have_posts()) :
                            while ($testimonials->have_posts()) : $testimonials->the_post();
                            $rating = get_post_meta(get_the_ID(), 'rating', true);
                        ?>
                            <div class="p-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <?php if (!empty($rating)) : ?>
                                        <div class="mb-3">
                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                <i class="fas fa-star <?php echo ($i <= $rating) ? 'text-warning' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <blockquote class="mb-3">
                                            <?php the_content(); ?>
                                        </blockquote>
                                        
                                        <div class="d-flex align-items-center">
                                            <?php if (has_post_thumbnail()) : ?>
                                            <div class="flex-shrink-0 me-3">
                                                <?php the_post_thumbnail('thumbnail', ['class' => 'rounded-circle', 'style' => 'width: 50px; height: 50px; object-fit: cover;']); ?>
                                            </div>
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0"><?php the_title(); ?></h6>
                                                <?php $procedure = get_post_meta(get_the_ID(), 'procedure', true); ?>
                                                <?php if (!empty($procedure)) : ?>
                                                <small class="text-muted"><?php echo esc_html($procedure); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        else:
                        ?>
                            <div class="p-3">
                                <div class="card h-100">
                                    <div class="card-body p-4 text-center">
                                        <p>No testimonials found. Your success story could be featured here!</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8 text-center">
                    <h3>Frequently Asked Questions</h3>
                    <p>Common questions about our special offers and procedures</p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="specialsFaq">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                    How do I redeem a special offer?
                                </button>
                            </h2>
                            <div id="faqCollapse1" class="accordion-collapse collapse show" data-bs-parent="#specialsFaq">
                                <div class="accordion-body">
                                    Mention the special when booking your consultation. Our staff will apply the discount to your procedure when scheduled within the valid timeframe.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                    Can specials be combined with other offers?
                                </button>
                            </h2>
                            <div id="faqCollapse2" class="accordion-collapse collapse" data-bs-parent="#specialsFaq">
                                <div class="accordion-body">
                                    Generally, our special offers cannot be combined with other promotions. Each special applies to a specific procedure or service as described.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                    How long are specials typically available?
                                </button>
                            </h2>
                            <div id="faqCollapse3" class="accordion-collapse collapse" data-bs-parent="#specialsFaq">
                                <div class="accordion-body">
                                    Each special has its own expiration date clearly listed. Most offers are available for 1-3 months, but limited-time offers may be shorter.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                                    Do I need to pay in full to get the special price?
                                </button>
                            </h2>
                            <div id="faqCollapse4" class="accordion-collapse collapse" data-bs-parent="#specialsFaq">
                                <div class="accordion-body">
                                    Payment requirements vary by special. Some offers require payment in full at booking, while others work with our standard payment plans. Details are specified on each special offer page.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <h3 class="mb-2">Ready to Transform Your Look?</h3>
                    <p class="lead mb-0">Schedule a consultation today to learn more about our special offers</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact-us'))); ?>" class="btn btn-light btn-lg">Book Your Consultation</a>
                </div>
            </div>
        </div>
    </section>
</main>



<?php get_footer(); ?>
