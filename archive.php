<?php
/**
 * The template for displaying archive pages
 *
 * This template is used for category, tag, author, date, and custom post type archives.
 * It displays a list of posts in a card-based grid layout with pagination.
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
    <!-- Archive Header -->
    <header class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <?php
                    if (is_category()) {
                        echo '<h1>Category: ' . single_cat_title('', false) . '</h1>';
                    } elseif (is_tag()) {
                        echo '<h1>Tag: ' . single_tag_title('', false) . '</h1>';
                    } elseif (is_author()) {
                        echo '<h1>Author: ' . get_the_author() . '</h1>';
                    } elseif (is_post_type_archive()) {
                        post_type_archive_title('<h1>', '</h1>');
                    } else {
                        echo '<h1>' . get_the_archive_title() . '</h1>';
                    }
                    
                    // Archive description if it exists
                    if (get_the_archive_description()) {
                        echo '<div class="archive-description mt-3">';
                        the_archive_description();
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Archive Content -->
    <section class="py-5">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="row g-4">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h2 class="h5">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    <div class="post-meta mb-3">
                                        <small class="text-muted">
                                            <?php echo get_the_date(); ?>
                                        </small>
                                    </div>
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="row mt-5">
                    <div class="col">
                        <?php the_posts_pagination([
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'class' => 'pagination justify-content-center',
                        ]); ?>
                    </div>
                </div>

            <?php else : ?>
                <div class="row">
                    <div class="col text-center py-5">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No posts found in this archive.
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
