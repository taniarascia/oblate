<?php get_header(); ?>

	<main class="main-content">

		<div class="small-container">

		<?php global $post; 
		$args = 'posts_per_page=99'; 
		$all_posts = get_posts($args);

		foreach ( $all_posts as $post ) : setup_postdata( $post ); 
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'thumbnail' );
		$url = $thumb['0']; ?>

			<a href="<?php the_permalink(); ?>" class="article-link">

				<article id="post-<?php the_ID(); ?>" class="all">
					<div class="article-header">
						<h1><?php the_title(); ?></h1>
						<time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('F j, Y'); ?></time>
					</div>

					<div class="article-image">
						<?php if ( get_post_thumbnail_id() ) { ?> <img src="<?php echo $url; ?>" alt="<?php the_title(); ?>"><?php } ?>
					</div>
				</article>

			</a>
		<?php endforeach; ?>

		</div>

	</main>

	<?php get_footer(); ?>