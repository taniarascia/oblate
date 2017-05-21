<article id="post-<?php the_ID(); ?>">

	<header class="single-header">
		<div class="container">
			<?php if ( get_post_thumbnail_id() ) { ?>
			<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a>
			<?php } ?>
			<h1>
				<?php the_title(); ?>
			</h1>
			<time datetime="<?php the_time( 'Y-m-d' ); ?>">
				<?php the_time( 'F j, Y' ); ?> /
				<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?> /
						<a href="https://twitter.com/share?
					url=<?php the_permalink(); ?>&
					via=taniarascia&
					text=<?php the_title(); ?>">
				Share
			</a>
			</time>
		</div>
	</header>

	<section class="single-body">
		<div class="container">
			<?php if ( has_excerpt() ) { ?>
			<div class="lead">
				<?php the_excerpt(); ?>
			</div>
			<?php } ?>
			<?php the_content(); ?>
		</div>
	</section>

</article>