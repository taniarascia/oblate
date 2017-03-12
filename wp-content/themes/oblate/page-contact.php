<?php get_header(); ?>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<div class="page-header text-center">
					<h1>Say Hello</h1>
				</div>
				
		<?php the_content(); ?>

		<?php endwhile; endif; ?>

<?php get_footer(); ?>