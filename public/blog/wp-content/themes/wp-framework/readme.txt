=== WP Framework ===
Contributors: ptahdunbar
Requires at least: 3.0.0
Tested up to: 3.1.x
Stable tag: 0.3.6
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=78N8DWS3BTE38
Tags: buddypress, theme-options, threaded-comments, sticky-post, translation-ready, microformats, rtl-language-support, editor-style, custom-header, custom-background, custom-menu

A Rapid Theme Development Framework.

== Description ==

WP Framework is a parent theme framework for developing custom WordPress themes from scratch. It's geared for designers and developers to rapidly create WordPress themes without having to rewrite complex features and functionality that are often needed in modern themes. For designers, you'll be able to take advantage of it's custom css grid generator so you can build your layout on a solid grid system. For developers, you'll be able to take advantage of it's theme options API so your clients don't have to mess around with code to customize your theme. Those are just two of the many features you'll find within this powerful theming framework for WordPress.

By default, it comes styled with a default look & feel based off of twentyten, clean well documented markup and code, and some killer features and functionality so you can immediately start focusing on the important features in your project right from the get go.

= Features =
	* Modern default look and feel based off of twentyten (using HTML5/CSS3).
	* Native BuddyPress Support built-in.
	* CSS grid framework for generating custom grid layouts.
	* Theme Options API for managing custom theme features in the Back-end.
	* Class generating functions with device detection.
	* Clean, well-documented markup and codebase.
	* Tons of useful template tags and functionality for theme development.
	* Extend core functionality using PHP classes to override methods.
	* Clean organized folder structure so everything is neatly organized.

= Rapid Theme Development =
"Rapid theme development is the ability to try out an idea with very little commitment or work involved." This is what WP Framework aims to accomplish.

Automatically when you download WP Framework, you can start tweaking the design by editing the master.css file located in the <code>/library/css/</code> folder.

Want to work on the DOM? Me too. WP Framework automatically loads a sample scripts.js file located in <code>/library/js/</code> with jQuery pre-loaded so you can quickly make elements fly off the page using your jQuery wizdary skillz.

Control everything from <strong>custom-functions.php</strong> where all the theme's logic resides.

= Credits =

WP Framework started out as a fork from some of the most amazing WordPress themes around at the time.
Without the help, support, ideas and inspiration from those Theme Authors and their work, 
WP Framework wouldn't be what it is today. Please check out their projects and support them in anyway. Thanks!

*	The k2 Crew <http://getk2.com>
*	Scott Wallick <http://plaintxt.org/themes/sandbox>
*	Ian Stewart <http://themeshaper.com/thematic-for-wordpress>
*	Ben Eastaugh and Chris Sternal-Johnson <http://tarskitheme.com>
*	Justin Tadlock <http://themehybrid.com>
*	Chris Pearson <http://diythemes.com>
*	Alex King <http://carringtontheme.com>

== Installation ==

= From your WordPress Admin =
1.  Download a copy of WP Framework from the [wordpress.org](http://wordpress.org/extend/themes/wp-framework/) repository.
2. 	Go to Apperarance -> Themes and click on Install Themes.
3.  Click on "Upload" and and upload the wp-framework.zip file to your site.
4.  That's it! Get started building your theme!

= Via FTP =

1.  Download a copy of WP Framework from the [wordpress.org](http://wordpress.org/extend/themes/wp-framework/) repository.
2.  Extract the archive then upload the /wp-framework/ folder into your 'wp-content/themes' directory using your favorite FTP program.
3.  Login to your WordPress Admin and navigate to the Appearance Menu
4.  Select WP Framework then Activate it.
5.  That's it! Get started building your theme!

= Subversion =

Using the command line, navigate into your 'wp-content/themes' directory, then copy & paste
the following line:

svn co http://themes.svn.wordpress.org/wp-framework/0.3.6 wp-framework

1.  Login to your WordPress Admin and navigate to the Apperance Tab
2.  Select WP Framework then Activate it.
3.  That's it! Get started building your theme!

== Getting Started ==

Here's some quick tips and best practices to keep in mind during theme development:

While WP Framework allows you to make direct changes to the HTML code, please be aware that it may change in a future release. So to make sure you're getting the most out of WP Framework, it's recommended to only use child themes for customizations so you'll your themes will take advantage of newer updates and improvements to the codebase.

* Always use <code>get_template_part()</code> in your templates when loading php files in order to take advantage of child theme inheritance. Also, when linking to your css and js files, use <code>get_theme_part()</code> so your child themes can inherit those files too!

= Useful Constants =

Here are a few constants to use throughout your template files:
*	<code>PARENT_THEME_DIR</code> - alias to <code>TEMPLATEPATH</code>
*	<code>PARENT_THEME_URI</code> - alias to <code>get_template_directory_uri()</code>
*	<code>CHILD_THEME_DIR</code> - alias to <code>STYLESHEETPATH</code>
*	<code>CHILD_THEME_URI</code> - alias to <code>get_stylesheet_directory_uri()</code>

Getting the relative paths for theme assets:
*	<code>THEME_LIBRARY</code> - returns path to the library directory. defaults to library/
*	<code>THEME_I18N</code> - returns path to the languages directory. defaults to library/languages/
*	<code>THEME_FUNC</code> - returns path to the functions directory. defaults to library/functions/
*	<code>THEME_IMG</code> - returns path to the images directory. defaults to library/images/
*	<code>THEME_CSS</code> - returns path to the css directory. defaults to library/css/
*	<code>THEME_JS</code> - returns path to the js directory. defaults to library/js/

= How to use <code>get_theme_part()</code> =

Similar to <code>get_template_part()</code> works, but works for non-php files also.

Getting the url path to a file:
<pre>
<?php echo get_theme_part( THEME_CSS . '/master.css' ); ?>
// Echos http://example.com/wp-content/themes/wp-framework/library/css/master.css
</pre>

Getting the file path to a file:
<pre>
<?php echo get_theme_part( THEME_FUNC . '/custom-widgets.php', 'file' ); ?>
// Echos /Users/ptah/Sites/wordpress/wp.dev/wp-content/themes/wp-framework/library/functions/custom-widgets.php
</pre>

= Internationalization in your Theme =

In order to make sure your theme is accessible for non-english speakers, you'll need to make sure any static text used throughout your theme is internationalized.

Example:

<pre>
<h1 class="entry-title">This is somewhat embarrassing, isn&rsquo;t it?</h1>
</pre>

Convert that to:

<pre>
<h1 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', t() ); ?></h1>
</pre>

If you have a function, here's how that works:

<pre>
function foobar() {
	return 'I like my pizza with cake';
}
</pre>

<pre>
function foobar() {
	return __( 'I like my pizza with cake', t() );
}
</pre>

The two main functions are <code>_e();</code> and <code>__();</code> where the first function echos the string where as the latter returns the string. Both functions take a second parameter which is the text domain. WP Framework uses the function <code>t()</code> to return that value.

Those are the basic functions you'll need to remember. To learn more about i18n, check out the [codex](http://codex.wordpress.org/I18n_for_WordPress_Developers) article.

== Changelog ==

= 0.1 =
Initial Release

= 0.2 =
Initial Release

= 0.2.4 =
Minor Release

= 0.2.4.1 =
Bug fixes and improvements

= 0.2.4.2 =
Bug fixes and improvements

= 0.2.4.3 =
Bug fixes and improvements

= 0.2.4.4 =
Bug fixes and improvements

= 0.2.4.5 =
Bug fixes and improvements

= 0.2.4.6 =
Bug fixes and improvements

= 0.2.4.7 =
Bug fixes and improvements

= 0.2.4.8 =
Bug fixes and improvements

= 0.2.4.9 =
Bug fixes and improvements

= 0.2.4.10 =
Bug fixes and improvements

= 0.3.0 =
Major updates and changes

= 0.3.1 =
Bug fixes and improvements

= 0.3.2 =
Bug fixes and improvements

= 0.3.3 =
Bug fixes and improvements

= 0.3.4 =
Bug fixes and improvements

= 0.3.5 =
Bug fixes and improvements

= 0.3.6 =
Bug fixes and improvements

== Upgrade Notice ==

Do not edit the <code>framework</code> folder. That is all.

== TODO ==

Things that I'd like to add in a future release of WP Framework.

*	add .dev stylesheets and scripts support
*	add custom widgets to enhance and/or add new features
*	add default images for: pingback/trackback comments
*	better support for attachments template/attachment display
*	add shortcodes for nav-menu & other useful features