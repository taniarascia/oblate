<?php get_header(); ?>

  <main class="main-content">
    <section class="single">
      <div class="small-container">
       
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

					get_template_part( 'content-single', get_post_format() );
		
					if ( comments_open() || get_comments_number() ) :
			
					comments_template();
			
					endif;?>

          <?php	endwhile; endif; ?>
      </div>
    </section>
  </main>

<?php get_footer(); ?>