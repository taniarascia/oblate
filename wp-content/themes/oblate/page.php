<?php get_header();?>
	<main>
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();?>
			<article id="post-<?php the_ID(); ?>">
				<h2><?php the_title();?></h2>
				<p>
					<?php the_content(); ?>
				</p>
			</article>
			<?php // End the loop.
		endwhile;
		?>
	</main>
	<?php get_footer();?>
