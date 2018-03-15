<article id="post-<?php the_ID(); ?>" class="cell">
		<?php if ( get_post_thumbnail_id() ) { ?>
		<div class="image-thumbnail">
			<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a>
		</div>
		<?php } ?>
		<h1>
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h1>
	<div>
		<?php the_excerpt(); ?>
	</div>
	<div class="response"><a href="<?php the_permalink(); ?>">Read</a> |
		<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
	</div>
</article>