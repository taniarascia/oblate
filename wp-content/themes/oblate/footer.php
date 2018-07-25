            </section>
            <?php get_sidebar(); ?>
        </div>
    </div>

    </main>

    <footer class="site-footer-top">
        <div class="container">
            <div class="grid-four">
                <div>
                    <nav class="site-footer-brand">
                        <a href="<?php echo site_url(); ?>">
                            <img src="<?php echo site_url(); ?>/wp-content/uploads/floppy.png" class="brand-icon">
                            <span>Tania Rascia</span>
                        </a>
                    </nav>
                    <p class="intro">I'm Tania, a <strong>designer and developer</strong>. I write the missing instruction manuals of the web.</p>
                    <p class="footer-social-links">
                        <a href="https://github.com/taniarascia" target="_blank"><i class="fab fa-2x fa-github-alt" aria-hidden="true"></i></a>
                        <a href="https://twitter.com/taniarascia" target="_blank"><i class="fab fa-2x fa-twitter" aria-hidden="true"></i></a>
                        <a href="https://linkedin.com/in/taniarascia" target="_blank"><i class="fab fa-2x fa-linkedin" aria-hidden="true"></i></a>
                        <a href="https://join.slack.com/t/httpchat/shared_invite/enQtNDAxODEwMTU2ODM0LTljMGRjZDZmZTA1ZDEwNjc5M2QwYjk5ZjViMGUzNDYzZjJhMGM2OTNmNTNkODM1OWYzZWIzYjA2NTU4YTczZWU" target="_blank"><i class="fab fa-2x fa-slack" aria-hidden="true"></i></a>
                        <a href="<?php echo site_url(); ?>/contact"><i class="far fa-2x fa-comments" aria-hidden="true"></i></a>
                        </p>
                    <p>
                        <h3>Email List</h3>
                        <p>Friendly updates straight to your inbox.</p>
                        <form id="newsletter-form" action="https://newsletter.taniarascia.com/sendy/subscribe" method="POST" accept-charset="utf-8" target="_blank">
                            <input type="email" name="email" required id="email" class="email" placeholder="Email" pattern="[a-zA-Z0-9._\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,4}">
                            <input type="hidden" name="list" value="P2bfC2WL3TvnTWEmucMbbg">
                            <input type="submit" name="submit" id="submit" value="Submit">
                        </form>
                    </p>
                </div>
                <div>
                    <h2>About</h2>
                    <nav class="footer-nav">
                        <a href="<?php echo site_url(); ?>/me">About me</a>
                        <a href="<?php echo site_url(); ?>/tutorials">Tutorials</a>
                        <a href="<?php echo site_url(); ?>/work">Work</a>
                        <a href="<?php echo site_url(); ?>/thoughts">Writing</a>
                        <a href="<?php echo site_url(); ?>/music" target="_blank">Music</a>
                        <a href="<?php echo site_url(); ?>/contact">Contact me</a>
                    </nav>
                </div>
                <div>
                    <h2>Most Popular</h2>
                    <nav class="footer-nav">
                        <a href="<?php echo site_url(); ?>/es6-syntax-and-feature-overview/">JavaScript ES6</a>
                        <a href="<?php echo site_url(); ?>/developing-a-wordpress-theme-from-scratch/">WordPress</a>
                        <a href="<?php echo site_url(); ?>/what-is-bootstrap-and-how-do-i-use-it/">Bootstrap</a>
                        <a href="<?php echo site_url(); ?>/how-to-use-jquery-a-javascript-library/">jQuery</a>
                        <a href="<?php echo site_url(); ?>/how-to-install-and-use-node-js-and-npm-mac-and-windows/">Node.js</a>
                        <a href="<?php echo site_url(); ?>/how-to-use-json-data-with-php-or-javascript/">JSON APIs</a>
                        <a href="<?php echo site_url(); ?>/how-to-use-the-command-line-for-apple-macos-and-linux/">Command Line</a>
                        <a href="<?php echo site_url(); ?>/learn-sass-now/">Sass</a>
                    </nav>
                </div>
                <div>
                    <h2>Projects</h2>
                    <nav class="footer-nav">
                        <div><a href="https://laconia.site" target="_blank">Laconia MVC</a> / <a href="https://github.com/taniarascia/laconia" target="_blank">Source</a></div>
                        <div><a href="https://taniarascia.github.io/primitive" target="_blank">Primitive CSS</a> / <a href="https://github.com/taniarascia/primitive" target="_blank">Source</a></div>
                        <div><a href="https://github.com/taniarascia/new-moon" target="_blank">New Moon Code Theme</a></div>
                    </nav>
                </div>
            </div>
        </div>
    </footer>

    <footer class="site-footer-bottom">
        <div class="container">
            <div class="site-footer-bottom-grid">
                <div>
                    <small>Made with
                        <i class="fas fa-heart"></i> and
                        <i class="fas fa-music"></i> in Chicago</small>
                </div>
                <div>
                    <a class="social gh" href="https://github.com/taniarascia" target="_blank">
                        <i class="fab fa-github-alt"></i>
                        </i>
                    </a>
                    <a class="social tw" href="https://twitter.com/taniarascia" target="_blank">
                        <i class="fab fa-twitter"></i>
                        </i>
                    </a>
                </div>
            </div>
        </div>
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
<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>