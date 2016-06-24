<?php get_header(); ?>

  <main class="home-content">

    <section class="banner">
      <div class="small-container">
        <h1>Tania Rascia</h1>
        <h2>Front End Web Developer</h2>
        <p class="opener">I design and develop websites in Chicago. I also write high quality guides and tutorials. No ads. No sponsored posts. No fluff. Let's just learn and create.</p>
        <a href="https://twitter.com/taniarascia" class="social-icon"><i class="fa fa-twitter fa-3x"></i></a>
        <a href="https://github.com/taniarascia" class="social-icon"><i class="fa fa-github fa-3x"></i></a>
        <a href="http://codepen.com/taniarascia" class="social-icon"><i class="fa fa-codepen fa-3x"></i></a>
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
            <div class="article-header">
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
        <h2 class="large-heading">Tutorials</h2>
        <p>The missing instruction manuals for popular web services.</p>
        <a class="button" href="<?php echo site_url(); ?>/blog">View All</a>
      </div>
    </section>

    <section class="tutorials">
      <div class="tutorials-grid">
        <a href="<?php echo site_url(); ?>/developing-a-wordpress-theme-from-scratch/" id="wordpress-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/wptut.png" class="responsive-image">
          <h3>WordPress</h3>
        </a>
        <a href="<?php echo site_url(); ?>/what-is-bootstrap-and-how-do-i-use-it/" id="bootstrap-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/bootstrap.png" class="responsive-image">
          <h3>Bootstrap</h3>
        </a>
        <a href="<?php echo site_url(); ?>/learn-sass-now/" id="sass-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/sass-150x150.png" class="responsive-image">
          <h3>Sass</h3>
        </a>
        <a href="<?php echo site_url(); ?>/getting-started-with-gulp/" id="gulp-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/gulptut.png" class="responsive-image">
          <h3>Gulp</h3>
        </a>
        <a href="<?php echo site_url(); ?>/make-a-static-website-with-jekyll/" id="jekyll-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/jekylltut.png" class="responsive-image">
          <h3>Jekyll</h3>
        </a>
        <a href="<?php echo site_url(); ?>/getting-started-with-git/" id="git-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/gittut.png" class="responsive-image">
          <h3>Git</h3>
        </a>
        <a href="<?php echo site_url(); ?>/getting-started-with-aws-setting-up-a-virtual-server/" id="aws-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/awstut.png" class="responsive-image">
          <h3>Amazon Web Services</h3>
        </a>
        <a href="<?php echo site_url(); ?>/local-environment/" id="apache-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/apache.png" class="responsive-image">
          <h3>Apache</h3>
        </a>
        <a href="<?php echo site_url(); ?>/getting-started-with-grunt-and-sass/" id="grunt-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/grunt-150x150.png" class="responsive-image">
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
      <img src="<?php echo site_url(); ?>/wp-content/uploads/jstut.png">
    </section>

  </main>

  <?php get_footer(); ?>
