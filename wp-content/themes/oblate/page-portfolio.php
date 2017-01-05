<?php get_header(); ?>

<?php $args = array(
				'post_type' => 'portfolio-items',
				'order' => 'asc',
				'posts_per_page' => '30',
			);

			$portfolio = new WP_Query( $args );

			if ( $portfolio->have_posts() ) :  while ( $portfolio->have_posts() ) : $portfolio->the_post(); ?>

<section class="portfolio-container">
	<div class="portfolio-description">
		<div class="portfolio-inner">
			<h3><?php the_title(); ?></h3>
			<a class="button" href="<?php echo get_the_excerpt(); ?>" target="_blank">View Website</a>
		</div>
	</div>
	<div class="portfolio-example vertical-center">
		<a href="<?php echo get_the_excerpt(); ?>" target="_blank"><img src="<?php echo the_post_thumbnail_url( 'large' ); ?>" alt="<?php the_title(); ?>"></a>
	</div>
</section>

<?php endwhile; endif; wp_reset_postdata(); ?>

<?php get_footer(); ?>