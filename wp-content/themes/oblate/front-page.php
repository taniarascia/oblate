<?php get_header(); ?>

	<section class="content">
		<section class="page-body">
			<section class="article-preview-section">
				<h2>Latest Tutorials</h2>
				<p>My most recent articles, tutorials, and resources.</p>
		
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

			</section>
			<section class="article-preview-section">
				<h2>Most Popular</h2>
				<p>The biggest crowd pleasers.</p>

			
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

			</section>

			<section class="article-preview-section">
				<h2>My Thoughts</h2>
				<p>My life, observations, miscellaneous.</p>
			

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

			</section>

			<section class="article-preview-section">
				<h2>Quick Start</h2>
				<p>Links to beginner articles about popular development subjects.</p>
				<article class="quick-grid">
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
			</section>
		</section>
	</section>

<?php get_footer();