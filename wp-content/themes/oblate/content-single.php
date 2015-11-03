<article id="post-<?php the_ID(); ?>" class="single-article">
	<div class="article-header center">
		<h2><?php the_title();?></h2>
		<time>
			<?php the_time('F j, Y');?>
		</time>
		<?php the_tags('&nbsp;☍&nbsp;<span class="tags">', ', ', '', '</span>'); ?>
	</div>
	<?php the_content(); ?>
		<div class="social-bar">
			<a href="https://twitter.com/share" class="twitter-share-button" data-via="taniarascia">Tweet</a>
			<script>
				! function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0],
						p = /^http:/.test(d.location) ? 'http' : 'https';
					if (!d.getElementById(id)) {
						js = d.createElement(s);
						js.id = id;
						js.src = p + '://platform.twitter.com/widgets.js';
						fjs.parentNode.insertBefore(js, fjs);
					}
				}(document, 'script', 'twitter-wjs');

			</script>
			<div class="fb-share-button" data-href="<?php echo get_permalink();?>" data-layout="button_count"></div>
		</div>
</article>
