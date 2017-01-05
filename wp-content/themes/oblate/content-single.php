<article id="post-<?php the_ID(); ?>">
	<?php if ( get_post_thumbnail_id() ) { ?>
	<div class="single-article-image">
		<img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150">
	</div>
	<?php } ?>
		
	<div class="single-article-header">
		<h1><?php the_title(); ?></h1>
		<time datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( 'F j, Y' ); ?></time>
		<?php the_tags('<div class="tags-container"><span class="tags">' ,'</span><span class="tags">','</span></div>'); ?>
	</div>

	<?php the_content(); ?>
</article>

<div class="blurb">
	<cite>Written by Tania Rascia<br>
		<a href="https://github.com/taniarascia" target="_blank" class="gh-button"><i class="fa fa-github" aria-hidden="true"></i> GitHub</a> <a href="https://twitter.com/taniarascia" target="_blank" class="tw-button"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</a></cite>
</div>

<div class="container random-posts">
	<?php
	$args = array( 
		'posts_per_page' => 3, 
		'orderby' => 'rand' );

	$rand_posts = get_posts( $args );

	foreach ( $rand_posts as $post ) : setup_postdata( $post ); ?>
			
			<h3><?php if ( get_post_thumbnail_id() ) { ?><a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="35" width="35"></a><?php } ?> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			 

	<?php endforeach; 

	wp_reset_postdata(); ?>
</div>