<?php get_header(); ?>

  <main class="main-content">

      <div class="small-container">

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

						get_template_part( 'content-page', get_post_format() );

						endwhile; endif; ?>

      </div>

  </main>

  <?php get_footer(); ?>
