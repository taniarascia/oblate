<a class="post" id="post-<?php the_ID(); ?>" href="<?php the_permalink(); ?>">
    <?php if ( get_post_thumbnail_id() ) : ?>
    <div class="post-thumbnail">
		<img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="30" width="30">
    </div>
    <?php endif; ?>	
	<div class="post-title"><?php the_title(); ?></div>
	<span class="post-date"><time><?php the_time( 'F j, Y' ); ?></time></span>
</a>