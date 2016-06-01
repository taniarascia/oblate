<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>

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
</head>

<body>

	<nav class="navigation">
		<div class="left">
			<a href="<?php echo site_url(); ?>" class="one"><img src="<?php bloginfo('template_directory'); ?>/images/tr.png" class="face" alt="Tania Rascia"></a>
			<a href="<?php echo site_url(); ?>" class="two">Latest</a>
			<a href="<?php echo site_url(); ?>/all" class="three">All</a>
		</div>

		<div class="right">
			<a href="<?php echo site_url(); ?>/me" class="four">About</a>
			<a href="<?php echo site_url(); ?>/portfolio" class="five">Portfolio</a>
			<a href="<?php echo site_url(); ?>/projects" class="six">Projects</a>
		</div>
	</nav>