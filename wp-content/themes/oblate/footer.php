	<footer class="footer">
	<div class="container">
	<span>Tania Rascia is my name.</span>
		<nav>
			<a href="<?php echo site_url(); ?>/me">About</a>
			<a href="<?php echo site_url(); ?>/resume">Resume</a>
			<a href="<?php echo site_url(); ?>/blog">Blog</a>
			<a href="<?php echo site_url(); ?>/work">Portfolio</a>
			<a href="<?php echo site_url(); ?>/contact">Contact</a>
			<a href="https://github.com/taniarascia">GitHub</a>
		</nav>
	</div>
	</footer>
	
</main>

</section>

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
	
<?php if ( is_single() ) { ?>

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
 "description": "<?php echo esc_html(get_the_excerpt()); ?>",
 "articleBody": "<?php $content = get_the_content();  echo wp_filter_nohtml_kses( $content ); ?>"
 }
</script>	

<?php endwhile; endif; ?>
	
<?php } ?>
	
		
</body>

</html>