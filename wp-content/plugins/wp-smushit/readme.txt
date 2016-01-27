=== WP Smush ===
Plugin Name: WP Smush
Version: 2.1.3
Author: WPMU DEV
Author URI: http://premium.wpmudev.org/
Contributors: WPMUDEV, alexdunae
Tags: Attachment,Attachments,Compress,Compress Image File,Compress Image Size,Compress JPG,Compressed JPG, Compression Image,Image,Images,JPG,Optimise,Optimize,Photo,Photos,Pictures,PNG,Reduce Image Size,Smush,Smush.it,Upload,WordPress Compression,WordPress Image Tool,Yahoo, Yahoo Smush.it
Requires at least: 3.5
Tested up to: 4.4.1
Stable tag: 2.1.3
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Reduce image file sizes, improve performance and boost your SEO using the free <a href="https://premium.wpmudev.org/">WPMU DEV</a> WordPress Smush API.

== Description ==

<blockquote>
<h4>All new massively improved smushing!</h4>
<br>
Try our completely new, incredibly powerful, and 100% free image Smusher, brought to you by the superheroes at WPMU DEV!
 <br>
We'll do all the heavy lifting with our superhero servers, your site will be loading quicker in no time at all, leaving you to concentrate on making your content great!
 <br>
Cheers<br>
WPMU DEVMan
 <br>
</blockquote>

WP Smush strips hidden, bulky information from your images, reducing the file size without losing quality. The faster your site loads, the more Google, Bing, Yahoo and other search engines will like it.

[youtube https://www.youtube.com/watch?v=GCzH7z05s5U]

Heavy image files may be slowing down your site without you even knowing it. WP Smush meticulously scans every image you upload – or have already added to your site – and cuts all the unnecessary data for you.

★★★★★ <br>
“I had no idea that my page load time was being dragged down by the images. The plugin nearly halved the time it took.” - <a href="http://profiles.wordpress.org/karlcw">karlcw</a>

Install WP Smush and find out why it's the most popular image optimization plugin for WordPress available today with over 1 million downloads.

<blockquote>
<h4>If you like WP Smush, you'll love WP Smush Pro</h4>
<br>
<a href="https://premium.wpmudev.org/project/wp-smush-pro/?utm_source=wordpress.org&utm_medium=readme">WP Smush Pro</a> gives you everything you'll find in WP Smush and more:
<ul>
  <li>"Super-Smush" your images with our intelligent multi-pass lossy compression. Get over 2x more compression than lossless with almost no noticeable quality loss!</li>
  <li>Get even better lossless compression. We try multiple methods to squeeze every last byte out of your images.</li>
  <li>Smush images up to 32MB (WP Smush is limited to 1MB)</li>
  <li>Bulk smush ALL your images with one click! No more rate limiting.</li>
  <li>Keep a backup of your original un-smushed images in case you want to restore later.</li>
	<li>24/7/365 support from <a href="https://premium.wpmudev.org/support/?utm_source=wordpress.org&utm_medium=readme">the best WordPress support team on the planet</a>.</li>
	<li><a href="https://premium.wpmudev.org/?utm_source=wordpress.org&utm_medium=readme">350+ other premium plugins and themes</a> included in your membership.</li>
</ul>

Upgrade to <a href="https://premium.wpmudev.org/project/wp-smush-pro/?utm_source=wordpress.org&utm_medium=readme">WP Smush Pro</a> and optimize more and larger image files faster to increase your site’s performance.
</blockquote>

Features available to both WP Smush and Pro users include:
<ul>
	<li>Optimize your images using advanced lossless compression techniques.</li>
	<li>Process JPEG, GIF and PNG image files.</li>
	<li>Auto-Smush your attachments on upload.</li>
	<li>Manually smush your attachments individually in the media library, or in bulk 50 attachments at a time.</li>
	<li>Smush all images 1MB or smaller.</li>
	<li>Use of WPMU DEV's fast and reliable Smush API service.</li>
	<li>View advanced compression stats per-attachment and library totals.</li>
</ul>
Discover for yourself why WP Smush is the most popular free image optimization plugin with more than two million downloads.


== Screenshots ==

1. See individual attachment savings from WP Smush in the Media Library.
2. See the total savings from WP Smush in the Media Library.

== Installation ==

1. Upload the `wp-smush` plugin to your `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Configure your desired settings via the `Media -> WP Smush` settings page.
1. Done!

== Upgrade Notice ==

Yahoo's Smush.it API is gone forever. So WPMU DEV built our own free API that is faster, more reliable, and more powerful. Upgrade now!


== Changelog ==

= 2.1.3 =
* Fixed: Compatibility with WPMU DEV Dashboard plugin v4.0, was not detecting pro status

= 2.1.2 =
* Fixed: Important fix for transient issue leading to mutliple api requests

= 2.1.1 =
* Fixed: Workaround for terrible bug in W3TC file based object caching

= 2.1 =
* Fixed: Untranslatable strings in settings
* Fixed: Increased is_pro() API timeouts
* Fixed: Remove redundant _get_api_key() cache check
* Fixed: Some PHP notices on fresh installs

= 2.0.7.1 =

* Fixed: Bulk Smush button keeps spinning after 50 images for free version
* Fixed: NextGen Bulk Smush button issue

= 2.0.7 =

* Fixed: Invalid header issue
* Fixed: Warnings in file functions
* Updated: Added limit on query results, for sites with higher image count, Use filter `wp_smush_media_query_limit` to adjust values
* Added: Sortable WP Smush column in Media Library
* Added: Filters `wp_smush_media_image` for Media Gallery, `wp_smush_nextgen_image` For NextGen gallery,
          allows to skip a particular image size from smushing
* Added: NextGen Gallery support (Pro feature)

= 2.0.6.5 =

* Updated: Skip webp images ( causing ajax error for few sites )
* Fixed: Warning and Notices ( Media Library not loading )
* Fixed: Smush full size image if no other sizes are available
* Added: Detailed text for stats and settings

= 2.0.6.3 =

* Fixed: Change File permission after replacing image
* Fixed: Directory path for files
* Fixed: Workaround for Auto Smush issue on Hostgator
* Fixed: Smush button doesn't works in media library dialog on post screen, when add media is clicked
		 (https://wordpress.org/support/topic/like-wp-smush-a-lot)
* New:   Show number of images smushed in stats column
* Added: Support for WP Retina 2x Plugin
* Added: Filter `WP_SMUSH_API_TIMEOUT` to change the default time out value from 60s
* Added: Smush original image option (Pro Only)

= 2.0.6.2 =

* Use string for text domain instead of PHP Constant ( WordPress Guideline )

= 2.0.6.1 =

* Updated: Max image size limit to 32Mb for Pro Version

= 2.0.6 =

* Fixed: Conflict with various themes while editing post and page
* Fixed: Word Count not working
* Fixed: Notice and Warnings

= 2.0.5 =

* New:   Allow Super-smush for existing smushed images (Pro Only)
* Fixed: IMPORTANT - broken transient caching for is_pro
* Fixed: Fixed conflict with wp gallery link plugin in grid view
* Fixed: Other small fixes


= 2.0.4 =
* Fix: Fatal error conflict with some plugins on fronted of site

= 2.0.3 =
* Fixed (Important Update) - Image being corrupted while regenerating thumbnails

= 2.0.2 =
* Check for existing constant definition, before defining new

= 2.0.1 =
* UI changes

= 2.0 =
* Complete rewrite to use WPMU DEV's new fast and reliable API service.
* New: One-click bulk smushing of all your images.
* New: "Super-Smush" your images with our intelligent multi-pass lossy compression. Get over 2x more compression with almost no noticeable quality loss! (Pro)
* New: Keep a backup of your original un-smushed images in case you want to restore later. (Pro)
* UX/UI updated with overall stats, progress bar.

= 1.7 =
* Use Ajax for Bulk Smush to avoid timeouts and internal server error
* Other Notice and bug fixes
* Settings moved under Media > WP Smush.it
* Added debug log file

= 1.6.5.4 =
* Added settings option to disable check for home url.
* for PHP 5.4.x reordered class WpSmushit contructors to prevent Strict Standards Exception

= 1.6.5.3 =
* Removed check for file within local site home path. 

= 1.6.5.2 =
* Corrected issues with Windows paths compare.
* Added debug output option to help with user support issues. 

= 1.6.5.1 =
* Correct Settings > Media issue causing settings to report warnings and not save. 
* Corrected some processing logic to better handling or image path. Images still need to be within ABSPATH of site
* Correct image URL passed to Smush.it API to convert https:// urls to http:// since the API does not allow https:// images


= 1.6.5 =
* Codes reformatted and cleaned up into a php class
* More texts are translatable now

= 1.6.4 =
* Fixed a bug that prevents execution

= 1.6.3 =
* check image size before uploading (1 MB limit)
* attempt to smush more than one image before bailing (kind thanks to <a href="http://wordpress.org/support/profile/xrampage16">xrampage16</a>)
* allow setting timeout value under `Media > Settings` (default is 60 seconds)

= 1.6.2 =
* about to get a new lease on life notice

= 1.6.1 =
* no longer maintained notice

= 1.6.0 =
* added setting to disable automatic smushing on upload (default is true)
* on HTTP error, smushing will be temporarily disabled for 6 hours

= 1.5.0 =
* added basic integration for the <a href="http://wordpress.org/extend/plugins/wp-smushit-nextgen-gallery-integration/">NextGEN gallery plugin</a>
* add support for media bulk action dropdown
* compatibility with WordPress earlier than 3.1
* added a <a href="http://dunae.ca/donate.html">donate link</a>

= 1.4.3 =
* cleaner handling of file paths

= 1.4.2 =
* bulk smush.it will no longer re-smush images that were successful

= 1.4.1 =
* bug fixes

= 1.4.0 =
* bulk smush.it

= 1.3.4 =
* bug fixes

= 1.3.3 =
* add debugging output on failure

= 1.3.2 =
* removed realpath() call
* IPv6 compat

= 1.3.1 =
* handle images stored on other domains -- props to [ka-ri-ne](http://wordpress.org/support/profile/ka-ri-ne) for the fix
* avoid time-out errors when working with larger files -- props to [Milan Dinić](http://wordpress.org/support/profile/dimadin) for the fix

= 1.2.10 =
* removed testing link

= 1.2.9 =
* updated Smush.it endpoint URL

= 1.2.8 =
* fixed path checking on Windows servers

= 1.2.7 =
* update to workaround WordPress's new JSON compat layer (see [trac ticket](http://core.trac.wordpress.org/ticket/11827))

= 1.2.6 =
* updated Smush.it endpoint URL
* fixed undefined constant

= 1.2.5 =
* updated Smush.it endpoint URL

= 1.2.4 =
* removed debugging code that was interfering with the Flash uploader

= 1.2.3 =
* bug fix

= 1.2.2 =
* updated to use Yahoo! hosted Smush.it service
* added security checks to files passed to `wp_smushit()`

= 1.2.1 =
* added support for PHP 4
* created admin action hook as workaround to WordPress 2.9's `$_registered_pages` security (see [changeset 11596](http://core.trac.wordpress.org/changeset/11596))
* add savings amount in bytes to Media Library (thx [Yoast](http://www.yoast.com/))

= 1.2 =
* added support for `WP_Http`

= 1.1.3 =
* fixed activation error when the PEAR JSON library is already loaded

= 1.1.2 =
* added test for `allow_url_fopen`

= 1.1.1 =
* added error message on PHP copy error

= 1.1 =
* improved handling of errors from Smush.it
* added ability to manually smush images from media library
* fixed inconsistent path handling from WP 2.5 -> WP 2.7

= 1.0.2 =
* added 'Not processed' status message when browsing media library

= 1.0.1 =
* added i10n functions

= 1.0 =
* first edition

== About Us ==
WPMU DEV is a premium supplier of quality WordPress plugins and themes. For premium support with any WordPress related issues you can join us here:
<a href="https://premium.wpmudev.org/?utm_source=wordpress.org&utm_medium=readme">https://premium.wpmudev.org/</a>

Don't forget to stay up to date on everything WordPress from the Internet's number one resource:
<a href="https://premium.wpmudev.org/blog/?utm_source=wordpress.org&utm_medium=readme">WPMU DEV Blog</a>

Hey, one more thing... we hope you <a href="http://profiles.wordpress.org/WPMUDEV/">enjoy our free offerings</a> as much as we've loved making them for you!

== Contact and Credits ==

Originally written by Alex Dunae at Dialect ([dialect.ca](http://dialect.ca/?wp_smush_it), e-mail 'alex' at 'dialect dot ca'), 2008-11.