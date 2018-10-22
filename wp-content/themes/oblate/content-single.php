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
			<h2 class="text-center">A Note</h2>
			<p>Hi, my name is <strong>Tania Rascia</strong>. I've turned down every advertiser, sponsored post, and affiliate who has contacted me. <strong>I write free resources for <mark>300,000+ monthly readers</mark></strong> and helped thousands of successfully transition into a design/development career.</p>
			<p>You know what I stand for, and you know my commitment to you - <strong>no bullshit, no sponsored posts, no ads, and no paywalls.</p>
			<p class="text-center"><mark>If you enjoy my content, please consider supporting what I do!</mark></strong></p>
			<p class="text-center"><a class="button yellow-button" href="https://ko-fi.com/taniarascia" target="_blank"><i class="fas fa-coffee"></i> Buy me a coffee</a></p>
		</div>
    </div>
</article>