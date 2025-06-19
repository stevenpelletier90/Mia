<?php
/**
 * The template for displaying the Specials archive page.
 */

get_header(); ?>

<?php mia_breadcrumbs(); ?>

<div class="specials-archive-page">

    <!-- 1. Hero Section -->
    <header class="specials-hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold text-dark">Mia Aesthetics Specials</h1>
            <p class="lead text-secondary mb-4">Your dream look, now within reach.</p>
            <a href="/free-virtual-consultation/" class="btn btn-primary btn-lg px-4 shadow">
                Free Virtual Consultation
            </a>
        </div>
    </header>

    <!-- 2. Specials Archive Grid -->
    <main class="specials-grid py-5">
        <div class="container">

            <?php if ( have_posts() ) : ?>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 g-lg-5">

                    <?php while ( have_posts() ) : the_post(); ?>

                        <div class="col">
                            <div class="card h-100 shadow-sm special-card">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="card-img-container">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('large', ['class' => 'card-img-top']); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <?php the_title( '<h2 class="card-title h5"><a href="' . esc_url( get_permalink() ) . '" class="text-decoration-none text-dark">', '</a></h2>' ); ?>
                                    <div class="card-text text-secondary small">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary mt-auto align-self-start">
                                        Learn More
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>

                </div> <!-- .row -->

            <?php else : ?>

                <div class="text-center">
                    <h2>No Specials Found</h2>
                    <p>Please check back later for new offers.</p>
                </div>

            <?php endif; ?>

        </div> <!-- .container -->
    </main>

</div><!-- .specials-archive-page -->

<?php get_footer(); ?>