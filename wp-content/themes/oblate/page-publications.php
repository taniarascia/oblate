<?php get_header(); ?>

    <div class="container">
        <header class="search-enabled">
          <h1>Publications</h1>
          <form id="search-form" role="search" class="search-form" method="get" action="<?php echo home_url( '/' ); ?>" onsubmit="return false;">
            <span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
            <div class="search-wrapper">
              <input id="filter" type="search" placeholder="<?php echo esc_attr_x( 'Filter', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>"
        name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>">
              <i class="fas fa-search search-icon"></i>
            </div>
          </form>
        </header>
        
        <section class="list">
            <div class="lead" id="none-found">No results found.</div>

            <?php $args = array(
              'post_type' => 'publications',
              'order' => 'asc',
              'category_name' => 'Log Rocket'
            ); 

            $logrocket = new WP_Query( $args );

            if ( $logrocket->have_posts() ) :  ?>

            <h3>Logrocket</h3>

            <?php while ( $logrocket->have_posts() ) : $logrocket->the_post();  ?>

            <a class="post" id="post-<?php the_ID(); ?>" href="<?php echo get_the_content(); ?>" target="_blank">
            <?php if ( get_post_thumbnail_id() ) : ?>
            <div class="post-thumbnail">
            <img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="30" width="30">
            </div>
            <?php endif; ?>	
            <div class="post-title"><?php the_title(); ?></div>
            <span class="post-date"><time><?php the_time( 'F j, Y' ); ?></time></span>
            </a>

            <?php endwhile; endif; wp_reset_postdata(); ?>

            <?php $args = array(
              'post_type' => 'publications',
              'order' => 'asc',
              'category_name' => 'Codrops'
            ); 

            $codrops = new WP_Query( $args );

            if ( $codrops->have_posts() ) :  ?>

            <h3>Codrops</h3>

            <?php while ( $codrops->have_posts() ) : $codrops->the_post();  ?>

            <a class="post" id="post-<?php the_ID(); ?>" href="<?php echo get_the_content(); ?>" target="_blank">
            <?php if ( get_post_thumbnail_id() ) : ?>
            <div class="post-thumbnail">
            <img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="30" width="30">
            </div>
            <?php endif; ?>	
            <div class="post-title"><?php the_title(); ?></div>
            <span class="post-date"><time><?php the_time( 'F j, Y' ); ?></time></span>
            </a>

            <?php endwhile; endif; wp_reset_postdata(); ?>

            <?php $args = array(
              'post_type' => 'publications',
              'order' => 'asc',
              'category_name' => 'Progress'
            ); 

            $pr = new WP_Query( $args );

            if ( $pr->have_posts() ) :  ?>

            <h3>Progress</h3>

            <?php while ( $pr->have_posts() ) : $pr->the_post();  ?>

            <a class="post" id="post-<?php the_ID(); ?>" href="<?php echo get_the_content(); ?>" target="_blank">
            <?php if ( get_post_thumbnail_id() ) : ?>
            <div class="post-thumbnail">
            <img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="30" width="30">
            </div>
            <?php endif; ?>	
            <div class="post-title"><?php the_title(); ?></div>
            <span class="post-date"><time><?php the_time( 'F j, Y' ); ?></time></span>
            </a>

            <?php endwhile; endif; wp_reset_postdata(); ?>

            <?php $args = array(
              'post_type' => 'publications',
              'order' => 'asc',
              'category_name' => 'Envato'
            ); 

            $envato = new WP_Query( $args );

            if ( $envato->have_posts() ) :  ?>

            <h3>Envato Tuts+</h3>

            <?php while ( $envato->have_posts() ) : $envato->the_post();  ?>

            <a class="post" id="post-<?php the_ID(); ?>" href="<?php echo get_the_content(); ?>" target="_blank">
            <?php if ( get_post_thumbnail_id() ) : ?>
            <div class="post-thumbnail">
            <img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="30" width="30">
            </div>
            <?php endif; ?>	
            <div class="post-title"><?php the_title(); ?></div>
            <span class="post-date"><time><?php the_time( 'F j, Y' ); ?></time></span>
            </a>

            <?php endwhile; endif; wp_reset_postdata(); ?>

             <?php $args = array(
              'post_type' => 'publications',
              'order' => 'asc',
              'category_name' => 'SitePoint'
            ); 

            $sp = new WP_Query( $args );

            if ( $sp->have_posts() ) :  ?>

            <h3>SitePoint</h3>

            <?php while ( $sp->have_posts() ) : $sp->the_post();  ?>

            <a class="post" id="post-<?php the_ID(); ?>" href="<?php echo get_the_content(); ?>" target="_blank">
            <?php if ( get_post_thumbnail_id() ) : ?>
            <div class="post-thumbnail">
            <img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="30" width="30">
            </div>
            <?php endif; ?>	
            <div class="post-title"><?php the_title(); ?></div>
            <span class="post-date"><time><?php the_time( 'F j, Y' ); ?></time></span>
            </a>

            <?php endwhile; endif; wp_reset_postdata(); ?>

            <?php $args = array(
              'post_type' => 'publications',
              'order' => 'asc',
              'category_name' => 'Digital Ocean'
            ); 
                
            $do = new WP_Query( $args );

            if ( $do->have_posts() ) :  ?>

            <h3>DigitalOcean</h3>
            
            <?php while ( $do->have_posts() ) : $do->the_post();  ?>
          
            <a class="post" id="post-<?php the_ID(); ?>" href="<?php echo get_the_content(); ?>" target="_blank">
            <?php if ( get_post_thumbnail_id() ) : ?>
            <div class="post-thumbnail">
            <img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="30" width="30">
            </div>
            <?php endif; ?>	
              <div class="post-title"><?php the_title(); ?></div>
            <span class="post-date"><time><?php the_time( 'F j, Y' ); ?></time></span>
            </a>

            <?php endwhile; endif; wp_reset_postdata(); ?>

            </div>
        </section>
    </div>

<?php get_footer();