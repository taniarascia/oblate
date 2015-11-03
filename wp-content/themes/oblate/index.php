<?php get_header();?>
	<main>
<?php if (have_posts()) : ?>
<?php $post = $posts[0]; $c=0;?>
<?php while (have_posts()) : the_post();
 $c++;
if( !$paged && $c == 1 && is_front_page()) :?>

<article id="post-<?php the_ID(); ?>" class="article-excerpt first">
	<div class="article-header">
		<h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
		<time>
			<?php the_time('F j, Y');?>
		</time>
	</div> 
	 <?php the_content(); ?>
</article>

<?php else :?>
			<?php get_template_part( 'content', get_post_format() );
		 endif;
			endwhile;?>
			<div class="posts-links">
				<div class="left">
					<?php previous_posts_link();?>
				</div>
				<div class="right">
					<?php next_posts_link(); ?>
				</div>
			</div>
			<?php endif;
		?>
	</main>
	<?php get_sidebar();?>
		<?php get_footer();?>
