<article id="post-<?php the_ID(); ?>">
		
	<div class="single-article-header">
		
	<h1 class="text-center"><?php the_title(); ?></h1>
		<?php if ( has_excerpt() ) { ?>
		<div class="lead-excerpt"><?php the_excerpt(); ?></div>
		<?php } ?>
	</div>

	<?php the_content(); ?>
</article>