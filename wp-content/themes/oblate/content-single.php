<article class="article" id="post-<?php the_ID(); ?>">
    <div class="container">
		<header class="single-header">
			<?php if ( get_post_thumbnail_id() ) : ?>
			    <img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="75" width="75">
            <?php endif; ?>
			<h1><?php the_title(); ?></h1>
			<div class="date"><time datetime="<?php the_time( 'Y-m-d' ); ?>"> 
                <?php the_time( 'F j, Y' ); ?></time> &nbsp;/&nbsp;
                <?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
			</div>
		</header>

		<?php the_content(); ?>

		<div class="author">
			<h2 class="text-center">Note</h2>
			<p>Hi, my name is Tania Rascia. I've turned down every offer for advertisements or sponsored posts on this website. I write free resources that have helped thousands of people successfully transition into a web development career.</p>
			<p>My committment is to no bullshit, no sponsored posts, no ads, and no paywalls. If you enjoy my content, please consider supporting what I do.</p>
			<p class="text-center"><a class="button yellow-button" href="https://ko-fi.com/taniarascia" target="_blank">Support my work <i class="fas fa-coffee"></i></a></p>
		</div>
			<h2>Newsletter</h2>
			<p>New articles on web development every two weeks.</p>
			<form id="newsletter-form" class="newsletter-form" action="https://newsletter.taniarascia.com/sendy/subscribe" method="POST" accept-charset="utf-8" target="_blank">
				<input type="email" name="email" required id="newsletter-email" class="email" placeholder="Email address" pattern="[a-zA-Z0-9._\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,4}">
				<input type="hidden" name="list" value="P2bfC2WL3TvnTWEmucMbbg">
				<input type="submit" name="submit" id="newsletter-submit" value="Submit">
			</form>
    </div>
</article>