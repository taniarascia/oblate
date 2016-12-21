<?php get_header(); ?>

<section class="page">
	<div class="small-container">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

			get_template_part( 'content-page', get_post_format() );

		endwhile; endif; ?>

	</div>
</section>

<?php get_footer(); ?>