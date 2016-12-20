<?php get_header(); ?>

<?php $args = array(
				'post_type' => 'portfolio-items',
				'order' => 'asc',
				'posts_per_page' => '30',
			 );

			$portfolio = new WP_Query( $args );

			if ( $portfolio->have_posts() ) : ?>

		<div class="portfolio">

			<?php while ( $portfolio->have_posts() ) : $portfolio->the_post(); ?>
				<div class="portfolio-item">
					<h2><?php the_title(); ?></h2>
					<a href="<?php echo get_the_excerpt(); ?>" target="_blank"><img src="<?php echo the_post_thumbnail_url( 'large' ); ?>" class="responsive-image" alt="<?php the_title(); ?>"></a>
				</div>
			<?php endwhile; ?>

		</div>

	<?php endif; wp_reset_postdata(); ?>

<?php get_footer(); ?>