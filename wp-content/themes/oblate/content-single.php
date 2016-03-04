<article id="post-<?php the_ID(); ?>" class="single-article">
	<div class="article-header-single center">
				<?php  $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
	 $url = $thumb['0']; if (get_post_thumbnail_id()) {?><img src="<?php echo $url;?>" class="article-image" alt="<?php the_title();?>"><?php } ?>
		<h2><?php the_title();?></h2>
		<time datetime="<?php the_time('Y-m-d');?>">
			<?php the_time('F j, Y');?>
		</time>
		<?php the_tags('&nbsp;☍&nbsp;<span class="tags">', ', ', '', '</span>'); ?>
	</div>
	<?php the_content(); ?>
		<div class="alert">
			<a href="http://twitter.com/intent/tweet?text=<?php echo the_title()?>&url=<?php the_permalink();?>&via=taniarascia" target="_blank" title="Share to Twitter" class="block"><img src="<?php bloginfo('template_directory');?>/images/twitter.png" class="block-i" alt="Share this article"> Share this article!</a>
		</div>
</article>
