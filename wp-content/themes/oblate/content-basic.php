<article id="post-<?php the_ID(); ?>" class="article-excerpt">
	<time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time>
	<h1>
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>
	</h1>
	<?php the_excerpt(); ?>
	<?php the_tags( '<div class="tags">', '', '</div>' ); ?>
</article>
