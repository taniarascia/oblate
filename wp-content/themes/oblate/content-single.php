<article id="post-<?php the_ID(); ?>" class="single-article">
	<div class="article-header center">
		<h2><?php the_title();?></h2>
		<time>
			<?php the_time('F j, Y');?>
		</time>
		<?php the_tags('&nbsp;☍&nbsp;<span class="tags">', ', ', '', '</span>'); ?>
			<?php if (get_post_thumbnail_id()) {?><img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) );?>" style="margin-top:30px;"><?php } ?>
	</div>
	<?php the_content(); ?>
		<div class="alert">
			<a href="http://twitter.com/intent/tweet?text=<?php echo the_title()?>&url=<?php the_permalink();?>&via=taniarascia" target="_blank" title="Share to Twitter" class="block"><img src="<?php bloginfo('template_directory');?>/images/twitter.png" class="block-i"> Share this article!</a>
		</div>
</article>
