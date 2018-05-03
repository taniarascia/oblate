<article id="post-<?php the_ID(); ?>">
	<header class="single-header alternate-background">
		<div class="small-container">
			<?php if ( get_post_thumbnail_id() ) { ?>
			<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a>
			<?php } ?>
				<div class="single-title">
					<h1>
						<?php the_title(); ?>
					</h1>
					<div class="the-time">
						By <a href="<?php echo site_url(); ?>/me">Tania Rascia</a> &nbsp;/&nbsp;
						<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
						<time datetime="<?php the_time( 'Y-m-d' ); ?>"> &nbsp;/&nbsp;
						<?php the_time( 'F j, Y' ); ?></time>
					</div>	
			<div class="single-social">
				<a class="button secondary-button" href="https://github.com/taniarascia" target="_blank"><i class="fa fa-github"></i> &nbsp;GitHub</a> <a class="button secondary-button" href="https://twitter.com/intent/tweet?text=<?php echo the_title(); ?>&amp;url=<?php echo the_permalink(); ?>&amp;hashtags=webdev&amp;via=taniarascia" target="_blank"><i class="fa fa-twitter"></i> &nbsp;Twitter</a>
			</div>
		</div>
	</header>

	<section class="single-body">
		<div class="small-container">
		<div class="excerpt"><?php the_excerpt(); ?></div>
			<?php the_content(); ?>
			<div class="article-email">
			<h3>Email List</h3>
			<p>Get friendly updates, infrequently.</p>
			<div id="mailchimp-article">
				<form action="//taniarascia.us12.list-manage.com/subscribe/post?u=ec794fab6e35a491a001cc25d&amp;id=5276386071" method="post"
				  id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					<label for="mce-EMAIL"><span class="screen-reader-text">Email</span></label><input type="email" value="" name="EMAIL"
					  class="required email" id="mce-EMAIL" placeholder="Email address">
					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div>
					<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_ec794fab6e35a491a001cc25d_5276386071" tabindex="-1" value=""></div>
					<div class="clear"><button type="submit" name="subscribe" id="mc-embedded-subscribe" class="button"><i class="fa fa-arrow-right" aria-hidden="true"></i></button></div>
				</form>
			</div>
		</div>
			<div class="author">
				<p><img src="<?php echo site_url(); ?>/wp-content/uploads/face-300x300.jpg" class="my-avatar">
				<h3>Tania Rascia</strong></h3>
		
			Hiya. I'm <a href="<?php echo site_url(); ?>/me">Tania</a>, a web developer from Chicago. Hope you enjoyed my ad-free, bullshit-free site. If you liked it, tell someone about it!</p>
			<a class="button secondary-button" href="https://github.com/taniarascia" target="_blank"><i class="fa fa-github"></i> &nbsp;GitHub</a>
			<a class="button secondary-button" href="https://twitter.com/taniarascia" target="_blank"><i class="fa fa-twitter"></i> &nbsp;Twitter</a>

			</div>
		</div>

	</section>

</article>
