<a href="<?php the_permalink();?>" class="article-selected">
	<article id="post-<?php the_ID(); ?>">
	<div class="article-header center">
		<h2><?php the_title();?></h2>
<time>
	<?php the_time('F j, Y');?>
</time>
</div>
<?php the_excerpt(); ?>
	</article>
	</a>
