<?php
/*	UI WIDGET
	adds one playlist player at a time via mode/tickbock ui */

if ( class_exists("WP_Widget") ) {
	if ( !class_exists("MP3_jPlayer") ) {
		
		class MP3_jPlayer extends WP_Widget	{

/* Constructor (required by api) */
			function MP3_jPlayer() {
				
				$widget_ops = array( 
					'classname' => 'mp3jplayerwidget', 
					'description' => __('Adds a playlist player. Choose playback mode and options.', 
					'mp3jplayerwidget') 
				);
				$control_ops = array( 
					'id_base' => 'mp3-jplayer-widget',
					'width' => 800 
				);
				$this->WP_Widget( 'mp3-jplayer-widget', __('MP3j-ui', 'mp3jplayerwidget'), $widget_ops, $control_ops );
			}
		
/*	Sets up widget playlist and writes player (required by api) */
			function widget( $args, $instance ) {
				global $mp3_fox;
				$mp3_fox->dbug['str'] .= "\n### Checking UI widget...";
				if ( !is_home() && !is_archive() && !is_singular() && !is_search() ) { return; }
				
				if ( $mp3_fox->page_filter( $instance['restrict_list'], $instance['restrict_mode'] ) ) { 
					$mp3_fox->dbug['str'] .= "\nExiting (page filter says no)";
					return;
				}
				
			//playlist building
				if ( $instance['widget_mode'] == "1" ) { // Must be singular and have fields playlist
					if ( is_singular() ) {
						if ( ($meta = $mp3_fox->meta_to_KVs()) ) {
							$templist = $mp3_fox->generate_playlist( $meta['Ks'], $meta['Vs'] );
						}
						if ( $templist['count'] < 1 ) { 
							return; 
						}
					} else { 
						return; 
					}
					$Wplist = $templist;
				}
				if ( $instance['widget_mode'] == "2" ) {
					$captions = ""; 
					if ( !($Npl = $mp3_fox->string_to_playlist( $instance['arb_playlist'], $captions )) ) {
						return;
					}
					$Wplist = $Npl;
				}
				if ( $instance['widget_mode'] == "3" ) {
					if ( !($newl = $mp3_fox->make_widget_playlist( $instance )) ) { 
						return;
					}
					$Wplist = $newl;
				}
				
				if ( $instance['slice_size'] != "" && $instance['slice_size'] >= 1 ) { $Wplist = $mp3_fox->pick_from_playlist( $instance['slice_size'], $Wplist ); }
				if ( $instance['shuffle'] == "true" ) { if ( $Wplist['count'] > 1 ) { shuffle( $Wplist['order'] ); } }
								
			// Write it
				$PlayerName = "MI_" . $mp3_fox->M_no; 
				$mp3_fox->write_playlist_js( $Wplist, $PlayerName );
				
			// Set up bits
				$width = ( $instance['player_width'] == "" ) ? $mp3_fox->theSettings['player_width'] : $instance['player_width'];
				$popout_title = strip_tags($instance['title']);
				$popout_title = trim($popout_title);
				//$popout_title = ( !empty($popout_title) ) ? $popout_title . " | " . get_bloginfo('name') : get_bloginfo('name') . " | " . get_bloginfo('description');
				$popout_title = ( !empty($popout_title) ) ? $popout_title . " | " . get_bloginfo('name') : get_bloginfo('name');
				
				$list = ( $instance['playlist_mode'] == "true" ) ? "true" : "false";
				$autoplay = ( $instance['autoplay'] == "true" ) ? "true" : "false";
				$loop = ( $instance['loop'] == "true" ) ? "true" : "false";
				$vol = $instance['volume'];
				$pos = $instance['position'];
				$pn = $instance['pn_buttons'];
				$stop = $instance['stop_button'];
				$dload = $instance['download_link'];
				$pID = $mp3_fox->Player_ID;
				
				$addclass = ( $instance['mods'] == "true" ) ? " mp3j_widgetmods" : "";
				if ( $dload == "true" || $dload == "y" || $dload == "1"  ) {
					$dload_info = "true";
					$dload_html = "<div id=\"download_mp3j_" . $pID . "\" class=\"dloadmp3-MI" . $addclass . "\"></div>";
				} elseif ( $dload == "loggedin" ) {
					if ( is_user_logged_in() ) {
						$dload_info = "true";
						$dload_html = "<div id=\"download_mp3j_" . $pID . "\" class=\"dloadmp3-MI" . $addclass . "\"></div>";
					} else {
						$dload_info = "false";
						if ( $mp3_fox->theSettings['loggedout_dload_text'] == "" ) {
							$dload_html = "";
						} else {
							if ( $mp3_fox->theSettings['loggedout_dload_link'] != "" ) {
								$dload_html = "<div id=\"download_mp3j_" . $pID . "\" class=\"dloadmp3-MI whilelinks" . $addclass . "\"><a href=\"" . $mp3_fox->theSettings['loggedout_dload_link'] . "\">" . $mp3_fox->theSettings['loggedout_dload_text'] . "</a></div>";
							} else {
								$dload_html = "<div id=\"download_mp3j_" . $pID . "\" class=\"dloadmp3-MI logintext" . $addclass . "\"><p>" . $mp3_fox->theSettings['loggedout_dload_text'] . "</p></div>";
							}
						}
					}
				} else {
					$dload_info = "false";
					$dload_html = "";
				}
				
			// Add info to js info array
				$play = "#USE_G#";
				$pp_height = (int)$instance['player_height'];
				$pp_height = ( empty($pp_height) || $pp_height === 0 ) ? 'false' : $pp_height;
				$pp_title = str_replace("'", "\'", $popout_title);
				$pp_title = str_replace("&#039;", "\'", $pp_title);
				
				$mp3_fox->JS['players'][] = "{ list: MP3J_PLAYLISTS." . $PlayerName . ", tr: 0, type: 'MI', lstate: " . $list . ", loop: " . $loop . ", play_txt: '" . $play . "', pause_txt: '', pp_title: '" . $pp_title . "', autoplay: " . $autoplay . ", download: " . $dload_info . ", vol: " . $vol . ", height: " . $pp_height . ", cssclass: '" . $instance['style'] . "' }";
				
			// Make transport buttons
				$prevnext = ( $Wplist['count'] > 1 && $pn == "true" ) ? "<div class=\"Next_mp3j\" id=\"Next_mp3j_" . $pID . "\">Next&raquo;</div><div class=\"Prev_mp3j\" id=\"Prev_mp3j_" . $pID . "\">&laquo;Prev</div>" : "";
				$play_h = "<div class=\"buttons_mp3j\" id=\"playpause_mp3j_" . $pID . "\">Play Pause</div>";
				$stop_h = ( $stop == "true" ) ? "<div class=\"stop_mp3j\" id=\"stop_mp3j_" . $pID . "\">Stop</div>" : "";
				
			// Build player html
				//$theplayer = $mp3_fox->write_primary_player( $PlayerName, $pID, $pos, $width, $mods, $dload, '', $play_h, $stop_h, $prevnext, $instance['player_height'], $list );
				//$theplayer = $mp3_fox->write_primary_player( $pID, $pos, $width, $mods, $dload, '', $play_h, $stop_h, $prevnext, $instance['player_height'], $list );
				//$theplayer = $mp3_fox->write_primary_player( $pID, $pos, $width, $addclass, $dload_html, '', $play_h, $stop_h, $prevnext, $instance['player_height'], $list, $Wplist['count'] );
				$theplayer = $mp3_fox->write_primary_player( $pID, $pos, $width, $addclass, $dload_html, '', $play_h, $stop_h, $prevnext, $instance['player_height'], $list, $Wplist['count'], $instance['style'] );
				
				extract( $args ); // supplied WP theme vars 
				echo $before_widget;
				if ( $instance['title'] ) { echo $before_title . $instance['title'] . $after_title; }
				echo $theplayer;
				echo $after_widget;
				
				$mp3_fox->write_jp_div();
				$mp3_fox->dbug['str'] .= "\nOK (id " . $mp3_fox->Player_ID . ")";
				$mp3_fox->M_no++;
				$mp3_fox->Player_ID++;			
				return;
			}
	   
/*	Updates the widget settings (required by api) */			
			function update( $new_instance, $old_instance ) {
				
				$instance = $old_instance;
				$instance['title'] = $new_instance['title'];
				$instance['id_to_play'] = strip_tags( $new_instance['id_to_play'] );
				$instance['widget_mode'] = $new_instance['widget_mode'];
				$instance['shuffle'] = $new_instance['shuffle'];
				$instance['restrict_list'] = strip_tags( $new_instance['restrict_list'] );
				$instance['restrict_mode'] = $new_instance['restrict_mode'];
				$instance['play_library'] = $new_instance['play_library'];
				$instance['arb_playlist'] = strip_tags( $new_instance['arb_playlist'] );
				$instance['play_page'] = $new_instance['play_page'];
				$instance['slice_size'] = strip_tags( $new_instance['slice_size'] );
				$instance['play_folder'] = $new_instance['play_folder'];
				$instance['download_link'] = $new_instance['download_link'];
				$instance['playlist_mode'] = $new_instance['playlist_mode'];
				$instance['player_width'] = $new_instance['player_width'];
				$instance['autoplay'] = $new_instance['autoplay'];
				$instance['loop'] = $new_instance['loop'];
				$instance['mods'] = $new_instance['mods'];
				$instance['position'] = $new_instance['position'];
				$instance['pn_buttons'] = $new_instance['pn_buttons'];
				$instance['stop_button'] = $new_instance['stop_button'];
				$instance['player_height'] = $new_instance['player_height'];
				$instance['style'] = $new_instance['style'];
				
				$instance['folder_to_play'] = strip_tags( $new_instance['folder_to_play'] );
				if ( strpos($instance['folder_to_play'], "http://") === false && strpos($instance['folder_to_play'], "www.") === false ) {
					if ( !empty($instance['folder_to_play']) ) {
						$instance['folder_to_play'] = trim($instance['folder_to_play']);
						if ( $instance['folder_to_play'] != "/" ) {
							$instance['folder_to_play'] = trim($instance['folder_to_play'], "/");
							$instance['folder_to_play'] = "/" . $instance['folder_to_play'];
						}
					}
				}
				
				$instance['volume'] = preg_replace("/[^0-9]/", "", $new_instance['volume']); 
				if ($instance['volume'] < 0 || $instance['volume']=="") { $instance['volume'] = "0"; }
				if ($instance['volume'] > 100) { $instance['volume'] = "100"; }
								
				return $instance;
			}

/*	Creates defaults and writes widget panel (required by api) */						
			function form( $instance ) {
			
				global $mp3_fox;
				$mp3_fox->theSettings = get_option('mp3FoxAdminOptions');
				
				$defaultvalues = array(
					'title' => '',
					'id_to_play' => '',
					'widget_mode' => '1',
					'shuffle' => 'false',
					'restrict_list' => '',
					'restrict_mode' => 'exclude',
					'play_library' => 'false',
					'arb_playlist' => '',
					'play_page' => 'false',
					'slice_size' => '',
					'play_folder' => 'false',
					'folder_to_play' => '',
					'download_link' => $mp3_fox->theSettings['show_downloadmp3'],
					'playlist_mode' => $mp3_fox->theSettings['playlist_show'],
					'player_width' => '100%',
					'autoplay' => $mp3_fox->theSettings['auto_play'],
					'loop' => $mp3_fox->theSettings['playlist_repeat'],
					'volume' => $mp3_fox->theSettings['initial_vol'],
					'mods' => 'false',
					'position' => 'rel-L',
					'pn_buttons' => 'true',
					'stop_button' => 'true',
					'player_height' => '',
					'style' => ''
				);
				
				$instance = wp_parse_args( (array) $instance, $defaultvalues );
				$helptext_col = "color:#a0a0a0;";
	?>
					
					<h3 style="text-align:right; font-size: 11px; margin-bottom:0px;"><a href="options-general.php?page=mp3jplayer.php">Plugin Options & Help</a></h3>
					<p style="margin-top:-18px; margin-bottom:10px;">Play Mode:</p>
					<p style="margin-bottom: 10px;"><input type="radio" id="<?php echo $this->get_field_id( 'widget_mode' ); ?>" name="<?php echo $this->get_field_name( 'widget_mode' ); ?>" value="1" <?php if ($instance['widget_mode'] == "1") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
						&nbsp;&nbsp;Mode 1 &nbsp;<span class="description" style="margin-left: 0px;">Custom fields</span>
						<span class="description" style="margin: 0px 0px 14px 10px; font-size: 11px; <?php echo $helptext_col; ?>">Auto adds a player on SINGLE posts/pages, when they have tracks in their custom fields.</span></p>
					<div style="margin:0px 0px 0px 25px; border-top: 1px solid #eee; height: 10px;"></div>
					<p style="margin-top:0px; margin-bottom:6px;"><input type="radio" id="<?php echo $this->get_field_id( 'widget_mode' ); ?>" name="<?php echo $this->get_field_name( 'widget_mode' ); ?>" value="2" <?php if ($instance['widget_mode'] == "2") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
						&nbsp;&nbsp;Mode 2 &nbsp;<span class="description" style="margin-left: 0px;">Fixed playlist</span>
						<span class="description" style="margin: 0px 0px 10px 10px; font-size: 11px; <?php echo $helptext_col; ?>">A <code><?php echo $mp3_fox->theSettings['f_separator']; ?></code> separated list of filenames or full URI's.</span></p>
					<p style="margin-left:25px; margin-bottom:15px; font-size: 11px;"><textarea class="widefat" style="font-size:11px;" rows="4" cols="80" id="<?php echo $this->get_field_id( 'arb_playlist' ); ?>" name="<?php echo $this->get_field_name( 'arb_playlist' ); ?>"><?php echo $instance['arb_playlist']; ?></textarea></p>
					<div style="margin: 0px 0px 0px 25px; border-top: 1px solid #eee; height: 5px;"></div>
					<p style="margin-top: 0px;margin-bottom: 10px;"><input type="radio" id="<?php echo $this->get_field_id( 'widget_mode' ); ?>" name="<?php echo $this->get_field_name( 'widget_mode' ); ?>" value="3" <?php if ($instance['widget_mode'] == "3") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
						&nbsp;&nbsp;Mode 3 &nbsp;<span class="description" style="margin-left: 0px;">Generate playlist</span></p>
					<div style="margin:-25px 0 0 200px;">
							<p style="margin: 0px 0px 2px 25px; font-size: 11px;"><input type="checkbox" id="<?php echo $this->get_field_id( 'play_page' ); ?>" name="<?php echo $this->get_field_name( 'play_page' ); ?>" value="true" <?php if ($instance['play_page'] == "true") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
								&nbsp;From page ID &nbsp;<input class="widefat" style="width:55px;" type="text" id="<?php echo $this->get_field_id( 'id_to_play' ); ?>" name="<?php echo $this->get_field_name( 'id_to_play' ); ?>" value="<?php echo $instance['id_to_play']; ?>" /></p>
							<p style="margin:0px 0px 1px 25px; font-size:11px;"><input type="checkbox" id="<?php echo $this->get_field_id( 'play_library' ); ?>" name="<?php echo $this->get_field_name( 'play_library' ); ?>" value="true" <?php if ($instance['play_library'] == "true") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
								&nbsp;My library</p>
						
	<?php
						if ( $instance['folder_to_play'] == "" ) { 
							$folder = $mp3_fox->theSettings['mp3_dir']; 
						} else { 
							$folder = $instance['folder_to_play']; 
						}
						$foldertracks = $mp3_fox->grab_local_folder_mp3s( $folder );
						if ( $foldertracks !== true && $foldertracks !== false ) {
							if ( ($c = count($foldertracks)) > 0 ) { 
								$style = "color:#282;";
								$txt = $c . "&nbsp;mp3";
								if ( $c != 1 ) { $txt .= "'s"; }
								$txt .= "&nbsp;in this folder";
							} else {
								$style = "color:#aaa;";
								$txt = "There are no mp3's here";
							}
						} elseif ( $foldertracks === true ) {
							$txt = "Folder not found, check path<br />and permissions";
							$style = "color:#dfad00;";
						} else {
							$txt = "x Remote or inaccessible folder";
							$style = "color:#f56b0f;";
						}
	?>

							<p style="margin:0px 0px 0px 25px; font-size: 11px;"><input type="checkbox" id="<?php echo $this->get_field_id( 'play_folder' ); ?>" name="<?php echo $this->get_field_name( 'play_folder' ); ?>" value="true" <?php if ($instance['play_folder'] == "true") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
								&nbsp;A folder: &nbsp;<input class="widefat" type="text" style="width:300px; margin-right:0px; font-size:11px;" id="<?php echo $this->get_field_id( 'folder_to_play' ); ?>" name="<?php echo $this->get_field_name( 'folder_to_play' ); ?>" value="<?php echo $instance['folder_to_play']; ?>" /></p>
							<p class="description" style="text-align:right; margin:0px 200px 4px 0px; font-size:10px; <?php echo $style; ?>"><?php echo $txt; ?></p>
					</div>
					<div style="margin: 10px 0px 8px 0px; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">
						<p style="font-size: 11px; margin: 10px 0px 8px 0px;">Initial volume &nbsp;<input class="widefat" style="width:40px; text-align:right;" type="text" id="<?php echo $this->get_field_id( 'volume' ); ?>" name="<?php echo $this->get_field_name( 'volume' ); ?>" value="<?php echo $instance['volume']; ?>" /> &nbsp;<span class="description" style="font-size:11px; color:#999999;">(0 - 100)</span></p>
						<div style="margin:-30px 0 0 225px;">		
								<p style="font-size: 11px;margin: 0px 0px 2px 0px;"><input type="checkbox" id="<?php echo $this->get_field_id( 'autoplay' ); ?>" name="<?php echo $this->get_field_name( 'autoplay' ); ?>" value="true" <?php if ($instance['autoplay'] == "true") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
									&nbsp;Autoplay</p>
								<p style="font-size: 11px;margin: 0px 0px 4px 0px;"><input type="checkbox" id="<?php echo $this->get_field_id( 'loop' ); ?>" name="<?php echo $this->get_field_name( 'loop' ); ?>" value="true" <?php if ($instance['loop'] == "true") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
									&nbsp;Repeat</p>
						</div>
						<div style="margin:-39px 0 0 336px;">
								<p style="font-size: 11px;margin: 0px 0px 2px 0px;">Download
									<select id="<?php echo $this->get_field_id( 'download_link' ); ?>" name="<?php echo $this->get_field_name( 'download_link' ); ?>" style="width:65px; font-size:11px;">
										<option value="true" <?php if ( 'true' == $instance['download_link'] ) { echo 'selected="selected"'; } ?>>Yes</option>
										<option value="false" <?php if ( 'false' == $instance['download_link'] ) { echo 'selected="selected"'; } ?>>No</option>
										<option value="loggedin" <?php if ( 'loggedin' == $instance['download_link'] ) { echo 'selected="selected"'; } ?>>Logged in</option>
									</select></p>
								<p style="font-size: 11px;margin: 0px 0px 2px 0px;"><input type="checkbox" id="<?php echo $this->get_field_id( 'playlist_mode' ); ?>" name="<?php echo $this->get_field_name( 'playlist_mode' ); ?>" value="true" <?php if ($instance['playlist_mode'] == "true") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
									&nbsp;Show playlist</p>
						</div>
						<div style="margin:-42px 0 0px 470px;">		
								<p style="font-size: 11px;margin: 0px 0px 2px 0px;"><input type="checkbox" id="<?php echo $this->get_field_id( 'pn_buttons' ); ?>" name="<?php echo $this->get_field_name( 'pn_buttons' ); ?>" value="true" <?php if ($instance['pn_buttons'] == "true") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
									&nbsp;Prev/next buttons</p>
								<p style="font-size: 11px;margin: 0px 0px 2px 0px;"><input type="checkbox" id="<?php echo $this->get_field_id( 'stop_button' ); ?>" name="<?php echo $this->get_field_name( 'stop_button' ); ?>" value="true" <?php if ($instance['stop_button'] == "true") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
									&nbsp;Stop button</p>
						</div>
						<div style="margin:-37px 0 10px 630px;">
							<p style="font-size:11px;margin: 0px 0px 2px 0px;"><input type="checkbox" id="<?php echo $this->get_field_id( 'shuffle' ); ?>" name="<?php echo $this->get_field_name( 'shuffle' ); ?>" value="true" <?php if ($instance['shuffle'] == "true") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
								&nbsp;Shuffle</p>
							<p style="margin:0px 0px 10px 0px; font-size:11px;">
								Pick &nbsp;<input class="widefat" style="width:35px; text-align:right;" type="text" id="<?php echo $this->get_field_id( 'slice_size' ); ?>" name="<?php echo $this->get_field_name( 'slice_size' ); ?>" value="<?php echo $instance['slice_size']; ?>" />&nbsp; track(s)</p>
						</div>
						<p style="margin:0px 0px 8px 225px; font-size:11px; ">Width: 
							<input class="widefat" style="font-size:11px; width:60px;" type="text" id="<?php echo $this->get_field_id( 'player_width' ); ?>" name="<?php echo $this->get_field_name( 'player_width' ); ?>" value="<?php echo $instance['player_width']; ?>" />
							&nbsp;&nbsp;Height: <input class="widefat" style="font-size:11px; width:60px;" type="text" id="<?php echo $this->get_field_id( 'player_height' ); ?>" name="<?php echo $this->get_field_name( 'player_height' ); ?>" value="<?php echo $instance['player_height']; ?>" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Align:
							<select id="<?php echo $this->get_field_id( 'position' ); ?>" name="<?php echo $this->get_field_name( 'position' ); ?>" class="widefat" style="width:90px; font-size:11px;">
								<option value="rel-L" <?php if ( 'rel-L' == $instance['position'] ) { echo 'selected="selected"'; } ?>>Left</option>
								<option value="rel-C" <?php if ( 'rel-C' == $instance['position'] ) { echo 'selected="selected"'; } ?>>Centre</option>
								<option value="rel-R" <?php if ( 'rel-R' == $instance['position'] ) { echo 'selected="selected"'; } ?>>Right</option>
								<option value="left" <?php if ( 'left' == $instance['position'] ) { echo 'selected="selected"'; } ?>>Float left</option>
								<option value="right" <?php if ( 'right' == $instance['position'] ) { echo 'selected="selected"'; } ?>>Float right</option>
							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="<?php echo $this->get_field_id( 'mods' ); ?>" name="<?php echo $this->get_field_name( 'mods' ); ?>" value="true" <?php if ($instance['mods'] == "true") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
							Mods</p>
							
							
							<p style="font-size:11px;">Style (<strong><a href="<?php echo $mp3_fox->PluginFolder; ?>/style-param-help.htm">Help</a></strong>) : <input class="widefat" style="font-size:11px; width:200px;" type="text" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" value="<?php echo $instance['style']; ?>" />
								<br /></p>
							
							
					</div>
					<p style="font-size: 11px; margin:10px 0px 4px 0px;">
						Include <input type="radio" id="<?php echo $this->get_field_id( 'restrict_mode' ); ?>" name="<?php echo $this->get_field_name( 'restrict_mode' ); ?>" value="include" <?php if ($instance['restrict_mode'] == "include") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
						or <input type="radio" id="<?php echo $this->get_field_id( 'restrict_mode' ); ?>" name="<?php echo $this->get_field_name( 'restrict_mode' ); ?>" value="exclude" <?php if ($instance['restrict_mode'] == "exclude") { _e('checked="checked"', "mp3jplayerwidget"); }?> />
						Exclude pages &nbsp;<input class="widefat" style="font-size:11px; width:200px;" type="text" id="<?php echo $this->get_field_id( 'restrict_list' ); ?>" name="<?php echo $this->get_field_name( 'restrict_list' ); ?>" value="<?php echo $instance['restrict_list']; ?>" /></p>
					<p class="description" style="font-size:11px; margin-top:4px; margin-bottom:1px; <?php echo $helptext_col; ?>">A comma separated list, can use post ID's, <code>index</code>, <code>archive</code>, <code>post</code>, and <code>search</code>.</p> 
					<p style="margin: -43px 0px 25px 420px; font-size: 11px;">Widget Heading: <input class="widefat" style="width:260px;" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" /></p>	
				
	<?php	
			}
		} //end class
	}	
}
?>