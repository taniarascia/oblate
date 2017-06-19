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
		<blockquote class="testimonial">"Just wanted to say that I find the tone and style of your articles to be very easy to learn from and they have helped me and a handful of people I know a long way."
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

<?php get_footer(); 

