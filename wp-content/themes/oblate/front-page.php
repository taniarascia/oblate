<?php 

get_header(); ?>

    <div class="container">
        <section class="section">
			<h1>Hi, I'm Tania. </h1>
			<p class="lead">I'm a <strong>web developer</strong>, <strong>designer</strong>, and <strong>former chef</strong> from Chicago. I build open source projects and write the missing instruction manuals of the web. </p>
            <p class="lead">I created this site to document and share everything I learn, and share a bit of myself with the world. My website has no bullshit, no ads, no sponsored posts, and no paywalls. If you enjoy my content, please consider <a href="https://ko-fi.com/taniarascia" target="_blank">supporting what I do.</a></p>
            <div class="front-page-social">	
			    <a class="github-button" href="https://github.com/taniarascia" data-size="large" data-text="Follow" data-show-count="true" aria-label="Follow @taniarascia on GitHub">Follow</a>
				<a href="https://twitter.com/taniarascia?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-show-screen-name="false" data-show-count="true" data-size="large">Follow @taniarascia</a>
				<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
			</div>
		</section>

		<section class="section">
			<blockquote>
			"Tania writes extremely clear, concise tutorials that have the best ratio of learning to bullshit that I've encountered so far. It's no exaggeration to say that I wouldn't currently have a job in development without this site. So thanks for ruining my life, Tania."
			<cite>&mdash; Craig Lam, Web Developer</cite>
			</blockquote>
		</section>

		<section class="section">
			<h2>Latest Posts 
				<a class="view-all" href="<?php echo site_url(); ?>/blog">View all</a>
			</h2>
			<?php $args = array(
				'order'          => 'desc',
				'orderby'        => 'publish_date',
				'posts_per_page' => '5',
			);

			$latest = new WP_Query( $args );

			if ( $latest->have_posts() ) :  while ( $latest->have_posts() ) : $latest->the_post(); 
				get_template_part( 'content', get_post_format() );
			endwhile; endif; wp_reset_postdata(); ?>

		</section>

		<section class="section">
			<h2>Most Popular</h2>
			<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '9',
				'category_name' => 'Popular',
			);

			$popular = new WP_Query( $args );

			if ( $popular->have_posts() ) :  while ( $popular->have_posts() ) : $popular->the_post(); 
				get_template_part( 'content', get_post_format() );
			endwhile; endif; wp_reset_postdata(); ?>
			
		</section>
	
		<section class="section">
			<h2>Open Source Projects</h2>
			<div class="post">
				<div class="post-thumbnail">
				    <i class="fas fa-fire icon primitive-icon"></i>
				</div>
				<a class="post-title" href="https://taniarascia.github.io/primitive">Primitive</a>
				<div class="post-description">Sass boilerplate that provides helpful, browser-consistent styling for buttons, forms, tables, lists, and typography.</div>
				<div>
				    <a class="github-button" href="https://github.com/taniarascia/primitive" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star Primitive on GitHub">Star</a>
				</div>
			</div>
			<div class="post">
				<div class="post-thumbnail">
					<i class="fas fa-sun icon laconia-icon"></i>
				</div>
			    <a class="post-title" href="https://laconia.site" target="_blank">Laconia</a>
			    <div class="post-description">A modern MVC application written in plain PHP without libraries or frameworks.</div>
			    <div>
				    <a class="github-button" href="https://github.com/taniarascia/laconia" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star Laconia on GitHub">Star</a>
			    </div>
		    </div>
			<div class="post">
				<div class="post-thumbnail">
				    <img src="<?php echo site_url(); ?>/wp-content/uploads/newmoon.png" alt="New Moon Code Theme" height="30" width="30">
				</div>
			    <a class="post-title" href="https://taniarascia.github.io/new-moon">New Moon</a>
			    <div class="post-description">The undisputed world's best code theme, optimized for front and back end web development.</div>
			    <div>
				<a class="github-button" href="https://github.com/taniarascia/new-moon" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star New Moon on GitHub">Star</a>
		        </div>
		    </div>
		    <div class="post">
				<div class="post-thumbnail">
					<i class="fab fa-wordpress icon"></i>
				</div>
                <a class="post-title" href="https://github.com/taniarascia/oblate" target="_blank">Oblate</a>
                <div class="post-description">The source code of this website.</div>
                <div>
                    <a class="github-button" href="https://github.com/taniarascia/oblate" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star Oblate on GitHub">Star</a>
                </div>
		    </div>
	
		</section>

		<section class="section">
		    <h2>Publications
				<a class="view-all" href="<?php echo site_url(); ?>/publications">View all</a>
			</h2>
			<?php $args = array(
				'post_type' => 'publications',
				'order' => 'desc',
				'posts_per_page' => '5',
            ); 
            
			$publications = new WP_Query( $args );

			if ( $publications->have_posts() ) :  while ( $publications->have_posts() ) : $publications->the_post();  ?>
			
			<a class="post" id="post-<?php the_ID(); ?>" href="<?php echo get_the_content(); ?>" target="_blank">
			    <div class="post-title"><?php the_title(); ?></div>
				<span class="post-date"><time><?php the_time( 'F j, Y' ); ?></time></span>
		    </a>

			<?php endwhile; endif; wp_reset_postdata(); ?>
			
		</section>

		<section class="section">
			<h2>Speaking</h2>
			<h5>WordCamp Chicago</h5>
				<a class="post" href="https://wordpress.tv/2017/08/30/tania-rascia-how-to-develop-a-wordpress-theme-from-scratch/" target="_blank">
					<div class="post-thumbnail">
					<i class="fab fa-wordpress icon"></i>
					</div>
				<div class="post-title">How to Develop a WordPress Theme from Scratch</div>
				<span class="post-date"><time>April 30, 2017</time></span>
			</a>
		</section>

		<section class="section">
			
			<div class="newsletter">
				<h2>Newsletter</h2>
				<p>Sign up if you'd like to get email updates when I write new articles or other big updates. I typically send something out once a month or so. Never any spam.</p>
				<form id="newsletter-form" class="newsletter-form" action="https://newsletter.taniarascia.com/sendy/subscribe" method="POST" accept-charset="utf-8" target="_blank">
                    <input type="email" name="email" required id="email-sidebar" class="email" placeholder="Email address" pattern="[a-zA-Z0-9._\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,4}">
                    <input type="hidden" name="list" value="P2bfC2WL3TvnTWEmucMbbg">
                    <input type="submit" name="submit" id="submit-sidebar" value="Submit">
                </form>
            </div>
		</section>
	</div>

<?php 

get_footer();