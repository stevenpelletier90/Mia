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

    <!-- Hero Section -->
    <header class="surgeon-header py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1><?php the_title(); ?></h1>
                    <p class="surgeon-role mb-4">Plastic Surgeon at Mia Aesthetics</p>
                    <?php 
                    $location = get_field('surgeon_location');
                    if($location) : 
                        $location_title = get_the_title($location);
                    ?>
                    <p class="surgeon-location mb-4">Based Out of Our <?php echo esc_html($location_title); ?> Location</p>
                    <?php endif; ?>
                    <div class="credentials">
                        <span class="surgeon-badge me-2 mb-2">Board Certified</span>
                        <span class="surgeon-badge me-2 mb-2">Stanford University Medical School</span>
                        <span class="surgeon-badge me-2 mb-2">University of Pennsylvania</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <?php 
                    $video_details = get_field('video_details');
                    if($video_details && !empty($video_details['video_url'])) : 
                        $video_url = $video_details['video_url'];
                        $video_title = $video_details['video_title'];
                        $video_thumbnail = $video_details['video_thumbnail'];
                        $thumbnail_url = '';
                        
                        // Prepare YouTube URL with autoplay parameter
                        if(strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                            // Check if the URL already has parameters
                            if(strpos($video_url, '?') !== false) {
                                $video_url .= '&autoplay=1&mute=0';
                            } else {
                                $video_url .= '?autoplay=1&mute=0';
                            }
                        }
                        
                        if($video_thumbnail) {
                            $thumbnail_url = $video_thumbnail['url'];
                        } else {
                            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        }
                    ?>
                        <div class="video-container ratio ratio-16x9 shadow rounded overflow-hidden">
                            <div class="video-placeholder" style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');">
                                <div class="video-play-button">
                                    <span class="play-icon"><i class="fa-solid fa-play"></i></span>
                                </div>
                            </div>
                            <iframe 
                                id="surgeon-video" 
                                class="d-none"
                                src="" 
                                data-src="<?php echo esc_url($video_url); ?>" 
                                title="<?php echo esc_attr($video_title ? $video_title : get_the_title()); ?>" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    <?php elseif(has_post_thumbnail()) : ?>
                        <div class="ratio ratio-16x9 shadow rounded overflow-hidden">
                            <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="img-fluid">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <article class="py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content Column -->
                <div class="col-lg-8">
                    <!-- About Section -->
                    <section class="mb-5">
                        <h2 class="section-title mb-4">About <?php echo get_the_title(); ?></h2>
                        <?php 
                        // Display the main content from the default WordPress editor
                        the_content(); 
                        ?>
                    </section>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Contact Card -->
                    <div class="card consultation-card mb-4 shadow-sm">
                        <div class="card-body p-3">
                            <h3 class="h5 mb-4 text-center">Contact Information</h3>
                            <div class="mb-3">
                                <strong class="d-block mb-2">Office Location</strong>
                                <p class="mb-0">
                                    <?php
                                    $location = get_field('surgeon_location');
                                    if($location) {
                                        echo 'Mia Aesthetics ' . esc_html(get_the_title($location)) . '<br>';
                                    }
                                    
                                    $address = get_field('address');
                                    if($address) {
                                        echo nl2br(esc_html($address));
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="mb-4">
                                <button class="btn btn-primary w-100">Schedule Consultation</button>
                            </div>
                        </div>
                    </div>

                    <!-- Education & Training Card -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="h5 mb-4">Education & Training</h3>
                            <div class="mb-3">
                                <strong class="d-block mb-2">Undergraduate</strong>
                                <p>University of Pennsylvania - Bioengineering</p>
                            </div>
                            <div class="mb-3">
                                <strong class="d-block mb-2">Medical School</strong>
                                <p>Stanford University</p>
                            </div>
                            <div class="mb-0">
                                <strong class="d-block mb-2">Residency</strong>
                                <p class="mb-0">Loyola University Medical Center - Plastic Surgery</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <!-- FAQ Section - Optional -->
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

<script>
jQuery(document).ready(function($) {
    $('.video-play-button').on('click', function() {
        var videoContainer = $(this).closest('.video-container');
        var videoPlaceholder = videoContainer.find('.video-placeholder');
        var iframe = videoContainer.find('iframe');
        
        // Load the video src from data attribute
        iframe.attr('src', iframe.data('src'));
        
        // Hide placeholder and show iframe
        videoPlaceholder.hide();
        iframe.removeClass('d-none');
    });
});
</script>
<?php get_footer(); ?>