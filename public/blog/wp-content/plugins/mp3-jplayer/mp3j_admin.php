<?php
		function mp3j_print_admin_page() { 
			
			global $mp3_fox;
			$theOptions = $mp3_fox->getAdminOptions();
			$colours_array = array();
			
			if (isset($_POST['update_mp3foxSettings']))
			{
				if (isset($_POST['mp3foxVol'])) {
					$theOptions['initial_vol'] = preg_replace("/[^0-9]/", "", $_POST['mp3foxVol']); 
					if ($theOptions['initial_vol'] < 0 || $theOptions['initial_vol']=="") { $theOptions['initial_vol'] = "0"; }
					if ($theOptions['initial_vol'] > 100) { $theOptions['initial_vol'] = "100"; }
				}
				if (isset($_POST['mp3foxPopoutMaxHeight'])) {
					$theOptions['popout_max_height'] = preg_replace("/[^0-9]/", "", $_POST['mp3foxPopoutMaxHeight']); 
					if ( $theOptions['popout_max_height'] == "" ) { $theOptions['popout_max_height'] = "750"; }
					if ( $theOptions['popout_max_height'] < 200 ) { $theOptions['popout_max_height'] = "200"; }
					if ( $theOptions['popout_max_height'] > 1200 ) { $theOptions['popout_max_height'] = "1200"; }
				}
				if (isset($_POST['mp3foxPopoutWidth'])) {
					$theOptions['popout_width'] = preg_replace("/[^0-9]/", "", $_POST['mp3foxPopoutWidth']); 
					if ( $theOptions['popout_width'] == "" ) { $theOptions['popout_width'] = "400"; }
					if ( $theOptions['popout_width'] < 250 ) { $theOptions['popout_width'] = "250"; }
					if ( $theOptions['popout_width'] > 1600 ) { $theOptions['popout_width'] = "1600"; }
				}
				if (isset($_POST['mp3foxMaxListHeight'])) {
					$theOptions['max_list_height'] = preg_replace("/[^0-9]/", "", $_POST['mp3foxMaxListHeight']); 
					if ( $theOptions['max_list_height'] < 0 ) { $theOptions['max_list_height'] = ""; }
				}
				
				if (isset($_POST['mp3foxfolder'])) { $theOptions['mp3_dir'] = $mp3_fox->prep_path( $_POST['mp3foxfolder'] ); }
				if (isset($_POST['mp3foxCustomStylesheet'])) { $theOptions['custom_stylesheet'] = $mp3_fox->prep_path( $_POST['mp3foxCustomStylesheet'] ); }
				if (isset($_POST['mp3foxTheme'])) { $theOptions['player_theme'] = $_POST['mp3foxTheme']; }			
				if (isset($_POST['mp3foxFloat'])) { $theOptions['player_float'] = $_POST['mp3foxFloat']; }
				if (isset($_POST['mp3foxPlayerWidth'])) { $theOptions['player_width'] = $_POST['mp3foxPlayerWidth']; }
				if (isset($_POST['mp3foxPopoutBackground'])) { $theOptions['popout_background'] = $_POST['mp3foxPopoutBackground']; }
				if (isset($_POST['mp3foxPopoutBGimage'])) { $theOptions['popout_background_image'] = $_POST['mp3foxPopoutBGimage']; }
				if (isset($_POST['mp3foxPluginVersion'])) { $theOptions['db_plugin_version'] = $_POST['mp3foxPluginVersion']; }
				if (isset($_POST['mp3foxPopoutButtonText'])) { $theOptions['popout_button_title'] = $_POST['mp3foxPopoutButtonText']; }
				if (isset($_POST['librarySortcol'])) { $theOptions['library_sortcol'] = $_POST['librarySortcol']; }
				if (isset($_POST['libraryDirection'])) { $theOptions['library_direction'] = $_POST['libraryDirection']; }
				if (isset($_POST['disableJSlibs'])) { $theOptions['disable_jquery_libs'] = ( preg_match("/^yes$/i", $_POST['disableJSlibs']) ) ? "yes" : ""; }
				if (isset($_POST['MtogBox1'])) { $theOptions['admin_toggle_1'] = $_POST['MtogBox1']; }
				if (isset($_POST['file_separator'])) { $theOptions['f_separator'] = $_POST['file_separator']; }
				if (isset($_POST['caption_separator'])) { $theOptions['c_separator'] = $_POST['caption_separator']; }
				
				
				$theOptions['paddings_top'] = ( $_POST['mp3foxPaddings_top'] == "" ) ? "0px" : $_POST['mp3foxPaddings_top'];
				$theOptions['paddings_bottom'] = ( $_POST['mp3foxPaddings_bottom'] == "" ) ? "0px" : $_POST['mp3foxPaddings_bottom'];
				$theOptions['paddings_inner'] = ( $_POST['mp3foxPaddings_inner'] == "" ) ? "0px" : $_POST['mp3foxPaddings_inner'];
				$theOptions['auto_play'] = (isset($_POST['mp3foxAutoplay'])) ? $_POST['mp3foxAutoplay'] : "false";
				$theOptions['allow_remoteMp3'] = (isset($_POST['mp3foxAllowRemote'])) ? $_POST['mp3foxAllowRemote'] : "false";
				//$theOptions['playlist_AtoZ'] = (isset($_POST['mp3foxAtoZ'])) ? $_POST['mp3foxAtoZ'] : "false";
				$theOptions['player_onblog'] = (isset($_POST['mp3foxOnBlog'])) ? $_POST['mp3foxOnBlog'] : "false";
				//$theOptions['playlist_UseLibrary'] = (isset($_POST['mp3foxUseLibrary'])) ? $_POST['mp3foxUseLibrary'] : "false";
				$theOptions['playlist_show'] = (isset($_POST['mp3foxShowPlaylist'])) ? $_POST['mp3foxShowPlaylist'] : "false";
				$theOptions['remember_settings'] = (isset($_POST['mp3foxRemember'])) ? $_POST['mp3foxRemember'] : "false";
				$theOptions['hide_mp3extension'] = (isset($_POST['mp3foxHideExtension'])) ? $_POST['mp3foxHideExtension'] : "false";
				
				
				//$theOptions['show_downloadmp3'] = (isset($_POST['mp3foxDownloadMp3'])) ? $_POST['mp3foxDownloadMp3'] : "false";
				if (isset($_POST['mp3foxDownloadMp3'])) { $theOptions['show_downloadmp3'] = $_POST['mp3foxDownloadMp3']; }
				$theOptions['dload_text'] = ( $_POST['dload_text'] == "" ) ? "DOWNLOAD MP3" : $_POST['dload_text'];
				
				//$theOptions['loggedout_dload_text'] = ( $_POST['loggedout_dload_text'] == "" ) ? "LOG IN TO DOWNLOAD!" : $_POST['loggedout_dload_text'];
				$theOptions['loggedout_dload_text'] = ( $_POST['loggedout_dload_text'] == "" ) ? "" : $_POST['loggedout_dload_text'];
				$theOptions['loggedout_dload_link'] = ( $_POST['loggedout_dload_link'] == "" ) ? "" : $_POST['loggedout_dload_link']; //allow it to be empty
				
				$theOptions['disable_template_tag'] = (isset($_POST['disableTemplateTag'])) ? $_POST['disableTemplateTag'] : "false";
				$theOptions['echo_debug'] = (isset($_POST['mp3foxEchoDebug'])) ? $_POST['mp3foxEchoDebug'] : "false";
				$theOptions['add_track_numbering'] = (isset($_POST['mp3foxAddTrackNumbers'])) ? $_POST['mp3foxAddTrackNumbers'] : "false";
				$theOptions['enable_popout'] = (isset($_POST['mp3foxEnablePopout'])) ? $_POST['mp3foxEnablePopout'] : "false";
				$theOptions['playlist_repeat'] = (isset($_POST['mp3foxPlaylistRepeat'])) ? $_POST['mp3foxPlaylistRepeat'] : "false";
				$theOptions['use_fixed_css'] = (isset($_POST['mp3foxUseFixedCSS'])) ? $_POST['mp3foxUseFixedCSS'] : "false";
				$theOptions['encode_files'] = (isset($_POST['mp3foxEncodeFiles'])) ? $_POST['mp3foxEncodeFiles'] : "false";
				$theOptions['animate_sliders'] = (isset($_POST['mp3foxAnimSliders'])) ? $_POST['mp3foxAnimSliders'] : "false";
				$theOptions['run_shcode_in_excerpt'] = (isset($_POST['runShcodeInExcerpt'])) ? $_POST['runShcodeInExcerpt'] : "false";
				$theOptions['volslider_on_singles'] = (isset($_POST['volslider_onsingles'])) ? $_POST['volslider_onsingles'] : "false";
				$theOptions['volslider_on_mp3j'] = (isset($_POST['volslider_onmp3j'])) ? $_POST['volslider_onmp3j'] : "false";
				$theOptions['touch_punch_js'] = (isset($_POST['touch_punch_js'])) ? $_POST['touch_punch_js'] : "false";
				
				$theOptions['force_browser_dload'] = (isset($_POST['force_browser_dload'])) ? $_POST['force_browser_dload'] : "false";
				$theOptions['make_player_from_link'] = (isset($_POST['make_player_from_link'])) ? $_POST['make_player_from_link'] : "false";
				if (isset($_POST['make_player_from_link_shcode'])) { $theOptions['make_player_from_link_shcode'] = $_POST['make_player_from_link_shcode']; }
				
				//if (isset($_POST['force_browser_dload_remote'])) { $theOptions['force_browser_dload_remote'] = $_POST['force_browser_dload_remote']; }
				
				$theOptions['dloader_remote_path'] = (isset($_POST['dloader_remote_path'])) ? $_POST['dloader_remote_path'] : "";
				
				// Colours array//
				if (isset($_POST['mp3foxScreenColour'])) { $colours_array['screen_colour'] = $_POST['mp3foxScreenColour']; }
				if (isset($_POST['mp3foxScreenOpac'])) { $colours_array['screen_opacity'] = $_POST['mp3foxScreenOpac']; }
				if (isset($_POST['mp3foxLoadbarColour'])) { $colours_array['loadbar_colour'] = $_POST['mp3foxLoadbarColour']; }
				if (isset($_POST['mp3foxLoadbarOpac'])) { $colours_array['loadbar_opacity'] = $_POST['mp3foxLoadbarOpac']; }
				if (isset($_POST['mp3foxPosbarColour'])) { $colours_array['posbar_colour'] = $_POST['mp3foxPosbarColour']; }
				if (isset($_POST['mp3foxPosbarTint'])) { $colours_array['posbar_tint'] = $_POST['mp3foxPosbarTint']; }
				if (isset($_POST['mp3foxPosbarOpac'])) { $colours_array['posbar_opacity'] = $_POST['mp3foxPosbarOpac']; }
				if (isset($_POST['mp3foxScreenTextColour'])) { $colours_array['screen_text_colour'] = $_POST['mp3foxScreenTextColour']; }
				if (isset($_POST['mp3foxPlaylistColour'])) { $colours_array['playlist_colour'] = $_POST['mp3foxPlaylistColour']; }
				if (isset($_POST['mp3foxPlaylistTint'])) { $colours_array['playlist_tint'] = $_POST['mp3foxPlaylistTint']; }
				if (isset($_POST['mp3foxPlaylistOpac'])) { $colours_array['playlist_opacity'] = $_POST['mp3foxPlaylistOpac']; }
				if (isset($_POST['mp3foxListTextColour'])) { $colours_array['list_text_colour'] = $_POST['mp3foxListTextColour']; }
				if (isset($_POST['mp3foxListCurrentColour'])) { $colours_array['list_current_colour'] = $_POST['mp3foxListCurrentColour']; }
				if (isset($_POST['mp3foxListHoverColour'])) { $colours_array['list_hover_colour'] = $_POST['mp3foxListHoverColour']; }
				if (isset($_POST['mp3foxListBGaHover'])) { $colours_array['listBGa_hover'] = $_POST['mp3foxListBGaHover']; }
				if (isset($_POST['mp3foxListBGaCurrent'])) { $colours_array['listBGa_current'] = $_POST['mp3foxListBGaCurrent']; }
				if (isset($_POST['mp3foxVolGrad'])) { $colours_array['volume_grad'] = $_POST['mp3foxVolGrad']; }
				if (isset($_POST['mp3foxListDivider'])) { $colours_array['list_divider'] = $_POST['mp3foxListDivider']; }
				if (isset($_POST['mp3foxIndicator'])) { $colours_array['indicator'] = $_POST['mp3foxIndicator']; }
				$theOptions['colour_settings'] = $colours_array;
				
				update_option($mp3_fox->adminOptionsName, $theOptions);
				$mp3_fox->theSettings = $theOptions;
			?>
				<!-- Settings saved message -->
				<div class="updated"><p><strong><?php _e("Settings Updated.", $mp3_fox->textdomain );?></strong></p></div>
			
			<?php 
			}
			// Pick up current colours
			$current_colours = $theOptions['colour_settings'];
			?>
			
			<div class="wrap">
				<h2>&nbsp;</h2>
				<h1>Mp3<span style="font-size:16px;"> - </span>jPlayer<span class="description" style="font-size:10px;">&nbsp; <?php echo $mp3_fox->version_of_plugin; ?></span> &nbsp;&nbsp;
					<?php if ( $theOptions['disable_jquery_libs'] == "yes" ) { ?><span style="font-size: 11px; font-weight:700; color:#f66;">(jQuery and UI scripts are turned off)</span><?php } ?></h1>

				<h5 style="margin: 0 0 30px 0; padding:0; font-size:10px;">
					<a href="javascript:MP3J_ADMIN.toggleit('tog_0','HELP');" id="tog_0-toggle" class="fox_buttonlink">HELP</a>&nbsp;<a href="widgets.php" class="fox_buttonlink">WIDGETS</a>
				</h5>
				
				<div id="tog_0-list" style="border-bottom:1px solid #ccc; margin-bottom:25px;">
							
							<p>Add players using <code>[mp3j]</code> <code>[mp3t]</code> <code>[mp3-jplayer]</code> <code>[mp3-popout]</code> shortcodes, links to mp3s, <a href="widgets.php">widgets</a>, and <a href="<?php echo $mp3_fox->PluginFolder; ?>/template-tag-help.htm">Template Tags</a>.</p>
							
							<br />
							<h3 style="margin-left:0;">Shortcode Parameters</h3>
							<div class="Ahelp1">
								
								<h4><code>[mp3-jplayer]</code> <span class="description">Playlist player</span></h4>
								
								<h5>Parameters:</h5>
								<p><code>tracks</code> <span class="description">filenames/URIs/FEEDs (<code>,</code> separated)</span><br />
									<code>captions</code> <span class="description">caption text (<code>;</code> separated)</span><br />
									<code>vol</code> <span class="description">0 - 100</span><br />
									<code>autoplay</code> <span class="description">y/n</span><br />
									<code>loop</code> <span class="description">y/n</span><br />
									<code>dload</code> <span class="description">y / n / loggedin (show download link)</span><br />
									<code>list</code> <span class="description">y/n (show playlist)</span><br />
									<code>pick</code> <span class="description">a number (picks random selection)</span><br />
									<code>shuffle</code> <span class="description">y/n (shuffle track order)</span><br />
									<code>title</code> <span class="description">text above player</span><br />
									<code>pos</code> <span class="description">rel-L, rel-C, rel-R, left, right</span><br />
									<code>width</code> <span class="description">in px or %</span><br />
									<code>height</code> <span class="description">in px (player height excluding list)</span><br />
									<code>pn</code> <span class="description">y/n (show prev/next buttons)</span><br />
									<code>stop</code> <span class="description">y/n (show stop button)</span><br />
									<code>id</code> <span class="description">a page id (to read the custom fields from)</span><br />
									<code>images</code> <span class="description">comma separated list of track image urls</span><br />
									<!--<code>imglinks</code> <span class="description">comma separated list of arbitrary links</span><br />-->
									<code>fsort</code> <span class="description">asc/desc (folder feed direction)</span><br />
									<code>style</code> <span class="description">modify player style</span> <strong><a href="<?php echo $mp3_fox->PluginFolder; ?>/style-param-help.htm">Help</a></strong></p>
								
							</div>
							
							<div class="Ahelp1">
								<h4><code>[mp3j]</code> &amp; <code>[mp3t]</code> <span class="description">Single-track players</span></h4>
								
								<h5>Parameters:</h5>
								<p><code>track</code> <span class="description">filename or URI</span><br />
									<code>caption</code> <span class="description">caption text (right of title)</span><br />
									<code>vol</code> <span class="description">0 - 100</span><br />
									<code>volslider</code> <span class="description">y/n</span><br />
									<code>autoplay</code> <span class="description">y/n</span><br />
									<code>loop</code> <span class="description">y/n</span><br />
									<code>title</code> <span class="description">text (replaces both title and caption)</span><br />
									<code>bold</code> <span class="description">y/n</span><br />
									<code>flip</code> <span class="description">y/n (play/pause)</span><br />
									<code>ind</code> <span class="description">y/n (hide indicator and time)</span><br />
									<code>flow</code> <span class="description">y/n (don't line break)</span><br />
									<code>style</code> <span class="description">modify player style</span> <strong><a href="<?php echo $mp3_fox->PluginFolder; ?>/style-param-help.htm">Help</a></strong></p>
								
								<h5>Also for <code>[mp3t]</code></h5>
								<p><code>play</code> play button text<br /><code>stop</code> pause button text</p>
								
							</div>
							
							<div class="Ahelp1">
								<h4><code>[mp3-popout]</code> <span class="description">Link to pop-out player</span></h4>
								
								<h5>Parameters:</h5>
								<p><code>tracks</code> <span class="description">files/URIs/FEEDs (<code>,</code> separated)</span><br />
									<code>captions</code> <span class="description">caption text  (<code>;</code> separated)</span><br />
									<code>vol</code> <span class="description">0 - 100</span><br />
									<code>autoplay</code> <span class="description">y / n</span><br />
									<code>loop</code> <span class="description">y / n</span><br />
									<code>dload</code> <span class="description">y / n / loggedin (show download link)</span><br />
									<code>list</code> <span class="description">y/n (show popout playlist)</span><br />
									<code>pick</code> <span class="description">number (pick random selection)</span><br />
									<code>shuffle</code> <span class="description">y/n (shuffle track order)</span><br />
									<code>title</code> <span class="description">title for the popout window</span><br />
									<code>pos</code> <span class="description">rel-L, rel-C, rel-R, left, right (link position)</span><br />
									<code>text</code> <span class="description">text for the link</span><br />
									<code>height</code> <span class="description">px (popout player height excluding it's list)</span><br />
									<code>id</code> <span class="description">a page id (to read the custom fields from)</span><br />
									<code>tag</code> <span class="description">html tag for text (eg. <code>h2</code>, Default is <code>p</code>)</span><br />
									<code>image</code> <span class="description">image for the popout link</span><br />
									<code>images</code> <span class="description">comma separated list of track image urls</span><br />
									<!--<code>imglinks</code> <span class="description">comma separated list of arbitrary links</span><br />-->
									<code>fsort</code> <span class="description">asc/desc (folder feed direction)</span><br />
									<code>style</code> <span class="description">modify player style</span> <strong><a href="<?php echo $mp3_fox->PluginFolder; ?>/style-param-help.htm">Help</a></strong></p>
							</div>
														
							<!-- Not in this release
							<div class="Ahelp1">
								<h5><code>[mp3-link]</code> Play from a playlist player</h5>
								<p><code>player</code> number of the player to operate<br /><code>track</code> the track number<br /><code>text</code> link text, defaults to <code>Play</code><br /><code>bold</code> y/n</p>
							</div>
							-->
							
							<br class="clearB" /><br />
							
							<p><strong>Eg</strong>. <span class="description">Play a url:</span> <code>[mp3j track="http://somedomain.com/myfile.mp3"]</code><br />
								<strong>Eg</strong>. <span class="description">Play a file from default folder (set below) or library:</span> <code>[mp3j track="myfile.mp3"]</code><br />
								<strong>Eg</strong>. <span class="description">Make a playlist player:</span> <code>[mp3-jplayer tracks="fileA.mp3, http://somedomain.com/fileB.mp3, fileC.mp3"]</code><br />
								<strong>Eg</strong>. <span class="description">Add titles:</span> <code>[mp3-jplayer tracks="My Title@fileA.mp3, My Title@fileB.mp3, My Title@fileC.mp3"]</code><br />
								<strong>Eg</strong>. <span class="description">Add captions:</span> <code>[mp3-jplayer tracks="fileA.mp3, fileB.mp3" captions="Caption A; Caption B"]</code></p>
							<p><a href="http://sjward.org/jplayer-for-wordpress" target="_blank">More shortcode examples</a></p>
							<br />
							
							<p><strong>Use these commands with [mp3-jplayer] in the <code>tracks</code> parameter to playlist entire folders or the library:</strong></p>
							<p><code>FEED:LIB</code> playlists all mp3s in the library.<br />
								<code>FEED:DF</code> playlists your default folder.<br />
								<code>FEED:/my/music</code> playlists a folder or subfolder (relative to root of domain, not the WP install)</p>
							<p><strong>Eg</strong>. Play 5 random tracks from the library: <code>[mp3-jplayer tracks="FEED:LIB" pick="5"]</code><br />
								<strong>Eg</strong>. Play everything in the folder called 'tunes': <code>[mp3-jplayer tracks="FEED:/tunes"]</code></p>
								
							<p>The <code>tracks</code> parameter can contain a mix of FEEDs and filenames/urls, eg. <code>[mp3-jplayer tracks="myfileA.mp3, FEED:/tunes, Title@myfileB.mp3, FEED:DF"]</code>.
								When just a filename is used the file must be in either your media library or in the default folder (set on this page). You can also specify a file in a sub 
								directory in the default folder eg. <code>tracks=&quot;subfolder/file.mp3&quot;</code>.</p>
							
							<br />
														
							<h3 style="margin-left:0;">Custom Fields</h3>
							<p>You can write playlists into the custom fields that are on page and post edit screens (check your 'screen options' at top-right 
								if they're not visible). They can be picked up with any of the shortcodes (from any page/post, or with the template tag, or by the widgets). See below for how 
								to set them up and some example uses:</p>
							
							<p class="description">1. Enter <code>mp3</code> into the left hand box (the 'key' box).<br />2. Write the filename, URI, or 'FEED' (see above) into the right hand box (the 'value' box) and hit 'add custom field'</p>
							<p>Add each track or 'FEED' in a new field pair.</p>
							<p>To add titles and captions in the custom fields use the following format:</p>
							<p class="description">1. Add a dot, then the caption in the left hand box, eg: <code>mp3.My Caption</code>
								<br />2. Add the title, then an '@' before the filename in the right box, eg: <code>My Title@filename</code></p>
							<p>The keys (left boxes) can be numbered, eg:<code>1 mp3</code> will be first on the playlist.</p>
							
							<p><strong>Eg</strong>. If a custom field key / value pair is set as <code>mp3</code> / <code>FEED:LIB</code>, then the library is available to any shortcodes, so:<br />
								
								<code>[mp3j]</code> or <code>[mp3t]</code> <span class="description">plays the next track from the library in a single player</span> <br />
								<code>[mp3j track="3"]</code> or <code>[mp3t track="3"]</code> <span class="description">plays track 3 from the library in a single player</span> <br />
								<code>[mp3-jplayer]</code> <span class="description">playlists all the custom fields in a playlist player</span><br /></p>
							
							<br />
							
							
							<h3 style="margin-left:0;">Widgets</h3>
							<p class="description">
								MP3j-sh - <span class="description">Adds players by writing shortcodes.</span><br />
								MP3j-ui - <span class="description">Adds a playlist player by using tick boxes and modes. Note that some features such as manually written captions, and additional style (css classes) cannot be set with this widget, use the mp3j-sh widget instead.</span>
								</p>
							<br />
							<h3 style="margin-left:0;">Template Tags</h3>
							<p class="description">For use in theme files, See <a href="<?php echo $mp3_fox->PluginFolder; ?>/template-tag-help.htm">Template Tag Help</a> for more details.</p>
							<p style="line-height:22px;"><code style="font-size:13px;">mp3j_addscripts( $style )</code><br /><code style="font-size:13px;">mp3j_put( $shortcodes )</code><br /><code style="font-size:13px;">mp3j_grab_library( $format )</code><br/><code style="font-size:13px;">mp3j_debug()</code></p>

							<br />
							
							<h3 style="margin-left:0;">Testing</h3>
							<p><strong>Make sure you're using default plugin settings for these tests.</strong></p>
							<p>You can test the plugin with a valid mp3 by copy/pasting the following shortcode into a page or post:<br />
								<code>[mp3-jplayer tracks="http://sjward.org/seven.mp3"]</code></p>
								
							<p>To test link replacement copy/paste one of these links, if you're not sure which one then paste them both (one of them should work):</p>
							<p>If you use the visual (default) editor: <code><a href="http://sjward.org/seven.mp3">Test link</a></code><br />
							If you use the text editor: <code>&lt;a href="http://sjward.org/seven.mp3"&gt;Test link&lt;/a&gt;</code></p>
				
							<p><br />Links to info:<br /><a href="http://sjward.org/jplayer-for-wordpress">Demo page</a><br /><a href="<?php echo $mp3_fox->PluginFolder; ?>/style-param-help.htm">Style Parameter Help</a><br /><a href="<?php echo $mp3_fox->PluginFolder; ?>/remote/help.txt">Forcing Remote Downloads</a><br /><a href="<?php echo $mp3_fox->PluginFolder; ?>/template-tag-help.htm">Template Tag Help</a></p>
							<br /><br />
				
				</div>
				
				<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
					<p class="jtext">Initial volume &nbsp; <input type="text" style="text-align:center;" size="2" name="mp3foxVol" value="<?php echo $theOptions['initial_vol']; ?>" /> &nbsp; <span class="description">(0 - 100)</span></p>
					<p class="jtick"><input type="checkbox" name="mp3foxAutoplay" value="true" <?php if ($theOptions['auto_play'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Autoplay</p>
					<p class="jtick"><input type="checkbox" name="mp3foxPlaylistRepeat" value="true" <?php if ($theOptions['playlist_repeat'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Loop</p>
					<p class="jtick"><input type="checkbox" name="mp3foxAddTrackNumbers" value="true" <?php if ($theOptions['add_track_numbering'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Number the tracks</p>
					<!--<p class="jtick"><input type="checkbox" name="mp3foxAnimSliders" value="true" <?php //if ($theOptions['animate_sliders'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Animate sliders</p>-->
					
					<br />
					<p class="jtick"><input type="checkbox" name="make_player_from_link" value="true" <?php if ($theOptions['make_player_from_link'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> 
								&nbsp; Turn mp3 links into players</p>
							<p style="margin-left:45px;"><span class="description">(Use the 'Add media' button on the edit screen to add links, or you can manually add/write links into the page. Links will be turned into players using the shortcode specified under 'Template Options' below.)</span></p>
							
					
					<br />
					<p class="jtick"><input type="checkbox" name="mp3foxOnBlog" value="true" <?php if ($theOptions['player_onblog'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Show players in posts on index, archive, and search pages &nbsp;<span class="description">(doesn't affect widgets)</span></p>
					<p class="jtick"><input type="checkbox" name="runShcodeInExcerpt" value="true" <?php if ($theOptions['run_shcode_in_excerpt'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Run shortcodes in post excerpts &nbsp;<span class="description">(this works for manually written post excerpts only)</span></p>
					<br />			
					
			<?php
			$greyout_field = ( $theOptions['player_theme'] != "styleI" ) ? "background:#fcfcfc; color:#d6d6d6; border-color:#f0f0f0;" : "background:#fff; color:#000; border-color:#dfdfdf;";
			$greyout_text = ( $theOptions['player_theme'] != "styleI" ) ? "color:#d6d6d6;" : "color:#444;";
			?>
					<!-- COLOUR / STYLE -->
					<div style="height:35px"><p style="width:55px; margin:0 0 0 20px; line-height:32px;">Players:</p></div> 
					<p style="margin:-35px 0px 0px 75px; line-height:32px;"><select name="mp3foxTheme" id="player-select" style="width:94px; font-size:11px; line-height:19px;">
							<option value="styleF" <?php if ( 'styleF' == $theOptions['player_theme'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Silver</option>
							<option value="styleG" <?php if ( 'styleG' == $theOptions['player_theme'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Dark</option>
							<option value="styleH" <?php if ( 'styleH' == $theOptions['player_theme'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Text</option>
							<option value="styleI" <?php if ( 'styleI' == $theOptions['player_theme'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Custom</option>
						</select>&nbsp;
						<span id="player-csssheet" style=" <?php echo $greyout_text; ?>"> &nbsp;uri:</span><input type="text" id="mp3fcss" style="width:480px; <?php echo $greyout_field; ?>" name="mp3foxCustomStylesheet" value="<?php echo $theOptions['custom_stylesheet']; ?>" /></p>
					
					<?php 
						//$showbox = ( $theOptions['admin_toggle_1'] == "true" ) ? "" : " display:none;";
						//$hide = ( $theOptions['admin_toggle_1'] == "true" ) ? "Hide " : "";
					?>
					
					<p style="margin:4px 0px 15px 20px;"><a href="javascript:MP3J_ADMIN.toggleit('tog_1','Colour Settings');" id="tog_1-toggle" class="fox_buttonlink bl2">Colour Settings</a></p>
					
					<div id="tog_1-list" style="position:relative; margin:0px 0px 15px 20px; width:579px;">
							
							<div style="position:relative; width:579px; height:20px; padding-top:2px; border-top:1px solid #eee; border-bottom:1px solid #eee; background:#f9f9f9;">
								<div style="float:left; width:90px; margin-left:9px;"><p class="description" style="margin:0px;"><strong>AREA</strong></p></div> 
								<div style="float:left; width:390px;"><p class="description" style="margin:0px;">&nbsp;Opacity&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;Colour</p></div>
							</div>
							
							<div style="position:relative; width:579px; padding-top:6px;">
								<div style="float:left; width:90px; margin-left:9px; border:0px solid #aaa;"><p style="margin:0px;line-height:32px;">Screen:<br />Loading bar:<br />Position bar:<br />Playlist:</p></div> 
								<div style="float:left; width:390px; border:0px solid #aaa;">
									<p style="margin:0px;line-height:32px;">
										<input type="text" size="4" name="mp3foxScreenOpac" value="<?php echo $current_colours['screen_opacity']; ?>" />
										&nbsp;&nbsp;<input type="text" id="opA" onkeyup="udfcol('opA','blA');" size="10" name="mp3foxScreenColour" value="<?php echo $current_colours['screen_colour']; ?>" />
										<span class="addcol" onclick="putfcolour('opA','blA');">&nbsp;+&nbsp;</span>
										<span class="bl" onclick="sendfcolour('opA');" id="blA" style="background:<?php echo $current_colours['screen_colour']; ?>;">&nbsp;&nbsp;</span>
										<br />
										<input type="text" size="4" name="mp3foxLoadbarOpac" value="<?php echo $current_colours['loadbar_opacity']; ?>" />
										&nbsp;&nbsp;<input type="text" id="opB" onkeyup="udfcol('opB','blB');" size="10" name="mp3foxLoadbarColour" value="<?php echo $current_colours['loadbar_colour']; ?>" />
										<span class="addcol" onclick="putfcolour('opB','blB');">&nbsp;+&nbsp;</span>
										<span class="bl" onclick="sendfcolour('opB');" id="blB" style="background:<?php echo $current_colours['loadbar_colour']; ?>;">&nbsp;&nbsp;</span>
										<br />
										<input type="text" size="4" name="mp3foxPosbarOpac" value="<?php echo $current_colours['posbar_opacity']; ?>" />
										&nbsp;&nbsp;<input type="text" id="opC" onkeyup="udfcol('opC','blC');" size="10" name="mp3foxPosbarColour" value="<?php echo $current_colours['posbar_colour']; ?>" />
										<span class="addcol" onclick="putfcolour('opC','blC');">&nbsp;+&nbsp;</span>
										<span class="bl" onclick="sendfcolour('opC');" id="blC" style="background:<?php echo $current_colours['posbar_colour']; ?>;">&nbsp;&nbsp;</span>
										&nbsp; &nbsp;<select name="mp3foxPosbarTint" style="width:115px; font-size:11px;">
											<option value="" <?php if ( '' == $current_colours['posbar_tint'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>(default)</option>
											<option value="soften" <?php if ( 'soften' == $current_colours['posbar_tint'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Light grad</option>
											<option value="softenT" <?php if ( 'softenT' == $current_colours['posbar_tint'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Tip</option>
											<option value="darken" <?php if ( 'darken' == $current_colours['posbar_tint'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Dark grad</option>
											<option value="none" <?php if ( 'none' == $current_colours['posbar_tint'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>None</option>
										</select>
										<br />
										<input type="text" size="4" name="mp3foxPlaylistOpac" value="<?php echo $current_colours['playlist_opacity']; ?>" />
										&nbsp;&nbsp;<input type="text" id="opD" onkeyup="udfcol('opD','blD');" size="10" name="mp3foxPlaylistColour" value="<?php echo $current_colours['playlist_colour']; ?>" />
										<span class="addcol" onclick="putfcolour('opD','blD');">&nbsp;+&nbsp;</span>
										<span class="bl" onclick="sendfcolour('opD');" id="blD" style="background:<?php echo $current_colours['playlist_colour']; ?>;">&nbsp;&nbsp;</span>
										&nbsp; &nbsp;<select name="mp3foxPlaylistTint" style="width:115px; font-size:11px;">
											<option value="" <?php if ( '' == $current_colours['playlist_tint'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>(default)</option>
											<option value="lighten2" <?php if ( 'lighten2' == $current_colours['playlist_tint'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Light grad</option>
											<option value="lighten1" <?php if ( 'lighten1' == $current_colours['playlist_tint'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Soft grad</option>
											<option value="darken1" <?php if ( 'darken1' == $current_colours['playlist_tint'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Dark grad</option>
											<option value="darken2" <?php if ( 'darken2' == $current_colours['playlist_tint'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Darker grad</option>
											<option value="none" <?php if ( 'none' == $current_colours['playlist_tint'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>None</option>
										</select>
									</p>
								</div>
								<br clear="all" />
							</div>
							
							<div id="pickerwrap">
								<div id="plugHEX"></div>
								<div id="plugCUR"></div>
								<div id="plugin" onmousedown="HSVslide('drag','plugin',event); return false;"><div id="SV" onmousedown="HSVslide('SVslide','plugin',event)"><div id="SVslide" style="top:-4px; left:-4px;"><br /></div></div><div id="H" onmousedown="HSVslide('Hslide','plugin',event)"><div id="Hslide" style="top:-7px; left:-8px;"><br /></div><div id="Hmodel"></div></div></div>
							</div>
							
							<div style="position:relative;width:175px; height:150px; margin:-200px 0px 28px 405px; padding:50px 0px 0px 0px; border:0px solid #666;">
								<p style="margin:0px 0px 8px 0px; text-align:right;">Indicator:&nbsp;
									<select name="mp3foxIndicator" style="width:80px; font-size:11px;">
										<option value="" <?php if ( '' == $current_colours['indicator'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>(default)</option>
										<option value="tint" <?php if ( 'tint' == $current_colours['indicator'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Greyscale</option>
										<option value="colour" <?php if ( 'colour' == $current_colours['indicator'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Colour</option>
									</select></p>
								<p style="margin:0px 0px 8px 0px; text-align:right;">Volume bar:&nbsp;
									<select name="mp3foxVolGrad" style="width:80px; font-size:11px;">
										<option value="" <?php if ( '' == $current_colours['volume_grad'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>(default)</option>
										<option value="light" <?php if ( 'light' == $current_colours['volume_grad'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Light</option>
										<option value="dark" <?php if ( 'dark' == $current_colours['volume_grad'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Dark</option>
									</select></p>
								<p style="margin:0px 0px 0px 0px; text-align:right;">Dividers:&nbsp;
									<select name="mp3foxListDivider" style="width:80px; font-size:11px;">
										<option value="" <?php if ( '' == $current_colours['list_divider'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>(default)</option>
										<option value="light" <?php if ( 'light' == $current_colours['list_divider'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Light</option>
										<option value="med" <?php if ( 'med' == $current_colours['list_divider'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Medium</option>
										<option value="dark" <?php if ( 'dark' == $current_colours['list_divider'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Dark</option>
										<option value="none" <?php if ( 'none' == $current_colours['list_divider'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>None</option>
									</select></p>
							</div>
							
							<div style="position:relative; width:579px; height:20px; padding-top:2px; border-top:1px solid #eee; border-bottom:1px solid #eee; background:#f9f9f9;">
								<div style="float:left; width:90px; margin-left:9px;"><p class="description" style="margin:0px;"><strong>TEXT</strong></p></div> 
								<div style="float:left; width:430px;"><p class="description" style="margin:0px;">Colour</p></div>
								<br clear="all" />
							</div>
							
							<div style="position:relative; width:579px; padding-top:6px;">
								<div style="float:left; width:65px; margin-left:9px; border:0px solid #aaa;"><p style="margin:0px;line-height:32px;">Screen:<br />Playlist:<br />Selected:<br />Hover:</p></div>
								<div style="float:left; width:460px; border:0px solid #aaa;">
									<p style="margin:0px;line-height:32px;">
										<input type="text" id="opE" onkeyup="udfcol('opE','blE');" size="10" name="mp3foxScreenTextColour" value="<?php echo $current_colours['screen_text_colour']; ?>" />
										<span class="addcol" onclick="putfcolour('opE','blE');">&nbsp;+&nbsp;</span>
										<span class="bl" onclick="sendfcolour('opE');" id="blE" style="background:<?php echo $current_colours['screen_text_colour']; ?>;">&nbsp;&nbsp;</span>
										<br />
										<input type="text" id="opF" onkeyup="udfcol('opF','blF');" size="10" name="mp3foxListTextColour" value="<?php echo $current_colours['list_text_colour']; ?>" />
										<span class="addcol" onclick="putfcolour('opF','blF');">&nbsp;+&nbsp;</span>
										<span class="bl" onclick="sendfcolour('opF');" id="blF" style="background:<?php echo $current_colours['list_text_colour']; ?>;">&nbsp;&nbsp;</span>
										<br />
										<input type="text" id="opG" onkeyup="udfcol('opG','blG');" size="10" name="mp3foxListCurrentColour" value="<?php echo $current_colours['list_current_colour']; ?>" /> 
										<span class="addcol" onclick="putfcolour('opG','blG');">&nbsp;+&nbsp;</span>
										<span class="bl" onclick="sendfcolour('opG');" id="blG" style="background:<?php echo $current_colours['list_current_colour']; ?>;">&nbsp;&nbsp;</span>
										&nbsp; &nbsp; Background: <input type="text" id="opH" onkeyup="udfcol('opH','blH');" size="10" name="mp3foxListBGaCurrent" value="<?php echo $current_colours['listBGa_current']; ?>" />
										<span class="addcol" onclick="putfcolour('opH','blH');">&nbsp;+&nbsp;</span>
										<span class="bl" onclick="sendfcolour('opH');" id="blH" style="background:<?php echo $current_colours['listBGa_current']; ?>;">&nbsp;&nbsp;</span>
										<br />
										<input type="text" id="opI" onkeyup="udfcol('opI','blI');" size="10" name="mp3foxListHoverColour" value="<?php echo $current_colours['list_hover_colour']; ?>" />
										<span class="addcol" onclick="putfcolour('opI','blI');">&nbsp;+&nbsp;</span>
										<span class="bl" onclick="sendfcolour('opI');" id="blI" style="background:<?php echo $current_colours['list_hover_colour']; ?>;">&nbsp;&nbsp;</span>
										&nbsp; &nbsp; Background: <input type="text" id="opJ" onkeyup="udfcol('opJ','blJ');" size="10" name="mp3foxListBGaHover" value="<?php echo $current_colours['listBGa_hover']; ?>" />
										<span class="addcol" onclick="putfcolour('opJ','blJ');">&nbsp;+&nbsp;</span>
										<span class="bl" onclick="sendfcolour('opJ');" id="blJ" style="background:<?php echo $current_colours['listBGa_hover']; ?>;">&nbsp;&nbsp;</span>
									</p>
								</div>
								<br clear="all" />
							</div>
							
							<div style="position:relative; width:579px; height:20px; margin-top:30px; padding-top:2px; border-top:1px solid #eee; border-bottom:1px solid #eee; background:#f9f9f9;">
								<div style="float:left; width:90px; margin-left:9px;"><p class="description" style="margin:0px;"><strong>POP-OUT</strong></p></div> 
								<div style="float:left; width:430px;"><p class="description" style="margin:0px;">Background</p></div>
								<br clear="all" />
							</div>
							
							<div style="width:579px; padding-top:6px;">
								<div style="float:left; width:65px; margin-left:9px; border:0px solid #aaa;"><p style="margin:0px;line-height:32px;">Colour:<br />Image:</p></div>
								<div style="float:left; width:460px; border:0px solid #aaa;">
									<p style="margin:0px;line-height:32px;">
										<input type="text" id="opK" onkeyup="udfcol('opK','blK');"  size="10" name="mp3foxPopoutBackground" value="<?php echo $theOptions['popout_background']; ?>" />
										<span class="addcol" onclick="putfcolour('opK','blK');">&nbsp;+&nbsp;</span>
										<span class="bl" onclick="sendfcolour('opK');" id="blK" style="background:<?php echo $theOptions['popout_background']; ?>;">&nbsp;&nbsp;</span></p>
									<p style="margin:4px 0px 0px 0px;line-height:32px;">
										<input type="text" style="width:503px;" name="mp3foxPopoutBGimage" value="<?php echo $theOptions['popout_background_image']; ?>" /></p>
								</div>
								<br clear="all" />
							</div>
							<p class="description" style="margin-top: 30px; margin-bottom: 0px;">&nbsp;&nbsp;(Opacity values from 0 to 100, leave any fields blank to use the default setting)</p>
					</div><!-- close fox_styling-list	-->	
					<br /><br />
								
					<!-- MP3s -->
					<h3>Media Library</h3>
			<?php
			// create library file list //
			$library = $mp3_fox->grab_library_info();
			$L_count = ( $library ) ? $library['count'] : "0";
			echo "<p class=\"description\" style=\"margin:0 0 2px 35px;\">Library contains <strong>" . $L_count . "</strong> mp3";
			if ( $library['count'] != 1 ) { echo "'s&nbsp;"; }
			else { echo "&nbsp;"; }
			
			if ( $L_count > 0 ) {
				//echo "<a href=\"javascript:mp3jp_listtoggle('fox_library','files');\" id=\"fox_library-toggle\">Show files</a> | <a href=\"media-new.php\">Upload new</a>";
				echo "<a href=\"javascript:MP3J_ADMIN.showhideit('fox_library','files');\" id=\"fox_library-toggle\">Show files</a> | <a href=\"media-new.php\">Upload new</a>";
				echo "</p>";
				//echo "<div id=\"fox_library-list\" style=\"display:none;\">\n";
				echo "<div id=\"fox_library-list\">\n";
				$liblist = '<p style="margin-left:0px;">';
				$br = '<br />';
				$tagclose = '</p>';
				$n = 1;
				foreach ( $library['filenames'] as $i => $file ) {
					//$liblist .= "<a href=\"media.php?attachment_id=" . $library['postIDs'][$i] . "&amp;action=edit\" style=\"font-size:11px;\">[Edit]</a>&nbsp;&nbsp;<span style=\"color:#aaa;font-size:11px;\">" . $n++ . "&nbsp;</span> " . $file . "&nbsp;&nbsp;<span style=\"color:#aaa;font-size:11px;\">\"" . $library['titles'][$i] . "\"&nbsp;&nbsp;" . $library['excerpts'][$i] . "</span>" . $br;
					switch( $theOptions['library_sortcol'] ) {
						case "title":
							$liblist .= "<a href=\"media.php?attachment_id=" . $library['postIDs'][$i] . "&amp;action=edit\" style=\"font-size:11px;\">[Edit]</a>&nbsp;&nbsp;<span style=\"color:#aaa;font-size:11px;\">" . $n++ . "&nbsp;&nbsp;\"" . $library['titles'][$i] . "\"&nbsp;&nbsp;" . $library['excerpts'][$i] . "</span>&nbsp;&nbsp;" . $file . $br; 
							break;
						case "caption": 
							$liblist .= "<a href=\"media.php?attachment_id=" . $library['postIDs'][$i] . "&amp;action=edit\" style=\"font-size:11px;\">[Edit]</a>&nbsp;&nbsp;<span style=\"color:#aaa;font-size:11px;\">" . $n++ . "&nbsp;&nbsp;" . $library['excerpts'][$i] . "&nbsp;&nbsp;\"" . $library['titles'][$i] . "\"</span>&nbsp;&nbsp;" . $file . $br; 
							break;
						default: 
							$liblist .= "<a href=\"media.php?attachment_id=" . $library['postIDs'][$i] . "&amp;action=edit\" style=\"font-size:11px;\">[Edit]</a>&nbsp;&nbsp;<span style=\"color:#aaa;font-size:11px;\">" . $n++ . "</span>&nbsp;&nbsp;" . $file . "&nbsp;&nbsp;<span style=\"color:#aaa;font-size:11px;\">\"" . $library['titles'][$i] . "\"&nbsp;&nbsp;" . $library['excerpts'][$i] . "</span>" . $br;
					}
				}
				$liblist .= $tagclose;
				echo $liblist;
				echo '</div>';
			}
			else { echo "<a href=\"media-new.php\">Upload new</a></p>"; }
			?>
					
					<p class="description" style="margin:0 0 0 33px;">You just need to write filenames in playlists to play from the library.</p>
					<p style="margin:12px 0 12px 34px;">Order playlists by:&nbsp;
						<select name="librarySortcol" style="width:110px; font-size:11px;">
							<option value="file" <?php if ( 'file' == $theOptions['library_sortcol'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Filename</option>
							<option value="date" <?php if ( 'date' == $theOptions['library_sortcol'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Date Uploaded</option>
							<option value="caption" <?php if ( 'caption' == $theOptions['library_sortcol'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Caption, Title</option>
							<option value="title" <?php if ( 'title' == $theOptions['library_sortcol'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Title</option>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Direction:&nbsp;
						<select name="libraryDirection" style="width:60px; font-size:11px;">
							<option value="ASC" <?php if ( 'ASC' == $theOptions['library_direction'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>ASC</option>
							<option value="DESC" <?php if ( 'DESC' == $theOptions['library_direction'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>DESC</option>
						</select>
					</p><br />					
					
					<!-- Folder -->
					<h3>Folder or URI</h3>
					<p class="description" style="margin:0 0 0 35px;">Set a default folder or uri for playing mp3's in the box below, eg. <code>/music</code> or <code>www.anothersite.com/music</code><br />You just need to write filenames in playlists to play from here.</p>
					<p style="margin:10px 0px 5px 35px;">Default path: &nbsp; <input type="text" style="width:385px;" name="mp3foxfolder" value="<?php echo $theOptions['mp3_dir']; ?>" /></p>
			
			<?php 
			// create file-list if directory is local
			$n = 1;
			$folderuris = $mp3_fox->grab_local_folder_mp3s( $theOptions['mp3_dir'] );
			if ( is_array($folderuris) ){
				foreach ( $folderuris as $i => $uri ) {
					$files[$i] = strrchr( $uri, "/" );
					$files[$i] = str_replace( "/", "", $files[$i] );
				}
				$c = (!empty($files)) ? count($files) : 0;
				echo "<p class=\"description\" style=\"margin: 0px 0px 14px 117px;\">This folder contains <strong>" . $c . "</strong> mp3";
				if ( $c != 1 ) { echo "'s&nbsp;"; }
				else { echo "&nbsp;"; }
				if ( $c > 0 ) {
					//echo "<a href=\"javascript:mp3jp_listtoggle('fox_folder','files');\" id=\"fox_folder-toggle\">Show files</a></p>";
					echo "<a href=\"javascript:MP3J_ADMIN.showhideit('fox_folder','files');\" id=\"fox_folder-toggle\">Show files</a></p>";
					//echo "<div id=\"fox_folder-list\" style=\"display:none;\">\n<p style=\"margin-left:0px;\">";
					echo "<div id=\"fox_folder-list\">\n<p style=\"margin-left:0px;\">";
					
					//natcasesort($files);
					
					foreach ( $files as $i => $val ) {
						echo "<span style=\"color:#aaa;font-size:11px;\">" . $n++ . "</span>&nbsp;&nbsp;" . $val . "<br />";
					}
					echo "</p>\n</div>\n";
				}
				else { echo "</p>";	}
			}
			elseif ( $folderuris == true )
				echo "<p class=\"description\" style=\"margin: 0px 0px 14px 117px;\">Unable to read or locate the folder <code>" . $theOptions['mp3_dir'] . "</code> check the path and folder permissions</p>";
			else 
				echo "<p class=\"description\" style=\"margin: 0px 0px 14px 117px;\">No info is available on remote folders but you can play from here if you know the filenames</p>"; 
			?>						
					
					
					<br />
					<div class="joptionswrap">
						
						<!--<a class="fox_buttonlink bl3" href="javascript:mp3jp_listtoggle('fox_op1','Playlist Player Options');" id="fox_op1-toggle">Playlist Player Options</a>-->
						<a class="fox_buttonlink bl3" href="javascript:MP3J_ADMIN.toggleit('fox_op1','Playlist Player Options');" id="fox_op1-toggle">Playlist Player Options</a>
						<div id="fox_op1-list" class="jopbox">
							<br />
							<p>Width: &nbsp; <input type="text" style="width:75px;" name="mp3foxPlayerWidth" value="<?php echo $theOptions['player_width']; ?>" /> &nbsp; <span class="description">pixels (px) or percent (%)</span></p>
							<p>Align: &nbsp;&nbsp; 
								<select name="mp3foxFloat" style="width:94px; font-size:11px; line-height:16px;">
									<option value="none" <?php if ( 'none' == $theOptions['player_float'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Left</option>
									<option value="rel-C" <?php if ( 'rel-C' == $theOptions['player_float'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Centre</option>
									<option value="rel-R" <?php if ( 'rel-R' == $theOptions['player_float'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Right</option>
									<option value="left" <?php if ( 'left' == $theOptions['player_float'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Float left</option>
									<option value="right" <?php if ( 'right' == $theOptions['player_float'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Float right</option>
								</select></p>
							<br /><br />
							
							<!-- <p><input type="checkbox" name="mp3foxDownloadMp3" value="true" <?php //if ($theOptions['show_downloadmp3'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Display a 'Download mp3' link</p> -->
							<h3 style="margin-left:0;"><strong>Downloads</strong></h3>
							<p style="margin-bottom:10px;">Show download link:
								<select name="mp3foxDownloadMp3" style="width:120px; font-size:11px; line-height:16px;">
									<option value="true" <?php if ( 'true' == $theOptions['show_downloadmp3'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>Yes</option>
									<option value="false" <?php if ( 'false' == $theOptions['show_downloadmp3'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>No</option>
									<option value="loggedin" <?php if ( 'loggedin' == $theOptions['show_downloadmp3'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>To logged in users</option>
								</select>
								&nbsp;&nbsp; 
								</p>
							
							<p class="description" style="margin:0 0 5px 30px;">When setting a player for logged in downloads, use the following options to add text/link for logged out visitors:</p> 
							<p style="margin-left:30px;">Visitor text: &nbsp; <input type="text" style="width:145px;" name="loggedout_dload_text" value="<?php echo $theOptions['loggedout_dload_text']; ?>" /> &nbsp;<span class="description">(leave blank for no text/link)</span></p>
							<p style="margin-left:30px;">Visitor link: &nbsp; <input type="text" style="width:350px;" name="loggedout_dload_link" value="<?php echo $theOptions['loggedout_dload_link']; ?>" /> &nbsp;<span class="description">(optional url for visitor text)</span></p>							
							
							<br />
							<!--<span class="description">(can be set per-player via shortcodes)</span>-->
							
							<p>Download link text: <input type="text" style="width:140px;" name="dload_text" value="<?php echo $theOptions['dload_text']; ?>" /></p>
							
							<p style="margin-top:15px;"><input type="checkbox" name="force_browser_dload" value="true" <?php if ($theOptions['force_browser_dload'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; 
								Try to force browsers to save downloads <span class="description">(no mobile support yet)</span>
								<!--&nbsp; Local files  
								<input type="radio" name="force_browser_dload_remote" value="false" <?php //if ($theOptions['force_browser_dload_remote'] == "false") { echo 'checked="checked"'; } ?> /> 
								&nbsp; | &nbsp; 
								<input type="radio" name="force_browser_dload_remote" value="true" <?php //if ($theOptions['force_browser_dload_remote'] == "true") { echo 'checked="checked"'; } ?>/> 
								All files -->
								</p>
								
								<p style="margin:10px 0 0 30px;"><span class="description">If you play from other domains and want to force the download, then use 
									the field<br />below to specify a path to a downloader file. <a href="<?php echo $mp3_fox->PluginFolder; ?>/remote/help.txt">See help on setting this up</a>.</span></p>
							
								<p style="margin:5px 0 0 30px;">Path to remote downloader files: <input type="text" style="width:240px;" name="dloader_remote_path" value="<?php echo $theOptions['dloader_remote_path']; ?>" /></p>
								
							<!--<p style="margin:5px 0 0 25px;"><span class="description">(if you select 'All files' then you'll need to place a downloader file on any remote servers you want to force downloads from.
								There's a file included in the plugin for use on servers running php, see <a href="<?php //echo $mp3_fox->PluginFolder; ?>/remote/help.txt">remote setup help</a> for instructions)</span></p>-->
								
													
							<br /><br />
							<h3 style="margin-left:0;"><strong>Margins</strong></h3>	
							<p>Above players: &nbsp; <input type="text" size="5" style="text-align:center;" name="mp3foxPaddings_top" value="<?php echo $theOptions['paddings_top']; ?>" /> <span class="description">&nbsp; pixels (px) or percent (%)</span><br />
								Inner margin: (floated players) &nbsp; <input type="text" size="5" style="text-align:center;" name="mp3foxPaddings_inner" value="<?php echo $theOptions['paddings_inner']; ?>" /> <span class="description">&nbsp; pixels (px) or percent (%)</span><br />
								Below players: &nbsp; <input type="text" size="5" style="text-align:center;" name="mp3foxPaddings_bottom" value="<?php echo $theOptions['paddings_bottom']; ?>" /> <span class="description">&nbsp; pixels (px) or percent (%)</span></p>
							
							
							<br /><br />
							<h3 style="margin-left:0;"><strong>Playlists</strong></h3>
							<p>Max playlist height: &nbsp; <input type="text" size="6" style="text-align:center;" name="mp3foxMaxListHeight" value="<?php echo $theOptions['max_list_height']; ?>" /> px &nbsp; <span class="description">(a scroll bar will show for longer playlists, leave it blank for no limit)</span></p>							
							<p><input type="checkbox" name="mp3foxShowPlaylist" value="true" <?php if ($theOptions['playlist_show'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Start with playlists showing</p>
							
							<div style="margin: 10px 0px 10px 0px; padding:6px; background:#f9f9f9; border:1px solid #eee;">
									<p>Playlist Separators <span class="description">- CAUTION: You'll need to manually update any existing playlists if you change the separators!</p>
									<p style="margin-left:20px;">Files: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<select name="file_separator" style="width:120px; font-size:11px; line-height:16px;">
											<option value="," <?php if ( ',' == $theOptions['f_separator'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>, (comma)</option>
											<option value=";" <?php if ( ';' == $theOptions['f_separator'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>; (semicolon)</option>
											<option value="###" <?php if ( '###' == $theOptions['f_separator'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>### (3 hashes)</option>
										</select>
										&nbsp;&nbsp;<span class="description">eg.</span> <code>tracks="fileA.mp3 <?php echo $theOptions['f_separator']; ?> Title@fileB.mp3 <?php echo $theOptions['f_separator']; ?> fileC.mp3"</code></p>
									
									<p style="margin-left:20px;">Captions: &nbsp;&nbsp; 
										<select name="caption_separator" style="width:120px; font-size:11px; line-height:16px;">
											<option value="," <?php if ( ',' == $theOptions['c_separator'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>, (comma)</option>
											<option value=";" <?php if ( ';' == $theOptions['c_separator'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>; (semicolon)</option>
											<option value="###" <?php if ( '###' == $theOptions['c_separator'] ) { _e('selected="selected"', $mp3_fox->textdomain ); } ?>>### (3 hashes)</option>
										</select>
										&nbsp;&nbsp;<span class="description">eg.</span> <code>captions="Caption A <?php echo $theOptions['c_separator']; ?> Caption B <?php echo $theOptions['c_separator']; ?> Caption C"</code></p>
							</div>
							
						</div>
						
						<!--<a class="fox_buttonlink bl3" href="javascript:mp3jp_listtoggle('fox_op5','Single Player Options');" id="fox_op5-toggle">Single Player Options</a>-->
						<a class="fox_buttonlink bl3" href="javascript:MP3J_ADMIN.toggleit('fox_op5','Single Player Options');" id="fox_op5-toggle">Single Player Options</a>
						<div id="fox_op5-list" class="jopbox">
							<p><input type="checkbox" name="volslider_onsingles" value="true" <?php if ($theOptions['volslider_on_singles'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Volume sliders on [mp3<strong>t</strong>] players</p>
							<p><input type="checkbox" name="volslider_onmp3j" value="true" <?php if ($theOptions['volslider_on_mp3j'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Volume sliders on [mp3<strong>j</strong>] players</p>
						</div>
						
						<!--<a class="fox_buttonlink bl3" href="javascript:mp3jp_listtoggle('fox_op2','Pop-Out Options');" id="fox_op2-toggle">Pop-Out Options</a>-->
						<a class="fox_buttonlink bl3" href="javascript:MP3J_ADMIN.toggleit('fox_op2','Pop-Out Options');" id="fox_op2-toggle">Pop-Out Options</a>
						<div id="fox_op2-list" class="jopbox">
							<p><input type="checkbox" name="mp3foxEnablePopout" value="true" <?php if ($theOptions['enable_popout'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Enable the pop-out player</p>
							<p>Window width: &nbsp; <input type="text" size="4" style="text-align:center;" name="mp3foxPopoutWidth" value="<?php echo $theOptions['popout_width']; ?>" /> px <span class="description">&nbsp; (250 - 1600)</span></p>
							<p>Window max height: &nbsp; <input type="text" size="4" style="text-align:center;" name="mp3foxPopoutMaxHeight" value="<?php echo $theOptions['popout_max_height']; ?>" /> px <span class="description">&nbsp; (200 - 1200) &nbsp; a scroll bar will show for longer playlists</span></p>
							<p>Launch button text: &nbsp; <input type="text" style="width:200px;" name="mp3foxPopoutButtonText" value="<?php echo $theOptions['popout_button_title']; ?>" /></p>
						</div>
						
						<!--<a class="fox_buttonlink bl3" href="javascript:mp3jp_listtoggle('fox_op3','File Options');" id="fox_op3-toggle">File Options</a>-->
						<a class="fox_buttonlink bl3" href="javascript:MP3J_ADMIN.toggleit('fox_op3','File Options');" id="fox_op3-toggle">File Options</a>
						<div id="fox_op3-list" class="jopbox">
							<p><input type="checkbox" name="mp3foxHideExtension" value="true" <?php if ($theOptions['hide_mp3extension'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Hide '.mp3' extension if a filename is displayed<br /><span class="description" style="margin-left:28px;">(filenames are displayed when there's no available titles)</span></p>
							<p><input type="checkbox" name="mp3foxEncodeFiles" value="true" <?php if ($theOptions['encode_files'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Encode URI's and filenames<br /><span class="description" style="margin-left:28px;">(provides some obfuscation of your urls in the page source)</span></p>
							<p><input type="checkbox" name="mp3foxAllowRemote" value="true" <?php if ($theOptions['allow_remoteMp3'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp; Allow playing of off-site mp3's<br /><span class="description" style="margin-left:28px;">(unchecking this option doesn't affect mp3's playing from a remote default folder if one is set above)</span></p>
						</div>
						
						<!--<a class="fox_buttonlink bl3" href="javascript:mp3jp_listtoggle('fox_op4','Template Options');" id="fox_op4-toggle">Template Options</a>-->
						<a class="fox_buttonlink bl3" href="javascript:MP3J_ADMIN.toggleit('fox_op4','Template Options');" id="fox_op4-toggle">Template Options</a>
						<div id="fox_op4-list" class="jopbox">
							
							
							<p style="margin:10px 0 10px 0px;">Shortcode for 'Turn mp3 links into players' option:</p>
							
							<p style="margin:0px 0 20px 25px;"><textarea class="widefat" style="width:580px; height:100px;" name="make_player_from_link_shcode"><?php 
								$deslashed = str_replace('\"', '"', $theOptions['make_player_from_link_shcode'] );
								echo $deslashed; 
								?></textarea><br /><span class="description">Can also include text/html. Placeholders:</span> <code>{TEXT}</code> <span class="description">- Link text,</span> <code>{URL}</code> <span class="description">- Link url.</span></p>
							
							
							
							<p><input type="checkbox" name="mp3foxUseFixedCSS" value="true" <?php if ($theOptions['use_fixed_css'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp;Bypass colour settings<br />&nbsp; &nbsp; &nbsp; &nbsp;<span class="description">(colours can still be set in css)</span></p>
							<p><input type="checkbox" name="disableTemplateTag" value="true" <?php if ($theOptions['disable_template_tag'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp;Bypass player template-tags in theme files<br />&nbsp; &nbsp; &nbsp; &nbsp;<span class="description">(ignores mp3j_addscripts() and mp3j_put() template functions)</span></p>
							
							<?php $greyout_text = ( $theOptions['disable_jquery_libs'] == "yes" ) ? ' style="color:#d6d6d6;"' : ''; ?>
							<p<?php echo $greyout_text; ?>><input type="checkbox" name="touch_punch_js" value="true" <?php if ($theOptions['touch_punch_js'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp;Include additional js for touch screen support<br />&nbsp; &nbsp; &nbsp; &nbsp;<span class="description"<?php echo $greyout_text; ?>>(adds jquery.ui.touch-punch.js script)</span></p>
							<p><input type="checkbox" name="mp3foxEchoDebug" value="true" <?php if ($theOptions['echo_debug'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /> &nbsp;Turn on debug<br />&nbsp; &nbsp; &nbsp; &nbsp;<span class="description">(info appears in the source view near the bottom)</span></p>
							<?php $bgc = ( $theOptions['disable_jquery_libs'] == "yes" ) ? "#fdd" : "#f9f9f9"; ?>
							<div style="margin: 20px 0px 10px 0px; padding:6px; background:<?php echo $bgc; ?>; border:1px solid #eee;">
								<p style="margin:0 0 5px 18px;">Disable jQuery and jQuery-UI javascript libraries? &nbsp; <input type="text" style="width:60px;" name="disableJSlibs" value="<?php echo $theOptions['disable_jquery_libs']; ?>" /></p>
								<p style="margin: 0 0 8px 18px;"><span class="description"><strong>CAUTION!!</strong> This option will bypass the request <strong>from this plugin only</strong> for both jQuery <strong>and</strong> jQuery-UI scripts,
									you <strong>MUST</strong> be providing these scripts from an alternative source.
									<br />Type <code>yes</code> in the box and save settings to bypass jQuery and jQuery-UI.</span></p>
							</div>
						</div>
					</div><!-- close .joptionswrap -->
										
					<p style="margin-top: 4px;"><input type="submit" name="update_mp3foxSettings" class="button-primary" value="<?php _e('Update Settings', $mp3_fox->textdomain ) ?>" /> &nbsp; Remember settings if plugin is deactivated &nbsp;<input type="checkbox" name="mp3foxRemember" value="true" <?php if ($theOptions['remember_settings'] == "true") { _e('checked="checked"', $mp3_fox->textdomain ); }?> /></p>
					<input id="fox_styling" type="hidden" name="MtogBox1" value="<?php echo $theOptions['admin_toggle_1']; // Colour settings toggle state ?>" />
					<input type="hidden" name="mp3foxPluginVersion" value="<?php echo $mp3_fox->version_of_plugin; ?>" />
				</form><br />				
				<div style="margin: 15px 120px 25px 0px; border-top: 1px solid #999; height: 30px;"><p class="description" style="margin: 0px 120px px 0px;"><a href="http://sjward.org/jplayer-for-wordpress">Plugin home page</a></p></div>
			</div>
		<?php
		}
?>