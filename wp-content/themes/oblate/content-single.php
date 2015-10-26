<div class="content">
	<article id="post-<?php the_ID(); ?>">
		<div class="article-header center">
			<h2><?php the_title();?></h2>
			<time>
				<?php the_time('F j, Y');?>
			</time>
			<?php the_tags('&nbsp;☍&nbsp;<span class="tags">', ', ', '', '</span>'); ?>
		</div>
		<?php the_content(); ?>
	</article>
</div>
