<?php get_header(); ?>

	<div class="portfolio-container vertical-center">

		<?php $args = array(
				'post_type' => 'work',
				'order' => 'asc',
				'posts_per_page' => '-1',
			);

			$portfolio = new WP_Query( $args );

			if ( $portfolio->have_posts() ) :  while ( $portfolio->have_posts() ) : $portfolio->the_post(); ?>


			<div class="portfolio-example">
				<div class="portfolio-item">
					<a href="<?php echo the_permalink(); ?>" target="_blank"><img src="<?php echo the_post_thumbnail_url( 'large' ); ?>" alt="<?php the_title(); ?>"></a>
					<h3><?php the_title(); ?></h3>
				</div>
			</div>

			<?php endwhile; endif; wp_reset_postdata(); ?>

	</div>

	<?php get_footer(); ?>