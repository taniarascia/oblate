<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="page-header bright-header snippets-header">
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
		<blockquote class="testimonial">"Thanks a million for your well-written, easy-to-follow and practical guides. Previously I hated working in Wordpress because it always felt a bit like trying to find the right tools in somebody else's kitchen — but now I feel confident opening up an existing theme and understanding how it works."
		<cite>&mdash; David Bock</cite></blockquote>
	</div>
</section>

<section class="alternate-background">
	<div class="large-container">
		<div class="grid">

			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'Snippets',
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