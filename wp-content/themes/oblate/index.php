<?php get_header(); ?>

<main class="main-content margin-top text-center">
	<div class="small-container">
	
	<?php get_search_form(); ?>
	
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

	</div>
</main>

<?php get_footer(); ?>