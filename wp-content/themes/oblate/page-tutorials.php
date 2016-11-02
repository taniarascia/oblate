<?php get_header(); ?>

	<main class="main-content">

		<div class="large-container">

		<?php $args = array(
          'order' => 'asc',
          'posts_per_page' => '30',
					'category_name' => 'development'
           );
          $development = new WP_Query( $args );
      
          if ( $development->have_posts() ) : ?>

        <div class="portfolio">

          <?php while ( $development->have_posts() ) : $development->the_post();
                $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' ); // Custom thumbnail size ?>

            <div class="portfolio-item">
							<h2><?php the_title(); ?></h2>
              <a href="<?php echo get_the_excerpt(); ?>" target="_blank"><img src="<?php echo $thumb[0]; ?>" class="responsive-image" alt="<?php the_title(); ?>"></a>
            </div>

            <?php endwhile; ?>

        </div>

      <?php endif; wp_reset_postdata(); ?>
		</div>

	</main>

	<?php get_footer(); ?>