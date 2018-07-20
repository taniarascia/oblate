<?php 

get_header();
	
if ( have_posts() ) : while ( have_posts() ) : the_post(); 

	get_template_part( 'content-single', get_post_format() );

	if ( comments_open() || get_comments_number() ) :
	
		comments_template();
	
	endif; 

endwhile; endif; 

get_footer(); 