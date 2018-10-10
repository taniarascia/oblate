<article id="post-<?php the_ID(); ?>" class="article-preview">
<div class="image-thumbnail">
	<?php if ( get_post_thumbnail_id() ) : ?>
		<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="100" width="100"></a>
		<?php endif; ?>	
	</div>
	<div class="preview-title">
		<h1>
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h1>
		<div class="response"><time><?php the_time( 'F j, Y' ); ?></time> /
			<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
		</div>
	</div>
	<div class="preview-excerpt">
		<?php the_excerpt(); ?>
	</div>

</article>