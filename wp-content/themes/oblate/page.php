<?php get_header();?>
	<main class="large-container">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();?>

			<article id="post-<?php the_ID(); ?>">
				<h2><?php the_title();?></h2>
				<div class="row">
					<div class="column _6 pad">
						<p>
							<?php the_content(); ?>
						</p>
					</div>
					<div class="column _3 pad center">
						<img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) );?>">
					</div>
				</div>
			</article>

			<?php // End the loop.
		endwhile;
		?>
	</main>
	<?php get_footer();?> 
