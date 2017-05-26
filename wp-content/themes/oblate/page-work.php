<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="page-header bright-header portfolio-header">
	<div class="small-container">
		<h1>
			<?php the_title(); ?>
		</h1>
		<?php the_content(); ?>
	</div>
</header>

<?php endwhile; endif; ?>

<section class="portfolio-page alternate-background">

	<div class="large-container">
		<div class="grid">

			<?php $args = array(
				'post_type' => 'work',
				'order' => 'asc',
				'posts_per_page' => '-1',
			);

			$work = new WP_Query( $args );

			if ( $work->have_posts() ) :  while ( $work->have_posts() ) : $work->the_post();
			$url = get_post_meta($post->ID, 'url', true); ?>


			<article id="post-<?php the_ID(); ?>" class="cell">
				<?php if ( get_post_thumbnail_id() ) { ?>
				<div class="portfolio-thumbnail">
					<a href="<?php echo $url; ?>" target="_blank"><img src="<?php the_post_thumbnail_url( 'large' ); ?>" alt="<?php the_title(); ?>"></a>
				</div>
				<?php } ?>
				<div class="portfolio-description">
					<h1>
						<a href="<?php echo $url; ?>" target="_blank">
							<?php the_title(); ?>
						</a>
					</h1>

					<?php the_excerpt(); ?>
				</div>
			</article>

			<?php endwhile; endif; wp_reset_postdata(); ?>

		</div>
	</div>
</section>

<?php get_footer();
