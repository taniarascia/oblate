<?php get_header(); ?>
	<main class="container">
	<section class="shell">
	<div class="text-center">
	<pre class="ascii">	
 _              _                          _                            
| |_ __ _ _ __ (_) __ _ _ __ __ _ ___  ___(_) __ _   ___ ___  _ __ ___  
| __/ _` | '_ \| |/ _` | '__/ _` / __|/ __| |/ _` | / __/ _ \| '_ ` _ \ 
| || (_| | | | | | (_| | | | (_| \__ \ (__| | (_| || (_| (_) | | | | | |
 \__\__,_|_| |_|_|\__,_|_|  \__,_|___/\___|_|\__,_(_)___\___/|_| |_| |_|

 Web Development Guides and Tutorials
 </pre>
		</div>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
		
		get_template_part( 'content', get_post_format() );
		
			endwhile; ?>
			
			<!--<div class="posts-links">
				<div class="left">
					<?php previous_posts_link(); ?>
				</div>
				<div class="right">
					<?php next_posts_link(); ?>
				</div>
			</div>
<?php endif; ?> -->
		</section>
	</main>
	<?php get_sidebar(); ?>
		<?php get_footer(); ?>
