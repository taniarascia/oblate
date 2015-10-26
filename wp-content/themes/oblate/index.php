<?php get_header();?>
	<main>
		<?php 
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
			get_template_part( 'content', get_post_format() );
		
			endwhile;?>
			<div class="posts-links">
				<div class="left">
					<?php previous_posts_link();?>
				</div>
				<div class="right">
					<?php next_posts_link(); ?>
				</div>
			</div>
			<?php endif;
		?>
	</main>
	<?php get_sidebar();?>
		<?php get_footer();?>
