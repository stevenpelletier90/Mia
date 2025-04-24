<?php
/**
 * Template for displaying single surgeon
 */
get_header(); ?>
<main tabindex="0">
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

    <!-- Navigation Tabs (visible on all devices) -->
    <section class="surgeon-tabs">
        <div class="container">
            <div class="nav-scroller">
                <nav id="surgeon-tabs" class="nav nav-pills nav-justified">
                    <a class="nav-link" href="#surgeon-about">About</a>
                    <a class="nav-link" href="#surgeon-before-after">Before & After</a>
                    <a class="nav-link" href="#surgeon-specialities">Specialities</a>
                </nav>
            </div>
        </div>
    </section>

    <article class="py-5">
        <div class="container">
            <!-- Single Column Layout -->
            <section id="surgeon-about" class="mb-5">
                <h2 class="section-title mb-4">About <?php echo get_the_title(); ?></h2>
                <?php the_content(); ?>
            </section>

            <!-- Before & After Gallery Section -->
            <section id="surgeon-before-after" class="mb-5">
                <h2 class="section-title mb-4">Before & After</h2>
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="#" class="card cta-card text-decoration-none text-dark shadow-sm d-block h-100">
                            <img src="https://placehold.co/400x250" class="card-img-top" alt="Before and After Gallery Preview">
                            <div class="card-body p-3">
                                <h3 class="h5 card-title mb-2 text-center">Before & After Gallery</h3>
                                <p class="card-text text-center small">See the amazing results of procedures performed by <?php echo get_the_title(); ?>.</p>
                            </div>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Specialities Section (Redesigned) -->
            <section id="surgeon-specialities" class="mb-5">
                <h2 class="section-title mb-4">Specialities</h2>
                <div class="row">
                    <?php 
                    $specialized_procedures = array(
                        'Breast Augmentation',
                        'Brazilian Butt Lift',
                        'Tummy Tuck',
                        'Liposuction',
                        'Mommy Makeover'
                    );
                    
                    if(!empty($specialized_procedures)) :
                        foreach($specialized_procedures as $procedure) :
                    ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <a href="#" class="card cta-card text-decoration-none text-dark shadow-sm d-block h-100">
                                <div class="card-body p-3">
                                    <h3 class="h5 card-title mb-2"><?php echo esc_html($procedure); ?></h3>
                                    <p class="card-text small">Learn more about this specialized procedure.</p>
                                    <div class="text-end">
                                        <i class="fa-solid fa-arrow-right procedure-arrow"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                        endforeach; 
                    endif;
                    ?>
                </div>
            </section>
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

    <!-- Tab Navigation Script -->
    <script>
    (function() {
        // Get all nav links
        const navLinks = document.querySelectorAll('#surgeon-tabs .nav-link');
        
        // Add click event to each nav link
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Remove active class from all links
                navLinks.forEach(navLink => navLink.classList.remove('active'));
                // Add active class to clicked link
                this.classList.add('active');
            });
        });

        // Check if URL has a hash on page load
        const setActiveTabFromHash = () => {
            const hash = window.location.hash;
            if (hash) {
                // Remove active class from all links
                navLinks.forEach(navLink => navLink.classList.remove('active'));
                // Find the link that matches the hash and add active class
                const activeLink = document.querySelector(`#surgeon-tabs .nav-link[href="${hash}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            } else if (navLinks.length > 0) {
                // If no hash, set first tab as active
                navLinks[0].classList.add('active');
            }
        };

        // Set active tab on page load
        setActiveTabFromHash();
        
        // Listen for hash changes
        window.addEventListener('hashchange', setActiveTabFromHash);
    })();
    </script>
</main>

<?php get_footer(); ?>
