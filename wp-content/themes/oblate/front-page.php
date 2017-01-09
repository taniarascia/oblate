<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section class="banner">
	<div class="container">
		<h1>Tania Rascia</h1>
		<div class="small-container">
		<a href="https://github.com/taniarascia" target="_blank" class="gh-button"><i class="fa fa-github" aria-hidden="true"></i> GitHub</a> <a href="https://twitter.com/taniarascia" target="_blank" class="tw-button"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</a>
		</div>
		<div class="the-content">
			<?php the_content(); ?>
		</div>
	</div>
</section>

<?php endwhile; endif; ?>

<?php $args = array(
	'posts_per_page' => '3',
 );

$latest = new WP_Query( $args );

if ( $latest->have_posts() ) : ?>

	<section class="content latest-articles">
		<div class="container">
			<h1>Latest Articles</h1>
			<p>My most recent thoughts, guides, and tutorials.</p>
			<p><a href="<?php echo site_url(); ?>/blog" class="button">View all</a></p>

			<?php while ( $latest->have_posts() ) : $latest->the_post(); 

				get_template_part( 'content', get_post_format() );

			endwhile; ?>

		</div>
	</section>

<?php endif; wp_reset_postdata(); ?>

	<section class="content">
		<div class="container">
			<h1>Tutorials</h1>
			<p>The missing instruction manuals for popular web services.</p>
		</div>
	</section>

<section class="tutorials">
<div class="tutorials-grid">
<a href="<?php echo site_url(); ?>/developing-a-wordpress-theme-from-scratch/" id="wordpress-tutorial" class="tutorials-cell">
<img src="<?php echo site_url(); ?>/wp-content/uploads/wptut.png" class="responsive-image" alt="WordPress">
<h3>WordPress</h3>
</a>
<a href="<?php echo site_url(); ?>/what-is-bootstrap-and-how-do-i-use-it/" id="bootstrap-tutorial" class="tutorials-cell">
<img src="<?php echo site_url(); ?>/wp-content/uploads/bootstrap.png" class="responsive-image" alt="Bootstrap">
<h3>Bootstrap</h3>
</a>
<a href="<?php echo site_url(); ?>/learn-sass-now/" id="sass-tutorial" class="tutorials-cell">
<img src="<?php echo site_url(); ?>/wp-content/uploads/sass-150x150.png" class="responsive-image" alt="Sass">
<h3>Sass</h3>
</a>
<a href="<?php echo site_url(); ?>/getting-started-with-gulp/" id="gulp-tutorial" class="tutorials-cell">
<img src="<?php echo site_url(); ?>/wp-content/uploads/gulptut.png" class="responsive-image" alt="Gulp">
<h3>Gulp</h3>
</a>
<a href="<?php echo site_url(); ?>/make-a-static-website-with-jekyll/" id="jekyll-tutorial" class="tutorials-cell">
<img src="<?php echo site_url(); ?>/wp-content/uploads/jekylltut.png" class="responsive-image" alt="Jekyll">
<h3>Jekyll</h3>
</a>
<a href="<?php echo site_url(); ?>/getting-started-with-git/" id="git-tutorial" class="tutorials-cell">
<img src="<?php echo site_url(); ?>/wp-content/uploads/gittut.png" class="responsive-image" alt="Git">
<h3>Git</h3>
</a>
<a href="<?php echo site_url(); ?>/getting-started-with-aws-setting-up-a-virtual-server/" id="aws-tutorial" class="tutorials-cell">
<img src="<?php echo site_url(); ?>/wp-content/uploads/awstut.png" class="responsive-image" alt="AWS">
<h3>Amazon Web Services</h3>
</a>
<a href="<?php echo site_url(); ?>/local-environment/" id="lamp-tutorial" class="tutorials-cell">
<img src="<?php echo site_url(); ?>/wp-content/uploads/mamppro.png" class="responsive-image" alt="Apache">
<h3>LAMP Stack</h3>
</a>
<a href="<?php echo site_url(); ?>/getting-started-with-grunt-and-sass/" id="grunt-tutorial" class="tutorials-cell">
<img src="<?php echo site_url(); ?>/wp-content/uploads/grunt-150x150.png" class="responsive-image" alt="Grunt">
<h3>Grunt</h3>
</a>
</div>
</section>

<?php get_footer(); ?>