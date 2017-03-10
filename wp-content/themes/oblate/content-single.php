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