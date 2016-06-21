<?php get_header(); ?>

  <main class="home-content">

    <section class="banner text-center">
    </section>

    <section class="tutorials">
      <div class="content small-container">
        <h2>Tutorials</h2>
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


  </main>

  <?php get_footer(); ?>