<?php get_header(); ?>

<section class="landing-page">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<header class="landing-header">
		<div class="small-container">
			<h1>Tania Rascia</h1>
			<?php the_content(); ?>
		</div>
	</header>

	<?php endwhile; endif; ?>

	<section class="latest">
		<div class="small-container">
			<h4>Latest Posts</h4>

			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '3',
				'category__not_in' => array( 5 ),
			);

			$latest = new WP_Query( $args );

			if ( $latest->have_posts() ) :  while ( $latest->have_posts() ) : $latest->the_post(); ?>

			<b><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></b>
			<?php the_excerpt(); ?>

			<?php endwhile; endif; wp_reset_postdata(); ?>
		</div>
	</section>

	<section class="popular">
		<div class="small-container">

			<h4>Popular Posts</h4>

			<?php $args = array(
				'order' => 'asc',
				'posts_per_page' => '3',
				'category_name' => 'Popular',
				'category__not_in' => array( 5 ),
			);

			$popular = new WP_Query( $args );

			if ( $popular->have_posts() ) :  while ( $popular->have_posts() ) : $popular->the_post(); ?>

			<b><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></b>
			<?php the_excerpt(); ?>

			<?php endwhile; endif; ?>
		</div>
	</section>

	<div class="dark-box">
		<div class="small-container">
			<h2>My email list</h2>

			<p>If you'd like to get on the list to be updated about any posts, e-books, podcasts, or video tutorials to come, sign up below!</p>

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
<?php get_footer(); ?>
