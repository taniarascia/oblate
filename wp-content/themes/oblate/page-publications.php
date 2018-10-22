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
              'category_name' => 'Envato'
            ); 

            $sp = new WP_Query( $args );

            if ( $sp->have_posts() ) :  ?>

            <h3>Envato Tuts+</h3>

            <?php while ( $sp->have_posts() ) : $sp->the_post();  ?>

            <a class="post" id="post-<?php the_ID(); ?>" href="<?php echo get_the_content(); ?>" target="_blank">
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
            <div class="post-title"><?php the_title(); ?></div>
                <span class="post-date"><time><?php the_time( 'F j, Y' ); ?></time></span>
            </a>

            <?php endwhile; endif; wp_reset_postdata(); ?>

            <?php $args = array(
              'post_type' => 'publications',
              'order' => 'asc',
              'category_name' => 'Digital Ocean'
            ); 
                
            $sp = new WP_Query( $args );

            if ( $sp->have_posts() ) :  ?>

            <h3>DigitalOcean</h3>
            
            <?php while ( $sp->have_posts() ) : $sp->the_post();  ?>
          
            <a class="post" id="post-<?php the_ID(); ?>" href="<?php echo get_the_content(); ?>" target="_blank">
              <div class="post-title"><?php the_title(); ?></div>
                <span class="post-date"><time><?php the_time( 'F j, Y' ); ?></time></span>
            </a>

            <?php endwhile; endif; wp_reset_postdata(); ?>

            
            </div>
        </section>
    </div>

<?php get_footer();