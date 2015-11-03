(function ($) {
	$(function () { // DOM Ready

	});

})(jQuery);

setCookie ('stylesheet', 1, 60 * 60 * 24 * 365); // Sets cookie with a value of 1 valid for 1 year

var style = getCookie('stylesheet'); // Gets cookie value
document.getElementById("new-moon").href = "http://www.taniarascia.com/wp-content/themes/oblate/css/newmoon.css";
 
function toggleStyle() {
	var el = document.getElementById("new-moon");
	if (el.href.match("http://www.taniarascia.com/wp-content/themes/oblate/css/none.css")) {
		el.href = "http://www.taniarascia.com/wp-content/themes/oblate/css/newmoon.css";
	} else {
		el.href = "http://www.taniarascia.com/wp-content/themes/oblate/css/none.css";
	}
}
