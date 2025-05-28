<?php
get_header();
?>

<main id="primary" class="site-main">
    <header class="page-header">
        <h1 class="page-title">
            <?php single_term_title(); ?>
        </h1>
        <?php
        the_archive_description('<div class="archive-description">', '</div>');
        ?>
    </header>

    <?php if (have_posts()) : ?>
        <div class="post-list">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h2 class="entry-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        <?php the_posts_navigation(); ?>
    <?php else : ?>
        <p><?php esc_html_e('No posts found in this taxonomy.', 'your-theme-textdomain'); ?></p>
    <?php endif; ?>
</main>

<?php
get_footer();
?>
