<?php get_header();?>
	<main>

		<?php global $post; 
$args = 'posts_per_page=99'; 
$custom_posts = get_posts($args);
foreach($custom_posts as $post) : setup_postdata($post);?>

			<article id="post-<?php the_ID(); ?>">
				<h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
				<time>
					<?php the_time('F j, Y');?>
				</time>
				<?php the_tags('&bull; <span class="tags">', ', ', '', '</span>'); ?>
			</article>
			<?php endforeach; ?>

	</main>
	<?php get_footer();?>
