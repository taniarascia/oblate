<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php if ( !is_single() ) { ?>
	<meta name="description" content="Tania Rascia is a designer/developer and writer who breaks down complex concepts for all skill levels.">
	<?php } ?>

	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-W788DFR');</script>

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

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W788DFR"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

	<h1 class="screen-reader-text">Tania Rascia</h1>
	<a class="screen-reader-text" href="#main-content">Skip Navigation</a>

	 <header class="site-navigation">
        <div class="nav-container">
            <div class="site-navigation-grid">
                <nav class="site-navigation-brand">
                    <a href="<?php echo site_url(); ?>">
                        <img src="<?php echo site_url(); ?>/wp-content/uploads/floppy.png" class="brand-icon">
                        <span>Tania Rascia</span>
                    </a>
				</nav>
				<nav class="site-navigation-links">
                    <a href="<?php echo site_url(); ?>/me"><span class="nav-icon"><i class="far fa-heart"></i></span><span>About me</span></a>
                    <a href="<?php echo site_url(); ?>/tutorials"><span class="nav-icon"><i class="far fa-map"></i></span><span>Tutorials</span></a>
                    <a href="<?php echo site_url(); ?>/contact"><span class="nav-icon"><i class="far fa-comments"></i></span><span>Contact</span></a>
                    <a href="https://ko-fi.com/taniarascia" target="_blank"><span class="nav-icon"><i class="fas fa-coffee"></i></span> Buy Coffee</a>
                </nav>
                <nav class="site-navigation-search">
				<form role="search" method="get" class="nav-search" action="<?php echo home_url( '/' ); ?>">
					<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
					<input type="search" placeholder="<?php echo esc_attr_x( 'Search for...', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>"
					name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>">
				</form>
                </nav>
            </div>
        </div>
	</header>
	
	<main id="main-content">

		<div class="container-wrapper">
			<div class="grid">
				<section class="main-content">