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

<section>
	<div class="container">
		<blockquote class="testimonial">"By far your articles are the most crystal clear I've seen.  An ace web developer who can articulate without ego? That's gold!"
		<cite>&mdash; Ralphie Harvey</cite></blockquote>
	</div>
</section>

<?php endwhile; endif; ?>

<section class="alternate-background tutorials-page">
	<div class="large-container">
		
			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'front-end',
			);

			$frontend = new WP_Query( $args );

			if ( $frontend->have_posts() ) : ?>

			<h2>Front End &amp; Design</h2>
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

			<h2>Back End Development</h2>
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

			<h2>JavaScript</h2>
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

			<h2>Developer Workflow</h2>
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

			<h2>System Administration</h2>
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

			<h2>WordPress</h2>
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

			<h2>General</h2>
			<div class="grid">
			
			<?php while ( $frontend->have_posts() ) : $frontend->the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php	endwhile; ?> 
			</div>
			<?php endif; wp_reset_postdata(); ?>


		</div>

	</div>
</section>

<section>
	<div class="container">
		<blockquote class="testimonial">"I just stayed up almost all night with excitement after reading a number of your articles due to how well written and understandable they are. I feel like I hit the jackpot and did more work in one night than I have in a year."
		<cite>&mdash; Becci Melson</cite></blockquote>
	</div>
</section>

<?php get_footer(); 
