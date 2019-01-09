<article class="article" id="post-<?php the_ID(); ?>">
    <div class="container">
		<header class="single-header">
			<?php if ( get_post_thumbnail_id() ) : ?>
			    <img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="75" width="75">
            <?php endif; ?>
			<h1><?php the_title(); ?></h1>
			<div class="date">
				<time datetime="<?php the_time( 'Y-m-d' ); ?>"> 
				<?php the_time( 'F j, Y' ); ?>
				</time> &nbsp;/&nbsp;
				<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
			</div>
			
		</header>

		<?php the_content(); ?>

		<h2>Newsletter</h2>
			<p>Get updated when I create new content.<br> Unsubscribe whenever. Never any spam.</p>
			<form id="newsletter-form" class="newsletter-form" action="https://newsletter.taniarascia.com/sendy/subscribe" method="POST" accept-charset="utf-8" target="_blank">
				<input type="email" name="email" required id="newsletter-email" class="email" placeholder="Email address" pattern="[a-zA-Z0-9._\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,4}">
				<input type="hidden" name="list" value="P2bfC2WL3TvnTWEmucMbbg">
				<input type="submit" name="submit" id="newsletter-submit" value="Submit">
			</form>
		<div class="author">
			<h2>Note</h2>
			<p>I'm Tania. I turn down every ad, affiliate, and sponsor request I get. I write free resources that help thousands of people successfully become devs. If you enjoy my content, please consider supporting what I do.</p>
			<a class="button yellow-button" href="https://ko-fi.com/taniarascia" target="_blank">Support my work <i class="fas fa-coffee"></i></a>
		</div>
			
    </div>
</article>