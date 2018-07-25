<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<header class="tutorial-list">
	<a href="<?php echo site_url(); ?>/tutorials/#front-end">
		<i class="fas fa-laptop"></i> <span>Front End</span>
	</a>
	<a href="<?php echo site_url(); ?>/tutorials/#back-end">
		<i class="fas fa-code"></i> <span>Back End</span>
	</a>
	<a href="<?php echo site_url(); ?>/tutorials/#javascript">
		<i class="fab fa-js"></i> <span>JavaScript</span>
	</a>
	<a href="<?php echo site_url(); ?>/tutorials/#workflow">
		<i class="fas fa-code-branch"></i> <span>Workflow</span>
	</a>
	<a href="<?php echo site_url(); ?>/tutorials/#system">
		<i class="fas fa-database"></i> <span>DevOps</span>
	</a>
	<a href="<?php echo site_url(); ?>/tutorials/#wordpress">
		<i class="fab fa-wordpress"></i> <span>WordPress</span>
	</a>
	<a href="<?php echo site_url(); ?>/snippets">
		<i class="fas fa-cut"></i> <span>Snippets</span>
	</a>
	<a href="<?php echo site_url(); ?>/tutorials/#general">
		<i class="fas fa-map"></i> <span>General</span>
	</a>
</header>

<?php endwhile; ?>

<section class="content">
	<section class="page-body tutorials">
	
		<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'front-end',
			);

			$custom = new WP_Query( $args );

			if ( $custom->have_posts() ) : ?>

		<section class="article-preview-section">

		<h2 id="front-end">Front End &amp; Design</h2>
		<p>CSS, Sass, JavaScript, JS frameworks, APIs, and anything client-side.</p>

		<?php while ( $custom->have_posts() ) : $custom->the_post(); ?>
		<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>

	<?php endif; wp_reset_postdata(); ?>

		</section>

		<?php $args = array(
				'order' => 'desc',
				'posts_per_page' => '-1',
				'category_name' => 'back-end',
			);

			$custom = new WP_Query( $args );

			if ( $custom->have_posts() ) : ?>

		<section class="article-preview-section">

			<h2 id="back-end">Back End Development</h2>
			<p>Node.js, PHP, MySQL, and anything server-side. </p>

			<?php while ( $custom->have_posts() ) : $custom->the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

		</section>

	<?php endif; wp_reset_postdata(); ?>

	<?php $args = array(
			'order' => 'asc',
			'posts_per_page' => '-1',
			'category_name' => 'javascript',
		);

		$custom = new WP_Query( $args );

		if ( $custom->have_posts() ) : ?>

		<section class="article-preview-section">

			<h2 id="javascript">JavaScript</h2>
			<p>JavaScript, ES6, frameworks, and libraries.</p>

			<?php while ( $custom->have_posts() ) : $custom->the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

		</section>

	<?php endif; wp_reset_postdata(); ?>

	<?php $args = array(
			'order' => 'desc',
			'posts_per_page' => '-1',
			'category_name' => 'workflow',
		);

		$custom = new WP_Query( $args );

		if ( $custom->have_posts() ) : ?>

		<section class="article-preview-section">

			<h2 id="workflow">Developer Workflow</h2>
			<p>Task runners, preprocessors, and developer setup.</p>

			<?php while ( $custom->have_posts() ) : $custom->the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

		</section>

	<?php endif; wp_reset_postdata(); ?>

	<?php $args = array(
			'order' => 'desc',
			'posts_per_page' => '-1',
			'category_name' => 'system',
		);

		$custom = new WP_Query( $args );

		if ( $custom->have_posts() ) : ?>

	<section class="article-preview-section">
		<h2 id="system">System Administration</h2>
		<p>Linux, virtual servers, command line, and AWS. </p>

			<?php while ( $custom->have_posts() ) : $custom->the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

	</section>
	<?php endif; wp_reset_postdata(); ?>


	<?php $args = array(
			'order' => 'asc',
			'posts_per_page' => '-1',
			'category_name' => 'wordpress',
		);

		$custom = new WP_Query( $args );

		if ( $custom->have_posts() ) : ?>

	<section class="article-preview-section">
		<h2 id="wordpress">WordPress</h2>
		<p>Custom theming guides on the most popular content management system.</p>

		<?php while ( $custom->have_posts() ) : $custom->the_post(); ?>
		<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>

	</section>

	<?php endif; wp_reset_postdata(); ?>

	<?php $args = array(
			'order' => 'desc',
			'posts_per_page' => '-1',
			'category_name' => 'general',
		);

		$custom = new WP_Query( $args );

		if ( $custom->have_posts() ) : ?>

		<section class="article-preview-section">
			<h2 id="general">General</h2>
			<p>Setup, developer tips, SEO, and blogging.</p>

			<?php while ( $custom->have_posts() ) : $custom->the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

		</section>
	<?php endif; wp_reset_postdata(); ?>
		
	</section>
</section>

<?php get_footer();