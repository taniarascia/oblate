<article id="post-<?php the_ID(); ?>" class="article-excerpt">
	<div class="article-header">
		<h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
		<time>
			<?php the_time('F j, Y');?>
		</time>
		<?php the_tags('&nbsp;☍&nbsp;<span class="tags">', ', ', '', '</span>'); ?>
	</div> 
	 <?php the_excerpt(); ?>
</article>
