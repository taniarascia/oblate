<?php get_header(); ?>

<section class="content">

	<section class="page-body">
		
		<?php if ( have_posts() ) : ?>

		<section class="article-preview-section">

        <?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

            <?php endwhile; ?>
            
        </section>

        <?php endif; wp_reset_postdata(); ?>

    </section>

</section>

<?php get_footer();