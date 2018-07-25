<article id="post-<?php the_ID(); ?>" class="work-preview">
	<h1>
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>
	</h1>
	<?php the_excerpt(); ?>
    <?php if ( get_post_thumbnail_id() ) : ?>
	<div class="image-thumbnail">
		<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'large' ); ?>" alt="<?php the_title(); ?>"></a>
	</div>
    <?php endif; ?>
</article>