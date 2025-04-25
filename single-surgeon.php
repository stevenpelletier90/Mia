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

    <section class="surgeon-hero py-5" style="background-image: url('https://miaprod.wpenginepowered.com/wp-content/uploads/2025/04/atlanta.jpg');">
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
                    <p class="surgeon-location">Based Out of Our <?php echo esc_html($location_title); ?> Location</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Navigation Tabs (visible only on mobile) -->
    <section class="surgeon-tabs d-md-none mb-2">
        <div class="container">
            <div class="nav-scroller">
                <nav id="surgeon-tabs" class="nav nav-pills nav-justified">
                    <a class="nav-link" href="#surgeon-about">About</a>
                    <a class="nav-link" href="#surgeon-specialities">Specialities</a>
                    <a class="nav-link" href="#surgeon-before-after">Before & After</a>
                </nav>
            </div>
        </div>
    </section>

    <div class="pt-2 pb-5">
        <div class="container">
            <div class="row">
                <!-- Main Content Column -->
                <div class="col-lg-8">
                    <!-- About Section -->
                    <section id="surgeon-about" class="mb-5">
                        <h2 class="section-title mb-4">About <?php echo get_the_title(); ?></h2>
                        <?php the_content(); ?>
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
                                <div class="col-md-6 mb-4">
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
                
                <!-- Sidebar Column -->
                <div class="col-lg-4">
                    <div class="surgeon-sidebar">
                        <!-- Before & After Gallery Section -->
                        <section id="surgeon-before-after" class="mb-5 sidebar-section">
                            <h2 class="section-title mb-4">Before & After</h2>
                            <div class="card cta-card text-decoration-none text-dark shadow-sm mb-4">
                                <img src="https://placehold.co/400x250" class="card-img-top" alt="Before and After Gallery Preview">
                                <div class="card-body p-3">
                                    <h3 class="h5 card-title mb-2 text-center">Before & After Gallery</h3>
                                    <p class="card-text text-center small">See the amazing results of procedures performed by <?php echo get_the_title(); ?>.</p>
                                    <div class="text-center mt-3">
                                        <a href="#" class="mia-button mia-button-gold">View Gallery</a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- CTA Buttons -->
                            <div class="sidebar-cta-buttons">
                                <a href="#" class="btn btn-sidebar mb-3 w-100">
                                    <i class="fa-solid fa-road-circle-check me-2"></i>Surgical Journey
                                </a>
                                <a href="#" class="btn btn-sidebar mb-3 w-100">
                                    <i class="fa-solid fa-plane-departure me-2"></i>Out of Town Patients
                                </a>
                                <a href="#" class="btn btn-sidebar w-100">
                                    <i class="fa-solid fa-credit-card me-2"></i>Financing
                                </a>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        
        // Get all sections that we want to scroll to
        const sections = document.querySelectorAll('#surgeon-about, #surgeon-before-after, #surgeon-specialities');
        
        // Add click event to each nav link
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get the target section
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                if (targetSection) {
                    // Smooth scroll to the section
                    targetSection.scrollIntoView({ behavior: 'smooth' });
                    
                    // Update URL hash without scrolling
                    history.pushState(null, null, targetId);
                    
                    // Update active state
                    navLinks.forEach(navLink => navLink.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });

        // Function to set active tab based on scroll position
        const setActiveTabOnScroll = () => {
            // Get current scroll position
            const scrollPosition = window.scrollY;
            
            // Find the current section
            let currentSection = sections[0];
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 160; // Adjust offset to account for sticky header
                if (scrollPosition >= sectionTop) {
                    currentSection = section;
                }
            });
            
            // Set active class on the corresponding nav link
            const currentId = currentSection.getAttribute('id');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === `#${currentId}`) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        };
        
        // Set active tab on page load
        if (navLinks.length > 0) {
            navLinks[0].classList.add('active');
        }
        
        // Listen for scroll events
        window.addEventListener('scroll', setActiveTabOnScroll);
        
        // Initial check for active section
        setActiveTabOnScroll();
    })();
    </script>
</main>

<?php get_footer(); ?>
