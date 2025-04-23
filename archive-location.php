<?php
/**
 * Basic Archive Template
 * Can be used as: archive-procedures.php, archive-surgeons.php, etc.
 */

get_header(); ?>

<main>
    <!-- Archive Header -->
    <header class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1><?php post_type_archive_title(); ?></h1>
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
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

            <?php else : ?>
                <div class="row">
                    <div class="col">
                        <p>No posts found.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>