<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="page-header">
	<div class="container">
		<h1>
			<?php the_title(); ?>
		</h1>
	</div>
</header>

<div class="small-container">
	<?php get_search_form(); ?>
</div>

<div class="container">
	<p class="text-center"><strong>Popular topics:</strong> <a href="<?php echo site_url(); ?>?s=wordpress">WordPress</a>, <a href="<?php echo site_url(); ?>?s=bootstrap">Bootstrap</a>, <a href="<?php echo site_url(); ?>?s=jekyll">Jekyll</a>, <a href="<?php echo site_url(); ?>?s=sass">Sass</a>, <a href="<?php echo site_url(); ?>?s=navigation">navigation</a>
</div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
