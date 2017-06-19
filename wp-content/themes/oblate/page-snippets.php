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
		<blockquote class="testimonial">"Thanks a million for your well-written, easy-to-follow and practical guides. Previously I hated working in WordPress because it always felt a bit like trying to find the right tools in somebody else's kitchen — but now I feel confident opening up an existing theme and understanding how it works."
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

			$snippets = new WP_Query( $args );

			if ( $snippets->have_posts() ) :  while ( $snippets->have_posts() ) : $snippets->the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php	endwhile; ?>

		</div>
	</div>
</section>

<?php endif; wp_reset_postdata(); ?>

<section>
	<div class="container">
		<blockquote class="testimonial">"The team I work with is made up of mostly designers who learned to code on the job, and they have been having a hard time keeping up with modern workflows and standards. This blog, and your open source projects, are exactly what I've been hoping to find to help remedy some of their pain and confusion. I think you hit a major pain point for a lot of devs who are both new and even experienced ones."
		<cite>&mdash; Dan Fletcher</cite></blockquote>
	</div>
</section>

<?php get_footer(); 