<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
	 $url = $thumb['0']; ?>
	 
	<a href="<?php the_permalink();?>">
		
		<article id="post-<?php the_ID(); ?>" class="article-excerpt">
			
			<div class="article-header-single">
				<?php if (get_post_thumbnail_id()) { ?> <img src="<?php echo $url; ?>" class="image-float" alt="<?php the_title(); ?>"> <div><?php } else { ?><div class="no-image"><?php } ?>
				<div>		
				<h2><?php the_title();?></h2>
				</div>
				<time datetime="<?php the_time('Y-m-d');?>">
				
					<?php the_time('F j, Y');?>
					
				</time>
				
				</div>
			</div>

		</article>
		
</a>