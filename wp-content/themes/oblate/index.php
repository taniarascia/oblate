<?php get_header(); ?>

<section class="content">
    <section class="page-body">
        <section class="article-preview-section">

            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

			    get_template_part( 'content', get_post_format() );

		    endwhile; endif; ?>

        </section>

        <div class="posts-links">
            <div class="pagination-left">
                <?php previous_posts_link(); ?>
            </div>
            <div class="pagination-right">
                <?php next_posts_link(); ?>
            </div>
        </div>

        </section>
    </section>

<?php get_footer();