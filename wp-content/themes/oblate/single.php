<?php get_header();?>
	<main>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>">
				<div class="center">
					<h2><?php the_title();?></h2>
					<time>
						<?php the_time('F j, Y');?>
					</time>
					<?php the_tags('&nbsp;☍&nbsp;<span class="tags">', ', ', '', '</span>'); ?>
				</div>
				<p>
					<?php the_content(); ?>
				</p>
			</article>
			<?php 
			// If comments are open or we have at least one comment, load up the comment template. 
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;?>

				<?php	// End the loop.
			endwhile;
		endif;
		?>
	</main>
	<?php get_sidebar();?>
		<?php get_footer();?>
