<?php
/**
 * Basic Single Template
 * Use as: single.php, single-procedures.php, single-surgeons.php, etc.
 */

get_header(); ?>

<main data-bs-spy="scroll" data-bs-target="#tableOfContents" data-bs-offset="100" data-bs-smooth-scroll="true">
    <div class="container">
        <?php
        if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
        }
        ?>
    </div>
    <?php while (have_posts()) : the_post(); ?>
        <!-- Page Header -->
        <header class="post-header py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <h1><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <article class="py-4">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <?php 
                        // Check if overview_details repeater field exists and has rows
                        if (have_rows('overview_details')): ?>
                            <div class="overview-section mb-4">
                                <h2 id="overview">Overview</h2>
<div class="overview-content">
    <?php 
    // Loop through the repeater field rows
    while (have_rows('overview_details')): the_row(); 
        // Get the overview_item text area value
        $overview_item = get_sub_field('overview_item');
        if ($overview_item): ?>
            <div class="overview-item">
                <i class="fa-solid fa-check-circle overview-check"></i>
                <?php echo $overview_item; ?>
            </div>
        <?php endif;
    endwhile; ?>
</div>
                            </div>
                        <?php endif; ?>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="mb-4">
                                <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="content">
                            <?php the_content(); ?>
                        </div>
                        <?php
                        // Using display_page_faqs(true) to show heading from custom field
                        echo display_page_faqs(true);
                        ?>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="toc-container">
                            <h3>Table of Contents</h3>
                            <nav id="tableOfContents" class="toc-nav">
                                <ul class="toc-list nav flex-column">
                                    <!-- Table of contents will be dynamically generated from h2 tags -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
    
</main>


<?php get_footer(); ?>
