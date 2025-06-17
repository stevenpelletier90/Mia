<?php
/**
 * Conditions Archive Template
 * Displays conditions in a hierarchical structure with parents and children
 */

get_header(); ?>

<main>
    <div class="container">
        <?php if (function_exists("yoast_breadcrumb")) {
            yoast_breadcrumb('<p id="breadcrumbs">', "</p>");
        } ?>
    </div>
    
    <!-- Archive Header -->
    <section class="post-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-2">Conditions We Treat</h1>
                    <p class="lead mb-0">Browse our comprehensive list of conditions organized by category.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Conditions Content -->
    <section class="py-5">
        <div class="container">
            <?php
            // Get all parent conditions (no parent)
            $parent_conditions = new WP_Query([
                'post_type' => 'condition',
                'post_parent' => 0,
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC'
            ]);

            if ($parent_conditions->have_posts()) : ?>
                <div class="conditions-grid">
                    <?php while ($parent_conditions->have_posts()) : $parent_conditions->the_post(); 
                        $parent_id = get_the_ID();
                        
                        // Get child conditions for this parent
                        $child_conditions = new WP_Query([
                            'post_type' => 'condition',
                            'post_parent' => $parent_id,
                            'posts_per_page' => -1,
                            'orderby' => 'title',
                            'order' => 'ASC'
                        ]);
                        
                        $has_children = $child_conditions->have_posts();
                    ?>
                        <div class="condition-group mb-5">
                            <div class="condition-parent">
                                <h2 class="condition-title">
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <?php if (has_excerpt()) : ?>
                                    <p class="condition-excerpt text-muted mb-3">
                                        <?php echo get_the_excerpt(); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($has_children) : ?>
                                <div class="condition-children">
                                    <div class="row g-3">
                                        <?php while ($child_conditions->have_posts()) : $child_conditions->the_post(); ?>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="condition-item">
                                                    <a href="<?php the_permalink(); ?>" class="condition-link">
                                                        <span class="condition-name"><?php the_title(); ?></span>
                                                        <i class="fa-solid fa-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                <?php wp_reset_postdata(); ?>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <?php
                // Also show standalone conditions (those without parents AND without children)
                $standalone_conditions = new WP_Query([
                    'post_type' => 'condition',
                    'post_parent' => 0,
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'meta_query' => [
                        [
                            'key' => 'has_children',
                            'compare' => 'NOT EXISTS'
                        ]
                    ]
                ]);
                
                // Filter out conditions that have children
                $standalone_posts = [];
                if ($standalone_conditions->have_posts()) {
                    while ($standalone_conditions->have_posts()) {
                        $standalone_conditions->the_post();
                        $condition_id = get_the_ID();
                        
                        // Check if this condition has children
                        $children_check = new WP_Query([
                            'post_type' => 'condition',
                            'post_parent' => $condition_id,
                            'posts_per_page' => 1
                        ]);
                        
                        if (!$children_check->have_posts()) {
                            $standalone_posts[] = get_post($condition_id);
                        }
                        wp_reset_postdata();
                    }
                }
                
                if (!empty($standalone_posts)) : ?>
                    <div class="condition-group">
                        <div class="condition-parent">
                            <h2 class="condition-title">Other Conditions</h2>
                        </div>
                        <div class="condition-children">
                            <div class="row g-3">
                                <?php foreach ($standalone_posts as $post) : 
                                    setup_postdata($post); ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="condition-item">
                                            <a href="<?php the_permalink(); ?>" class="condition-link">
                                                <span class="condition-name"><?php the_title(); ?></span>
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="row">
                    <div class="col-12">
                        <p class="text-center text-muted">No conditions found.</p>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>