<?php get_header(); ?>

<section class="landing-page">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<header class="landing-header">
		<div class="small-container">
			<div class="banner">
				<h1>Tania Rascia</h1>
				<?php the_content(); ?>
				<!-- Place this tag where you want the button to render. -->
				<a class="github-button" href="https://github.com/taniarascia" data-count-href="/taniarascia/followers" data-count-api="/users/taniarascia#followers" data-count-aria-label="# followers on GitHub" aria-label="Follow @taniarascia on GitHub">Follow</a>
				<a class="twitter-follow-button" data-show-screen-name="false" href="https://twitter.com/taniarascia">Follow</a>
			</div>
			<h4>Email list</h4>
			<p>Join the list and I'll keep you up to date with new posts about design and development, along with information about my future endeavors. I respect your inbox as if it were my own - no bullshit, gimmicks, or ads.</p>
			<div id="mc_embed_signup">
				<form action="//taniarascia.us12.list-manage.com/subscribe/post?u=ec794fab6e35a491a001cc25d&amp;id=5276386071" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					<label for="mce-EMAIL"><span class="screen-reader-text">Email</span></label><input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Email address">
					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div>
					<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
					<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_ec794fab6e35a491a001cc25d_5276386071" tabindex="-1" value=""></div>
					<div class="clear"><input type="submit" value="Join" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
				</form>
			</div>
		</div>
	</header>

	<?php endwhile; endif; ?>

	<section class="latest">
		<div class="small-container">
			<h4>Latest posts</h4>

			<ul>
				<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '5',
				'category__not_in' => array( 5 ),
			);

			$latest = new WP_Query( $args );

			if ( $latest->have_posts() ) :  while ( $latest->have_posts() ) : $latest->the_post(); ?>

				<h3>
					<a href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
					</a> 
				</h3>
				
				<?php the_excerpt(); ?>

				<?php endwhile; endif; wp_reset_postdata(); ?>

			</ul>
		</div>
	</section>

	<section class="popular">
		<div class="small-container">

			<h4>Popular posts</h4>

			<ul>
				<?php $args = array(
				'order' => 'asc',
				'posts_per_page' => '5',
				'category_name' => 'Popular',
				'category__not_in' => array( 5 ),
			);

			$popular = new WP_Query( $args );

			if ( $popular->have_posts() ) :  while ( $popular->have_posts() ) : $popular->the_post(); ?>

				<li>
					<a href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
					</a> 
				</li>

				<?php endwhile; endif; wp_reset_postdata(); ?>

			</ul>

		</div>
	</section>

</section>
<?php get_footer(); ?>
