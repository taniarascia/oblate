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
						<?php the_time( 'F j, Y' ); ?> -
							<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
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

						<?php the_tags( '<div class="tags">', '', '</div>' ); ?>

		</div>

		<div class="dark-box">
			<div class="container">
				<h2>My email list</h2>

				<p>Consider joining my email list. I'll only be sending out the important stuff, like new blog posts and any updates about e-books, podcasts, or video tutorials to come.</p>

				<div id="mc_embed_signup">
					<form action="//taniarascia.us12.list-manage.com/subscribe/post?u=ec794fab6e35a491a001cc25d&amp;id=5276386071" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
						<label for="mce-EMAIL" style="margin-bototm:.5rem;">Email</label>
						<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" style="width: 100%;">
						<div id="mce-responses" class="clear">
							<div class="response" id="mce-error-response" style="display:none"></div>
							<div class="response" id="mce-success-response" style="display:none"></div>
						</div>
						<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
						<div style="position: absolute; left: -5000px;" aria-hidden="true">
							<input type="text" name="b_ec794fab6e35a491a001cc25d_5276386071" tabindex="-1" value="">
						</div>
						<div class="clear">
							<input type="submit" value="Join" name="subscribe" id="mc-embedded-subscribe" class="button alt-button">
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

</article>