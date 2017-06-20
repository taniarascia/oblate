<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="page-header bright-header thoughts-header">
	<div class="small-container">
		<h1>
			<?php the_title(); ?>
		</h1>
		<?php the_content(); ?>
	</div>
</header>

<?php endwhile; endif; ?>

<section>
	<div class="container">
		<blockquote class="testimonial">"I find the tone and style of your articles to be very easy to learn from and they have helped me and a handful of people I know a long way."
		<cite>&mdash; James Cruickshank, Perlego</cite></blockquote>
	</div>
</section>

<section class="alternate-background">
	<div class="large-container">
		<div class="grid">

			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'Thoughts',
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
		<blockquote class="testimonial">"Thank you so much for the tutorials and the great work you are doing. So many tutorials out there but somehow you get to combine the best bits of clear thought out instructions with sufficiently in-depth instructions to get projects started."
		<cite>&mdash; Stephan Ye</cite></blockquote>
	</div>
</section>

<?php get_footer(); 

