<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<header class="page-header bright-header">
	<div class="small-container">
		<h1>
			<?php the_title(); ?>
			<?php the_content(); ?>
		</h1>
	</div>
</header>

<?php endwhile; endif; ?>

<section id="projects">
	<div class="container">

		<h1>Primitive</h1>
		<p>A minimalist CSS framework and Sass starter-pack for front end web developers. Also includes <span class="eighties">Eighties Mode!</span></p>
		<a class="button alt-button" href="https://taniarascia.github.io/primitive/" target="_blank">View Project</a>
		<a class="button secondary-button" href="http://github.com/taniarascia/primitive/" target="_blank">GitHub Repo</a>

		<h1>New Moon</h1>
		<p>Beautiful syntax highlighting for Atom, Brackets, &amp; Sublime.</p>
		<a class="button alt-button" target="_blank" href="https://taniarascia.github.io/new-moon/">View Project</a>
		<a class="button secondary-button" target="_blank" href="https://github.com/taniarascia/new-moon">GitHub Repo</a>

		<h1>Ivory &amp; Ivory</h1>
		<p>Really weird musical side project.</p>
		<a class="button alt-button" target="_blank" href="https://www.taniarascia.com/music/">View Project</a>

		<h1>Balance Web Development</h1>
		<p>A timeline of web design trends from 1996 to 2016.</p>
		<a href="https://taniarascia.github.io/balancewebdev/" class="button alt-button" target="_blank">View Project</a>
		<a href="https://github.com/taniarascia/balancewebdev/" class="button secondary-button" target="_blank">GitHub Repo</a>
	</div>
</section>

<?php get_footer();
