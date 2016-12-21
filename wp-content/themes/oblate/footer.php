</main>

<footer class="footer">
	<a href="<?php echo site_url(); ?>/feed" class="social-icon"><i class="fa fa-rss fa-2x"></i></a>
	<a href="https://twitter.com/taniarascia" class="social-icon"><i class="fa fa-twitter fa-2x"></i></a>
	<a href="https://github.com/taniarascia" class="social-icon"><i class="fa fa-github fa-2x"></i></a>
	<a href="https://codepen.com/taniarascia" class="social-icon"><i class="fa fa-codepen fa-2x"></i></a>
	<a href="https://stackoverflow.com/users/4541434/tania-rascia" class="social-icon"><i class="fa fa-stack-overflow fa-2x"></i></a>
</footer>

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
  "description": "I'm Tania, and I simplify web development through effective and concise tutorials. Let's learn and create.",
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