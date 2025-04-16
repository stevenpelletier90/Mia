<?php
/**
 * Template for displaying single landing page posts
 */
get_header(); ?>
<main>
    <?php while (have_posts()) : the_post(); ?>
        <!-- Content -->
        <article>
            <div class="content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>