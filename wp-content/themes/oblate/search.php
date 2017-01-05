<?php get_header(); ?>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

			get_template_part( 'content', get_post_format() );

		endwhile; else: ?>

		<h3 class="text-center">Sorry, no results were found!</h3>
		<?php endif; ?>

<?php get_footer(); ?>