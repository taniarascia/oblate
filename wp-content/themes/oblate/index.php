<?php get_header(); ?>

<section class="blog-index">
	
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
	
</section>

<?php get_footer(); ?>