<?php get_header(); ?>

  <main class="main-content">

    <div class="small-container">

      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

						get_template_part( 'content-page', get_post_format() );

						endwhile; endif; ?>

        <div class="text-center">
          <p>No cute bullshit about buying me pizza or coffee. If pressing that button is something you want to do, then by all means, do.</p>

          <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick" />
            <input type="hidden" name="hosted_button_id" value="7JMKA39JXQNGL" />
            <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate" />
            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
          </form>
        </div>

    </div>


  </main>

  <?php get_footer(); ?>
