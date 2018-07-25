<?php get_header(); ?>

<section class="content">
	<section class="page-body">
		<header class="page-header">
			<h1><?php the_title(); ?></h1>
		</header>
		<section class="article-preview-section">

			<?php $args = array(
				'post_type' => 'work',
				'order' => 'asc',
				'posts_per_page' => '-1',
			);

			$work = new WP_Query( $args );

			if ( $work->have_posts() ) :  while ( $work->have_posts() ) : $work->the_post();
			$url = get_post_meta($post->ID, 'url', true); ?>

			<?php get_template_part( 'content-work', get_post_format() ); ?>

			<?php endwhile; endif; wp_reset_postdata(); ?>

		</section>
	</section>
</section>

<?php get_footer();