<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="page-header bright-header tutorials-header">
	<div class="small-container">
		<h1>
			<?php the_title(); ?>
		</h1>
		<?php the_content(); ?>
	</div>
</header>

<section class="quote-container">
	<div class="container">
		<blockquote class="testimonial">"By far your articles are the most crystal clear I've seen. An ace web developer who can articulate without ego? That's
			gold!"
			<cite>&mdash; Ralphie Harvey, Agile Software Product Delivery Expert</cite></blockquote>
	</div>
</section>

<?php endwhile; endif; ?>

<section class="topics">
	<h2 class="text-center">Topics</h2>
		<div class="small-container">
			<div class="topic">
				<h3><a href="#front-end"><span class="ico"><i class="fa fa-pencil" aria-hidden="true"></i></span> <span>Front End &amp; Design</span></a></h3>
			</div>
			<div class="topic">
				<h3><a href="#back-end"><span class="ico"><i class="fa fa-database" aria-hidden="true"></i></span> <span>Back End Development</span></a></h3>
			</div>
			<div class="topic">
				<h3><a href="#javascript"><span class="ico"><i class="fa fa-code" aria-hidden="true"></i></span> <span>JavaScript</span></a></h3>
			</div>
			<div class="topic">
				<h3><a href="#workflow"><span class="ico"><i class="fa fa-code-fork" aria-hidden="true"></i></span> <span>Developer Workflow</span></a></h3>
			</div>
			<div class="topic">
				<h3><a href="#system"><span class="ico"><i class="fa fa-server" aria-hidden="true"></i></span> <span>System Administration</span></a></h3>
			</div>
			<div class="topic">
				<h3><a href="#wordpress"><span class="ico"><i class="fa fa-wordpress" aria-hidden="true"></i></span> <span>WordPress</span></a></h3>
			</div>
			<div class="topic">
				<h3><a href="#general"><span class="ico"><i class="fa fa-desktop" aria-hidden="true"></i></span> <span>General</span></a></h3>
			</div>
		</div>
	</section>

<section class="alternate-background tutorials-page">

	<div class="large-container">

		<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'front-end',
			);

			$frontend = new WP_Query( $args );

			if ( $frontend->have_posts() ) : ?>

		<h2 id="front-end">Front End &amp; Design</h2>
		<div class="grid">

			<?php while ( $frontend->have_posts() ) : $frontend->the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php	endwhile; ?>
		</div>
		<?php endif; wp_reset_postdata(); ?>

		<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'back-end',
			);

			$frontend = new WP_Query( $args );

			if ( $frontend->have_posts() ) : ?>

		<h2 id="back-end">Back End Development</h2>
		<div class="grid">

			<?php while ( $frontend->have_posts() ) : $frontend->the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php	endwhile; ?>
		</div>
		<?php endif; wp_reset_postdata(); ?>


		<?php $args = array(
				'order' => 'asc',
				'posts_per_page' => '-1',
				'category_name' => 'javascript',
			);

			$frontend = new WP_Query( $args );

			if ( $frontend->have_posts() ) : ?>

		<h2 id="javascript">JavaScript</h2>
		<div class="grid">

			<?php while ( $frontend->have_posts() ) : $frontend->the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php	endwhile; ?>
		</div>
		<?php endif; wp_reset_postdata(); ?>


		<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'workflow',
			);

			$frontend = new WP_Query( $args );

			if ( $frontend->have_posts() ) : ?>

		<h2 id="workflow">Developer Workflow</h2>
		<div class="grid">

			<?php while ( $frontend->have_posts() ) : $frontend->the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php	endwhile; ?>
		</div>
		<?php endif; wp_reset_postdata(); ?>


		<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'system',
			);

			$frontend = new WP_Query( $args );

			if ( $frontend->have_posts() ) : ?>

		<h2 id="system">System Administration</h2>
		<div class="grid">

			<?php while ( $frontend->have_posts() ) : $frontend->the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php	endwhile; ?>
		</div>
		<?php endif; wp_reset_postdata(); ?>


		<?php $args = array(
				'order' => 'asc',
				'posts_per_page' => '-1',
				'category_name' => 'wordpress',
			);

			$frontend = new WP_Query( $args );

			if ( $frontend->have_posts() ) : ?>

		<h2 id="wordpress">WordPress</h2>
		<div class="grid">

			<?php while ( $frontend->have_posts() ) : $frontend->the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php	endwhile; ?>
		</div>
		<?php endif; wp_reset_postdata(); ?>


		<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'general',
			);

			$frontend = new WP_Query( $args );

			if ( $frontend->have_posts() ) : ?>

		<h2 id="general">General</h2>
		<div class="grid">

			<?php while ( $frontend->have_posts() ) : $frontend->the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php	endwhile; ?>
		</div>
		<?php endif; wp_reset_postdata(); ?>


	</div>

	</div>
</section>

<section class="quote-container">
	<div class="container">
		<blockquote class="testimonial">"I just stayed up almost all night with excitement after reading a number of your articles due to how well written and
			understandable they are. I feel like I hit the jackpot and did more work in one night than I have in a year."
			<cite>&mdash; <a href="beccimelson.com" target="blank">Becci Melson</a>, Support Engineer</cite></blockquote>
	</div>
</section>

<?php get_footer();