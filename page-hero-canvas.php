<?php
/**
 * Template Name: Hero Canvas
 * Template Post Type: post, page, procedure, condition, case
 * Description: Full-width canvas template with breadcrumbs and hero section.
 * Provides maximum flexibility for custom layouts using Bootstrap containers.
 * Similar to Blank Canvas but includes structured breadcrumbs and hero.
 *
 * @package MiaAesthetics
 */

get_header(); ?>

<main id="primary" <?php post_class(); ?>>
    <!-- Breadcrumbs -->
    <div class="container">
        <?php
        if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
        }
        ?>
    </div>
    
    <?php while (have_posts()) : the_post(); ?>
        <!-- Page Header -->
        <section class="post-header py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Full-Width Content Canvas -->
        <?php
            // The_content prints whatever HTML you drop in the editor
            // Use Bootstrap containers (.container, .container-fluid) within your content
            // to control width and layout as needed
            the_content();
        ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
