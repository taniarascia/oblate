<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="header gradient">
	<div class="container">
		<div class="header-main">
			<h1>Tania Rascia</h1>
			<p class="lead">Hi, I'm Tania! I'm a <strong>developer, designer, writer, and former chef</strong>. I document everything I learn and package it into easy-to-follow tutorials.</p>
			<p>I love creating open-source projects from scratch. <a href="https://taniarascia.github.io/primitive/" target="_blank">Primitive</a> is my Sass/CSS framework, <a href="https://github.com/taniarascia/laconia" target="_blank">Laconia</a> is my PHP MVC framework, and <a href="http://taniarascia.github.io/new-moon/" target="_blank">New Moon</a> is my code syntax theme, available on VSCode, Atom, Sublime, Brackets, and Chrome DevTools. I also occasionally write for <a href="https://www.digitalocean.com/community/tutorial_series/how-to-code-in-javascript">DigitalOcean</a>, <a href="https://www.sitepoint.com/beginners-guide-javascript-variables-and-datatypes/">SitePoint</a>, and more. Thanks for visiting!</p>
			 <p class="front-page-social text-center">
				<a class="sh" href="<?php echo site_url(); ?>/me/"><i class="fa fa-heart"></i> About me</a> 
				<a class="sh" href="https://github.com/taniarascia" target="_blank"><i class="fa fa-github"></i> GitHub</a> 
				<a class="sh" href="https://twitter.com/taniarascia" target="_blank"><i class="fa fa-twitter"></i> Twitter</a> 
			</p>
		</div>
	</div>
	
</header>

<?php endwhile; endif; ?>

<section>
	<div class="small-container">
		<div class="author">
			<h2 class="text-center">Quality and morality on the web</h2>
			<p>I have turned down every advertiser, sponsored post, and affiliate who has come to me. <strong>I give away all my knowledge for free to <mark>300,000+ monthly readers</mark></strong> and helped thousands of people learn and successfully transition into a design/development career.</p>

			<p><strong>You know what I stand for, and you know my commitment to you - no bullshit, no sponsored posts, no pop-ups, no blocked content, ads, schemes, tactics, or gimmicks. Just free, quality content. <mark>Support me by buying me a coffee</mark></strong>.</p>
			
			<div class="author-options">
				<div>
					<h3>I really appreciate your support!</h3>
					<p><a class="button paypal-button" href="https://ko-fi.com/taniarascia" target="_blank">Buy me a coffee</a> <a class="button paypal-button" href="https://patreon.com/taniarascia" target="_blank">Be a patron</a></p>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="quote-container">
	<div class="small-container">
		<blockquote class="testimonial">"Tania writes extremely clear, concise tutorials that have the best ratio of learning to bullshit that I've encountered
			so far. It's no exaggeration to say that I wouldn't currently have a job in development without this site. So thanks for
			ruining my life, Tania."
			<cite>&mdash; <a href="http://craiglam.com/" target="_blank">Craig Lam</a>, Web Developer</cite></blockquote>

			<blockquote class="testimonial">"I look forward to anything and everything you put out. You’re like an online programming coach!"
			<cite>&mdash; <a href="https://twitter.com/jondelbruggemma" target="_blank">Jon Delbrugge</a>, Mixed Martial Arts Fighter</cite></blockquote> 
	</div>
</section>

<section class="top">
	<div class="container">
		<div class="top-title">
			<h2>Latest Tutorials</h2>
			<p>My most recent articles, tutorials, and resources.</p>
			<p><a href="<?php echo site_url(); ?>/tutorials" class="button">View all</a></p>
		</div>

		<div class="grid">
			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '6',
				'category_name' => 'Tutorials',
				'category__not_in' => array( 5 ),
			);

			$latest = new WP_Query( $args );

			if ( $latest->have_posts() ) :  while ( $latest->have_posts() ) : $latest->the_post(); 

				 get_template_part( 'content', get_post_format() );

			endwhile; endif; wp_reset_postdata(); ?>

		</div>

		<div class="top-title">
			<h2>Most Popular</h2>
			<p>The biggest crowd pleasers.</p>
		</div>

		<div class="grid">
			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '6',
				'category_name' => 'Popular',
				'category__not_in' => array( 5 ),
			);

			$popular = new WP_Query( $args );

			if ( $popular->have_posts() ) :  while ( $popular->have_posts() ) : $popular->the_post(); 

				 get_template_part( 'content', get_post_format() );

			endwhile; endif; wp_reset_postdata(); ?>

		</div>


		<div class="top-title">
			<h2>My Thoughts</h2>
			<p>My life, observations, miscellaneous.</p>
		</div>

		<div class="grid">

			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '6',
				'category_name' => 'Thoughts',
				'category__not_in' => array( 5 ),
			);

			$thoughts = new WP_Query( $args );

			if ( $thoughts->have_posts() ) :  while ( $thoughts->have_posts() ) : $thoughts->the_post(); 

				 get_template_part( 'content', get_post_format() );

			endwhile; endif; wp_reset_postdata(); ?>

		</div>
</section>

<section class="choose-your-destiny text-center">
	<div class="container">
		<h2>Beginner Resources</h2>
		<p>Click on a topic to go directly to an awesome introductory resource.</p>
		<article class="quick quick-header">
			<a href="<?php echo site_url(); ?>/es6-syntax-and-feature-overview/"><img src="<?php echo site_url(); ?>/wp-content/uploads/js-150x150.png"> JavaScript/ES6</a>
			<a href="<?php echo site_url(); ?>/developing-a-wordpress-theme-from-scratch/"><img src="<?php echo site_url(); ?>/wp-content/uploads/wp-2-150x150.png"> WordPress</a>
			<a href="<?php echo site_url(); ?>/what-is-bootstrap-and-how-do-i-use-it/"><img src="<?php echo site_url(); ?>/wp-content/uploads/bs-logo-150x150.png"> Bootstrap</a>
			<a href="<?php echo site_url(); ?>/how-to-use-jquery-a-javascript-library/"><img src="<?php echo site_url(); ?>/wp-content/uploads/jq1-150x150.png"> jQuery</a>
			<a href="<?php echo site_url(); ?>/learn-sass-now/"><img src="<?php echo site_url(); ?>/wp-content/uploads/sass-150x150.png"> Sass</a>
			<a href="<?php echo site_url(); ?>/getting-started-with-gulp/"><img src="<?php echo site_url(); ?>/wp-content/uploads/gulp-150x150.png"> Gulp</a>
			<a href="<?php echo site_url(); ?>/getting-started-with-git/"><img src="<?php echo site_url(); ?>/wp-content/uploads/git-150x150.png"> Git</a>
			<a href="<?php echo site_url(); ?>/how-to-use-json-data-with-php-or-javascript/"><img src="<?php echo site_url(); ?>/wp-content/uploads/jsonimg-150x150.png"> JSON</a>
			<a href="<?php echo site_url(); ?>/how-to-use-the-command-line-for-apple-macos-and-linux/"><img src="<?php echo site_url(); ?>/wp-content/uploads/terminal-150x150.png"> Command line</a>
			<a href="<?php echo site_url(); ?>/create-a-simple-database-app-connecting-to-mysql-with-php/"><img src="<?php echo site_url(); ?>/wp-content/uploads/php-150x150.png"> PHP &amp; MySQL</a>
			<a href="<?php echo site_url(); ?>/how-to-install-and-use-node-js-and-npm-mac-and-windows/"><img src="<?php echo site_url(); ?>/wp-content/uploads/nodejs-150x150.png"> Node.js</a>
			<a href="<?php echo site_url(); ?>/design-for-developers/"><img src="<?php echo site_url(); ?>/wp-content/uploads/coloricon-150x150.png"> Design</a>
		</article>
	</div>
</section>

<section class="quote-container">
	<div class="small-container">
		<blockquote class="testimonial">"Hi Tania, I just wanted to say that your tutorials are by far the best I've seen. Well written and well explained. Thank
			you for all your effort."
			<cite>&mdash; <a href="https://github.com/jpggvilaca" target="_blank">João Vilaça</a>, Software Engineer</cite></blockquote>
	</div>
</section>

<?php get_footer();