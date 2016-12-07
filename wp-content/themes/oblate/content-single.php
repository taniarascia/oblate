<?php 
$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'thumbnail' );
$url = $thumb[0]; 
?>

	<div class="single-article-image">
		<?php if ( get_post_thumbnail_id() ) { ?> <img src="<?php echo $url; ?>" alt="<?php the_title(); ?>">
			<?php } ?>
	</div>

	<article id="post-<?php the_ID(); ?>">

		<div class="single-article-header">
			<h1><?php the_title(); ?></h1>
			<time datetime="<?php the_time('Y-m-d'); ?>">
				<?php the_time('F j, Y'); ?>
			</time>
				<?php the_tags('<div class="tags-container"><span class="tags">' ,'</span><span class="tags">','</span></div>'); ?>
		</div>

	<?php the_content(); ?>

	</article>