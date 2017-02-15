<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section class="banner">
	<div class="container">
		<h1>Tania Rascia</h1>
			<?php the_content(); ?>
	</div>
</section>

<?php endwhile; endif; ?>

<?php $args = array(
	'posts_per_page' => '3',
 );

$latest = new WP_Query( $args );

if ( $latest->have_posts() ) : ?>

	<section class="content latest-articles">
		<div class="container">
			<h1>Latest Articles</h1>
			<p>My most recent thoughts, guides, and tutorials.</p>
			<p><a href="<?php echo site_url(); ?>/blog" class="button">View all</a></p>

			<?php while ( $latest->have_posts() ) : $latest->the_post(); 

				get_template_part( 'content', get_post_format() );

			endwhile; ?>

		</div>
	</section>

<?php endif; wp_reset_postdata(); ?>

<?php get_footer(); ?>