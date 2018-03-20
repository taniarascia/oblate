<article id="post-<?php the_ID(); ?>">

	<header class="single-header alternate-background">
		<div class="small-container">
		<div class="flex-container fs">
			<?php if ( get_post_thumbnail_id() ) { ?>
			<a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150"></a>
			<?php } ?>
			<div>
			<h1>
				<?php the_title(); ?>
			</h1>
			<div class="the-time">
				By <a href="<?php echo site_url(); ?>/me">Tania Rascia</a> &nbsp;/&nbsp;
				<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
				<time datetime="<?php the_time( 'Y-m-d' ); ?>"> &nbsp;/&nbsp;
				<?php the_time( 'F j, Y' ); ?></time>
			</div>
			</div>
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
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
			Hiya. I'm <a href="<?php echo site_url(); ?>/me">Tania</a>, a web developer from Chicago. Hope you enjoyed my ad-free, bullshit-free site. If you liked it, tell someone about it!</p>
			<a class="button secondary-button" href="https://github.com/taniarascia" target="_blank"><i class="fa fa-github"></i> &nbsp;GitHub</a>
			<a class="button secondary-button" href="https://twitter.com/taniarascia" target="_blank"><i class="fa fa-twitter"></i> &nbsp;Twitter</a>
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
			">
			<input type="submit" name="submit" alt="PayPal - The safer, easier way to pay online!" class="button secondary-button" value="Donate with PayPal">
			</form>
			</div>
		</div>

	</section>

</article>
