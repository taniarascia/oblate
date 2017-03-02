<article id="post-<?php the_ID(); ?>" class="article-excerpt">
	<div class="article-image">
		<?php if ( get_post_thumbnail_id() ) { ?><a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a><?php } ?>
	</div>
	<div>
	<time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time>
	<?php the_tags('<div class="tags-container-page"><span class="tags">' ,'</span><span class="tags">','</span></div>'); ?>
		<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<?php the_excerpt(); ?>
	</div>
</article>