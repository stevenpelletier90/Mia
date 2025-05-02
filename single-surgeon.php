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
                    <h1 class="surgeon-name"><?php the_title(); ?></h1>
                    <?php 
                    $location = get_field('surgeon_location');
                    if($location) : 
                        $location_title = get_the_title($location);
                        $location_url = get_permalink($location);
                    ?>
                    <p class="surgeon-role mb-md-4 mb-0">
                        Plastic Surgeon at 
                        <a href="<?php echo esc_url($location_url); ?>" class="surgeon-title-link">
                            Mia Aesthetics <?php echo esc_html($location_title); ?>
                        </a>
                    </p>
                    <?php else: ?>
                    <p class="surgeon-role mb-md-4 mb-0">Plastic Surgeon at Mia Aesthetics</p>
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

    <div class="py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content Column -->
                <div class="col-lg-8">
                    <!-- About Section -->
                    <section id="surgeon-about" class="mb-5">
                        <h2 class="section-title mb-4">About <?php echo get_the_title(); ?></h2>
                        <?php the_content(); ?>
                    </section>


                    <?php 
                    // Get specialties from the ACF relationship field
                    $specialties = get_field('specialties');
                    
                    // Only show the section if specialties are selected
                    if($specialties && !empty($specialties)): 
                    ?>
                    <!-- Specialities Section (Redesigned) -->
                    <section id="surgeon-specialities" class="mb-5">
                        <h2 class="section-title mb-4">Specialities</h2>
                        <div class="row">
                            <?php foreach($specialties as $specialty): ?>
                                <div class="col-md-6 mb-4">
                                    <a href="<?php echo get_permalink($specialty->ID); ?>" class="card cta-card text-decoration-none text-dark d-block h-100">
                                        <div class="card-body p-3">
                                            <h3 class="h5 card-title mb-2"><?php echo get_the_title($specialty->ID); ?></h3>
                                            <?php 
                                            // Get the excerpt, fallback to a portion of the content if no excerpt exists
                                            $excerpt = get_the_excerpt($specialty->ID);
                                            if(empty($excerpt)) {
                                                $content = get_post_field('post_content', $specialty->ID);
                                                $excerpt = wp_trim_words($content, 20, '...');
                                            }
                                            ?>
                                            <p class="card-text small"><?php echo $excerpt; ?></p>
                                            <div class="text-end">
                                                <span>Learn More <i class="fa-solid fa-arrow-right procedure-arrow"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar Column -->
                <div class="col-lg-4">
                    <div class="surgeon-sidebar">
                        <?php 
                        // Check if there's a video URL in the ACF field
                        $video_url = get_field('video_details_video_url');
                        if($video_url): 
                            // Function to normalize YouTube URL to embed format
                            function get_youtube_embed_url($youtube_url) {
                                // Extract video ID from various YouTube URL formats
                                $video_id = '';
                                
                                // Match standard YouTube URLs (youtu.be and youtube.com)
                                if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
                                    $video_id = $matches[1];
                                } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
                                    $video_id = $matches[1];
                                } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
                                    $video_id = $matches[1];
                                } elseif (preg_match('/youtube\.com\/v\/([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
                                    $video_id = $matches[1];
                                } elseif (preg_match('/youtube\.com\/user\/\w+\/\?v=([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
                                    $video_id = $matches[1];
                                }
                                
                                // If we found a video ID, return the embed URL
                                if ($video_id) {
                                    return 'https://www.youtube.com/embed/' . $video_id;
                                }
                                
                                // If no valid YouTube URL format was found, return the original URL
                                return $youtube_url;
                            }
                            
                            // Get embed URL
                            $embed_url = get_youtube_embed_url($video_url);
                        ?>
                        <!-- Video Section -->
                        <section id="surgeon-video" class="mb-4 sidebar-section">
                            <div class="video-container">
                                <iframe 
                                    src="<?php echo esc_url($embed_url); ?>" 
                                    title="<?php echo esc_attr(get_the_title()); ?> Video"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </section>
                        <?php endif; ?>

                        <!-- Before & After Gallery Section -->
                        <section id="surgeon-before-after" class="mb-5 sidebar-section">
                            <div class="card cta-card text-decoration-none text-dark mb-4">
                                <img src="https://placehold.co/400x250" class="card-img-top" alt="Before and After Gallery Preview">
                                <div class="card-body p-3">
                                    <h3 class="h5 card-title mb-2 text-center">Before & After Gallery</h3>
                                    <p class="card-text text-center small">See the amazing results of procedures performed by <?php echo get_the_title(); ?>.</p>
                                    <div class="text-center mt-3">
                                        <a href="#" class="mia-button mia-button-gold">View Gallery</a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Enhanced CTA Buttons -->
                            <div class="sidebar-cta-buttons">
                                <a href="#" class="btn btn-sidebar">
                                    <i class="fa-solid fa-road-circle-check"></i>
                                    <span>Surgical Journey</span>
                                </a>
                                <a href="#" class="btn btn-sidebar">
                                    <i class="fa-solid fa-plane-departure"></i>
                                    <span>Out of Town Patients</span>
                                </a>
                                <a href="#" class="btn btn-sidebar">
                                    <i class="fa-solid fa-credit-card"></i>
                                    <span>Financing</span>
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
        
        // Get all sections that we want to scroll to for navigation purposes
        const sections = document.querySelectorAll('#surgeon-about, #surgeon-specialities, #surgeon-before-after');
        
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
