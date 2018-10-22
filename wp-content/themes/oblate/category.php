<?php 

get_header(); ?>

	<div class="container">

        <?php 
        if ( have_posts() ) : ?>
        
            <h1><?php single_cat_title(); ?></h1>

        <?php 
        while ( have_posts() ) : the_post(); 
            get_template_part( 'content', get_post_format() );
        endwhile; ?>
            
        <?php endif; wp_reset_postdata(); ?>
        
    </div>

<?php 

get_footer();