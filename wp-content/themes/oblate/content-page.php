<?php 
$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'thumbnail' );
$url = $thumb['0']; ?>
		
		<?php if ( get_post_thumbnail_id() ) { ?> <div class="single-article-image">
			<img src="<?php echo $url; ?>" alt="<?php the_title(); ?>" style="border-radius: 50%;">
		</div>
		<?php } ?>

		<article id="post-<?php the_ID(); ?>" class="single-article">
		
		<div class="single-article-header">
		<h1><?php the_title(); ?></h1>
		</div>
		
		<?php the_content(); ?>

			</article>