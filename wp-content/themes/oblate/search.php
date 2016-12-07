<?php get_header(); ?>

	<main class="main-content margin-top text-center">

			<div class="small-container">

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

						get_template_part( 'content', get_post_format() );

						endwhile; else: ?><h3 class="text-center">Sorry, no results were found!</h3><?php endif; ?>

			</div>
			
	</main>

<?php get_footer(); ?>
