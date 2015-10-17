(function ($) {
	$(function () { // DOM Ready

		$('.portfolio-grid li').hover(
			function () {
				$('h3', $(this)).show();
			},
			function () {
				$('h3', $(this)).hide();
			}
		);

	}); 
})(jQuery);
