console.log('Hello, fellow developer! Thanks for visiting. :)' +
	'\n\nThe design this blog is completely custom, based on my own' +
	'\nCSS framrwork/Sass boilerplate, Primitive. Check it out!' +
	'\nhttps://taniarascia.github.io/primitive' +
	'\n\nLike the syntax theme for code you see on my site?' +
	'\nNew Moon is world\'s best theme for coding. ' +
	'\nhttps://taniarascia.github.io/new-moon' + 
	'\n\nLooking for a good community of web developers?' +
	'\nCheck out HTTPChat. ' +
	'\nhttps://join.slack.com/t/httpchat/shared_invite/enQtNDAxODEwMTU2ODM0LTljMGRjZDZmZTA1ZDEwNjc5M2QwYjk5ZjViMGUzNDYzZjJhMGM2OTNmNTNkODM1OWYzZWIzYjA2NTU4YTczZWU' +
	'\n\n\n\nTry typing ↑ ↑ ↓ ↓ ← → ← → B A.');

function filterArticles() {
	var query;
	var input = document.getElementById('filter');
	var filter = input.value.toUpperCase();
	var section = document.querySelector('.list');
	var posts = section.getElementsByClassName('post');
	var count = 0;

	for (i = 0; i < posts.length; i++) {
		query = posts[i].getElementsByClassName('post-title')[0];
		if (query.innerHTML.toUpperCase().indexOf(filter) > -1) {
			posts[i].style.display = '';
		} else {
			posts[i].style.display = 'none';
		}
	}

	for (i = 0; i < posts.length; i++) {
		if (posts[i].style.display === 'none') {
			count++;
		} else {
			count = 0;
		}
	}

	if (count === posts.length) {
		document.getElementById('none-found').style.display = 'block'
		var h3 = document.querySelectorAll('.list h3');
		for (var i = 0; i < h3.length; i++) {
			h3[i].style.display = 'none'
		}
	} else {
		document.getElementById('none-found').style.display = 'none'
		var h3 = document.querySelectorAll('.list h3');
		for (var i = 0; i < h3.length; i++) {
			h3[i].style.display = 'block'
		}
	}
}

function nightMode() {
	if (localStorage.getItem('stylesheet')) {
		localStorage.clear();
		document.getElementById('night-css').setAttribute('href', '');
	} else {
		localStorage.setItem('stylesheet', '/wp-content/themes/oblate/css/night.css');
		document.getElementById('night-css').setAttribute('href', localStorage.getItem('stylesheet'));
	}
}

document.getElementById('night-mode').addEventListener('click', nightMode)

if (document.getElementById('filter')) {
	document.getElementById('filter').addEventListener('keyup', filterArticles)
}

/*
 * Konami-JS ~ 
 * :: Now with support for touch events and multiple instances for 
 * :: those situations that call for multiple easter eggs!
 * Code: https://github.com/snaptortoise/konami-js
 * Examples: http://www.snaptortoise.com/konami-js
 * Copyright (c) 2009 George Mandis (georgemandis.com, snaptortoise.com)
 * Version: 1.4.5 (3/2/2016)
 * Licensed under the MIT License (http://opensource.org/licenses/MIT)
 * Tested in: Safari 4+, Google Chrome 4+, Firefox 3+, IE7+, Mobile Safari 2.2.1 and Dolphin Browser
 */

var Konami = function (callback) {
	var konami = {
		addEvent: function (obj, type, fn, ref_obj) {
			if (obj.addEventListener)
				obj.addEventListener(type, fn, false);
			else if (obj.attachEvent) {
				// IE
				obj["e" + type + fn] = fn;
				obj[type + fn] = function () {
					obj["e" + type + fn](window.event, ref_obj);
				}
				obj.attachEvent("on" + type, obj[type + fn]);
			}
		},
		input: "",
		pattern: "38384040373937396665",
		load: function (link) {
			this.addEvent(document, "keydown", function (e, ref_obj) {
				if (ref_obj) konami = ref_obj; // IE
				konami.input += e ? e.keyCode : event.keyCode;
				if (konami.input.length > konami.pattern.length)
					konami.input = konami.input.substr((konami.input.length - konami.pattern.length));
				if (konami.input == konami.pattern) {
					konami.code(link);
					konami.input = "";
					e.preventDefault();
					return false;
				}
			}, this);
			this.iphone.load(link);
		},
		code: function (link) {
			window.location = link
		},
		iphone: {
			start_x: 0,
			start_y: 0,
			stop_x: 0,
			stop_y: 0,
			tap: false,
			capture: false,
			orig_keys: "",
			keys: ["UP", "UP", "DOWN", "DOWN", "LEFT", "RIGHT", "LEFT", "RIGHT", "TAP", "TAP"],
			code: function (link) {
				konami.code(link);
			},
			load: function (link) {
				this.orig_keys = this.keys;
				konami.addEvent(document, "touchmove", function (e) {
					if (e.touches.length == 1 && konami.iphone.capture == true) {
						var touch = e.touches[0];
						konami.iphone.stop_x = touch.pageX;
						konami.iphone.stop_y = touch.pageY;
						konami.iphone.tap = false;
						konami.iphone.capture = false;
						konami.iphone.check_direction();
					}
				});
				konami.addEvent(document, "touchend", function (evt) {
					if (konami.iphone.tap == true) konami.iphone.check_direction(link);
				}, false);
				konami.addEvent(document, "touchstart", function (evt) {
					konami.iphone.start_x = evt.changedTouches[0].pageX;
					konami.iphone.start_y = evt.changedTouches[0].pageY;
					konami.iphone.tap = true;
					konami.iphone.capture = true;
				});
			},
			check_direction: function (link) {
				x_magnitude = Math.abs(this.start_x - this.stop_x);
				y_magnitude = Math.abs(this.start_y - this.stop_y);
				x = ((this.start_x - this.stop_x) < 0) ? "RIGHT" : "LEFT";
				y = ((this.start_y - this.stop_y) < 0) ? "DOWN" : "UP";
				result = (x_magnitude > y_magnitude) ? x : y;
				result = (this.tap == true) ? "TAP" : result;

				if (result == this.keys[0]) this.keys = this.keys.slice(1, this.keys.length);
				if (this.keys.length == 0) {
					this.keys = this.orig_keys;
					this.code(link);
				}
			}
		}
	}

	typeof callback === "string" && konami.load(callback);
	if (typeof callback === "function") {
		konami.code = callback;
		konami.load();
	}

	return konami;
};

var easter_egg = new Konami(function () {

	var head = document.head
	var link = document.createElement('link')

	link.type = 'text/css'
	link.rel = 'stylesheet'
	link.href = '/wp-content/themes/oblate/css/eighties.css?ver=5'

	head.appendChild(link)

	document.getElementsByTagName('head')[0].appendChild(link);

});