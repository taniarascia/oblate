<?php get_header(); ?>

<section class="landing-page">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<div class="banner">
		<div class="container">
			<h1>Tania Rascia</h1>
			<?php the_content(); ?>
		</div>
	</div>
	
	<?php endwhile; endif; ?>

</section>

<?php get_footer(); ?>