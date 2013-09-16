<?php
if ( !class_exists("MP3j_Front") && class_exists("MP3j_Main") ) { class MP3j_Front extends MP3j_Main {
	
/*	Called on deactivation, deletes 
	settings if 'remember' option unticked. */
	function uninitFox() { 
		$theOptions = get_option($this->adminOptionsName);
		if ( $theOptions['remember_settings'] == "false" ) {
			delete_option($this->adminOptionsName);
		}
	}


/*	Flags for scripts via 
	template tag mp3j_addscripts(). */
	function scripts_tag_handler( $style = "" ) {
// Since 1.7 - convert old option name to new
		if ( $style == "styleA" || $style == "styleE" ) {	$style = "styleF"; }
		if ( $style == "styleB" ) { $style = "styleG"; }
		if ( $style == "styleC" ) { $style = "styleH"; }
		if ( $style == "styleD" ) { $style = "styleI"; }
		
		$this->stylesheet = ( $style == "" ) ? $this->theSettings['player_theme'] : $style;
		$this->scriptsflag = "true";
		return;
	}
		
				
/*	Returns library via 
	template tag mp3j_grab_library(). */
	function grablibrary_handler( $x ) {
		return $this->grab_library_info();
	}
	
	
/*	Checks whether js and css scripts are needed 
	on the page, and which css sheet to use if they are. */
	function header_scripts_handler() {
		$scripts = false;
		$allowed_widget = $this->has_allowed_widget( "mp3-jplayer-widget" );
		$allowed_widget_B = $this->has_allowed_widget( "mp3mi-widget" );
// Flagged in template 
		if ( $this->scriptsflag == "true" && $this->theSettings['disable_template_tag'] == "false" ) {
			$scripts = true;
		}
// On page types
		if ( is_home() || is_archive() || is_search() ) {
			if ( $allowed_widget || $allowed_widget_B || $this->theSettings['player_onblog'] == "true" ) {
				$scripts = true;
			}
		}
		if ( is_singular() ) {	
			if ( $allowed_widget || $allowed_widget_B || $this->has_shortcodes() || ($this->theSettings['make_player_from_link'] == "true" && $this->post_has_string('.mp3')) ) {
				$scripts = true;
			}				
		}
// Add the scripts
		if ( $scripts ) {
			$style = ( $this->stylesheet == "" ) ? $this->theSettings['player_theme'] : $this->stylesheet;
			$this->add_Scripts( $style );
			if ( $this->theSettings['run_shcode_in_excerpt'] == "true" ) {
				add_filter( 'the_excerpt', 'shortcode_unautop');
				add_filter( 'the_excerpt', 'do_shortcode');
			}
		}
		return;
	}
	
	
/*	Writes js playlists, 
	startup, and debug info. */	
	function footercode_handler() {

// Write js playlists
		if ( !empty($this->JS['playlists']) ) {
			$c = count($this->JS['playlists']);
			echo "\n<script type=\"text/javascript\">\nvar MP3J_PLAYLISTS = {";
			foreach ( $this->JS['playlists'] as $i => $list ) {
				echo "\n\t" . $this->JS['playlists'][$i];
				if ( $i < $c-1 ) {
					echo ",";
				}
			}
			echo "\n};\n</script>\n";
		} 

// Write doc ready
		if ( !empty($this->JS['players']) ) {
			
			$pp_bodycol = ( $this->theSettings['popout_background'] == "" ) ? "#fff" : $this->theSettings['popout_background'];
			echo "\n<script type=\"text/javascript\">\njQuery(document).ready(function () {";
			echo "\nif (typeof MP3_JPLAYER !== 'undefined') {";
			
			if ( !empty($this->JS['Stitle']) ) {
				echo "\n\tMP3_JPLAYER.footerjs = function () {\n" . $this->JS['Stitle'] . "\t};";
			}
			
			echo "
	MP3_JPLAYER.vars.play_f = " . $this->theSettings['encode_files'] . ";
	MP3_JPLAYER.vars.force_dload = " . $this->theSettings['force_browser_dload'] . ";
	MP3_JPLAYER.plugin_path = '" . $this->PluginFolder . "';";
	
			if ( $this->theSettings['force_browser_dload'] == "true" ) {
				echo "
	MP3_JPLAYER.vars.dl_remote_path = '" . $this->theSettings['dloader_remote_path'] . "';";
			
			}
	
			if ( $this->theSettings['enable_popout'] == "true" ) {
				echo "
	MP3_JPLAYER.vars.pp_width = " . $this->theSettings['popout_width'] . ";
	MP3_JPLAYER.vars.pp_maxheight = " . $this->theSettings['popout_max_height'] . ";
	MP3_JPLAYER.vars.pp_bodycolour = '" . $pp_bodycol . "';
	MP3_JPLAYER.vars.pp_bodyimg = '" . $this->theSettings['popout_background_image'] . "';
	MP3_JPLAYER.vars.pp_fixedcss = " . $this->theSettings['use_fixed_css'] . ";";
			}
			
			echo "
	MP3_JPLAYER.vars.dload_text = '" . $this->theSettings['dload_text'] . "';
	MP3_JPLAYER.vars.stylesheet_url = '" . $this->PP_css_url . "';";
			
// Write players info array js	
			if ( !empty($this->JS['players']) ) {
				$c = count($this->JS['players']);
				echo "\n\tMP3_JPLAYER.pl_info = [";
				foreach ( $this->JS['players'] as $k => $v ) { 
					echo "\n\t\t" . $v;
					if ( $k < $c-1 ) { echo ","; }
				}
				echo "\n\t];";
			}
			
// Write listnames
			if ( !empty($this->JS['listref']) ) {
				$c = count($this->JS['listref']);
				echo "\n\n\tMP3_JPLAYER.lists = [";
				foreach ( $this->JS['listref'] as $j => $ln ) {
					echo "\n\t\tMP3J_PLAYLISTS." . $ln;
					if ( $j < $c-1 ) { echo ","; }
				}
				echo "\n\t];\n";
			}

// Add popout_css property
			echo $this->PP_css_settings;

// Add footer titles call
			echo "\n\tif (typeof MP3_JPLAYER.footerjs !== 'undefined') { MP3_JPLAYER.footerjs(); }";

// Add init call				
			echo "\n\tMP3_JPLAYER.init();";
			echo "\n}"; //close if create_mp3_jplayer exists 
			echo"\n});\n</script>\n";
		}

// Write debug
		if ( $this->theSettings['echo_debug'] == "true" ) { 
			$this->debug_info(); 
		}
		
		return;	
	}


/* Work out playlist 
	for single players. */
	function decide_S_playlist( $track, $caption ) {
		if ( $track == "" ) { // Auto increment 
			if ( !$this->fieldgrab_check() || $this->Caller == "widget" || $this->F_listlength <= $this->S_autotrack ) { 
				return false;
			}
			$track = ++$this->S_autotrack;
			$playername = $this->F_listname;
		} elseif ( is_numeric($track) ) { // Has a track number
			if ( !$this->fieldgrab_check() || $this->Caller == "widget" || $this->F_listlength < $track ) { 
				return false; 
			}
			$playername = $this->F_listname;
		} else { // Has arbitrary file/uri				
			if ( !($Npl = $this->string_to_playlist( $track, $caption )) ) { 
				return false;
			}
			$track = 1;					
			$playername = "inline_" . $this->S_no++;
			$this->write_playlist_js( $Npl, $playername, $this->S_arb++ );
		}
		return array( 'track' => $track, 'playername' => $playername );		
	}


/*	Handles [mp3t] shortcodes 
	single players with text buttons. */	
	function inline_play_handler( $atts, $content = null ) {
		
		$this->dbug['str'] .= "\n### Checking [mp3t]...";
		if ( !$this->Caller && (is_home() || is_archive() || is_search()) && $this->theSettings['player_onblog'] == "false" ) {
			$this->dbug['str'] .= "\nExiting (player_onblog is unticked)";
			return;
		}
		
		$id = $this->Player_ID;			
		extract(shortcode_atts(array( // Defaults
			'bold' => 'y',
			'play' => 'Play',
			'track' => '',
			'caption' => '',
			'flip' => 'l',
			'title' => '#USE#',
			'stop' => 'Stop',
			'ind' => 'y',
			'autoplay' => $this->theSettings['auto_play'],
			'loop' => $this->theSettings['playlist_repeat'],
			'vol' => $this->theSettings['initial_vol'],
			'flow' => 'n',
			'volslider' => $this->theSettings['volslider_on_singles'],
			//'cssclass' => '',
			'style' => ''
		), $atts));
		
		$cssclass = $style;
				
		$tn = $this->decide_S_playlist( $track, $caption );
		if ( !$tn ) { 
			$this->dbug['str'] .= "\nExiting (no track here)";
			return;
		}
		
		$divO = '<span class="' . $cssclass . '">';
		$divC = "</span>";
		$b = "";
		if ( $flow == "n" || $this->Caller == "widget" ) {
			$divO = ( $cssclass == "" ) ? '<div style="font-size:14px; line-height:22px !important; margin:0 !important;">' : '<div class="' . $cssclass . '">';
			$divC = "</div>";
		}
	
	// Set font weight
		$b = ( $bold == "false" || $bold == "0" || $bold == "n" ) ? " style=\"font-weight:500;\"" : " style=\"font-weight:700;\"";
		
	// Set spacer between elements depending on play/stop/title
		if ( $play != "" && $title != "" ){	
			$spacer = "&nbsp;"; 
		} else {
			$spacer = "";
			if ( $play == "" && $stop != "" ) { $stop = " " . $stop; }
		}
	// Prep title
		$customtitle = ( $title == "#USE#" ) ? "" : $title;
	// Make id'd span elements
		$openWrap = $divO . "<span id=\"playpause_wrap_mp3j_" . $id . "\" class=\"wrap_inline_mp3j\"" . $b . ">";
		$vol_h = ( $volslider == 'true' || $volslider == 'Y' || $volslider == 'y' ) ? "<span class=\"vol_mp3t\" id=\"vol_mp3j_" . $id . "\"></span>" : "";
		$pos = "<span class=\"bars_mp3j\"><span class=\"load_mp3j\" id=\"load_mp3j_" . $id . "\"></span><span class=\"posbar_mp3j\" id=\"posbar_mp3j_" . $id. "\"></span>" . $vol_h  . "</span>";
		$play_h = "<span class=\"textbutton_mp3j\" id=\"playpause_mp3j_" . $id . "\">" . $play . "</span>";
		$title_h = ( $title == "#USE#" || $title != "" ) ? "<span class=\"T_mp3j\" id=\"T_mp3j_" . $id . "\">" . $customtitle . "</span>" : "";
		$closeWrap = ( $ind != "y" ) ? "<span style=\"display:none;\" id=\"statusMI_" . $id . "\"></span></span>" . $divC : "<span class=\"indi_mp3j\" id=\"statusMI_" . $id . "\"></span></span>" . $divC;
	// Assemble them		
		$html = ( $flip != "l" ) ? $openWrap . $pos . $title_h . $spacer . $play_h . $closeWrap : $openWrap . $pos . $play_h . $spacer . $title_h . $closeWrap;
	// Add title to js footer string if needed 
		if ( $title_h != "" && $title == "#USE#" ) {
			$this->JS['Stitle'] .= "\t\tjQuery(\"#T_mp3j_" . $id . "\").append(MP3J_PLAYLISTS." . $tn['playername'] . "[" . ($tn['track']-1) . "].name);\n";
			$this->JS['Stitle'] .= "\t\tif (MP3J_PLAYLISTS." . $tn['playername'] . "[" . ($tn['track']-1) . "].artist !==''){ jQuery(\"#T_mp3j_" . $id . "\").append('<span style=\"font-size:.75em;\"> - '+MP3J_PLAYLISTS." . $tn['playername'] . "[" . ($tn['track']-1) . "].artist+'</span>'); }\n";
		}
	// Add info to js info array
		$autoplay = ( $autoplay == "true" || $autoplay == "y" || $autoplay == "1" ) ? "true" : "false";
		$loop = ( $loop == "true" || $loop == "y" || $loop == "1" ) ? "true" : "false";
		$this->JS['players'][] = "{ list: MP3J_PLAYLISTS." . $tn['playername'] . ", tr: " . ($tn['track']-1) . ", type: 'single', lstate: '', loop: " . $loop . ", play_txt: '" . $play . "', pause_txt: '" . $stop . "', pp_title: '', autoplay:" . $autoplay . ", download: false, vol: " . $vol . ", height: '' }";
		
		$this->write_jp_div();
		$this->dbug['str'] .= "\nOK (id " . $this->Player_ID . ")";
		$this->Player_ID++;
		return $html;
	}
		
			
/*	Handles [mp3j] shortcodes.
	single players with button graphic */	
	function inline_play_graphic( $atts, $content = null ) {
		
		$this->dbug['str'] .= "\n### Checking [mp3j]...";
		if ( !$this->Caller && (is_home() || is_archive() || is_search()) && $this->theSettings['player_onblog'] == "false" ) { 
			$this->dbug['str'] .= "\nExiting (player_onblog is unticked)";
			return;
		}
		
		$id = $this->Player_ID;			
		extract(shortcode_atts(array( // Defaults
			'bold' => 'y',
			'track' => '',
			'caption' => '',
			'flip' => 'r',
			'title' => '#USE#',
			'ind' => 'y',
			'autoplay' => $this->theSettings['auto_play'],
			'loop' => $this->theSettings['playlist_repeat'],
			'vol' => $this->theSettings['initial_vol'],
			'flow' => 'n',
			'volslider' => $this->theSettings['volslider_on_mp3j'],
			//'cssclass' => '',
			'style' => ''
		), $atts));
		
		$cssclass = $style;
				
		$tn = $this->decide_S_playlist( $track, $caption );
		if ( !$tn ) { 
			$this->dbug['str'] .= "\nExiting (no track here)";
			return;
		}
				
		$divO = '<span class="' . $cssclass . '">';
		$divC = "</span>";
		$b = "";
		if ( $flow == "n" || $this->Caller == "widget" ) {
			$divO = ( $cssclass == "" ) ? '<div style="font-size:14px; line-height:22px !important; margin:0 !important;">' : '<div class="' . $cssclass . '">';
			$divC = "</div>";
		}
	// Set font weight
		$b = ( $bold == "false" || $bold == "N" || $bold == "n" ) ? " style=\"font-weight:500;\"" : " style=\"font-weight:700;\"";
	// Prep title
		$customtitle = ( $title == "#USE#" ) ? "" : $title;
	// tell js it's graphics buttons
		$play = "#USE_G#";
	// Make id'd span elements
		$flippedcss = ( $flip == "r" ) ? "" : " flipped";
		$openWrap = $divO . "<span id=\"playpause_wrap_mp3j_" . $id . "\" class=\"wrap_inline_mp3j\"" . $b . ">";
		$vol_h = ( $volslider == 'true' || $volslider == 'y' || $volslider == 'Y' ) ? "<span class=\"vol_mp3j" . $flippedcss . "\" id=\"vol_mp3j_" . $id . "\"></span>" : "";
		$pos = "<span class=\"bars_mp3j\"><span class=\"loadB_mp3j\" id=\"load_mp3j_" . $id . "\"></span><span class=\"posbarB_mp3j\" id=\"posbar_mp3j_" . $id . "\"></span></span>";
		$play_h = "<span class=\"buttons_mp3j\" id=\"playpause_mp3j_" . $id . "\">&nbsp;</span>";
		$spacer = "";
		$title_h = ( $title == "#USE#" || $title != "" ) ? "<span class=\"T_mp3j\" id=\"T_mp3j_" . $id . "\">" . $customtitle . "</span>" : "";
		$indi_h = ( $ind != "y" ) ? "<span style=\"display:none;\" id=\"statusMI_" . $id . "\"></span>" : "<span class=\"indi_mp3j\" id=\"statusMI_" . $id . "\"></span>";
	// Assemble them		
		$html = ( $flip == "r" ) ? $openWrap . "<span class=\"group_wrap\">" . $pos . $title_h . $indi_h . "</span>" . $play_h . $vol_h . "</span>" . $divC : $openWrap . $play_h . "&nbsp;<span class=\"group_wrap\">" . $pos . $title_h . $indi_h . "</span>" . $vol_h . "</span>" . $divC;
	// Add title to js footer string if needed 
		if ( $title_h != "" && $title == "#USE#" ) {
			$this->JS['Stitle'] .= "\t\tjQuery(\"#T_mp3j_" . $id . "\").append(MP3J_PLAYLISTS." . $tn['playername'] . "[" . ($tn['track']-1) . "].name);\n";
			$this->JS['Stitle'] .= "\t\tif (MP3J_PLAYLISTS." . $tn['playername'] . "[" . ($tn['track']-1) . "].artist !==''){ jQuery(\"#T_mp3j_" . $id . "\").append('<span style=\"font-size:.75em;\"> - '+MP3J_PLAYLISTS." . $tn['playername'] . "[" . ($tn['track']-1) . "].artist+'</span>'); }\n";
		}
	// Add info to js info array
		$autoplay = ( $autoplay == "true" || $autoplay == "y" || $autoplay == "1" ) ? "true" : "false";
		$loop = ( $loop == "true" || $loop == "y" || $loop == "1" ) ? "true" : "false";
		$this->JS['players'][] = "{ list: MP3J_PLAYLISTS." . $tn['playername'] . ", tr:" . ($tn['track']-1) . ", type:'single', lstate:'', loop:" . $loop . ", play_txt:'" . $play . "', pause_txt:'', pp_title:'', autoplay:" . $autoplay . ", download:false, vol:" . $vol . ", height:'' }";
		
		$this->write_jp_div();
		$this->dbug['str'] .= "\nOK (id " . $this->Player_ID . ")";
		$this->Player_ID++;
		return $html;
	}	


/*	Work out playlist 
	for playlist players. */
	function decide_M_playlist( $fsort, $tracks, $captions, $id, $pick, $shuffle, $images = "", $imglinks = "" ) {
		$this->folder_order = $fsort;
		if ( !($Npl = $this->string_to_playlist( $tracks, $captions, $images, $imglinks )) ) { 
			if ( $tracks != "" && $id == "" ) { 
				return false;
			}
			if ( $id == "" && (is_home() || is_archive() || is_search()) && $this->Caller == "widget" ) { //dont allow widgets to try mode 1 on index pages
				return false;
			} 
			if ( ($meta = $this->meta_to_KVs($id)) ) {
				if ( !($Npl = $this->generate_playlist( $meta['Ks'], $meta['Vs'] )) ) { 
					return false; 
				}
			} else {
				return false;
			}				 
		}
		if ( $pick != "" && $pick >= 1 ) { $Npl = $this->pick_from_playlist( $pick, $Npl ); }
		if ( $shuffle ) { shuffle( $Npl['order'] ); }
		return $Npl;	
	}


/*	Handles [mp3-jplayer] 
	playlist player shortcodes. */	
	function primary_player ( $atts, $content = null ) {
		
		$this->dbug['str'] .= "\n### Checking [mp3-jplayer]...";
		if ( !$this->Caller && (is_home() || is_archive() || is_search()) && $this->theSettings['player_onblog'] == "false" ) { 
			$this->dbug['str'] .= "\nExiting (player_onblog is unticked)";
			return;
		}
		
		$pID = $this->Player_ID;
		extract(shortcode_atts(array( // Defaults
			'tracks' => '',
			'captions' => '',
			'dload' => $this->theSettings['show_downloadmp3'],
			'title' => '',
			'list' => $this->theSettings['playlist_show'],
			'pn' => 'y',
			'width' => '',
			'pos' => $this->theSettings['player_float'],
			'stop' => 'y',
			'shuffle' => false,
			'pick' => '',
			'mods' => false,
			'id' => '',
			'loop' => $this->theSettings['playlist_repeat'],
			'autoplay' => $this->theSettings['auto_play'],
			'vol' => $this->theSettings['initial_vol'],
			'height' => '',
			'fsort' => 'asc',
			'style' => '',
			'images' => '',
			'imglinks' => ''
		), $atts));
				
		$cssclass = $style;
		
		$Npl = $this->decide_M_playlist( $fsort, $tracks, $captions, $id, $pick, $shuffle, $images, $imglinks );
		if ( !$Npl ) { 
			$this->dbug['str'] .= "\nExiting (no tracks here)";
			return;
		}
	// Write it
		$PlayerName = "MI_" . $this->M_no; 
		$this->write_playlist_js( $Npl, $PlayerName );
		
	// Add info to js info array
		$trnum = 0;
		$pp_height = (int)$height;
		$pp_height = ( empty($pp_height) || $pp_height === 0 ) ? 'false' : $pp_height;
		$play = "#USE_G#";
		$pp_title = ( $title == "" ) ? get_bloginfo('name') : $title . " | " . get_bloginfo('name');
		$pp_title = str_replace("'", "\'", $pp_title);
		$pp_title = str_replace("&#039;", "\'", $pp_title);
		$list = ( $list == "true" || $list == "y" || $list == "1" ) ? "true" : "false";
				
		$addclass = ( $mods == "true" || $mods == "y" || $mods == "1" ) ? " mp3j_widgetmods" : "";
		
		if ( $dload == "true" || $dload == "y" || $dload == "1"  ) {
			$dload_info = "true";
			$dload_html = "<div id=\"download_mp3j_" . $pID . "\" class=\"dloadmp3-MI" . $addclass . "\"></div>";
		} elseif ( $dload == "loggedin" ) {
			if ( is_user_logged_in() ) {
				$dload_info = "true";
				$dload_html = "<div id=\"download_mp3j_" . $pID . "\" class=\"dloadmp3-MI" . $addclass . "\"></div>";
			} else {
				$dload_info = "false";
				if ( $this->theSettings['loggedout_dload_text'] == "" ) {
					$dload_html = "";
				} else {
					if ( $this->theSettings['loggedout_dload_link'] != "" ) {
						$dload_html = "<div id=\"download_mp3j_" . $pID . "\" class=\"dloadmp3-MI whilelinks" . $addclass . "\"><a href=\"" . $this->theSettings['loggedout_dload_link'] . "\">" . $this->theSettings['loggedout_dload_text'] . "</a></div>";
					} else {
						$dload_html = "<div id=\"download_mp3j_" . $pID . "\" class=\"dloadmp3-MI logintext" . $addclass . "\"><p>" . $this->theSettings['loggedout_dload_text'] . "</p></div>";
					}
				}
			}
		} else {
			$dload_info = "false";
			$dload_html = "";
		}
		
		$autoplay = ( $autoplay == "true" || $autoplay == "y" || $autoplay == "1" ) ? "true" : "false";
		$loop = ( $loop == "true" || $loop == "y" || $loop == "1" ) ? "true" : "false";
		
		$this->JS['players'][] = "{ list: MP3J_PLAYLISTS." . $PlayerName . ", tr: " . $trnum . ", type: 'MI', lstate: " . $list . ", loop: " . $loop . ", play_txt: '" . $play . "', pause_txt: '', pp_title: '" . $pp_title . "', autoplay: " . $autoplay . ", download: " . $dload_info . ", vol: " . $vol . ", height: " . $pp_height . ", cssclass: '" . $cssclass . "' }";
	
	// Make transport buttons
		$prevnext = ( $Npl['count'] > 1 && $pn == "y" ) ? "<div class=\"Next_mp3j\" id=\"Next_mp3j_" . $pID . "\">Next&raquo;</div><div class=\"Prev_mp3j\" id=\"Prev_mp3j_" . $pID . "\">&laquo;Prev</div>" : "";
		$play_h = "<div class=\"buttons_mp3j\" id=\"playpause_mp3j_" . $pID . "\">Play Pause</div>";
		$stop_h = ( $stop == "y" ) ? "<div class=\"stop_mp3j\" id=\"stop_mp3j_" . $pID . "\">Stop</div>" : "";
	
	// Build player html
		if ( $this->Caller && $width == "" ) { $width = "100%"; } //set a default width when called by tag/sc-widget and it wasn't specified
		$player = $this->write_primary_player( $pID, $pos, $width, $addclass, $dload_html, $title, $play_h, $stop_h, $prevnext, $height, $list, $Npl['count'], $cssclass );
		
		$this->write_jp_div();
		$this->dbug['str'] .= "\nOK (id " . $this->Player_ID . ")";
		$this->M_no++;
		$this->Player_ID++;
		return $player;
	}


/*	Handles [mp3-popout] shortcode
	link to popout player. */	
		function popout_link_player ( $atts, $content = null ) {
		
			$this->dbug['str'] .= "\n### Checking [mp3-popout]...";
			if ( !$this->Caller && (is_home() || is_archive() || is_search()) && $this->theSettings['player_onblog'] == "false" ) { 
				$this->dbug['str'] .= "\nExiting (player_onblog is unticked)";
				return;
			}
			
			$pID = $this->Player_ID;
			extract(shortcode_atts(array( // Defaults
				'tracks' => '',
				'captions' => '',
				'dload' => $this->theSettings['show_downloadmp3'],
				'title' => '',
				'text' => '',
				'list' => $this->theSettings['playlist_show'],
				'pos' => $this->theSettings['player_float'],
				'shuffle' => false,
				'pick' => '',
				'id' => '',
				'loop' => $this->theSettings['playlist_repeat'],
				'autoplay' => $this->theSettings['auto_play'],
				'vol' => $this->theSettings['initial_vol'],
				'height' => '',
				'tag' => 'p',
				'image' => '',
				'fsort' => 'asc',
				'style' => '',
				'images' => '',
				'imglinks' => ''
			), $atts));
			
			$cssclass = $style;
			
			$Npl = $this->decide_M_playlist( $fsort, $tracks, $captions, $id, $pick, $shuffle, $images, $imglinks );
			if ( !$Npl ) { 
				$this->dbug['str'] .= "\nExiting (no tracks here)";
				return;
			}
			
		// Write it
			$PlayerName = "popout_" . $this->M_no; 
			$this->write_playlist_js( $Npl, $PlayerName );

		// Add info to js info array
			$cssclass = ( $cssclass == "" ) ? "wrap-MI" : $cssclass; 
			$pp_height = (int)$height;
			$pp_height = ( empty($pp_height) || $pp_height === 0 ) ? 'false' : $pp_height;
			$play = "#USE_G#";
			$pp_title = ( $title == "" ) ? get_bloginfo('name') : $title;
			$pp_title = str_replace("'", "\'", $pp_title);
			$pp_title = str_replace("&#039;", "\'", $pp_title);
			$list = ( $list == "true" || $list == "y" || $list == "1" ) ? "true" : "false";
			$dload_info = ( $dload == "true" || $dload == "y" || $dload == "1" ) ? "true" : "false";
			$autoplay = ( $autoplay == "true" || $autoplay == "y" || $autoplay == "1" ) ? "true" : "false";
			$loop = ( $loop == "true" || $loop == "y" || $loop == "1" ) ? "true" : "false";
			$this->JS['players'][] = "{ list: MP3J_PLAYLISTS." . $PlayerName . ", tr:0, type:'popout', lstate:" . $list . ", loop:" . $loop . ", play_txt:'" . $play . "', pause_txt:'', pp_title:'" . $pp_title . "', autoplay:false, download:" . $dload_info . ", vol:" . $vol . ", height:" . $pp_height . ", cssclass: '" . $cssclass . "' }";
		
		//Make it	
			$image_h = ( $image == "" ) ? "<div class=\"mp3j-popout-link\"></div>" : "<img style=\"float:left; margin-right:10px;\" src=\"" . $image . "\" />";
			$player = '<div class="mp3j-popout-link-wrap" id="mp3j_popout_' . $pID . '">' . $image_h . '<'.$tag.'>' . $text . '</'.$tag.'></div>';
			
			$this->write_jp_div();
			$this->dbug['str'] .= "\nOK (id " . $this->Player_ID . ")";
			$this->M_no++;
			$this->Player_ID++;
			return $player;
		}


/*	Called via mp3j_put() 
	in template to run shortcodes. */
	function template_tag_handler( $stuff = "" ) {
		if ( $this->theSettings['disable_template_tag'] == "true" ) { 
			return;
		}
		if ( !empty($stuff) ) {
			$this->fieldgrab_check();
			$this->Caller = "tag";
			$players = do_shortcode( $stuff );				
			$this->Caller = false;
			echo $players;
		}
		return;			
	}
		
		
/*	Grabs current id's fields 
	if not done already. */
	function fieldgrab_check() {
		global $post;
		if ( $post->ID != "" && $post->ID != $this->postID ) {
			$this->postID = $post->ID; 
			$this->F_listname = false; 
			$this->F_listlength = false; 
			$this->S_autotrack = 0;
			$this->dbug['str'] .= "\nLooking in custom fields on post id " . $this->postID . " - ";
			
			if ( ($meta = $this->meta_to_KVs()) ) { 	
				$playlist = $this->generate_playlist( $meta['Ks'], $meta['Vs'] );
				if ( $playlist['count'] > 0 ) {
					$name = "fields_" . $this->F_no++;
					$this->write_playlist_js( $playlist, $name );
					$this->F_listname = $name;
					$this->F_listlength = $playlist['count'];
					$this->dbug['str'] .= "\nDone, " . $this->F_listlength . " track(s) found.";
				} else {
					$this->dbug['str'] .= "No tracks here.";
				}
			}
		}
		return $this->F_listname; 
	}


// Close class, close if.	
}}
?>