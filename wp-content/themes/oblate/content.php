<article id="post-<?php the_ID(); ?>" class="cell">
	<?php if ( get_post_thumbnail_id() ) { ?>
	<div class="image-thumbnail">
		<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a>
	</div>
	<?php } ?>
	<div>
		<h1>
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h1>
		<?php the_excerpt(); ?>
		<div class="response"><a href="<?php the_permalink(); ?>">Read</a> |
			<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
		</div>
		<div class="cell-share"> <a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>&via=taniarascia&hashtags=webdev"
			  target="_blank"><i class="fa fa-twitter"></i></a></div>
	</div>
</article>