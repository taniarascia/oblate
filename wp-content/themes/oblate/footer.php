<?php if ( !is_page( 'resume' ) ) { ?>

<footer class="footer">
 <div class="container flex-container">
	 <div class="footer-social">
     <h3>Tania Rascia</h3>
     <p>
       <a href="<?php echo site_url(); ?>/me">About me</a>&nbsp;|&nbsp; 
       <a href="<?php echo site_url(); ?>/blog">Archive</a>&nbsp;|&nbsp;
       <a href="https://twitter.com/taniarascia">Twitter</a>&nbsp;|&nbsp;
       <a href="<?php echo site_url(); ?>/contact">Contact</a>
      </p>
    <p>I'm <a href="<?php echo site_url(); ?>/me">Tania</a>, a <strong>designer, developer, writer, and former chef</strong> from Chicago. I currently work full-time as a web developer, and sometimes I write for <a href="https://www.digitalocean.com/community/tutorial_series/how-to-code-in-javascript">DigitalOcean</a> and SitePoint. I love to create things for the web.</p>
    <p>Like what you see on this site? Help me keep it ad-free and wonderful. <a href="https://www.patreon.com/taniarascia" target="_blank">Be a patron</a> or <a href="https://paypal.me/taniarascia/5">buy me a coffee.</a></p>
		
	 </div>
	 <div class="footer-support">
   <h3>Email List</h3>
   <p>No bullshit, no ads, no sponsored posts, just
    <strong>quality content</strong> from yours truly.
    </p>
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
	</div>
</footer>
<?php } ?>
</main>

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