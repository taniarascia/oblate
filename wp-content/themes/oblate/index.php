<?php get_header();?>
	<main>
		<?php 
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
			get_template_part( 'content', get_post_format() );
		
			endwhile;
		
			the_posts_pagination( array(
				'prev_text'          => __( '← ' ),
				'next_text'          => __( ' →'),
				'before_page_number' => '<span>' . __( 'Page') . ' </span>',
			) );
		endif;
		?>
	</main>
	<?php get_sidebar();?>
		<?php get_footer();?>
