<article id="post-<?php the_ID(); ?>">
	<header class="single-header alternate-background">
		<div class="small-container">
			<?php if ( get_post_thumbnail_id() ) { ?>
			<img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150">
			<?php } ?>
				<div class="single-title">
					<h1>
						<?php the_title(); ?>
					</h1>
					<div class="the-time">
						<a href="<?php echo site_url(); ?>/me">Tania Rascia</a> &nbsp;/&nbsp;
						<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
						<time datetime="<?php the_time( 'Y-m-d' ); ?>"> &nbsp;/&nbsp;
						<?php the_time( 'F j, Y' ); ?></time>
					</div>	
			<div class="single-social">
			<p>Liked this article? <a href="https://ko-fi.com/taniarascia">Buy me a coffee</a>.</p>
			</div>
		</div>
	</header>

	<section class="single-body">
		<div class="small-container">
		<div class="excerpt"><?php the_excerpt(); ?></div>
			<?php the_content(); ?>

			<div class="author">
				<h2 class="text-center">Quality and morality on the web</h2>
				<p>Hi, my name is <strong>Tania Rascia</strong>. I have turned down every advertiser, sponsored post, and affiliate who has come to me. <strong>I give away all my knowledge for free to <mark>300,000+ monthly readers</mark></strong> and helped thousands of people learn and successfully transition into a design/development career.</p>

				<p><strong>You know what I stand for, and you know my commitment to you - no bullshit, no sponsored posts, no pop-ups, no blocked content, ads, schemes, tactics, or gimmicks. Just free, quality content. <mark>Support me by buying me a coffee</mark></strong>.</p>
				
				<div class="author-options">
					<div>
						<h3>I really appreciate your support!</h3>
						<p><a class="button paypal-button" href="https://ko-fi.com/taniarascia" target="_blank">Buy me a coffee</a> <a class="button paypal-button" href="https://patreon.com/taniarascia" target="_blank">Be a patron</a></p>
					</div>
				</div>
			</div>

			<div class="article-email">
			<h3>Email List</h3>
			<p>Infrequent friendly updates.</p>
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

	</section>

</article>