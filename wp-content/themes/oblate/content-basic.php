<article id="post-<?php the_ID(); ?>" class="search-results">
	<div>
	<?php if ( get_post_thumbnail_id() ) { ?>
				<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150" class="search-image"></a>
				<?php } ?>
	</div>
	<div>
		<div class="the-time"><time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time></div>
		<h1>
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h1>
		<?php the_excerpt(); ?>
	</div>
</article>