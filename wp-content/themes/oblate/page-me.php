<?php 

get_header();

    if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <div class="container">
            <h1><?php the_title(); ?></h1>

            <?php the_content(); ?>

            <h2>Newsletter</h2>
            <form id="newsletter-form" class="newsletter-form" action="https://newsletter.taniarascia.com/sendy/subscribe" method="POST" accept-charset="utf-8" target="_blank">
                <input type="email" name="email" required id="email-sidebar" class="email" placeholder="Email address" pattern="[a-zA-Z0-9._\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,4}">
                <input type="hidden" name="list" value="P2bfC2WL3TvnTWEmucMbbg">
                <input type="submit" name="submit" id="submit-sidebar" value="Submit">
            </form>
        </div>

    <?php 
    endwhile; endif; 

get_footer();
