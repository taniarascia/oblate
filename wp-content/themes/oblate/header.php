<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php if ( !is_single() ) { ?>
	<meta name="description" content="Web developer, designer, autodidact. Tania breaks down complex concepts in a clear, simple way for all skill levels.">
	<?php } ?>



	<?php if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Speed Insights') === false): ?>

	<script>
		(function (i, s, o, g, r, a, m) {
			i['GoogleAnalyticsObject'] = r;
			i[r] = i[r] || function () {
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
	<?php wp_head(); ?>
</head>

<body>
	<h1 class="screen-reader-text">Tania Rascia Web Design and Development</h1>
	<a class="screen-reader-text" href="#main-content">Skip Navigation</a>

	<header class="main-nav-outer">
		<div class="nav-container">
			<nav class="main-navigation">
				<a href="<?php echo site_url(); ?>" class="home <?php if ( is_front_page() ) { ?>current<?php } ?>"><i class="fa fa-home" aria-hidden="true"></i> <span>Home</span></a>
				<a href="<?php echo site_url(); ?>/tutorials" class="tutorials <?php if ( is_page( 'tutorials' ) ) { ?>current<?php } ?>"><i class="fa fa-map-o" aria-hidden="true"></i> <span>Tutorials</span></a>
				<a href="<?php echo site_url(); ?>/snippets" class="snippets <?php if ( is_page( 'snippets' ) ) { ?>current<?php } ?>"><i class="fa fa-scissors" aria-hidden="true"></i> <span>Snippets</span></a>
				<a href="<?php echo site_url(); ?>/me" class="tania <?php if ( is_page( 'me' ) ) { ?>current<?php } ?>"><i class="fa fa-heart-o" aria-hidden="true"></i> <span>Tania</span></a>
				<a href="<?php echo site_url(); ?>/work" class="portfolio <?php if ( is_page( 'work' ) ) { ?>current<?php } ?>"><i class="fa fa-folder-open-o" aria-hidden="true"></i> <span>Portfolio</span></a>
				<a href="<?php echo site_url(); ?>/thoughts" class="thoughts <?php if ( is_page( 'thoughts' ) ) { ?>current<?php } ?>"><i class="fa fa-commenting-o" aria-hidden="true"></i> <span>Thoughts</span></a>
			</nav>
		<div class="search-div">
			<form role="search" method="get" class="nav-search" action="<?php echo home_url( '/' ); ?>">
				<label>
				<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span></label>
				<input type="search" placeholder="<?php echo esc_attr_x( '', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>"
					name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" class="nav-search-input">
				<button type="submit" class="nav-search-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
			</form>
		</div>
	</div>
	</header>

	<main class="main" id="main-content">