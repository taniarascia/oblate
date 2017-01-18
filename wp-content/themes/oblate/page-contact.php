<?php get_header(); ?>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>">
				<div class="page-header">
					<h1>Say Hello</h1>
				</div>
			<?php the_content(); ?>
			</article>

		endwhile; endif; ?>

<?php get_footer(); ?>