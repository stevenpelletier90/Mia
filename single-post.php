<?php
/**
 * The template for displaying single blog posts
 *
 * This is an enhanced template that includes features like:
 * - Reading time calculation
 * - Author information and avatar
 * - Category and tag display
 * - Breadcrumb navigation
 * - Related posts
 * - Schema.org structured data for SEO
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mia_Aesthetics
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
    
    <?php while (have_posts()) : the_post(); 
        // Calculate reading time
        $content = get_the_content();
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // Assume 200 words per minute
    ?>

        <!-- Article Header -->
        <header class="bg-light py-4">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Meta information -->
                        <div class="mb-3">
                            <?php
                            $categories = get_the_category();
                            if ($categories) {
                                foreach($categories as $category) {
                                    echo '<a href="' . get_category_link($category->term_id) . '" class="text-decoration-none me-2 text-primary">#' . $category->name . '</a>';
                                }
                            }
                            ?>
                        </div>
                        
                        <h1 class="mb-3"><?php the_title(); ?></h1>
                        
                        <div class="d-flex align-items-center text-muted small mb-3">
                            <?php 
                            $author_id = get_the_author_meta('ID');
                            $author_avatar = get_avatar_url($author_id, ['size' => 40]);
                            ?>
                            <img src="<?php echo $author_avatar; ?>" alt="<?php echo get_the_author(); ?>" class="me-2" width="40" height="40">
                            <div>
                                <div>By <a href="<?php echo get_author_posts_url($author_id); ?>" class="text-decoration-none"><?php the_author(); ?></a></div>
                                <div>
                                    <?php echo get_the_date(); ?> • 
                                    <?php echo $reading_time; ?> min read • 
                                    <?php comments_number('0 comments', '1 comment', '% comments'); ?>
                                </div>
                            </div>
                        </div>

                        <?php if (has_excerpt()) : ?>
                            <p class="lead mb-0"><?php echo get_the_excerpt(); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <article class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <?php if (has_post_thumbnail()) : ?>
                            <figure class="mb-4">
                                <?php the_post_thumbnail('large', ['class' => 'img-fluid mb-2']); ?>
                                <?php
                                $thumbnail_caption = get_the_post_thumbnail_caption();
                                if ($thumbnail_caption) {
                                    echo '<figcaption class="text-muted small">' . $thumbnail_caption . '</figcaption>';
                                }
                                ?>
                            </figure>
                        <?php endif; ?>
                        
                        <div class="content mb-5">
                            <?php the_content(); ?>
                        </div>

                        <!-- Tags -->
                        <?php
                        $tags = get_the_tags();
                        if ($tags) : ?>
                            <div class="mb-5">
                                <h2 class="h5 mb-3">Related Topics</h2>
                                <?php foreach($tags as $tag) : ?>
                                    <a href="<?php echo get_tag_link($tag->term_id); ?>" class="text-decoration-none me-2 text-primary">#<?php echo $tag->name; ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Author Bio -->
                        <?php if (get_the_author_meta('description')) : ?>
                            <div class="border-top pt-4 mb-5">
                                <h2 class="h5 mb-3">About the Author</h2>
                                <div class="d-flex">
                                    <?php echo get_avatar($author_id, 80, '', '', ['class' => 'me-3']); ?>
                                    <div>
                                        <h3 class="h6 mb-2"><?php the_author(); ?></h3>
                                        <p class="mb-2"><?php echo get_the_author_meta('description'); ?></p>
                                        <a href="<?php echo get_author_posts_url($author_id); ?>" class="text-decoration-none">View all posts</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Comments -->
                        <?php comments_template(); ?>
                    </div>
                    
                    <div class="col-lg-4">
                        <!-- Schedule Consultation CTA -->
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h2 class="h5 mb-3">Schedule a Consultation</h2>
                                <p class="small mb-3">Interested in learning more? Schedule a private consultation to discuss your goals.</p>
                                <a href="/consultation" class="btn btn-primary w-100">Book Your Consultation</a>
                                <div class="text-center mt-2">
                                    <small class="text-muted">Or call us at: (555) 123-4567</small>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Procedures -->
                        <div class="mb-4">
                            <h2 class="h5 mb-3">Popular Procedures</h2>
                            <?php
                            $procedures = new WP_Query([
                                'post_type' => 'procedures',
                                'posts_per_page' => 5,
                                'orderby' => 'menu_order',
                                'order' => 'ASC'
                            ]);

                            if ($procedures->have_posts()) : ?>
                                <ul class="list-unstyled">
                                    <?php while ($procedures->have_posts()) : $procedures->the_post(); ?>
                                        <li class="mb-2">
                                            <a href="<?php the_permalink(); ?>" class="text-decoration-none d-flex align-items-center">
                                                <i class="fas fa-chevron-right text-primary me-2 small"></i>
                                                <?php the_title(); ?>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php endif;
                            wp_reset_postdata();
                            ?>
                            <a href="/procedures" class="btn btn-outline-primary btn-sm">View All Procedures</a>
                        </div>

                        <!-- Financing Options -->
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h2 class="h5 mb-3">Financing Options</h2>
                                <p class="small mb-3">We offer various financing options to help make your procedure more affordable.</p>
                                <ul class="list-unstyled mb-3">
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Multiple payment plans</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>0% financing available</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Insurance accepted</li>
                                </ul>
                                <a href="/financing" class="btn btn-outline-primary btn-sm">Learn More</a>
                            </div>
                        </div>

                        <!-- Patient Resources -->
                        <div class="mb-4">
                            <h2 class="h5 mb-3">Patient Resources</h2>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="/before-after/" class="text-decoration-none">
                                        <i class="fas fa-images text-primary me-2"></i>
                                        Before & After Gallery
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="https://patient.miaaesthetics.com/s/login?ec=302&startURL=%2Fs%2Fhome" target="_blank" class="text-decoration-none">
                                        <i class="fas fa-file-alt text-primary me-2"></i>
                                        Patient Portal
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="/faqs/" class="text-decoration-none">
                                        <i class="fas fa-question-circle text-primary me-2"></i>
                                        FAQs
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="/conditions/" class="text-decoration-none">
                                        <i class="fas fa-heart text-primary me-2"></i>
                                        Conditions We Treat
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Related Posts -->
                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            $category_ids = array();
                            foreach($categories as $category) {
                                $category_ids[] = $category->term_id;
                            }
                            
                            $related_posts = new WP_Query([
                                'category__in' => $category_ids,
                                'post__not_in' => [get_the_ID()],
                                'posts_per_page' => 3,
                                'orderby' => 'rand'
                            ]);

                            if ($related_posts->have_posts()) : ?>
                                <div class="mb-4">
                                    <h2 class="h5 mb-3">Related Articles</h2>
                                    <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                                        <div class="mb-3">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('thumbnail', ['class' => 'img-fluid mb-2']); ?>
                                                </a>
                                            <?php endif; ?>
                                            <h3 class="h6 mb-1"><a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a></h3>
                                            <div class="text-muted small"><?php echo get_the_date(); ?></div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php endif;
                            wp_reset_postdata();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </article>

        <?php
        // Schema.org structured data for SEO
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author()
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => get_site_icon_url()
                ]
            ]
        ];

        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'large');
        }
        ?>
        <script type="application/ld+json"><?php echo json_encode($schema); ?></script>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
