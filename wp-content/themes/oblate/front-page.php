<?php get_header(); ?>

	<main class="home-content">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<section class="banner">
				<div class="small-container">
					<div class="opener">
						<h1>Tania Rascia</h1>
						<a class="github-button" href="https://github.com/taniarascia" data-count-href="/taniarascia/followers" data-count-api="/users/taniarascia#followers" data-count-aria-label="# followers on GitHub" aria-label="Follow @taniarascia on GitHub">taniarascia</a>
						<a class="twitter-follow-button" href="https://twitter.com/taniarascia" data-show-screen-name="false">Follow @taniarascia</a>
						<div class="the-content">
							<?php the_content(); ?>
						</div>
					</div>
				</div> 
			</section>

			<?php endwhile; endif; ?>

				<section class="content">
					<div class="small-container">
						<div class="opener">
							<h2 class="large-heading">Latest Articles</h2>
							<p>My newest thoughts, guides, and resources.</p>
							<a class="button" href="<?php echo site_url(); ?>/blog">View All</a>
						</div>
					</div>
				</section>

				<section>
					<div class="large-container">
						<div class="latest-articles text-center">
							<?php 
$args = array(
'posts_per_page' => 3,
);  
$frontPage = new WP_Query( $args ); if ($frontPage->have_posts()) : while ($frontPage->have_posts()) : $frontPage->the_post();
$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'thumbnail' );
$url = $thumb['0']; ?>

								<a href="<?php the_permalink(); ?>" class="article-link">
          <article id="post-<?php the_ID(); ?>" class="article-excerpt">
            <div>
              <h1><?php the_title(); ?></h1>
                <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time>
                <?php the_excerpt(); ?>
            </div>
            <div class="article-image">
            <?php if ( get_post_thumbnail_id() ) { ?> <img src="<?php echo $url; ?>" alt="<?php the_title(); ?>"><?php } ?>
            </div>
          </article>
        </a>
								<?php endwhile; endif; ?>
						</div>
					</div>
				</section>

				<section class="content">
					<div class="small-container">
						<div class="opener">
							<h2 class="large-heading">Tutorials</h2>
							<p>The missing instruction manuals for popular web services.</p>
							<a class="button" href="<?php echo site_url(); ?>/blog">View All</a>
						</div>
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
						<a href="<?php echo site_url(); ?>/local-environment/" id="apache-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/apache.png" class="responsive-image" alt="Apache">
          <h3>Apache</h3>
        </a>
						<a href="<?php echo site_url(); ?>/getting-started-with-grunt-and-sass/" id="grunt-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/grunt-150x150.png" class="responsive-image" alt="Grunt">
          <h3>Grunt</h3>
        </a>
					</div>
				</section>

				<section class="javascript content">
					<div class="small-container">
						<h2 class="large-heading">JavaScript</h2>
						<p>Introductory lessons on JavaScript - Day by Day.</p>
						<a class="button" href="<?php echo site_url(); ?>/javascript-day-one">Start Here</a>
					</div>
					<img src="<?php echo site_url(); ?>/wp-content/uploads/jstut.png" alt="JavaScript">
				</section>

	</main>

	<?php get_footer(); ?>