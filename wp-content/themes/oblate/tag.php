<?php get_header(); ?>

<header class="page-header">
	<div class="small-container">
		<h1><?php single_tag_title(); ?></h1>
	</div>
</header>

<div class="small-container">

		<?php 
		
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
		 get_template_part( 'content-basic', get_post_format() );
		
		endwhile; ?>

	</div>

<div class="small-container">

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
