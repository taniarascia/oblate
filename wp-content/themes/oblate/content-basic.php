<article id="post-<?php the_ID(); ?>" class="article-excerpt article-flex">
	<div>
		<time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time>
		<h1>
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h1>
		<?php the_excerpt(); ?>
		<?php the_tags( '<div class="tags">', '', '</div>' ); ?>
	</div>
	<?php if ( get_post_thumbnail_id() ) { ?>
	<div class="basic-image">
		<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a>
	</div>
	<?php } ?>
</article>
