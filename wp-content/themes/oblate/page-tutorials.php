<?php get_header(); ?>

	<section class="landing-page">

		<div class="landing-content vertical-center">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<div class="banner">
					<div class="container">
						<h1>Tania Rascia</h1>
						<?php the_content(); ?>
					</div>
				</div>
				<?php endwhile; endif; ?>
		</div>
		<div class="callout-content vertical-center">
			<?php $args = array(
							'posts_per_page' => '3',
						);

$latest = new WP_Query( $args );

if ( $latest->have_posts() ) : ?>

				<div class="latest-container">

					<?php while ( $latest->have_posts() ) : $latest->the_post(); ?>

						<?php get_template_part( 'content', get_post_format() ); ?>

							<?php endwhile; ?>

								<div class="text-center"> <a href="<?php echo site_url(); ?>" class="button alt-button">View All</a></div>

				</div>

				<?php endif; wp_reset_postdata(); ?>

		</div>

	</section>

	<?php get_footer(); ?>