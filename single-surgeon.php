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

    <div class="surgeon-hero-section">
        <div class="container">
            <div class="row align-items-center g-0">
                <div class="col-md-3 col-lg-2 mb-4 mb-md-0">
                    <?php 
                    // Get surgeon headshot
                    $headshot_id = get_field('surgeon_headshot');
                    if($headshot_id): 
                        // Get image URL from ID
                        $headshot_url = wp_get_attachment_image_url($headshot_id, 'full');
                        ?>
                        <div class="surgeon-headshot">
                            <img src="<?php echo esc_url($headshot_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="img-fluid rounded-circle">
                        </div>
                    <?php else: ?>
                        <div class="surgeon-headshot surgeon-headshot-placeholder">
                            <div class="placeholder-circle">
                                <i class="fa-solid fa-user-doctor"></i>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-9 col-lg-10">
                    <div class="surgeon-hero-content ms-md-4">
                        <h1><?php echo get_the_title(); ?></h1>
                        <?php 
                        // Get surgeon location
                        $location = get_field('surgeon_location');
                        if($location): 
                            // Get location title and remove state abbreviation (e.g., ", IL")
                            $location_title = get_the_title($location);
                            $location_title = preg_replace('/, [A-Z]{2}$/', '', $location_title);
                        ?>
                            <p class="surgeon-location">Plastic Surgeon at Mia Aesthetics <?php echo $location_title; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Surgeon Navigation - Only visible on mobile -->
    <div class="surgeon-mobile-nav d-md-none sticky-top" style="top: var(--navbar-height);">
        <div class="container">
            <div class="surgeon-nav-buttons">
                <a href="#surgeon-about" class="surgeon-nav-btn active">About</a>
                <a href="#surgeon-specialities" class="surgeon-nav-btn">Specialities</a>
                <a href="#surgeon-before-after" class="surgeon-nav-btn">Before & After</a>
            </div>
        </div>
    </div>

    <div class="surgeon-content-section">
        <div class="container">
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
            <!-- Video Section (visible on mobile before content) -->
            <div class="row d-lg-none">
                <div class="col-12">
                    <section id="surgeon-video-mobile" class="sidebar-section" style="border-radius: 0;">
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
                </div>
            </div>
            <?php endif; ?>

            <div class="row">
                <!-- Main Content Column -->
                <div class="col-lg-8">
                    <!-- About Section -->
                    <section id="surgeon-about">
                        <h2 class="section-title">About <?php echo get_the_title(); ?></h2>
                        <?php the_content(); ?>
                    </section>

                    <?php 
                    // Get specialties from the ACF relationship field
                    $specialties = get_field('specialties');
                    
                    // Only show the section if specialties are selected
                    if($specialties && !empty($specialties)): 
                    ?>
                    <!-- Specialities Section (Redesigned) -->
                    <section id="surgeon-specialities">
                        <h2 class="section-title">Specialities</h2>
                        <div class="row">
                            <?php foreach($specialties as $specialty): ?>
                                <div class="col-md-6 specialty-card-wrapper">
                                    <a href="<?php echo get_permalink($specialty->ID); ?>" class="card cta-card text-decoration-none text-dark d-block h-100">
                                        <div class="card-body">
                                            <h3 class="h5 card-title"><?php echo get_the_title($specialty->ID); ?></h3>
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
                        <?php if($video_url): ?>
                        <!-- Video Section (visible only on desktop) -->
                        <section id="surgeon-video" class="sidebar-section d-none d-lg-block" style="border-radius: 0;">
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
                        <section id="surgeon-before-after" class="sidebar-section">
                            <div class="card cta-card text-decoration-none text-dark">
                                <img src="https://placehold.co/400x250" class="card-img-top" alt="Before and After Gallery Preview">
                                <div class="card-body">
                                    <h3 class="h5 card-title text-center">Before & After Gallery</h3>
                                    <p class="card-text text-center small">See the amazing results of procedures performed by <?php echo get_the_title(); ?>.</p>
                                    <div class="text-center card-action">
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
    <section class="faq-section">
        <div class="container">
            <div class="faq-container">
                <?php echo display_page_faqs(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>

<script>
// Function to calculate and set the hero section height
function setSurgeonHeroHeight() {
    // Get the elements
    const header = document.querySelector('header');
    const breadcrumbs = document.getElementById('breadcrumbs');
    const mobileCta = document.querySelector('.mobile-cta-container');
    const mobileNav = document.querySelector('.surgeon-mobile-nav');
    const surgeonHero = document.querySelector('.surgeon-hero-section');
    
    if (!surgeonHero) return;
    
    // Check if we're on mobile (max-width: 767px)
    if (window.innerWidth <= 767) {
        // Get the heights
        const headerHeight = header ? header.offsetHeight : 0;
        const breadcrumbsHeight = breadcrumbs ? breadcrumbs.offsetHeight : 0;
        const mobileCtaHeight = mobileCta ? mobileCta.offsetHeight : 0;
        const mobileNavHeight = mobileNav ? mobileNav.offsetHeight : 0;
        
        // Calculate the total height to subtract
        const subtractHeight = headerHeight + breadcrumbsHeight + mobileCtaHeight + mobileNavHeight;
        
        // Get the actual viewport height (works better on iOS)
        const windowHeight = window.innerHeight;
        
        // Set the hero height with a minimum to prevent it from becoming too small
        const calculatedHeight = windowHeight - subtractHeight;
        const minHeight = 250; // Minimum height in pixels
        
        // Use the larger of the calculated height or minimum height
        surgeonHero.style.height = `${Math.max(calculatedHeight, minHeight)}px`;
        
        console.log('Mobile view detected');
        console.log('Window height:', windowHeight);
        console.log('Header height:', headerHeight);
        console.log('Breadcrumbs height:', breadcrumbsHeight);
        console.log('Mobile CTA height:', mobileCtaHeight);
        console.log('Mobile Nav height:', mobileNavHeight);
        console.log('Total height subtracted:', subtractHeight);
        console.log('Calculated height:', calculatedHeight);
        console.log('Final height:', Math.max(calculatedHeight, minHeight));
    } else {
        // On desktop, use the CSS default (350px)
        surgeonHero.style.height = '';
    }
}

// Function to handle active state of navigation buttons
function handleNavActiveState() {
    const navBtns = document.querySelectorAll('.surgeon-nav-btn');
    const sections = Array.from(navBtns).map(btn => {
        const targetId = btn.getAttribute('href').substring(1);
        return document.getElementById(targetId);
    });
    
    if (navBtns.length === 0 || sections.some(section => !section)) return;
    
    // Get current scroll position
    const scrollPosition = window.scrollY;
    
    // Find which section is currently in view
    let activeIndex = 0;
    sections.forEach((section, index) => {
        if (!section) return;
        
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        
        if (scrollPosition >= sectionTop - 200 && 
            scrollPosition < sectionTop + sectionHeight - 200) {
            activeIndex = index;
        }
    });
    
    // Update active class
    navBtns.forEach((btn, index) => {
        if (index === activeIndex) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
}

// Run on page load
document.addEventListener('DOMContentLoaded', function() {
    setSurgeonHeroHeight();
    
    // Add click event listeners to nav buttons for smooth scrolling
    document.querySelectorAll('.surgeon-nav-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const headerHeight = document.querySelector('header').offsetHeight;
                const mobileNavHeight = document.querySelector('.surgeon-mobile-nav').offsetHeight;
                const offset = headerHeight + mobileNavHeight;
                
                const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - offset;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Run on window resize
window.addEventListener('resize', setSurgeonHeroHeight);

// Run on scroll
window.addEventListener('scroll', handleNavActiveState);

// Run after a short delay to ensure all elements are fully rendered
setTimeout(function() {
    setSurgeonHeroHeight();
    handleNavActiveState();
}, 500);
</script>
