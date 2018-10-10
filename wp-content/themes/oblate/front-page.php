<?php get_header(); ?>

<section class="content">
	<section class="page-body">
		<h3>Hey, I'm Tania. I'm a Front End Software Engineer, designer, and former chef. I write practical, concise, free resources that have resonated with thousands of self-taught developers around the globe.</h3> 
		<p>I wrote the <a href="<?php site_url(); ?>/getting-started-with-react">Beginner's Guide to React</a>, the definitive guide to <a href="<?php site_url(); ?>/developing-a-wordpress-theme-from-scratch">Developing a WordPress Theme from Scratch</a>, DigitalOcean's tutorial series on <a href="https://www.digitalocean.com/community/tutorial_series/how-to-code-in-javascript">How to Code in JavaScript</a> and <a href="https://www.digitalocean.com/community/tutorial_series/understanding-the-dom-document-object-model">the DOM</a>, and many more. 
	</p>
	<p>I also build open source projects - an <a href="https://github.com/taniarascia/laconia" target="_blank">MVC framework in PHP</a>, a <a href="https://github.com/taniarascia/primitive" target="_blank">minimalist CSS framework in Sass</a>, and <a href="https://taniarascia.github.io/new-moon/" target="_blank">the undisputed world's best code theme</a>.</p>
	<p>I do this in my spare time for free, for you, because I want to, and because I believe I can inspire others to create beautiful corners of the internet as well.</p>

		<section class="article-preview-section">
			<h2>Latest Posts</h2>
			<p>My most recent articles, tutorials, and resources.</p>
	
			<?php $args = array(
				'order'          => 'desc',
				'orderby'        => 'publish_date',
				'posts_per_page' => '9',
			);

			$latest = new WP_Query( $args );

			if ( $latest->have_posts() ) :  while ( $latest->have_posts() ) : $latest->the_post(); 
				get_template_part( 'content', get_post_format() );
			endwhile; endif; wp_reset_postdata(); ?>

		</section>
		<section class="article-preview-section">
			<h2>Most Popular</h2>
			<p>The missing instruction manuals of the web.</p>
		
			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '9',
				'category_name' => 'Popular',
			);

			$popular = new WP_Query( $args );

			if ( $popular->have_posts() ) :  while ( $popular->have_posts() ) : $popular->the_post(); 
				get_template_part( 'content', get_post_format() );
			endwhile; endif; wp_reset_postdata(); ?>

		</section>

		<section class="article-preview-section">
			<h2>Quick Start</h2>
			<p>Links to beginner articles about popular development subjects.</p>
			<article class="quick-grid">
				<a href="<?php echo site_url(); ?>/getting-started-with-react/"><img src="<?php echo site_url(); ?>/wp-content/uploads/react-logo-150x150.png"> React</a>
				<a href="<?php echo site_url(); ?>/es6-syntax-and-feature-overview/"><img src="<?php echo site_url(); ?>/wp-content/uploads/js-150x150.png"> JavaScript/ES6</a>
				<a href="<?php echo site_url(); ?>/developing-a-wordpress-theme-from-scratch/"><img src="<?php echo site_url(); ?>/wp-content/uploads/wp-2-150x150.png"> WordPress</a>
				<a href="<?php echo site_url(); ?>/what-is-bootstrap-and-how-do-i-use-it/"><img src="<?php echo site_url(); ?>/wp-content/uploads/bs-logo-150x150.png"> Bootstrap</a>
				<a href="<?php echo site_url(); ?>/how-to-use-jquery-a-javascript-library/"><img src="<?php echo site_url(); ?>/wp-content/uploads/jq1-150x150.png"> jQuery</a>
				<a href="<?php echo site_url(); ?>/learn-sass-now/"><img src="<?php echo site_url(); ?>/wp-content/uploads/sass-150x150.png"> Sass</a>
				<a href="<?php echo site_url(); ?>/getting-started-with-git/"><img src="<?php echo site_url(); ?>/wp-content/uploads/git-150x150.png"> Git</a>
				<a href="<?php echo site_url(); ?>/how-to-use-json-data-with-php-or-javascript/"><img src="<?php echo site_url(); ?>/wp-content/uploads/jsonimg-150x150.png"> JSON</a>
				<a href="<?php echo site_url(); ?>/how-to-use-the-command-line-for-apple-macos-and-linux/"><img src="<?php echo site_url(); ?>/wp-content/uploads/terminal-150x150.png"> Command line</a>
				<a href="<?php echo site_url(); ?>/create-a-simple-database-app-connecting-to-mysql-with-php/"><img src="<?php echo site_url(); ?>/wp-content/uploads/php-150x150.png"> PHP &amp; MySQL</a>
				<a href="<?php echo site_url(); ?>/how-to-install-and-use-node-js-and-npm-mac-and-windows/"><img src="<?php echo site_url(); ?>/wp-content/uploads/nodejs-150x150.png"> Node.js</a>
				<a href="<?php echo site_url(); ?>/design-for-developers/"><img src="<?php echo site_url(); ?>/wp-content/uploads/coloricon-150x150.png"> Design</a>
			</article>
		</section>
	</section>
</section>

<?php get_footer();