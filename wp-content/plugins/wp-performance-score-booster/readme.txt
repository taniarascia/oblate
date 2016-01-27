=== WP Performance Score Booster ===
Contributors: dipakcg
Tags: performance, speed, time, query, strings, gzip, compression, caching, boost, pingdom, gtmetrix, yslow, pagespeed, enqueue, scripts
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3S8BRPLWLNQ38
Requires at least: 3.5
Tested up to: 4.4
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Speed-up page load times and improve website scores in services like PageSpeed, YSlow, Pingdom and GTmetrix.

== Description ==
This plugin speed-up page load times and improve website scores in services like PageSpeed, YSlow, Pingdom and GTmetrix.

= This plugin will... =
* Remove any query strings from static resources like CSS & JS files
* Enable GZIP compression (compress text, html, javascript, css, xml and so on)
* Add Vary: Accept-Encoding header, and
* Set expires caching (leverage browser caching).

**P.S. It is aways the best policy to open a [support thread](http://wordpress.org/support/plugin/wp-performance-score-booster) first before posting a negative review.**

== Installation ==
1. Upload the ‘wp-performance-score-booster’ folder to the ‘/wp-content/plugins/‘ directory
2. Activate the plugin through the ‘Plugins’ menu in WordPress.
3. That’s it!

== Frequently Asked Questions ==
= What does this plugin do? =

It speed-up page load times and improve website scores in services like PageSpeed, YSlow, Pingdom and GTmetrix. It will remove any query strings from static resources like CSS & JS files,  enable GZIP compression (compress text, html, javascript, css, xml and so on), add Vary: Accept-Encoding header and set expires caching (leverage browser caching).

= Any specific requirements for this plugin to work? =

* GZIP compression should be enabled in your web-server (apache?). If not then you can ask your web hosting provider.
* .htaccess in your WordPress root folder must have write permissions.

= Is that it? =

Pretty much, yeah.

== Screenshots ==
1. Admin Settings

== Changelog ==
= 1.4, Feb 28, 2015 =
* Added News and Updates section in admin options

= 1.3.1, Dec 30, 2014 =
* Fixed issues with htaccess causing internal server error

= 1.3, Dec 29, 2014 =
* Fixed issues with htaccess custom rules overrides
* WP Performance Score Booster now adds rules to htaccess outside default WordPress block

= 1.2.2, Dec 27, 2014 =
* Added support for language translations

= 1.2.1, Nov 17, 2014 =
* Removed (temporarily) feature to enqueue scripts to footer

= 1.2, Nov 17, 2014 =
* Added feature to enqueue scripts to footer
* Added support for Vary: Accept-Encoding header
* Fixed minor issues for remove query strings from static resources

= 1.1.1, Sept 02, 2014 =
* Added feature (for urls with &ver) to remove query strings from static resources

= 1.1, Aug 31, 2014 =
* Added Admin Options / Settings

= 1.0, Aug 26, 2014 =
* Initial release

== Upgrade Notice ==
Added Admin Options / Settings.