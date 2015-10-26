<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="author" content="Tania Rascia">
	<meta name="description" content="Minimalist web design and development blog.">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Tania Rascia</title>

	<link href="<?php bloginfo('template_directory');?>/css/style.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Lato:400,700,900' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700,900' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto+Mono:400,500,700' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Merriweather:400,700,900' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<?php wp_head(); ?>
	
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42068444-1', 'auto');
  ga('send', 'pageview');
	</script>
	
</head>

<body>
	<header>
			<div class="flex vertical">
				<div class="box">
					<a href="<?php bloginfo('wpurl');?>"><img src="<?php bloginfo('template_directory');?>/images/myface.png" class="logo-image"></a>
				</div>
				<div class="box social">
					<a href="https://twitter.com/taniarascia"><img src="<?php bloginfo('template_directory');?>/images/twitter-dark.png" class="darken"></a>&nbsp;
					<a href="https://github.com/taniarascia"><img src="<?php bloginfo('template_directory');?>/images/github-dark.png" class="darken"></a>
				</div>
			</div>
	</header>
