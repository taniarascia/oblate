<?php get_header(); ?>

<section class="content">
	<section class="page-body">
		<header class="page-header">
			<h1><?php echo get_search_query(); ?></h1>
		</header>

			<?php if ( have_posts() ) : ?>

			<section class="article-preview-section">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', get_post_format() ); ?>

			<?php endwhile;  ?>
			
		</section>

		<div class="posts-links">
			<div class="pagination-left">
				<?php previous_posts_link(); ?>
			</div>
			<div class="pagination-right">
				<?php next_posts_link(); ?>
			</div>
		</div>
		<?php else : ?>
		<h2>Sorry! No results were found for this query.</h2>
		<?php endif; wp_reset_postdata(); ?>

	</section>
</section>

<?php get_footer(); 