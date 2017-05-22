<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="page-header bright-header tania-header">
	<div class="small-container">
		<h1>
			<?php the_title(); ?>
		</h1>
	</div>
</header>

<section class="section-content">
	<div class="container">
		<?php the_content(); ?>
	</div>
</section>

<img src="<?php echo site_url(); ?>/wp-content/uploads/20172.jpg" alt="" class="responsive-image" style="display: block; margin: 0 auto;">

<?php endwhile; endif; ?>

<?php get_footer(); 