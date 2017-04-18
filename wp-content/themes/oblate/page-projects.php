<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="page-header">
	<div class="small-container">
		<h1>
			<?php the_title(); ?>
		</h1>
		<?php the_content(); ?>
	</div>
</header>

<?php endwhile; endif; ?>

<section id="projects">

	<section id="primitive" class="projects">
		<div class="small-container">
			<h1>Primitive</h1>
			<p>A minimalist CSS framework and Sass starter-pack for front end web developers. Also includes <span class="eighties">Eighties Mode!</span></p>
			<a class="button-primitive" href="https://taniarascia.github.io/primitive/" target="_blank">View Project</a>
			<a class="button-primitive" href="http://github.com/taniarascia/primitive/" target="_blank">GitHub Repo</a>
		</div>
	</section>

	<section id="new-moon" class="projects">
		<div class="small-container">
			<img src="<?php echo site_url(); ?>/wp-content/uploads/newmoon.png" class="responsive-image">
			<h1>New Moon</h1>
			<p>Beautiful syntax highlighting for Atom, Brackets, &amp; Sublime.</p>
			<a class="button" target="_blank" href="https://taniarascia.github.io/new-moon/">View Project</a>
			<a class="button" target="_blank" href="https://github.com/taniarascia/new-moon">GitHub Repo</a>
		</div>
	</section>

	<section id="music" class="projects">
		<div class="small-container">
			<h1>Ivory &amp; Ivory</h1>
			<p>Really weird musical side project.</p>
			<a class="button" target="_blank" href="https://www.taniarascia.com/music/">View Project</a>
		</div>
	</section>

	<section id="balance" class="projects">
		<div class="small-container">
			<img src="https://taniarascia.github.io/balancewebdev/images/enso.png" class="responsive-image">
			<h1>Balance Web Development</h1>
			<p>A timeline of web design trends from 1996 to 2016.</p>
			<a href="https://taniarascia.github.io/balancewebdev/" class="button" target="_blank">View Project</a>
			<a href="https://github.com/taniarascia/balancewebdev/" class="button" target="_blank">GitHub Repo</a>
		</div>
	</section>

</section>

<?php get_footer(); 