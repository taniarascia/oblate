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

<section class="alternate-background">
	<div class="large-container">
		<div class="grid">

			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'Tutorials',
			);

			$thoughts = new WP_Query( $args );

			if ( $thoughts->have_posts() ) :  while ( $thoughts->have_posts() ) : $thoughts->the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php	endwhile; ?>

		</div>
	</div>
</section>

<?php endif; wp_reset_postdata(); ?>

<section>
	<div class="container">
		<blockquote class="testimonial">"I just stayed up almost all night with excitement after reading a number of your articles due to how well written and understandable they are. I feel like I hit the jackpot and did more work in one night than I have in a year."
		<cite>&mdash; Becci Melson</cite></blockquote>
	</div>
</section>

<?php get_footer(); 
