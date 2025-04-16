<?php
/**
 * Basic Single Template
 * Use as: single.php, single-procedures.php, single-surgeons.php, etc.
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
    <?php while (have_posts()) : the_post(); ?>
        <!-- Page Header -->
        <header class="bg-light py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <h1><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <article class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="mb-4">
                                <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <!-- Sidebar content can go here if needed -->
                    </div>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>