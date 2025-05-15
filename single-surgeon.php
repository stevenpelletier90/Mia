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
                            // Get location URL
                            $location_url = get_permalink($location);
                        ?>
                            <p class="surgeon-location">Plastic Surgeon at <a href="<?php echo esc_url($location_url); ?>"> <?php echo $location_title; ?></a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Surgeon Navigation - Only visible on mobile -->
    <div id="surgeon-mobile-nav" class="surgeon-mobile-nav d-md-none sticky-top" style="top: var(--navbar-height);">
        <div class="container">
            <div class="surgeon-nav-buttons">
                <a href="#surgeon-about" class="surgeon-nav-btn">About</a>
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
                    <div class="sidebar-section" style="border-radius: 0;">
                        <div class="video-container">
                            <iframe 
                                src="<?php echo esc_url($embed_url); ?>" 
                                title="<?php echo esc_attr(get_the_title()); ?> Video"
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
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

                    <!-- Specialities Section -->
                    <section id="surgeon-specialities">
                        <h2 class="section-title">Specialities</h2>
                        <?php 
                        // Get specialties from the ACF relationship field
                        $specialties = get_field('specialties');
                        
                        // Only show specialties if they exist
                        if($specialties && !empty($specialties)): 
                        ?>
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
                        <?php else: ?>
                            <p>No specialities available for this surgeon.</p>
                        <?php endif; ?>
                    </section>
                </div>
                
                <!-- Sidebar Column -->
                <div class="col-lg-4">
                    <div class="surgeon-sidebar">
                        <?php if($video_url): ?>
                        <!-- Video Section (visible only on desktop) -->
                        <div class="sidebar-section d-none d-lg-block" style="border-radius: 0;">
                            <div class="video-container">
                                <iframe 
                                    src="<?php echo esc_url($embed_url); ?>" 
                                    title="<?php echo esc_attr(get_the_title()); ?> Video"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
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
