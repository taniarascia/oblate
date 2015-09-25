<?php get_header();?>
	<main>

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();?>

			<article id="post-<?php the_ID(); ?>">
				<div class="left">
					<h2><?php the_title();?></h2>
					<time>
						<?php the_time('F j, Y');?>
					</time>
					<?php the_tags('&bull; <span class="tags">', ', ', '', '</span>'); ?>
				</div>
				<div class="right">
					<?php the_category();?>
				</div>
				<div class="clear"></div>
				<p>
					<?php the_content(); ?>
				</p>
			</article>
			<?php // End the loop.
		endwhile;
		?>

	</main>
	<?php get_footer();?>
