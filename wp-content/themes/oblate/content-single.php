<article id="post-<?php the_ID(); ?>">

	<div class="single-article-header">

		<div class="single-article-image">
			<?php if ( get_post_thumbnail_id() ) { ?><a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a>
				<?php } ?>
		</div>

		<h1 class="text-center"><?php the_title(); ?></h1>
		<time datetime="<?php the_time( 'Y-m-d' ); ?>">
			<?php the_time( 'F j, Y' ); ?> -
				<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
		</time>
		<?php if ( has_excerpt() ) { ?>
			<div class="lead-excerpt">
				<?php the_excerpt(); ?>
			</div>
			<?php } ?>

	</div>



	<?php the_content(); ?>
</article>