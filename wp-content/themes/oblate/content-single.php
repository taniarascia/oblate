<article id="post-<?php the_ID(); ?>">
	<div class="center">
		<h2><?php the_title();?></h2>
		<time>
			<?php the_time('F j, Y');?>
		</time>
		<?php the_tags('&nbsp;☍&nbsp;<span class="tags">', ', ', '', '</span>'); ?>
	</div>
	<p>
		<?php the_content(); ?>
	</p>
</article>
