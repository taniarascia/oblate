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
				<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?> -
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

			<a class="button secondary-button" href="https://twitter.com/share?
					url=<?php the_permalink(); ?>&
					via=taniarascia&
					text=<?php the_title(); ?>">
				<i class="fa fa-twitter" aria-hidden="true"></i> Share to Twitter
			</a>

			<?php the_tags( '<div class="tags">', '', '</div>' ); ?>

		</div>

		<div class="email-container">
			<div class="dark-box">
				<div class="container">
					<h2>Email list</h2>
					<p>Join the list and I'll keep you up to date with new posts about design and development, along with information about my future endeavors. I respect your inbox as if it were my own - no bullshit, gimmicks, or ads.</p>
					<div id="mc_embed_signup">
						<form action="//taniarascia.us12.list-manage.com/subscribe/post?u=ec794fab6e35a491a001cc25d&amp;id=5276386071" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
							<label for="mce-EMAIL"><span class="screen-reader-text">Email</span></label><input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Email address"><div id="mce-responses" class="clear"><div class="response" id="mce-error-response" style="display:none"></div><div class="response" id="mce-success-response" style="display:none"></div></div><!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups--><div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_ec794fab6e35a491a001cc25d_5276386071" tabindex="-1" value=""></div><div class="clear"><input type="submit" value="Join" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

</article>
