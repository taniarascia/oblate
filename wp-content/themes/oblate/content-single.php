<article class="article" id="post-<?php the_ID(); ?>">
	<section class="single-body">
		<header class="single-header">
			<div class="single-header-flex">
				<div class="header-thumbnail">
					<?php if ( get_post_thumbnail_id() ) { ?>
					<img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="150" width="150">
					<?php } ?>
				</div>
				<div class="header-title">
					<h1><?php the_title(); ?></h1>
					<div class="article-data">
						<a href="<?php echo site_url(); ?>/me">Tania Rascia</a> &nbsp;/&nbsp;
						<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
						<time datetime="<?php the_time( 'Y-m-d' ); ?>"> &nbsp;/&nbsp;
							<?php the_time( 'F j, Y' ); ?></time>
					</div>	
				</div>
			</div>
		</header>

		<div class="excerpt"><?php the_excerpt(); ?></div>
		<?php the_content(); ?>
		<div class="author">
			<h2 class="text-center">Quality and morality on the web</h2>
			<p>Hi, my name is <strong>Tania Rascia</strong>. I've turned down every advertiser, sponsored post, and affiliate who has come to me. <strong>I give away all my knowledge for free to <mark>300,000+ monthly readers</mark></strong> and helped thousands of people learn and successfully transition into a design/development career.</p>

			<p>You know what I stand for, and you know my commitment to you - <strong>no bullshit, no sponsored posts, no pop ups, no blocked content, ads, schemes, tactics, or gimmicks. Just free, quality content.  <mark>If you enjoy my content, please consider supporting what I do!</mark></strong></p>
			
			<div class="author-options">
				<div>
					<p><a class="button yellow-button" href="https://ko-fi.com/taniarascia" target="_blank"><i class="fas fa-coffee"></i> Buy me a coffee</a></p>
				</div>
			</div>
		</div>
	</section>
</article>