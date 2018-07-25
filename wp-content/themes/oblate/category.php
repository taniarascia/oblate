<?php get_header(); ?>

<section class="content">
	<section class="page-body">
		
        <?php if ( have_posts() ) : ?>
        
        <header class="page-header">
            <h1><?php single_cat_title(); ?></h1>
		</header>

		<section class="article-preview-section">

        <?php while ( have_posts() ) : the_post(); 
                    get_template_part( 'content', get_post_format() );
              endwhile; ?>
            
        </section>

        <?php endif; wp_reset_postdata(); ?>
        
    </section>
</section>

<?php get_footer();