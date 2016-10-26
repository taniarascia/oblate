<?php get_header(); ?>

  <main class="main-content portfolio-page">

    <div class="small-container">

      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

						get_template_part( 'content-page', get_post_format() );

						endwhile; endif; ?>

    </div>

    <?php $args = array(
          'post_type' => 'portfolio-items',
          'order' => 'asc',
          'posts_per_page' => '30',
           );
          $portfolio = new WP_Query( $args );
      
          if ( $portfolio->have_posts() ) : ?>

        <div class="portfolio">

          <?php while ( $portfolio->have_posts() ) : $portfolio->the_post();
                $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' ); // Full sized image
                $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' ); // Custom thumbnail size ?>

            <div class="portfolio-item">
							<h2><?php the_title(); ?></h2>
              <a href="<?php echo get_the_excerpt(); ?>" target="_blank"><img src="<?php echo $image[0]; ?>" class="responsive-image" alt="<?php the_title(); ?>"></a>
            </div>

            <?php endwhile; ?>

        </div>

      <?php endif; wp_reset_postdata(); ?>

  </main>

  <?php get_footer(); ?>
