<?php
/**
 * Template Name: Case Category Page
 * Description: Displays a grid of Case posts that share the same “case-category” terms
 *              attached to this Page.  Mirrors the default page.php layout (breadcrumbs,
 *              hero header, featured image, content) and then injects the dynamic grid.
 *
 * @package Mia_Aesthetics
 */

/* -------------------------------------------------------------------------
 * Enqueue template-specific stylesheet
 * --------------------------------------------------------------------- */
add_action(
    'wp_enqueue_scripts',
    function () {
        wp_enqueue_style(
            'mia-case-category-page',
            get_template_directory_uri() . '/assets/css/_case-category-page.css',
            ['mia-base', 'mia-bootstrap'],
            wp_get_theme()->get('Version')
        );
    }
);

get_header();
?>

<main>
	<!-- Breadcrumbs ---------------------------------------------------->
	<div class="container">
		<?php
		if (function_exists('yoast_breadcrumb')) {
			yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
		}
		?>
	</div>

	<?php while (have_posts()) : the_post(); ?>
		<!-- Page Hero / Title ----------------------------------------->
		<section class="post-header py-5">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<h1><?php the_title(); ?></h1>
					</div>
				</div>
			</div>
		</section>

		<!-- Main Page Content ----------------------------------------->
		<article class="py-5">
			<div class="container">
				<div class="row">
					<div class="col">
						<?php if (has_post_thumbnail()) : ?>
							<div class="mb-4">
								<?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
							</div>
						<?php endif; ?>

						<div class="content">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</div>
		</article>
	<?php endwhile; ?>

	<?php
	/* -----------------------------------------------------------------
	 * CASE GRID QUERY
	 * ----------------------------------------------------------------*/
	$term_ids = wp_get_post_terms(get_the_ID(), 'case-category', ['fields' => 'ids']);
	$paged    = max(1, get_query_var('paged'));

	$query_args = [
		'post_type'      => 'case',          // CPT slug
		'posts_per_page' => 12,
		'paged'          => $paged,
	];

	if (!empty($term_ids)) {
		$query_args['tax_query'] = [
			[
				'taxonomy' => 'case-category',
				'field'    => 'term_id',
				'terms'    => $term_ids,
			],
		];
	}

	$case_query = new WP_Query($query_args);
	?>

	<section class="py-5">
		<div class="container">
			<?php if ($case_query->have_posts()) : ?>
				<div class="row g-4">
					<?php
					while ($case_query->have_posts()) :
						$case_query->the_post();
					?>
						<div class="col-md-6 col-lg-4">
							<div class="card h-100">
								<?php if (has_post_thumbnail()) : ?>
									<?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
								<?php endif; ?>

								<div class="card-body">
									<h2 class="h5">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h2>
									<?php the_excerpt(); ?>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>

				<!-- Pagination --------------------------------------->
				<div class="row mt-5">
					<div class="col">
						<?php
						the_posts_pagination([
							'prev_text' => '&laquo;',
							'next_text' => '&raquo;',
							'class'     => 'pagination justify-content-center',
						]);
						?>
					</div>
				</div>
			<?php else : ?>
				<p class="lead text-center mb-0">
					<?php esc_html_e('No cases found for the selected category.', 'mia-aesthetics'); ?>
				</p>
			<?php endif;

			wp_reset_postdata(); ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
