<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="page-header">
	<div class="small-container">
		<h1>
			<?php the_title; ?>
		</h1>
		<?php the_content(); ?>
	</div>
</header>

<?php endwhile; endif; ?>

<div class="small-container">

	<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'Snippets',
			);

			$thoughts = new WP_Query( $args );

			if ( $thoughts->have_posts() ) :  while ( $thoughts->have_posts() ) : $thoughts->the_post(); 

				get_template_part( 'content-basic', get_post_format() );

			endwhile; ?>

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
