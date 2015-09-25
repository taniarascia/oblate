<?php get_header();?>
	<main>

		<?php if ( have_posts() ) : 

			while ( have_posts() ) : the_post();
?>
			<article id="post-<?php the_ID(); ?>">
				<div class="left">
					<h2><?php the_title();?></h2>
					<time>
						<?php the_time('F j, Y');?>
					</time>
					<?php the_tags('&bull;&nbsp; <span class="tags">', ', ', '', '</span>'); ?>
				</div>
				<div class="right">
					<?php the_category();?>
				</div>
				<div class="clear"></div>
				<p>
					<?php the_content(); ?>
				</p>
				<hr>
				<?php 
			// If comments are open or we have at least one comment, load up the comment template. 
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;?>

			</article>
			<?php	// End the loop.
			endwhile;
		endif;
		?>

	</main>
	<?php get_sidebar();?>
		<?php get_footer();?>
