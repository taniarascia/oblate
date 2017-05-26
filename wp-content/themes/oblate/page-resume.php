<?php get_header(); ?>

<header class="resume-header">
	<div class="container">
		<h1>Tania Rascia</h1>
		<div class="subtitle">Web Designer &amp; Developer</div>
		<p>I'm a web designer and developer who's passionate about taking complex concepts and simplifying them. Below is a list of my knowledge, skills, published websites and articles.</p>
		<b>Email:</b> <a href="mailto:taniarascia@gmail.com">taniarascia@gmail.com</a><br>
		<b>GitHub:</b> <a href="https://github.com/taniarascia">taniarascia</a>
	</div>
</header>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section class="resume">
	<article>
		<div class="alternate-background">

			<?php the_content(); ?>

			<section class="portfolio-page alternate-background">
				<h3>Portfolio</h3>
				<div class="text-center margin-bottom">
					<a class="button alt-button" href="<?php echo site_url(); ?>/work" style="margin-bottom: 1.5rem;">View all</a>
				</div>
				<div class="large-container">
					<div class="grid">

						<?php $args = array(
				'post_type' => 'work',
				'order' => 'asc',
				'posts_per_page' => '6',
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
		</div>
	</article>
</section>

<?php endwhile; endif; ?>

<?php get_footer();
