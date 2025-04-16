<?php
/**
 * Search results template
 */

get_header(); ?>

<main>
    <header class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1>
                        <?php printf(
                            esc_html__('Search Results for: %s', 'mia-aesthetics'),
                            '<span>' . get_search_query() . '</span>'
                        ); ?>
                    </h1>
                </div>
            </div>
        </div>
    </header>

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
                                    <?php the_excerpt(); ?>
                                    <div class="text-muted small">
                                        <?php echo get_post_type_object(get_post_type())->labels->singular_name; ?>
                                    </div>
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
                    <div class="col">
                        <p><?php esc_html_e('No results found.', 'mia-aesthetics'); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>