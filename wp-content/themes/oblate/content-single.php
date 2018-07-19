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
			<p>Liked this article? <a href="https://paypal.me/taniarascia/5">Buy me a coffee</a>.</p>
			</div>
		</div>
	</header>

	<section class="single-body">
		<div class="small-container">
		<div class="excerpt"><?php the_excerpt(); ?></div>
			<?php the_content(); ?>

			<div class="author">
				<h3>Honesty, quality, morality</h3>
				<p>Hi, my name is <strong>Tania Rascia</strong>. Every single article on this website was written by me, for you. There's something I would like you to know.</p>

				<ul>
				<li>I have turned down every individual or company offering to write a <strong>sponsored post</strong> on my website. </li>
				<li>I have turned down every advertiser offering money in exchange for <strong>displaying their ads</strong> on my website.</li>
				<li>I have turned down the option of displaying <strong>paid affiliate links</strong> on my website.</li>
				</ul>

				<p>I don't use pop-ups, sell any products, or block my content. I give away all my knowledge for free to my 300,000+ monthly readers.</p>

				<p>I want to make my website a bastion of knowledge and hope. I want to keep sharing my knowledge and helping thousands of people learn and successfully transition into a design/development career.</p>

				<p><strong>If you support what I do, or if an article I have written has helped guide you in your learning journey, <mark>help me keep this website ad-free, updated, and wonderful</mark>. Support me by being a Patron or buying me a coffee.</strong></p>
				
				<div class="author-options">
					<div>
						<h3>Be a Patron</h3>
						<p><a class="button paypal-button" href="https://www.patreon.com/taniarascia" target="_blank">Be a patron</a></p>
					</div>
					<div>
						<h3>Donate</h3>
						<p><a class="button donate-button" href="https://paypal.me/taniarascia/5">$5</a> <a class="button" href="https://paypal.me/taniarascia/10">$10</a> <a class="button donate-button" href="https://paypal.me/taniarascia/20">$20</a></p>
					</div>
				</div>
			</div>

			<div class="article-email">
			<h3>Email List</h3>
			<p>No ads, no bullshit, no sponsored posts. Just <strong>quality content</strong> from yours truly.
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