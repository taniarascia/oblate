<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="page-header">
	<div class="small-container">
		<h1>
			<?php the_title(); ?>
		</h1>
		<?php the_content(); ?>
	</div>
</header>

<?php endwhile; endif; ?>

<section class="snippets">

	<div class="large-container">
		<div class="grid">

			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'Snippets',
			);

			$snippets = new WP_Query( $args );

			if ( $snippets->have_posts() ) :  while ( $snippets->have_posts() ) : $snippets->the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php	endwhile; ?>

		</div>

		<?php 
		$prev_link = get_previous_posts_link();
		$next_link = get_next_posts_link();

		if ($prev_link || $next_link) { ?>
		<div class="posts-links">
			<div class="pagination-left">
				<?php previous_posts_link(); ?>
			</div>
			<div class="pagination-right">
				<?php next_posts_link(); ?>
			</div>
		</div>
		<?php } ?>

	</div>

</section>

<?php endif; wp_reset_postdata(); ?>

<?php get_footer();
