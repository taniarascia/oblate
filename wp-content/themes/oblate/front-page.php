<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="header gradient">
	<div class="container">
		<h1>I'm a <span>web designer and developer</span> who loves sharing knowledge.</h1>
		<?php the_content(); ?>
		<a class="github-button" href="https://github.com/taniarascia" data-show-count="true" data-size="large" aria-label="Follow @taniarascia on GitHub">Follow</a>
		<a class="twitter-follow-button" data-size="large" data-show-screen-name="false" href="https://twitter.com/taniarascia">Follow</a>
	</div>
</header>

<?php endwhile; endif; ?>

<section>
	<div class="container">
		<blockquote class="testimonial">"Tania writes extremely clear, concise tutorials that have the best ratio of learning to bullshit that I've encountered so far. It's no exaggeration to say that I wouldn't currently have a job in development without this site. So thanks for ruining my life, Tania."
		<cite>&mdash; Craig Lam</cite></blockquote>
	</div>
</section>

<section class="top-three">
	<div class="large-container">
		<h2>Latest Tutorials</h2>
		<div class="grid">

			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '3',
				'category_name' => 'Tutorials',
				'category__not_in' => array( 5 ),
			);

			$latest = new WP_Query( $args );

			if ( $latest->have_posts() ) :  while ( $latest->have_posts() ) : $latest->the_post(); ?>

			<article class="cell">
				<div class="image-thumbnail">
					<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a>
				</div>
				<h1>
					<a href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
					</a>
				</h1>
				<?php the_excerpt(); ?>
			</article>

			<?php endwhile; endif; wp_reset_postdata(); ?>

		</div>
	</div>
</section>

<section class="top-three">
	<div class="large-container">

		<h2>Most Popular</h2>

		<div class="grid">

			<?php $args = array(
				'order' => 'asc',
				'posts_per_page' => '3',
				'category_name' => 'Popular',
				'category__not_in' => array( 5 ),
			);

			$popular = new WP_Query( $args );

			if ( $popular->have_posts() ) :  while ( $popular->have_posts() ) : $popular->the_post(); ?>

			<article class="cell">
				<div class="image-thumbnail">
					<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a>
				</div>
				<h1>
					<a href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
					</a>
				</h1>
				<?php the_excerpt(); ?>
			</article>

			<?php endwhile; endif; wp_reset_postdata(); ?>

		</div>
	</div>
</section>

<section class="top-three last">
	<div class="large-container">

		<h2>My Thoughts</h2>

		<div class="grid">

			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '3',
				'category_name' => 'Thoughts',
				'category__not_in' => array( 5 ),
			);

			$thoughts = new WP_Query( $args );

			if ( $thoughts->have_posts() ) :  while ( $thoughts->have_posts() ) : $thoughts->the_post(); ?>

			<article class="cell">
				<div class="image-thumbnail">
					<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a>
				</div>
				<h1>
					<a href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
					</a>
				</h1>
				<?php the_excerpt(); ?>
			</article>

			<?php endwhile; endif; wp_reset_postdata(); ?>

		</div>
	</div>
</section>

<section>
	<div class="container">
		<blockquote class="testimonial">"Hi Tania, I just wanted to say that your tutorials are by far the best I've seen in a <em>long</em> time. Well written and well explained. Thank you for all your effort."
		<cite>&mdash; João Vilaça</cite></blockquote>
	</div>
</section>

<?php get_footer();