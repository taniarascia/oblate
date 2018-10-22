<?php 

get_header(); ?>

    <div class="container">

    <?php 
    if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <article id="<?php the_id(); ?>" class="article">
		    <header class="page-header">
			    <h1><?php the_title(); ?></h1>
		    </header>
		
		<?php the_content(); ?>

        </article>

    <?php 
    endwhile; endif; ?>

    </div>

<?php 

get_footer();
