<?php get_header(); ?>

	<div class="page-header text-center">
		<h1>All Articles</h1>
	</div>

<?php 
$all_query = new WP_Query(
	array(
		'post_type'=>'post', 
		'post_status'=>'publish',
		'posts_per_page'=>-1
	)
); ?>

<div class="container">

	<?php while ( $all_query->have_posts() ) : $all_query->the_post(); ?>
	
	<div class="each-post">		
	<?php if ( get_post_thumbnail_id() ) { ?><div class="thumbs"><a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>" alt="<?php the_title(); ?>" height="35" width="35"></a></div><?php } ?> <div class="links"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
	</div>

	<?php endwhile;

	wp_reset_postdata(); ?>
	</div>

<?php get_footer(); ?>