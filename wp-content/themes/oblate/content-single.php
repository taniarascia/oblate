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
	<div>
		<img src="<?php echo site_url(); ?>/wp-content/themes/oblate/images/tr.png" class="blurb-image">
		Written by Tania Rascia
	</div>
	<div>
 		<a href="http://twitter.com/intent/tweet?text=<?php echo the_title()?>&url=<?php the_permalink();?>&via=taniarascia" target="_blank" title="Share to Twitter"><i class="fa fa-twitter fa-2x"></i></a>
	</div>
</div>