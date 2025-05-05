<?php
/**
 * The template for displaying the blog posts index
 *
 * This template is used when a static front page is set and
 * another page is designated to display the blog posts index.
 * In your case, this is the /patient-resources/ page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main>
    <div class="container">
        <?php if (function_exists("yoast_breadcrumb")) {
            yoast_breadcrumb('<p id="breadcrumbs">', "</p>");
        } ?>
    </div>
    <!-- Blog Header -->
    <header class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1>Patient Resources & Blog</h1>
                    <p class="lead mt-3">Stay informed with the latest news, tips, and insights from our medical professionals.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Blog Content -->
    <section class="py-5">
        <div class="container">
            <?php if (have_posts()): ?>
                <div class="row g-4">
                    <?php while (have_posts()):
                        the_post(); ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 blog-card">
                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail("medium", [
                                        "class" => "card-img-top",
                                    ]); ?>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h2 class="h5">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    <div class="post-meta mb-3">
                                        <small class="text-muted">
                                            <i class="far fa-calendar-alt me-1"></i> <?php echo get_the_date(); ?>
                                            <?php
                                            // Display categories
                                            $categories = get_the_category();
                                            if ($categories) {
                                                echo ' <span class="mx-1">|</span> <i class="far fa-folder me-1"></i> ';
                                                $cat_links = [];
                                                foreach (
                                                    $categories
                                                    as $category
                                                ) {
                                                    $cat_links[] =
                                                        '<a href="' .
                                                        esc_url(
                                                            get_category_link(
                                                                $category->term_id
                                                            )
                                                        ) .
                                                        '" class="text-decoration-none">' .
                                                        esc_html(
                                                            $category->name
                                                        ) .
                                                        "</a>";
                                                }
                                                echo implode(", ", $cat_links);
                                            }
                                            ?>
                                        </small>
                                    </div>
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="row mt-5">
                    <div class="col">
                        <?php the_posts_pagination([
                            "prev_text" => "&laquo;",
                            "next_text" => "&raquo;",
                            "class" => "pagination justify-content-center",
                        ]); ?>
                    </div>
                </div>

            <?php else: ?>
                <div class="row">
                    <div class="col text-center py-5">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No blog posts have been published yet. Check back soon for updates!
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
