<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="page-header">
	<div class="small-container">
		<h1>
			<?php the_title; ?>
		</h1>
	</div>
</header>

<div class="container">
	<?php the_content(); ?>
</div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
