<?php
/**
 * Basic Archive Template
 * Can be used as: archive-procedures.php, archive-surgeons.php, etc.
 */

get_header(); ?>

<main>
    <div class="container">
        <?php if (function_exists("yoast_breadcrumb")) {
            yoast_breadcrumb('<p id="breadcrumbs">', "</p>");
        } ?>
    </div>
    
    <!-- Archive Header -->
    <header class="post-header py-5">
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
            <?php
            // Custom query to get only top-level conditions (no parent)
            $top_level_conditions = new WP_Query([
                'post_type' => 'condition',
                'post_parent' => 0, // Only get top-level conditions
                'posts_per_page' => -1, // Show all
                'orderby' => 'title',
                'order' => 'ASC'
            ]);

            if ($top_level_conditions->have_posts()) : ?>
                <div class="row">
                    <div class="col-12">
                        <div class="conditions-accordion accordion" id="conditionsAccordion">
                            <?php 
                            $accordion_index = 0;
                            while ($top_level_conditions->have_posts()) : $top_level_conditions->the_post(); 
                                $condition_id = get_the_ID();
                                $accordion_id = 'condition-' . $condition_id;
                                $heading_id = 'heading-' . $accordion_id;
                                $collapse_id = 'collapse-' . $accordion_id;
                                
                                // Check for child conditions
                                $has_children = false;
                                $child_conditions = new WP_Query([
                                    'post_type' => 'condition',
                                    'post_parent' => $condition_id,
                                    'posts_per_page' => -1,
                                    'orderby' => 'title',
                                    'order' => 'ASC'
                                ]);
                                
                                if ($child_conditions->have_posts()) {
                                    $has_children = true;
                                }
                            ?>
                                <div class="accordion-item condition-item mb-3">
                                    <h2 class="accordion-header d-flex align-items-center justify-content-between" id="<?php echo $heading_id; ?>">
                                        <div class="d-flex align-items-center">
                                            <?php if ($has_children) : ?>
                                                <button 
                                                    class="accordion-button collapsed p-0 pe-3" 
                                                    type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#<?php echo $collapse_id; ?>" 
                                                    aria-expanded="false" 
                                                    aria-controls="<?php echo $collapse_id; ?>">
                                                    <span class="condition-title">
                                                        <?php the_title(); ?>
                                                    </span>
                                                </button>
                                            <?php else: ?>
                                                <a href="<?php the_permalink(); ?>" class="condition-link text-decoration-none condition-title">
                                                    <?php the_title(); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-primary view-condition-btn">
                                            View Condition
                                        </a>
                                    </h2>
                                    
                                    <?php if ($has_children) : ?>
                                        <div 
                                            id="<?php echo $collapse_id; ?>" 
                                            class="accordion-collapse collapse" 
                                            aria-labelledby="<?php echo $heading_id; ?>" 
                                            data-bs-parent="#conditionsAccordion">
                                            <div class="accordion-body">
                                                <div class="child-conditions-list">
                                                    <ul class="list-unstyled mb-0">
                                                        <?php while ($child_conditions->have_posts()) : $child_conditions->the_post(); ?>
                                                            <li class="child-condition-item">
                                                                <a href="<?php the_permalink(); ?>" class="child-condition-link">
                                                                    <i class="fa-solid fa-caret-right me-2"></i><?php the_title(); ?>
                                                                </a>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif;
                                    wp_reset_postdata(); // Reset after child query
                                    ?>
                                </div>
                            <?php 
                                $accordion_index++;
                                endwhile; 
                            ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col">
                        <p>No conditions found.</p>
                    </div>
                </div>
            <?php 
            endif;
            wp_reset_postdata(); // Reset after main query
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
