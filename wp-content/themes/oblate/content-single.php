<?php 
$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'thumbnail' );
$url = $thumb['0']; ?>

	<div class="single-article-image">
		<?php if ( get_post_thumbnail_id() ) { ?> <img src="<?php echo $url; ?>" alt="<?php the_title(); ?>">
			<?php } ?>
	</div>

	<article id="post-<?php the_ID(); ?>" class="single-article">

		<div class="single-article-header">
			<h1><?php the_title(); ?></h1>
			<time datetime="<?php the_time('Y-m-d'); ?>">
				<?php the_time('F j, Y'); ?>
			</time>
		</div>

		<?php the_content(); ?>
		
			<a href="http://twitter.com/intent/tweet?text=<?php echo the_title()?>&url=<?php the_permalink();?>&via=taniarascia" target="_blank" title="Share to Twitter" class="twitter-link button"><i class="fa fa-twitter fa-2x"></i> Share this article!</a>
	</article>