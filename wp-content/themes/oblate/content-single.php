<article id="post-<?php the_ID(); ?>" class="single-article">
	<div class="article-header center">
		<h2><?php the_title();?></h2>
		<time>
			<?php the_time('F j, Y');?>
		</time>
		<?php the_tags('&nbsp;☍&nbsp;<span class="tags">', ', ', '', '</span>'); ?>
	</div>
	<?php the_content(); ?>
		<a href="http://twitter.com/intent/tweet?text=<?php echo the_title()?>&url=<?php the_permalink();?>&via=taniarascia" target="_blank" title="Share to Twitter"><img src="<?php bloginfo('template_directory');?>/images/tweetbutton.png" class="twitter-button"></a>
</article>
