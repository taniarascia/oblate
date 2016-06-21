<?php get_header(); ?>

  <main class="home-content">

   <!-- <section class="banner text-center">
    </section>-->

    <section class="tutorials">
      <div class="content small-container">
        <h2 class="large-heading">Tutorials</h2>
        <p>The missing instruction manuals for popular web services.</p>
      </div>

      <div class="tutorials-grid">
        <a href="<?php echo site_url(); ?>/developing-a-wordpress-theme-from-scratch/" id="wordpress-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/wptut.png" style="responsive-image">
          <h3>WordPress</h3>
        </a>
        <a href="<?php echo site_url(); ?>/what-is-bootstrap-and-how-do-i-use-it/" id="bootstrap-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/bootstrap.png" style="responsive-image">
          <h3>Bootstrap</h3>
        </a>
        <a href="<?php echo site_url(); ?>/learn-sass-now/" id="sass-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/sass-150x150.png" style="responsive-image">
          <h3>Sass</h3>
        </a>
        <a href="<?php echo site_url(); ?>/getting-started-with-gulp/" id="gulp-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/gulptut.png" style="responsive-image">
          <h3>Gulp</h3>
        </a>
        <a href="<?php echo site_url(); ?>/make-a-static-website-with-jekyll/" id="jekyll-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/jekylltut.png" style="responsive-image">
          <h3>Jekyll</h3>
        </a>
        <a href="<?php echo site_url(); ?>/getting-started-with-git/" id="git-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/gittut.png" style="responsive-image">
          <h3>Git</h3>
        </a>
      </div>
    </section>

    <section class="content small-container">
      <h2 class="large-heading">JavaScript</h2>
      <p>Introductory lessons to JavaScript - Day by Day.</p>
    </section>

    <a href="<?php echo site_url(); ?>/javascript-day-one">
      <section class="javascript">
        <img src="<?php echo site_url(); ?>/wp-content/uploads/js-300x300.png">
      </section>
    </a>

    <section class="content small-container">
      <h2 class="large-heading">Latest Articles</h2>
      <div class="latest-articles">
        <?php 
$args = array(
'posts_per_page' => 2,
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
      <div class="text-center">
        <a class="button" href="<?php echo site_url(); ?>/blog">View All</a>
      </div>
    </section>

  </main>

  <?php get_footer(); ?>