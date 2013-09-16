Theme Name: Fiver
Description: Custom 21st Century HTML5 and CSS 3 theme with no images!
Author: Stinkyink
Version: 2.2.7
Tags: black, white, light, one-column, two-columns, fixed-width, custom-background, sticky-post, threaded-commentsLicense: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Change Log ==

2.2.7 	- Link attribution removed from footer

2.2.6 	- Killed invisible files

2.2.5   - Replace add_custom_background() with add_theme_support('custom-background') ready for WP 3.4

2.2.4   - Tweaked selector for .clearfix as it was interfering with the Disqus plugin

2.2.3 	- Removed unnecessary old code from comments.php
 	      - Made slight adjustment to function name in functions.php to increase compatibility
	      - Tweaked fiver_has_sidebar based on recommendation from chipbennett

2.2.2  	- Removed wp_register_script from functions.php for the html5 shiv as it was causing errors to appear in WP 3.3
 	      - Added a CSS tweak to remove borders around images in the nav and header (user request)

2.2.1 	- Replaced get_bloginfo(template_directory) with get_template_directory_uri() in header.php

2.2 	  - Added some more tags to help people find the theme on WordPress.org
	      - Added function to allow you to switch between a one or two column layout simply by removing the widgets (aka, "The Magic Sidebar")

2.1.3 	- Removed annoying __MACOSX directory

2.1.2 	- Changed font sizes to consistently use em's instead of a mixture of em's and px. Improved search results layout.

2.1.1 	- Improved support for HTML5 elements in older browsers, stops IE7 derping because it doesn't understand the tags.

2.1 	  - Added support for WordPress Custom Background functions allowing users to customise background colour / images and converted some solid colours used on borders etc to rgba to better work on coloured background. Need to suss out how to get foreground colours looking better if for instance, the user sets their background as black.

2.0 	  - Bug fix for users of lightbox plugins
