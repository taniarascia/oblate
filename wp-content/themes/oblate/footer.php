<section class="newsletter">
	<div class="small-container text-center">
		<h2>Tania's List</h2>
		<p>My tutorials, guides, and articles for <strong>web designers, developers, and autodidacts</strong>, sent out once a month or so. No bullshit, ads, or tricks.</p>
		<div id="mc_embed_signup">
			<form action="//taniarascia.us12.list-manage.com/subscribe/post?u=ec794fab6e35a491a001cc25d&amp;id=5276386071" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<label for="mce-EMAIL"><span class="screen-reader-text">Email</span></label><input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Email address">
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

<footer class="footer">
 <div class="container flex-container">
	 <div class="footer-social">
		 <p>I'm <strong>Tania Rascia</strong>, a web designer/developer, autodidact, tech writer and problem solver. I love hiking, karaoke, recording music, and building communities. <strong><a href="<?php echo site_url(); ?>/contact">Say hello</a></strong>! </p>
	  <div class="social-icons">
     <a href="https://www.taniarascia.com/feed"><i class="fa fa-rss fa-2x"></i></a>
     <a href="https://twitter.com/taniarascia" target="_blank"><i class="fa fa-twitter fa-2x"></i></a>
     <a href="https://github.com/taniarascia" target="_blank"><i class="fa fa-github fa-2x"></i></a>
     <a href="https://stackoverflow.com/users/4541434/tania-rascia" target="_blank"><i class="fa fa-stack-overflow fa-2x"></i></a>
     <a href="https://www.linkedin.com/in/taniarascia/" target="_blank"><i class="fa fa-linkedin-square fa-2x" aria-hidden="true"></i></a>
    </div>
	 </div>
	 <div class="footer-support">
		 <p>My site is <strong>free</strong> – and free of ads, clickbait, popups, guest posts, sponsorships, and bullshit.</p>
		 	<p><strong>Please consider <a href="https://www.patreon.com/taniarascia" target="_blank">becoming a patron</a></strong> if my work has been valuable to you.</p>
	 </div>
	</div>
</footer>
	
</main>

<?php if ( is_front_page() ) { ?>

<script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);

  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };

  return t;
}(document, "script", "twitter-wjs"));</script>

<?php } ?>
<?php wp_footer(); ?>
<?php if ( is_front_page() ) { ?>
<script type="application/ld+json">
{ 
  "@context": "http://schema.org", 
  "@type": "WebSite", 
  "url": "<?php echo site_url(); ?>", 
  "name": "<?php echo get_bloginfo( 'name'); ?> - <?php echo get_bloginfo( 'description' ); ?>",
   "author": {
      "@type": "Person",
      "name": "Tania Rascia"
    },
  "description": "Web designer, developer, autodidact. Tania breaks down complex concepts in a clear, simple way for all skill levels.",
  "potentialAction": { 
    "@type": "SearchAction", 
    "target": "<?php echo site_url(); ?>/?s={search_term}", 
    "query-input": "required name=search_term" } 
    }
</script>
<?php } ?>
<?php if ( is_page( 'me' ) ) { ?>
<script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Person",
      "image": "<?php echo site_url(); ?>/wp-content/uploads/taniasmall.jpg",
      "jobTitle": "Web developer",
      "name": "Tania Rascia",
      "gender": "female",
      "url": "<?php echo site_url(); ?>",
	    "sameAs" : [ 
      "https://github.com/taniarascia",
      "https://twitter.com/taniarascia" 
			]
    }
    </script>
<?php } ?>
	
<?php if ( is_singular() && !is_singular( 'work' ) && !is_front_page() ) { ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<script type="application/ld+json">
{ "@context": "http://schema.org", 
 "@type": "TechArticle",
 "headline": "<?php the_title(); ?>",
 "proficiencyLevel": "Beginner/intermediate",
 "image": "<?php echo the_post_thumbnail_url(); ?>",
   "author": {
      "@type": "Person",
      "name": "Tania Rascia"
    },
 "genre": "<?php foreach((get_the_category()) as $category) { echo $category->cat_name . ', '; } ?>", 
 "keywords": "<?php
  $posttags = get_the_tags();
  if ($posttags) {
    foreach($posttags as $tag) {
      echo $tag->name . ', '; 
    }
  }
?>", 
 "url": "<?php the_permalink(); ?>",
 "datePublished": "<?php the_time( 'Y-m-d' ); ?>",
 "description": "<?php echo esc_html(get_the_excerpt()); ?>"
 }
</script>	
<?php endwhile; endif; ?>
<?php } ?>
</body>

</html>