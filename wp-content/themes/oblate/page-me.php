<?php get_header(); ?>

	<?php 
$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'full' );
$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'thumbnail' );
$img = $image['0']; 
?>

		<main class="main-content">

			<?php if ( get_post_thumbnail_id() ) { ?>
				<div class="single-article-image">
					<img src="<?php echo $img; ?>" alt="<?php the_title(); ?>">
				</div>
				<?php } ?>

					<div class="small-container">

						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>">

								<div class="single-article-header">
									<h1 class="large-heading"><?php the_title(); ?></h1>
								</div>

								<?php the_content(); ?>

							</article>

							<?php endwhile; endif; ?>

					</div>

		</main>

		<?php get_footer(); ?>