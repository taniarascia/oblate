<?php get_header(); ?>

<div class="page-header">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
</div>

<div class="large-container">
	<div class="grid">

	<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'Tutorials',
			);

			$tutorials = new WP_Query( $args );

			if ( $tutorials->have_posts() ) :  while ( $tutorials->have_posts() ) : $tutorials->the_post(); ?>

		<?php get_template_part( 'content', get_post_format() ); ?>

		<?php	endwhile; ?>

	</div>
</div>

<div class="large-container">

	<div class="posts-links">
		<div class="pagination-left">
			<?php previous_posts_link(); ?>
		</div>
		<div class="pagination-right">
			<?php next_posts_link(); ?>
		</div>
	</div>

</div>

<?php endif; wp_reset_postdata(); ?>

<?php get_footer(); ?>
