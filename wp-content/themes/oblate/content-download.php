<article id="post-<?php the_ID(); ?>">
	<header class="single-header alternate-background">
		<div class="small-container">
			<h1>
				<?php the_title(); ?>
			</h1>
		</div>
	</header>

	<section class="single-body">
		<div class="small-container">
		<div class="excerpt"><?php the_excerpt(); ?></div>
			<?php the_content(); ?>
			<div class="author">
				<p><img src="<?php echo site_url(); ?>/wp-content/uploads/face-300x300.jpg" class="my-avatar">
				<h3>Tania Rascia</strong></h3>
		
			I'm <a href="<?php echo site_url(); ?>/me">Tania</a>, a web developer and writer from Chicago. I'm dedicated to making clear, concise guides and resources for everyone who wants to learn to code. I want to make the internet a better place in my small way.</p>
			<a class="button secondary-button" href="https://github.com/taniarascia" target="_blank"><i class="fa fa-github"></i> &nbsp;GitHub</a>
			<a class="button secondary-button" href="https://twitter.com/taniarascia" target="_blank"><i class="fa fa-twitter"></i> &nbsp;Twitter</a>
			</div>
		</div>
	</section>

</article>
