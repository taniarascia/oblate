<?php get_header(); ?>

	<div class="page-header text-center">
		<h1>Blog</h1>
	</div>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

		get_template_part( 'content', get_post_format() );

	endwhile; ?>
	
		<div class="posts-links">
			<div class="pagination-left">
				<?php previous_posts_link(); ?>
			</div>
			<div class="pagination-right">
				<?php next_posts_link(); ?>
			</div>
		</div>

	<?php endif; ?>
	
<?php get_footer(); ?>