<?php
/**
 * Template for displaying single surgeon
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

    <section class="surgeon-hero py-5">
        <div class="container">
            <div class="row align-items-center"> <!-- Removed g-0 to allow for natural spacing -->
                <div class="col-md-4 col-lg-3 order-md-1 order-1 mb-4 mb-md-0"> <!-- Reduced to col-lg-3 to decrease space -->
                    <?php 
                    $surgeon_headshot_id = get_field('surgeon_headshot');
                    if($surgeon_headshot_id && is_numeric($surgeon_headshot_id)) : ?>
                        <div class="surgeon-headshot-container text-center text-md-start">
                            <?php 
                            echo wp_get_attachment_image(
                                $surgeon_headshot_id, 
                                'medium', 
                                false, 
                                array(
                                    'class' => 'img-fluid surgeon-headshot',
                                    'alt' => get_the_title() . ' Headshot'
                                )
                            ); 
                            ?>
                        </div>
                    <?php elseif(has_post_thumbnail()) : ?>
                        <div class="surgeon-headshot-container text-center text-md-start">
                            <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>" 
                                 alt="<?php echo esc_attr(get_the_title()); ?>" 
                                 class="img-fluid surgeon-headshot">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-8 col-lg-9 order-md-2 order-2 surgeon-info">
                    <h1><?php the_title(); ?></h1>
                    <p class="surgeon-role mb-4">Plastic Surgeon at Mia Aesthetics</p>
                    <?php 
                    $location = get_field('surgeon_location');
                    if($location) : 
                        $location_title = get_the_title($location);
                    ?>
                    <p class="surgeon-location mb-4">Based Out of Our <?php echo esc_html($location_title); ?> Location</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Mobile-Only Navigation Tabs -->
    <section class="surgeon-mobile-tabs d-md-none">
        <div class="container">
            <div class="nav-scroller">
                <nav class="nav nav-pills nav-justified">
                    <a class="nav-link active" href="#about">About</a>
                    <a class="nav-link" href="#before-after">Before & After</a>
                    <a class="nav-link" href="#specialities">Specialities</a>
                </nav>
            </div>
        </div>
    </section>

    <article class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <section id="about" class="mb-5">
                        <h2 class="section-title mb-4">About <?php echo get_the_title(); ?></h2>
                        <?php the_content(); ?>
                    </section>
                </div>

                <div class="col-lg-4">
                    <!-- Before & After Gallery CTA Card -->
                    <a id="before-after" href="#" class="card cta-card text-decoration-none text-dark mb-4 shadow-sm d-block">
                        <img src="https://placehold.co/400x250" class="card-img-top" alt="Before and After Gallery Preview">
                        <div class="card-body p-3">
                            <h3 class="h5 card-title mb-2 text-center">Before & After Gallery</h3>
                            <p class="card-text text-center small">See the amazing results of procedures performed by <?php echo get_the_title(); ?>.</p>
                        </div>
                    </a>

                    <div id="specialities" class="card shadow-sm procedures-card">
                        <div class="card-body">
                            <h3 class="h5 mb-4">Specialized Procedures</h3>
                            <?php 
                            $specialized_procedures = array(
                                'Breast Augmentation',
                                'Brazilian Butt Lift',
                                'Tummy Tuck',
                                'Liposuction',
                                'Mommy Makeover'
                            );
                            
                            if(!empty($specialized_procedures)) : 
                                echo '<ul class="list-unstyled mb-0">';
                                foreach($specialized_procedures as $procedure) :
                                    // Wrap list item content in a link, add flex classes, set text white, remove text decoration
                                    echo '<li class="mb-1">'; // Reduced bottom margin slightly
                                    echo '<a href="#" class="d-flex justify-content-between align-items-center text-white text-decoration-none py-2 procedure-link">';
                                    echo '<span>' . esc_html($procedure) . '</span>';
                                    // Add right arrow icon with custom class for styling
                                    echo '<i class="fa-solid fa-arrow-right procedure-arrow"></i>';
                                    echo '</a>';
                                    echo '</li>';
                                endforeach;
                                echo '</ul>';
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

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

<?php get_footer(); ?>
