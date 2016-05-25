<?php 
$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'thumbnail' );
$url = $thumb['0']; ?>

<a href="<?php the_permalink(); ?>" class="article-link">
	<article id="post-<?php the_ID(); ?>" class="article-excerpt">
		
		<div class="article-header">
		<time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time>
		<h1><?php the_title(); ?></h1>
		<?php the_excerpt(); ?>
		</div>
		
		<div class="article-image">
		<?php if ( get_post_thumbnail_id() ) { ?> <img src="<?php echo $url; ?>" alt="<?php the_title(); ?>"><?php } ?>
		</div>

		</article>
</a>
