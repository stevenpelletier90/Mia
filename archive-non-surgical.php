<?php
/**
 * Non-Surgical Archive Template
 * Organizes non-surgical procedures into categories
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
    
    <!-- Archive Header -->
    <section class="post-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="mb-2">Our Non-Surgical Procedures</h1>
                    <p class="lead mb-0">Transform your appearance with our advanced non-surgical treatments and procedures.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Non-Surgical Categories -->
    <section class="procedure-categories py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Injectable Treatments -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card h-100 procedure-category-card">
                        <div class="category-image-container position-relative overflow-hidden">
                            <picture>
                                <source media="(max-width: 576px)" 
                                        srcset="/wp-content/uploads/2025/04/injectables-home.jpg?w=400&h=300&crop=1" 
                                        width="400" height="300">
                                <source media="(max-width: 768px)" 
                                        srcset="/wp-content/uploads/2025/04/injectables-home.jpg?w=600&h=400&crop=1" 
                                        width="600" height="400">
                                <img src="/wp-content/uploads/2025/04/injectables-home.jpg?w=800&h=600&crop=1" 
                                     alt="Injectable Treatments - Enhance your features with our expert injectable procedures" 
                                     class="card-img-top w-100 h-100 object-fit-cover"
                                     width="800" height="600"
                                     loading="lazy">
                            </picture>
                            <div class="category-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end">
                                <div class="overlay-content p-3 text-white w-100">
                                    <span class="category-badge px-2 py-1 rounded-pill small">Injectable Treatments</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h2 class="h4 mb-3">Injectable Treatments</h2>
                            <p class="card-text mb-3">Enhance your features with our expert injectable procedures.</p>
                            <ul class="list-unstyled procedure-links">
                                <?php
                                $injectable_query = new WP_Query([
                                    'post_type' => 'non-surgical',
                                    'meta_query' => [
                                        [
                                            'key' => 'procedure_category',
                                            'value' => 'injectable',
                                            'compare' => 'LIKE'
                                        ]
                                    ],
                                    'posts_per_page' => -1,
                                    'orderby' => 'title',
                                    'order' => 'ASC'
                                ]);
                                
                                if ($injectable_query->have_posts()):
                                    while ($injectable_query->have_posts()): $injectable_query->the_post(); ?>
                                        <li><a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a></li>
                                    <?php endwhile;
                                    wp_reset_postdata();
                                endif; ?>
                                <li><a href="<?php echo esc_url(home_url('/non-surgical-procedures/injectable/')); ?>" class="text-decoration-none"><strong>View All Injectable Treatments</strong></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Skin Treatments -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card h-100 procedure-category-card">
                        <div class="category-image-container position-relative overflow-hidden">
                            <picture>
                                <source media="(max-width: 576px)" 
                                        srcset="/wp-content/uploads/2025/04/skin-treatments-home.jpg?w=400&h=300&crop=1" 
                                        width="400" height="300">
                                <source media="(max-width: 768px)" 
                                        srcset="/wp-content/uploads/2025/04/skin-treatments-home.jpg?w=600&h=400&crop=1" 
                                        width="600" height="400">
                                <img src="/wp-content/uploads/2025/04/skin-treatments-home.jpg?w=800&h=600&crop=1" 
                                     alt="Skin Treatments - Rejuvenate your skin with our advanced treatment options" 
                                     class="card-img-top w-100 h-100 object-fit-cover"
                                     width="800" height="600"
                                     loading="lazy">
                            </picture>
                            <div class="category-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end">
                                <div class="overlay-content p-3 text-white w-100">
                                    <span class="category-badge px-2 py-1 rounded-pill small">Skin Treatments</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h2 class="h4 mb-3">Skin Treatments</h2>
                            <p class="card-text mb-3">Rejuvenate your skin with our advanced treatment options.</p>
                            <ul class="list-unstyled procedure-links">
                                <?php
                                $skin_query = new WP_Query([
                                    'post_type' => 'non-surgical',
                                    'meta_query' => [
                                        [
                                            'key' => 'procedure_category',
                                            'value' => 'skin',
                                            'compare' => 'LIKE'
                                        ]
                                    ],
                                    'posts_per_page' => -1,
                                    'orderby' => 'title',
                                    'order' => 'ASC'
                                ]);
                                
                                if ($skin_query->have_posts()):
                                    while ($skin_query->have_posts()): $skin_query->the_post(); ?>
                                        <li><a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a></li>
                                    <?php endwhile;
                                    wp_reset_postdata();
                                endif; ?>
                                <li><a href="<?php echo esc_url(home_url('/non-surgical-procedures/skin/')); ?>" class="text-decoration-none"><strong>View All Skin Treatments</strong></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Body Contouring -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card h-100 procedure-category-card">
                        <div class="category-image-container position-relative overflow-hidden">
                            <picture>
                                <source media="(max-width: 576px)" 
                                        srcset="/wp-content/uploads/2025/04/body-contouring-home.jpg?w=400&h=300&crop=1" 
                                        width="400" height="300">
                                <source media="(max-width: 768px)" 
                                        srcset="/wp-content/uploads/2025/04/body-contouring-home.jpg?w=600&h=400&crop=1" 
                                        width="600" height="400">
                                <img src="/wp-content/uploads/2025/04/body-contouring-home.jpg?w=800&h=600&crop=1" 
                                     alt="Body Contouring - Non-surgical body shaping and contouring treatments" 
                                     class="card-img-top w-100 h-100 object-fit-cover"
                                     width="800" height="600"
                                     loading="lazy">
                            </picture>
                            <div class="category-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end">
                                <div class="overlay-content p-3 text-white w-100">
                                    <span class="category-badge px-2 py-1 rounded-pill small">Body Contouring</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h2 class="h4 mb-3">Body Contouring</h2>
                            <p class="card-text mb-3">Non-surgical body shaping and contouring treatments.</p>
                            <ul class="list-unstyled procedure-links">
                                <?php
                                $body_query = new WP_Query([
                                    'post_type' => 'non-surgical',
                                    'meta_query' => [
                                        [
                                            'key' => 'procedure_category',
                                            'value' => 'body',
                                            'compare' => 'LIKE'
                                        ]
                                    ],
                                    'posts_per_page' => -1,
                                    'orderby' => 'title',
                                    'order' => 'ASC'
                                ]);
                                
                                if ($body_query->have_posts()):
                                    while ($body_query->have_posts()): $body_query->the_post(); ?>
                                        <li><a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a></li>
                                    <?php endwhile;
                                    wp_reset_postdata();
                                endif; ?>
                                <li><a href="<?php echo esc_url(home_url('/non-surgical-procedures/body/')); ?>" class="text-decoration-none"><strong>View All Body Contouring</strong></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Wellness Treatments -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card h-100 procedure-category-card">
                        <div class="category-image-container position-relative overflow-hidden">
                            <picture>
                                <source media="(max-width: 576px)" 
                                        srcset="/wp-content/uploads/2025/04/wellness-home.jpg?w=400&h=300&crop=1" 
                                        width="400" height="300">
                                <source media="(max-width: 768px)" 
                                        srcset="/wp-content/uploads/2025/04/wellness-home.jpg?w=600&h=400&crop=1" 
                                        width="600" height="400">
                                <img src="/wp-content/uploads/2025/04/wellness-home.jpg?w=800&h=600&crop=1" 
                                     alt="Wellness Treatments - Comprehensive wellness and beauty treatments" 
                                     class="card-img-top w-100 h-100 object-fit-cover"
                                     width="800" height="600"
                                     loading="lazy">
                            </picture>
                            <div class="category-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end">
                                <div class="overlay-content p-3 text-white w-100">
                                    <span class="category-badge px-2 py-1 rounded-pill small">Wellness Treatments</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h2 class="h4 mb-3">Wellness Treatments</h2>
                            <p class="card-text mb-3">Comprehensive wellness and beauty treatments.</p>
                            <ul class="list-unstyled procedure-links">
                                <?php
                                $wellness_query = new WP_Query([
                                    'post_type' => 'non-surgical',
                                    'meta_query' => [
                                        [
                                            'key' => 'procedure_category',
                                            'value' => 'wellness',
                                            'compare' => 'LIKE'
                                        ]
                                    ],
                                    'posts_per_page' => -1,
                                    'orderby' => 'title',
                                    'order' => 'ASC'
                                ]);
                                
                                if ($wellness_query->have_posts()):
                                    while ($wellness_query->have_posts()): $wellness_query->the_post(); ?>
                                        <li><a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a></li>
                                    <?php endwhile;
                                    wp_reset_postdata();
                                endif; ?>
                                <li><a href="<?php echo esc_url(home_url('/non-surgical-procedures/wellness/')); ?>" class="text-decoration-none"><strong>View All Wellness Treatments</strong></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Video Section -->
    <section class="procedure-videos py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-heading mb-3">Watch Our Featured Videos</h2>
                    <p class="lead text-muted">Learn more about our non-surgical procedures and patient experiences.</p>
                </div>
            </div>
            <?php
            // All testimonial videos loaded in a single array for consistency
            $video_data = array(
                array(
                    'id' => 'OxigXlYTqH8',
                    'title' => 'Featured Video 1'
                ),
                array(
                    'id' => 'sb8Kapy8mzU',
                    'title' => 'Featured Video 2'
                ),
                array(
                    'id' => '4-B_ISCne28',
                    'title' => 'Featured Video 3'
                ),
                array(
                    'id' => 'ykz9Z8Kh3Yo',
                    'title' => 'Featured Video 4'
                ),             
                array(
                    'id' => 'oxp3W3KY0hc',
                    'title' => 'Non-Surgical Treatment Testimonial'
                ),
                array(
                    'id' => 'b6b0KaW2cXE',
                    'title' => 'Injectable Treatment Testimonial'
                )
            );
            ?>
            <div class="row g-4">
                <?php foreach ($video_data as $video) : ?>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card h-100 procedure-video-card">
                            <div class="ratio ratio-16x9 rounded-top overflow-hidden">
                                <iframe 
                                    src="https://www.youtube.com/embed/<?php echo esc_attr($video['id']); ?>" 
                                    title="<?php echo esc_attr($video['title']); ?>" 
                                    allowfullscreen
                                    loading="lazy"
                                    tabindex="0"
                                    aria-label="<?php echo esc_attr($video['title']); ?> video"></iframe>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


</main>

<?php get_footer(); ?>