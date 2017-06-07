<article id="post-<?php the_ID(); ?>">

	<header class="single-header">
		<div class="large-container">
			<?php if ( get_post_thumbnail_id() ) { ?>
			<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a>
			<?php } ?>
			<h1>
				<?php the_title(); ?>
			</h1>
			<time datetime="<?php the_time( 'Y-m-d' ); ?>">
				<a href="https://twitter.com/taniarascia"><img src="<?php echo site_url(); ?>/wp-content/uploads/face-150x150.jpg" class="avatar-tania"></a>
				<?php the_time( 'F j, Y' ); ?> /
				<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
			</time>
			
		</div>
	</header>

	<section class="single-body">
		<div class="container">
			<?php the_content(); ?>
			<div class="section-content share" id="share">
				<a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>&via=taniarascia&hashtags=webdev" target="_blank">
					<i class="fa fa-twitter fa-2x" aria-hidden="true"></i>
				</a>
				<a href="https://www.reddit.com/submit?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank">
					<i class="fa fa-reddit-alien fa-2x" aria-hidden="true"></i>
				</a>
				<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" target="_blank">
					<i class="fa fa-facebook-official fa-2x" aria-hidden="true"></i>
				</a>
				<a href="https://news.ycombinator.com/submitlink?u=<?php the_permalink(); ?>&t=<?php the_title(); ?>" target="_blank">
					<i class="fa fa-hacker-news fa-2x" aria-hidden="true"></i>
				</a>
			</div>
		</div>

	</section>

</article>
