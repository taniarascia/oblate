<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="page-header">
	<div class="container">
		<h1>
			<?php the_title(); ?>
		</h1>
	</div>
</header>

<section class="case-study">
	<?php the_content(); ?>
</section>

<?php endwhile; endif; ?>

<?php get_footer();
