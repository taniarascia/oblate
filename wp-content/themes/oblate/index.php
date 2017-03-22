<?php get_header(); ?>

<header class="page-header">
	<h1>Blog</h1>
</header>

<div class="large-container">
	<div class="grid">

		<?php 
		
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
		 get_template_part( 'content', get_post_format() );
		
		endwhile; ?>

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

<?php endif; ?>

<?php get_footer(); ?>
