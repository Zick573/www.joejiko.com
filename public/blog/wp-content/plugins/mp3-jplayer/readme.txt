=== MP3-jPlayer ===
Author URI: http://www.sjward.org
Plugin URI: http://www.sjward.org/jplayer-for-wordpress
Contributors: simon.ward
Donate link: http://www.sjward.org/jplayer-for-wordpress
Tags: mp3, mp3 player, music player, audio, audio player, jplayer, playlist, jquery, shortcode, widget, css, post, page, sidebar, html5 
Requires at least: 2.8
Tested up to: 3.6
Stable tag: 1.8.4


Add mp3 audio players to posts, pages, and sidebars. HTML5 / Flash. Uses jPlayer.

== Description ==

- Flexible multi-player plugin.
- Playlist and single-file players.
- Pop-out player.
- Individual control of height, width, volume, download etc.
- Customise the colour scheme on the settings page.
- Uses a single instance of [jPlayer by Happyworm](http://jplayer.org/)
- Good compatibility across browsers/platforms. Works on iPhone 4, iPad. Uses HTML 5 or Flash if necessary.
- Editable player designs via CSS.
- Multisite compatible.

[View Demo here](http://sjward.org/jplayer-for-wordpress)

<br />
This plugin lets you add mp3 players to your site using shortcodes, widgets, link replacement, and template tags. There's useful stuff on the settings page such as default folder setting, mp3 file lists, and plenty of shortcode parameters to control things like width, height, autoplay, volume etc. 

You can play entire folders with one simple command, or the library, or make playlists track by track, randomise them, add titles and captions (or use the library ones), set playlists for download, hide your urls.

Widgets and tags can automatically pick up your track lists from posts/pages, or have their own playlists.

As only the one instance of jPlayer is created there's no loss of performance or speed however many players you put on a page.


<br />
<br />
**Shortcodes - Basic usage**

[mp3j] and [mp3t] add single-track players

<br />
eg. Play a url:
<code>[mp3j track="www.site.com/tune.mp3"]</code>

<br />
eg. Play a library or default folder mp3:
<code>[mp3j track="myfile.mp3"]</code>

<br />
eg. Play track 30 from custom-fields playlist/folder:
<code>[mp3j track="30"]</code>

<br />
eg Play incrementally from custom-fields playlist/folder:
<code>[mp3j]</code>

<br /><br />

[mp3-jplayer] adds playlist players

eg. Play files, url's, folders:
<code>[mp3-jplayer tracks="file.mp3, url, FEED:/myfolder"]</code>

<br />
eg. Play custom fields and shuffle them:
<code>[mp3-jplayer shuffle="y"]</code>

<br />
eg. Play 7 random library mp3's:
<code>[mp3-jplayer pick="7" tracks="FEED:LIB"]</code>
<br />

Other examples:

<code>[mp3t vol="70" loop="y" track="myfile.mp3"]</code>

<code>[mp3-jplayer width="30%" height="80px" autoplay="y" tracks="FEED:DF"]</code>

<code>[mp3j flip="y"]</code>

<br />
Please see the help on the plugin's settings page for more info and a full list of parameters.


== Installation ==

Install using WordPress:

1. Log in and go to 'plugins' -> 'Add New'.
3. Search for 'mp3-jplayer' and hit the 'Install now' link in the results, Wordpress will install it.
4. Activate the plugin.

Install manually:

1. Download the zip file and unzip it. 
2. Open the unzipped folder and upload the entire contents (1 folder and it's files and subfolders) to your `/wp-content/plugins` directory on the server.
3. Activate the plugin through the WordPress 'Plugins' menu.


== Frequently Asked Questions ==

= Supported file formats? =
Just mp3 files.

= Theme requirements? =
Themes need the wp_head() and wp_footer() calls in them.

= Mp3 encoding? =
Mp3's should be constant bit-rate (CBR) encoded at sample rates 44.1kHz, 22.05 kHz, 11.025 kHz, though variable bit-rate (VBR) files seem to work ok.

= Player says connecting but never plays? =
Check the filename spelling and the path/uri are correct. Remove any accented letters from mp3 filenames (and re-upload if they're from the library). Check the mp3 encoding (see above).

= Player just doesn't show up? =
This will happen if the playlist you've asked for doesn't result in anything to play, for example if you're using 'FEED' and the folder path is remote, or if you're playing remote files and the option 'allow mp3s from other domains' is unticked.

= Header and footer players? =
Use widget areas (if available), or use the mp3j_addscripts() and mp3j_put() functions in template files. See help in the plugin for an example.

= Player appears but something is broken? =
Probably a javascript conflict, often a hard-coded script in a theme. check your page source from the browser (CTRL+U) for repeated inclusions of both jQuery and jQuery-UI. 

= Report bugs/issues? =
Either on the forum at Wordpress, or [here](http://sjward.org/contact).


== Screenshots ==

1. Playlist player examples
2. A popout player example
3. Single players and playlist player examples 
4. The admin settings page
5. Colour picker opened on the settings page.



== Changelog ==

= 1.8.4 =
* Updated jQuery.jPlayer to 2.3.0 (security fixes).

= 1.8.3 =
* Moved to jQuery.jPlayer 2.2.0 (fixes plugin problems with recent flash release (v11.6) in browsers like IE and Firefox (time was displaying as 'NaN', tracks not advancing/autoplaying)).
* Fixed the auto number option for arbitrary single players (they were all numbered 1!).
* Fixed quotes in captions (they were unescaped still and would break players), thanks to Chris for reporting.
* Fixed a couple of routines that could throw php warnings, thanks to Rami for reporting.
* Added the much requested option to try force browsers into saving mp3 downloads (instead of playing them in some kinda built-in player). Maintains right click save-ability. No mobile support just yet. Switched on for local files by default. Can also be set up for remote files (see the help). Option is under 'Playlist player options' on the settings page. Please feedback any issues.
* Added option to turn any mp3 links in a page into players, which means you can now add players using the 'Add media' button on the page/post edit screens. It has as an editable shortcode on the player settings page (under template options). Option is on by default. Switch it off near top of settings page.  
* Added the 'style' parameter onto the MP3j-ui widget.

= 1.8.1 =
* Some css corrections - missing image for the buttons on the 'custom' style, and the smaller font sizes when using the 'mods' option. 

= 1.8 =
* Fixed bug in javascript that caused problems in WordPress 3.5 (players broke after a couple of clicks).
* Fixed bug when single quotes ended up in a popout title (it broke players).
* Fixed bug in widget when it was set with a non-existent page id (it broke players).
* Fixed bug with mp3j_put function (it could pick up the adjacent post's tracks in some scenarios).  
* Fixed bug with https urls.
* Fixed bug in pick parameter.
* Fixed bug with 'Allow mp3s from other domains' option (it affected single players when it was unticked).
* Fixed display of hours on long mp3s (player will display the hours only when needed).
* Fixed css that was hiding playlists in Opera browser.
* Fixed titles running into captions.
* Fixed titles obscuring slider motion (not IE proof). 
* Added 'images' parameter on [mp3-jplayer], they can be set per track and are carried to the popout.
* Added easier styling option via a 'style' parameter that can be used in shortcodes (takes class names separated by spaces). Some classes are included as follows: bigger1 bigger2 bigger3 bigger4 bigger5 smaller outline dark text bars100 bars150 bars200 bars250 nolistbutton nopopoutbutton nostop nopn wtransbars btransbars. See examples on the demo page.
* Added new download option 'loggedin' which shows alternative text/link if visitor is not logged in.
* Added shortcode [mp3-popout] which creates a link to a popout player.
* Added volume slider option and shortcode parameter for [mp3t] and [mp3j] players.
* Added order control of library mp3s (when using 'FEED:LIB'), options are (asc/desc) by upload date, title, filename, or caption/filename, this is a global setting (not per player).
* Added new shortcode parameter (fsort="desc") for reversing folder playlist order.
* Added option to run player shortcodes in manually written excerpts.
* Added template tag - mp3j_div() for use in theme files when using players in hidden/collapsable tabs, lightboxes etc (allows players to function ok in hidden elements if flash gets used).
* Added option to bypass jQuery / jQueryUI script requests.
* Added choice of separators to use when writing playlists in shortcodes/widgets.
* Added touchpunch.js for useable sliders on touch screen devices. 
* Many more improvements and minor fixes.

= 1.7.3 =
* Stopped files of audio/mpeg MIME type other than mp3 from showing on the player's library file list on the settings page. They won't appear in playlists when using 'FEED:LIB' now.  
* Corrected graphics error introduced last update on the popout button, thanks to Peter for reporting.

= 1.7.2 =
* Fixed bug in the case where sidebars_widgets array was not defined (was throwing a php warning), thanks to Craig for reporting.
* Fixed bug on search pages where full post content was being used (players in posts were breaking unless a player widget was present), thanks to Marco for reporting.
* Fixed loop parameter in single players (wasn't responding to 'n' or '0'). Thanks to George for reporting.
* Corrected the template tag handling so that it can auto pick-up mp3's from post fields on index/archive/search pages. 
* Fixed the 'text' player's colour pickup for the popout, and refined it's layout a little.
* Changed from using depreciated wp user-levels to capabilities for options page setup (was throwing a wp_debug warning).
* Corrected typos in the plugin help (invasion of capitalised L's).

= 1.7.1 =
* Fixed widgets on search pages, and added 'search' as an include/exclude value for the page filter. Thanks to Flavio for reporting.
* Fixed pick-up of default colours when using template tags, and the indicator on single players.

= 1.7 =
* Added multiple players ability, backwards compatible (see notes below).
* Added single-file players.
* Added pop-out.
* Added colour picker to settings.
* Added player width and height settings, captions (or titles) will word-wrap.
* Added shortcodes widget.
* Updated jQuery UI and fixed script enqueuing.
* Fixed page filter for widget, added index and archive options.
* Changed ul transport to div (for better stability across themes).
* General improvements and bug fixes.
* NOTE 1: File extensions must be used (previously it was optional).
* NOTE 2: Shortcodes are needed to add players within the content (previously it was optional). 
* NOTE 3: CSS has changed (id's changed to classes, most renamed), old sheets won't work without modification.

= 1.4.3 =
* Fixed player buttons for Modularity Lite and Portfolio Press themes (they were disappearing / misaligned when player was in sidebar), thanks to Nate, Jeppe, and Nicklas for the reports.
* Fixed the bug in stylesheet loading when using the mp3j_addscripts() template tag (style was not being loaded in some cases), thanks to biggordonlips for reporting. 

= 1.4.2 =
* Fixed error in the scripts handling for the widget, thanks to Kathy for reporting.
* Fixed the non-showing library captions when using widget modes 2/3 to play library files.
* Fixed (hopefully) the mis-aligned buttons that were still happening in some themes.

= 1.4.1 =
* Added a repeat play option on settings page.
* Fixed text-player buttons css in Opera.
* Fixed initial-volume setting error where only the slider was being set and not the volume. Thanks to Darkwave for reporting.

= 1.4.0 =
* Added a widget.
* Improvements to admin including library and default folder mp3 lists, custom stylesheet setting, and some new options.  
* Added new shortcode attributes shuffle, slice, id. New values for list
* Added a way to play whole folders, the entire library, to grab the tracks from another page.
* Added a simpler text-only player style that adopts theme link colours.
* Improved admin help.
* Some minor bug fixes.
* Some minor css improvements and fixes.

= 1.3.4 =
* Added template tags.
* Added new shortcode attributes play and list, and added more values for pos.
* Added new default position options on settings page
* Added a smaller player option

= 1.3.3 =
* Fixed the CSS that caused player to display poorly in some themes.

= 1.3.2 =
* Added the shortcode [mp3-jplayer] and attributes: pos (left, right, none), dload (true, false) which over-ride the admin-panel position and download settings on that post/page. Eg. [mp3-jplayer pos="right" dload="true"]
* Tweaked transport button graphic a wee bit.

= 1.3.1 =
* Fixed image rollover on buttons when wordpress not installed in root of site.

= 1.3.0 =
* First release on Wordpress.org
* Updated jquery.jplayer.min.js to version 1.2.0 (including the new .swf file). The plugin should now work on the iPad.
* Fixed admin side broken display of the uploads folder path that occured when a path had been specified but didn't yet exist.
* Fixed the broken link to the (new) media settings page when running in Wordpress 3.
* Changed the 'Use my media library titles...' option logic to allow any titles or captions to independently over-ride the library by default. The option is now 'Always use my media library titles...' which when ticked will give preference to library titles/captions over those in the custom fields.
* Modified the css for compatibility with Internet Explorer 6. The player should now display almost the same in IE6 as in other browsers.

= 1.2.12 = 
* Added play order setting, a 'download mp3' link option, show/hide playlist and option, a connecting state, a new style.  
* The 'Default folder' option can now be a remote uri to a folder, if it is then it doesn't get filtered from the playists when 'allow remote' is unticked. 

= 1.2.0 =
* Added playing of media library mp3's in the same way as from the default folder (ie. by entering just a filename). User does not have to specify where the tracks reside (recognises library file, default folder file, and local or remote uri's). 
* Added filter option to remove off-site mp3's from the playlists.
* The plugin now clears out it's settings from the database by default upon deactivation. This can be changed from the settings page.
* It's no longer necessary to include the file extension when writing filenames.

= 1.1.0 =
* Added captions, player status info, a-z sort option, basic player positioning, detecting of urls/default folder
* Fixed bug where using unescaped double quotes in a title broke the playlist, quotes are now escaped automatically and can be used.

= 1.0 =
* First release


== Upgrade Notice ==
This is a security maintenance release, please update if you're running an older version of this plugin. 
