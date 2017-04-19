<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section class="resume-outer">
	<div class="container">
		<?php the_content(); ?>
	</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer();
