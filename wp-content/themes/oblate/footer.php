</main>

<footer class="footer">
	<a href="<?php echo site_url(); ?>/feed" class="social-icon"><i class="fa fa-rss fa-3x"></i></a>
	<a href="https://twitter.com/taniarascia" class="social-icon"><i class="fa fa-twitter fa-3x"></i></a>
	<a href="https://github.com/taniarascia" class="social-icon"><i class="fa fa-github fa-3x"></i></a>
	<a href="http://codepen.com/taniarascia" class="social-icon"><i class="fa fa-codepen fa-3x"></i></a>
	<a href="http://stackoverflow.com/users/4541434/tania-rascia" class="social-icon"><i class="fa fa-stack-overflow fa-3x"></i></a>
</footer>

<?php wp_footer(); ?>

<?php if ( is_front_page() ) { ?>
	<script async defer src="https://buttons.github.io/buttons.js"></script>

	<script>
		window.twttr = (function (d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0],
				t = window.twttr || {};
			if (d.getElementById(id)) return t;
			js = d.createElement(s);
			js.id = id;
			js.src = "https://platform.twitter.com/widgets.js";
			fjs.parentNode.insertBefore(js, fjs);

			t._e = [];
			t.ready = function (f) {
				t._e.push(f);
			};

			return t;
		}(document, "script", "twitter-wjs"));
	</script>
<?php } ?>
		
</body>

</html>