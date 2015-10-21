<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="author" content="Tania Rascia">
	<meta name="description" content="Tania Rascia's Web Development Blog and Portfolio">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Tania Rascia</title>

	<link href="<?php bloginfo('template_directory');?>/css/style.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Lato:400,700,900' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700,900' rel='stylesheet' type='text/css'>

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

</head>

<body>
	<div class="front-page">
		<div class="full-screen">
			<div class="inner">
				<h1 class="front-header">Tania Rascia</h1>
				<h2 class="front-subtitle">Web Developer</h2>
				<a class="button-line" href="/blog">Blog</a><!-- <a class="button-line" href="/portfolio">Portfolio</a>-->
			</div>
		</div>
		<div class="front-social">
			<a href="https://twitter.com/taniarascia"><img src="<?php bloginfo('template_directory');?>/images/twitter.png" class="darken"></a>&nbsp;
			<a href="https://github.com/taniarascia"><img src="<?php bloginfo('template_directory');?>/images/github.png" class="darken"></a>
		</div>
	</div>
</body>

</html>
