<article id="post-<?php the_ID(); ?>" class="search-results">
	<div class="the-time"><time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time></div>
	<h1>
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>
	</h1>
	<?php the_excerpt(); ?>
</article>
