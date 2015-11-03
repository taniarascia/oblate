(function ($) {
	$(function () { // DOM Ready

	});

})(jQuery);

function light() {
	var el = document.getElementById("new-moon");
	el.href = "http://www.taniarascia.com/wp-content/themes/oblate/css/none.css";
}

function dark() {
	var el = document.getElementById("new-moon");
	el.href = "http://www.taniarascia.com/wp-content/themes/oblate/css/newmoon.css";
}


function toggleStyle() {
	var el = document.getElementById("new-moon");
	if (el.href.match("http://www.taniarascia.com/wp-content/themes/oblate/css/none.css")) {
		el.href = "http://www.taniarascia.com/wp-content/themes/oblate/css/newmoon.css";
	} else {
		el.href = "http://www.taniarascia.com/wp-content/themes/oblate/css/none.css";
	}
}
