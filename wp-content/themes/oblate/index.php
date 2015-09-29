<?php get_header();?>
	<main>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="highlight center">
				<h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
				<time>
					<?php the_time('F j, Y');?>
				</time>
				<?php the_tags('&nbsp;☍&nbsp;<span class="tags">', ', ', '', '</span>'); ?>
					<p>
						<?php the_excerpt(); ?>
					</p>
			</article>
			<?php	// End the loop.
			endwhile;
			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous' ),
				'next_text'          => __( 'Next'),
				'before_page_number' => '<span>' . __( 'Page') . ' </span>',
			) );
		endif;
		?>
	</main>
	<?php get_sidebar();?>
		<?php get_footer();?>
