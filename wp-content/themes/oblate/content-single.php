<article id="post-<?php the_ID(); ?>">

	<header class="single-header">
		<div class="small-container">
			<?php if ( get_post_thumbnail_id() ) { ?>
			<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a>
			<?php } ?>
			<h1>
				<?php the_title(); ?>
			</h1>
			<div class="the-time"><time datetime="<?php the_time( 'Y-m-d' ); ?>">
				<?php the_time( 'F j, Y' ); ?></time> /
				<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
			</div>
			
		</div>
	</header>

	<section class="single-body">
		<div class="small-container">
			<?php the_content(); ?>
			<div class="section-content share" id="share">
				<a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>&via=taniarascia&hashtags=webdev" target="_blank">	
				<span class="fa-stack fa-lg">
				<i class="fa fa-square fa-stack-2x twitter"></i>
				<i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
				</span>
				</a>
				<a href="https://www.reddit.com/submit?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank">
				<span class="fa-stack fa-lg">
				<i class="fa fa-square fa-stack-2x reddit"></i>
				<i class="fa fa-reddit-alien fa-stack-1x fa-inverse"></i>
				</span>
				</a>
				<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" target="_blank">
				<span class="fa-stack fa-lg">
					<i class="fa fa-square fa-stack-2x facebook"></i>
					<i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
					</span>
					</a>
				<a href="https://news.ycombinator.com/submitlink?u=<?php the_permalink(); ?>&t=<?php the_title(); ?>" target="_blank">
				<span class="fa-stack fa-lg">
				<i class="fa fa-square fa-stack-2x hackernews"></i>
				<i class="fa fa-hacker-news fa-stack-1x fa-inverse"></i>
				</span>
				</a>
			</div>
				<h2>The Author</h2>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
			<p><img src="<?php echo site_url(); ?>/wp-content/uploads/face-300x300.jpg" class="my-avatar">Thank you for reading! I'm <strong>Tania Rascia</strong>, and I write no-nonsense guides for designers and developers. <strong>If my content has been valuable to you, please help me keep the site <mark>ad-free</mark> and continuously updated with quality tutorials and material by making a donation.</strong> Any amount helps and is greatly appreciated!</p>
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
			">
			<input type="submit" name="submit" alt="PayPal - The safer, easier way to pay online!" class="button secondary-button" value="Donate!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>

		</div>

	</section>

</article>
