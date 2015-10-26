<article id="post-<?php the_ID(); ?>" class="grid">
	<div class="article-header center">
		<h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
		<time>
			<?php the_time('F j, Y');?>
		</time>
		<?php the_tags('&nbsp;☍&nbsp;<span class="tags">', ', ', '', '</span>'); ?>
	</div>
	<?php the_excerpt(); ?>
		<?php
// foreach((get_the_category()) as $category) {
   // if ($category->cat_name != 'Uncategorized') {
    //echo '<a class="category-block" href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a> ';   }}
	?>
</article>
