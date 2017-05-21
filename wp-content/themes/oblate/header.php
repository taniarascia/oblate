<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php if ( !is_single() ) { ?>
	<meta name="description" content="Web designer, developer, autodidact. Tania breaks down complex concepts in a clear, simple way for all skill levels.">
	<?php } ?>

	<?php wp_head(); ?>

	<?php if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Speed Insights') === false): ?>

	<script>
		(function(i, s, o, g, r, a, m) {
			i['GoogleAnalyticsObject'] = r;
			i[r] = i[r] || function() {
				(i[r].q = i[r].q || []).push(arguments)
			}, i[r].l = 1 * new Date();
			a = s.createElement(o),
				m = s.getElementsByTagName(o)[0];
			a.async = 1;
			a.src = g;
			m.parentNode.insertBefore(a, m)
		})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

		ga('create', 'UA-42068444-1', 'auto');
		ga('send', 'pageview');

	</script>

	<?php endif; ?>

	<?php if ( is_front_page() ) { ?>
	<script async defer src="https://buttons.github.io/buttons.js"></script>
	<?php } ?>

</head>

<body>
	<nav class="secondary-navigation">
		<div class="secondary-title"><a href="<?php echo site_url(); ?>">Tania Rascia</a> <span class="divider">|</span> <span class="secondary-subtitle"><?php if (is_front_page()) { ?>Web Designer, Developer, Autodidact<?php } else { echo get_the_title(); } ?></span></div>
		<form role="search" method="get" class="nav-search" action="<?php echo home_url( '/' ); ?>">
		<label>
			<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span></label>
			<input type="search" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" class="nav-search-input">
			<button type="submit" class="nav-search-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
		</form>
	</nav>

	<nav class="main-navigation">
		<a href="<?php echo site_url(); ?>/tutorials" class="tutorials"><i class="fa fa-map-o" aria-hidden="true"></i> Tutorials</a>
		<a href="<?php echo site_url(); ?>/snippets" class="snippets"><i class="fa fa-scissors" aria-hidden="true"></i> Snippets</a>
		<a href="<?php echo site_url(); ?>/me" class="tania"><i class="fa fa-heart-o" aria-hidden="true"></i> Tania</a>
		<a href="<?php echo site_url(); ?>/work" class="portfolio"><i class="fa fa-folder-open-o" aria-hidden="true"></i> Portfolio</a>
		<a href="<?php echo site_url(); ?>/thoughts" class="thoughts"><i class="fa fa-commenting-o" aria-hidden="true"></i> Thoughts</a>
	</nav>

	<main class="main">
