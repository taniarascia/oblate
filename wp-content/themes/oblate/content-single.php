<article id="post-<?php the_ID(); ?>">
		
	<div class="single-article-header">
		<div class="author">
			<img src="<?php echo site_url(); ?>/wp-content/themes/oblate/images/tr.png"> 
			<div class="name"><a href="<?php echo site_url(); ?>/me">Tania Rascia</a> - <a href="https://twitter.com/taniarascia" target="_blank"><i class="fa fa-twitter"></i></a> <a href="https://github.com/taniarascia" target="_blank"><i class="fa fa-github"></i></a> </div>
			<time datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( 'F j, Y' ); ?> - <?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?></time>
		</div>
		
	<h1><?php the_title(); ?></h1>
		<?php if ( has_excerpt() ) { ?>
		<div class="lead-excerpt"><?php the_excerpt(); ?></div>
		<?php } ?>
	</div>

	<?php the_content(); ?>
</article>

<blockquote class="details blurb">
If you found this article useful, please share it!
	<a href="http://twitter.com/intent/tweet?text=<?php echo the_title()?>&url=<?php the_permalink();?>&via=taniarascia" target="_blank" title="Share to Twitter"><i class="fa fa-twitter fa-2x"></i></a> 	
</blockquote>