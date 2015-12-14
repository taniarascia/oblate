<a href="<?php the_permalink();?>">
<article id="post-<?php the_ID(); ?>" class="article-excerpt">
<?php if(get_post_thumbnail_id()) {?><div class="flex-responsive"><div class="box three-fourths"> <?php } ?>		
	<div class="article-header">
		<h2><?php the_title();?></h2>
		<time><?php the_time('F j, Y');?></time>
	</div>
<?php the_excerpt(); ?>
<?php if(get_post_thumbnail_id()) {?>
	<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
	 $url = $thumb['0']; ?></div><div class="box one-fourth thumb">
	 <img src="<?php echo $url; ?>" class="desktop-only">
	</div></div><?php } ?>
</article>
</a>
