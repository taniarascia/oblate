<?php get_header(); ?>

  <main class="home-content">

    <section class="banner">

    </section>

    <section class="tutorials">

      <div class="small-container">

        <h2 class="text-center">Tutorials</h2>
        <p>As I like to call them, "The Lost Guides". </p>

      </div>

      <div class="tutorials-grid">
        <div id="wordpress-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/wptut.png" style="responsive-image">
        </div>
        <div id="bootstrap-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/bootstrap.png" style="responsive-image">
        </div>
        <div id="sass-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/sass-150x150.png" style="responsive-image">
        </div>
        <div id="gulp-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/gulptut.png" style="responsive-image">
        </div>
        <div id="jekyll-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/jekylltut.png" style="responsive-image">
        </div>
        <div id="git-tutorial" class="tutorials-cell">
          <img src="<?php echo site_url(); ?>/wp-content/uploads/gittut.png" style="responsive-image">
        </div>
      </div>

    </section>

  </main>

  <?php get_footer(); ?>