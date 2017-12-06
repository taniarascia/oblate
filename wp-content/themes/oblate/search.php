<?php get_header(); ?>

<header class="page-header gradient mb">
	<div class="small-container">
		<h1>
			<?php the_search_query(); ?>
		</h1>
		<p><strong><?php echo $wp_query->found_posts; ?></strong> results found.</p>
	</div>
</header>

<div class="small-container">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

			get_template_part( 'content-basic', get_post_format() );

		endwhile; else: ?>

	<h3 class="text-center">Sorry, no results were found!</h3>

	<?php endif; ?>

	<div class="posts-links">
		<div class="pagination-left">
			<?php previous_posts_link(); ?>
		</div>
		<div class="pagination-right">
			<?php next_posts_link(); ?>
		</div>
	</div>

</div>

<?php get_footer(); 