<?php get_header(); ?>

	<main class="main-content">

		<div class="small-container">

			<?php 
$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'full' );
$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'thumbnail' );
$img = $image['0']; 
?>

				<?php if ( get_post_thumbnail_id() ) { ?>
					<div class="single-article-image">
						<img src="<?php echo $img; ?>" alt="<?php the_title(); ?>" class="responsive-image">
					</div>
					<?php } ?>

						<article id="post-<?php the_ID(); ?>">

							<div class="single-article-header">
								<h1 class="large-heading"><?php the_title(); ?></h1>
							</div>

							<?php the_content(); ?>

						</article>

		</div>

	</main>

	<?php get_footer(); ?>