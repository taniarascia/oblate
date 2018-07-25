<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<article id="<?php the_id(); ?>" class="article">

	<section class="single-body">
		<header class="page-header">
			<h1><?php the_title(); ?></h1>
		</header>
		
		<?php the_content(); ?>
	</section>

</article>

<?php endwhile; endif; ?>

<?php get_footer();
