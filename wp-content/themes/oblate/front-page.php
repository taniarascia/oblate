<?php get_header(); ?>

<section class="landing-page">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<div class="banner">
		<div class="container">
			<h1>Tania Rascia</h1>
			<?php the_content(); ?>
				<h1 class="secondary">Latest Articles</h1>
		</div>
	</div>
	
	<?php endwhile; endif; ?>

	<div class="latest">
	<?php 
	$args = array(
						'posts_per_page' => '3',
					);

	$latest = new WP_Query( $args );

	if ( $latest->have_posts() ) : ?>


	<?php while ( $latest->have_posts() ) : $latest->the_post(); ?>
		<?php get_template_part( 'content-front', get_post_format() ); ?>
	<?php endwhile; ?>
<?php endif; wp_reset_postdata(); ?>
	</div>

</section>

<?php get_footer(); ?>