<a href="<?php the_permalink();?>">
<article id="post-<?php the_ID(); ?>" class="article-excerpt">
	<div class="article-header">
		<h2><?php the_title();?></h2>
<time>
	<?php the_time('F j, Y');?>
</time>
</div>
<?php the_excerpt(); ?>
	</article>
</a>
