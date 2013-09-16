<?php
if ( !class_exists("MP3j_Main") ) { class MP3j_Main	{
	
	// ---------------------- Update Me
	var $version_of_plugin = "1.8.4"; 
	var $M_no = 0;
	var $F_no = 0;
	var $S_no = 0;
	var $Caller = false;
	var $S_autotrack = 0;
	var $S_arb = 1;
	var $Player_ID = 0;
	var $scriptsflag = "false";
	var $JPdiv = false;
	var $postID = false;
	var $F_listname = false;
	var $F_listlength = false;	
	var $LibraryI = false;
	var $JS = array(
		'playlists' => array(),
		'listref' => array(),
		'players' => array(),
		'Stitle' => ''	
	);
	var $dbug = array(
		'str' => '',
		'arr' => array()
	);
	var $adminOptionsName = "mp3FoxAdminOptions";
	var $theSettings = array();
	var $Rooturl;
	var $WPinstallpath;
	var $textdomain = "mp3-jplayer";
	var $newCSScustom;
	var $stylesheet = "";
	var $folder_order = "asc";
	var $PP_css_url = "";
	var $PP_css_settings = "";
	var $PluginFolder = "";
			
/*	Set some vars and make compatible */
	function MP3j_Main () { 
		$this->WPinstallpath = get_bloginfo('wpurl');
		//$this->theSettings = $this->getAdminOptions();
		$this->Rooturl = preg_replace("/^www\./i", "", $_SERVER['HTTP_HOST']);
		$this->PluginFolder = plugins_url('', __FILE__);
		$this->newCSScustom = $this->PluginFolder . "/css/player-silverALT.css";
		$this->theSettings = $this->getAdminOptions();
	}
	
/*	Returns library mp3 filenames, titles, 
	excerpts, content, uri's, id's in indexed arrays. */
	function grab_library_info() {		
		if ( !$this->LibraryI ) {
			$direction = $this->theSettings['library_direction'];
			switch( $this->theSettings['library_sortcol'] ) {
				case "date": 
					$order = " ORDER BY post_date " . $direction; 
					break;
				case "title":
					$order = " ORDER BY post_title " . $direction; 
					break;
				case "caption": 
					$order = " ORDER BY post_excerpt " . $direction . ", post_title " . $direction; 
					break;
				default: 
					$order = "";
			}
			global $wpdb;		
			$audio = $wpdb->get_results("SELECT DISTINCT guid, post_title, post_excerpt, post_content, ID FROM $wpdb->posts WHERE post_mime_type = 'audio/mpeg'" . $order);
			
			if ( !empty($audio) ) {
				foreach ( $audio as $obj ) {
					if ( preg_match("!\.mp3$!i", $obj->guid) ) { //audio/mpeg has multiple file types so grab just mp3's
						$Titles[] = $obj->post_title;
						$Excerpts[] = $obj->post_excerpt;
						$Descriptions[] = $obj->post_content;
						$PostIDs[] = $obj->ID;
						$URLs[] = $obj->guid;
						$File = strrchr( $obj->guid, "/");
						$Filenames[] = str_replace( "/", "", $File);
					}
				}		
				if ( !empty($Filenames) ) {
					if ( $this->theSettings['library_sortcol'] == "file" ) { 
						natcasesort($Filenames);
						if ( $direction == "DESC" ) {
							$Filenames = array_reverse($Filenames, true);
						}
					}
					$c = count($Filenames);
					$this->LibraryI = array(	
						'filenames' => $Filenames,
						'titles' => $Titles,
						'urls' => $URLs,
						'excerpts' => $Excerpts,
						'descriptions' => $Descriptions,
						'postIDs' => $PostIDs,
						'count' => $c
					);
				}
			}
			//$this->dbug['arr']['Library'] = $this->LibraryI;
		}
		return $this->LibraryI;
	}

/*	Reads mp3's from a local 
	directory, returns array of uri's */			
	function grab_local_folder_mp3s( $folder ) {
		$items = array();
		if ( ($lp = strpos($folder, $this->Rooturl)) || preg_match("!^/!", $folder) ) {
			if ( $lp !== false ) {
				$fp = str_replace($this->Rooturl, "", $folder);
				$fp = str_replace("www.", "", $fp);
				$fp = str_replace("http://", "", $fp);
				$fp = str_replace("https://", "", $fp);
			} else {
				$fp = $folder;
			}
			$path = $_SERVER['DOCUMENT_ROOT'] . $fp;
			if ($handle = @opendir($path)) {
				$j=0;
				while (false !== ($file = readdir($handle))) {
					if ( $file != '.' && $file != '..' && filetype($path.'/'.$file) == 'file' && preg_match("!\.mp3$!i", $file) ) {
						$items[$j++] = $file;
					}
				}
				closedir($handle);
				if ( ($c = count($items)) > 0 ) {
					natcasesort($items);
					if ( $this->folder_order != "asc" ) {
						$items = array_reverse($items, true);
					}
					$fp = preg_replace( "!/+$!", "", $fp );
					foreach ( $items as $i => $mp3 ) {
						$items[$i] = "http://" . $_SERVER['HTTP_HOST'] . $fp . "/" . $mp3;
					}
				}
				$this->dbug['str'] .= "\nRead folder - Done, " . $c . "mp3(s) in folder http://" . $_SERVER['HTTP_HOST'] . $fp;
				return $items; //the tracks array
			} else {
				$this->dbug['str'] .= "\nRead folder - Couldn't open local folder, check path/permissions to http://" . $_SERVER['HTTP_HOST'] . $fp;
				return true;
			}
		} else {
			$this->dbug['str'] .= "\nRead folder - Path was remote or unreadable." . $fp;
			return false;
		}
	}

/*	Makes keys/values from a post's meta, 
	returns K's (captions) and V's (title@file). */
	function meta_to_KVs( $id = "" ) {
		if ( $id == "" ) { 
			global $post;
			if ( $post->ID == "" ) { return false; }
			$id = $post->ID;
		}
		global $wpdb;
		$postmeta = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE post_id =" .$id. " AND meta_value!='' ORDER BY meta_key ASC");
		
		if ( !empty($postmeta) ) {
			$Ks = array();
			$Vs = array();
			foreach ( $postmeta as $obj ) {
				if ( preg_match('/^([0-9]+(\s)?)?mp3(\..*)?$/', $obj->meta_key) == 1 ) { 
					$Ks[] = $obj->meta_key;
					$Vs[] = $obj->meta_value;
				}
			}
			if ( !empty($Ks) ) { //sort by keys (user numbering), clean up captions, get feeds	
				natcasesort($Ks);
				foreach ($Ks as $i => $k ) {
					$splitkey = explode('.', $k, 2);
					$ks[] = ( empty($splitkey[1]) ) ? "" : $splitkey[1];
					$vs[] = $Vs[$i];
				}			
				$meta = $this->collect_delete_feeds( $vs, $ks ); //adds the feeds
				return array( 
					'Ks' => $meta['Ks'],
					'Vs' => $meta['Vs']
				);
			}
		}	
		return false;
	}

/*	Adds/makes playlist from comma separated 
	lists of tracks/captions. Returns a playlist. */			
	function string_to_playlist( $Vstring, $Kstring = "", $images = "", $imglinks = "" ) {
		$V_sep = $this->theSettings['f_separator']; // tracks separator
		$K_sep = $this->theSettings['c_separator']; // captions separator
	//clean strings
		$Vstring = str_replace( array("</p>", "<p>", "<br />", "<br>", "<br/>", chr(10), chr(13)), "", $Vstring );
		$Vstring = trim( $Vstring );
		$Vstring = trim( $Vstring, $V_sep );	
		if ( empty($Vstring) ) { 
			return false;
		}
		$Kstring = str_replace( array("</p>", "<p>", "<br />", "<br>", "<br/>", chr(10), chr(13)), "", $Kstring );
		$Kstring = trim( $Kstring );
		$Kstring = trim( $Kstring, $K_sep );
	
	//make V's
		$Vs = explode( $V_sep, $Vstring );
		foreach ( $Vs as $i => $file ) { 
			$Vs[$i] = trim($file); 
		}
	//make K's
		$ks = array();
		if ( !empty($Kstring) ) { 
			$ks = explode( $K_sep, $Kstring );
		}
		foreach ( $Vs as $i => $v ) {
			$Ks[$i] = (empty($ks[$i])) ? "" : trim($ks[$i]);
		}
	//add any feeds		
		$meta = $this->collect_delete_feeds( $Vs, $Ks );
		
		
	//make images array
		$imgs = array();
		if ( !empty($images) ) {
			$images = trim( $images, ',' ); 
			$imgs = explode( ',', $images );
		}
		foreach ( $meta['Vs'] as $i => $v ) {
			$IMGs[$i] = (empty($imgs[$i])) ? "" : trim($imgs[$i]);
		}
	//make image urls array
		$iurls = array();
		if ( !empty($imglinks) ) { 
			$imglinks = trim( $imglinks, ',' );
			$iurls = explode( ',', $imglinks );
		}
		foreach ( $meta['Vs'] as $i => $v ) {
			$IURLs[$i] = (empty($iurls[$i])) ? "" : trim($iurls[$i]);
		}
		
	//make playlist
		$pl = $this->generate_playlist( $meta['Ks'], $meta['Vs'], $IMGs, $IURLs );
		if ( $pl['count'] < 1 ) { 
			return false;
		}
		return $pl;
	}

/*	Adds any FEEDs into K's/V's */
	function collect_delete_feeds( $values, $keys ){
		foreach ( $values as $i => $val ) {  
			if ( preg_match( "!^FEED:(DF|ID|LIB|/.*)$!i", $val ) == 1 ) { // keep ID for backwards compat
				$feedV = stristr( $val, ":" );
				$feedV = str_replace( ":", "", $feedV );
				$feedK = ( !empty($keys[$i]) ) ? $keys[$i] : "";
				$fed = $this->get_feed( $feedK, $feedV ); // gets the FEED tracks
				foreach ( $fed['Vs'] as $j => $v ) {
					$Vs[] = $v;
					$Ks[] = $fed['Ks'][$j];
				}
			} else {
				$Vs[] = $val;
				$Ks[] = $keys[$i];
			}	
		}
		return array( 
			'Vs' => $Vs,
			'Ks' => $Ks
		);	
	}

/*	Takes a FEED key/value and makes
	tracks/captions arrays accordingly */
	function get_feed( $feedK, $feedV ) {
		
		$Vs = array();
		$Ks = array();
		
		if ( $feedV == "ID" ) { 
			// do nothing
			// since 1.5
		} elseif ( $feedV == "LIB" ) {
			$lib = $this->grab_library_info();
			if ( $lib ) {
				foreach ( $lib['filenames'] as $j => $x ) {
					$Vs[] = $x;
					$Ks[] = ( empty($feedK) ) ? $lib['excerpts'][$j] : $feedK;
				}
			}
		} else { //assume folder
			if ( $feedV == "DF" ) { 
				$feedV = $this->theSettings['mp3_dir'];
			}
			$tracks = $this->grab_local_folder_mp3s( $feedV ); 
			if ( $tracks !== true && $tracks !== false && count($tracks) > 0 ) {
				foreach ( $tracks as $j => $x ) {
					$Vs[] = $x;
					$Ks[] = $feedK;
				}
			}
		}
		return array( 
			'Vs' => $Vs,
			'Ks' => $Ks
		);	
	}

/*	Makes a playlist 
	from K's and V's */
	function generate_playlist( $ks, $vs, $IMGs = array(), $IURLs = array() ) {
		if ( count($vs) == 0 ) { 
			return false;
		}	
		$Ts = $this->splitout_KVs( $ks, $vs );
		$playlist = $this->compare_swap( $Ts, $vs, $IMGs, $IURLs );
		if ( $this->theSettings['allow_remoteMp3'] == "false" ) {
			$playlist = $this->remove_mp3remote( $playlist );
		}
		return ( empty($playlist) ) ? false : $playlist;
	}

/*	Splits out V's 
	into file and title arrays */
	function splitout_KVs( $ks, $vs ) {		
		$vs = str_replace( array(chr(10), chr(13)), "", $vs ); //remove \n and \r characters
		foreach ( $vs as $i => $v ) {
			$v_rev = strrev($v);
			$v_split = explode('@', $v_rev, 2);
	//filenames
			$Filenames[$i] = strrev($v_split[0]);
			if ( preg_match('/^www\./i', $Filenames[$i]) && $Filenames[$i] != "www.mp3" ) { //if it's url with no http
				$Filenames[$i] = "http://" . $Filenames[$i];
			}				
	//titles					
			if ( empty($v_split[1]) ) {
				$Titles[$i] = ( $this->theSettings['hide_mp3extension'] == "true" ) ? preg_replace( '/(\.mp3|\.m4a)$/i', "", $Filenames[$i] ) : $Filenames[$i];
			} else {
				$Titles[$i] = strrev($v_split[1]);
			}
		}
		return array( 'artists' => $ks, 'titles' => $Titles, 'files' => $Filenames );
	}
		
/*	Does caption, title, path 
	swapping and cleaning */
	function compare_swap( $Ts, $vs, $IMGs, $IURLs ) {
		$lib = $this->grab_library_info();
		foreach ( $Ts['files'] as $i => $file ) {
			$lib_ID = ( $lib === false ) ? false : array_search( $file, $lib['filenames'] );
			$http = ( strpos($file, 'http://') === false && strpos($file, 'https://') === false ) ? false : true;				
			
			if ( $lib_ID !== false ) { //in library
				$Ts['files'][$i] = $lib['urls'][$lib_ID];
				$Ts['titles'][$i] = ( strpos($vs[$i], '@') === false ) ? $lib['titles'][$lib_ID] : $Ts['titles'][$i];					
				$Ts['artists'][$i] = ( $Ts['artists'][$i] == "" ) ? $lib['excerpts'][$lib_ID] : $Ts['artists'][$i];
			} else {
				if ( $http ) { //uri
					if ( strpos($Ts['titles'][$i], 'http://') !== false || strpos($Ts['titles'][$i], 'https://') !== false ) {
						$Ts['titles'][$i] = strrchr($Ts['titles'][$i], "/");
						$Ts['titles'][$i] = str_replace( "/", "", $Ts['titles'][$i]);
					}
				} else { //local path 
					if ( strpos($Ts['files'][$i], "/") !== 0 ) { //prepend df path
						$Ts['files'][$i] = ( $this->theSettings['mp3_dir'] == "/" ) ? $this->theSettings['mp3_dir'] . $Ts['files'][$i] :  $this->theSettings['mp3_dir'] . "/" . $Ts['files'][$i];
					}
				}
			}
			$Order[] = $i;
			$IMGs[$i] = (empty($IMGs[$i])) ? "" : $IMGs[$i];
			$IURLs[$i] = (empty($IURLs[$i])) ? "" : $IURLs[$i];
		}
		$Ts['titles'] = str_replace('"', '\"', $Ts['titles']); //escape quotes for js
		$Ts['artists'] = str_replace('"', '\"', $Ts['artists']); //escape quotes for js				
		$n = count($Ts['files']);
		return array( 'artists' => $Ts['artists'], 'titles' => $Ts['titles'], 'files' => $Ts['files'], 'order' => $Order, 'count' => $n, 'images' => $IMGs, 'imgurls' => $IURLs );
	}
			
/*	Removes remote uri's. 
	if the admin option is not ticked */
	function remove_mp3remote( $Ts ) {	
		foreach ( $Ts['order'] as $ik => $i ) {
			if ( strpos($Ts['files'][$i], $this->Rooturl) !== false 
				|| (strpos($Ts['files'][$i], "http://") === false && strpos($Ts['files'][$i], "https://") === false) 
				|| (strpos($this->theSettings['mp3_dir'], "http://") !== false && strpos($Ts['files'][$i], $this->theSettings['mp3_dir']) !== false) ) {
				$Files[$i] = $Ts['files'][$i];
				$Titles[$i] = $Ts['titles'][$i];
				$Captions[$i] = $Ts['artists'][$i];
				$IMGs[$i] = $Ts['images'][$i];
				$IURLs[$i] = $Ts['imgurls'][$i];
				$Order[] = $i;
			}
		}
		$n = count($Files);
		return array( 'artists' => $Captions, 'titles' => $Titles, 'files' => $Files, 'order' => $Order, 'count' => $n, 'images' => $IMGs, 'imgurls' => $IURLs );
	} 	

/*	Writes jPlayer 
	div if needed */
	function write_jp_div() {
		if ( !$this->JPdiv ) {
			echo "\n<div id=\"mp3_jplayer_items\" style=\"position:relative;overflow:hidden;\">\n\t<div id=\"mp3_jplayer_1_8\" style=\"left:-999em;\"></div>\n</div>\n";
			$this->JPdiv = true;
			$this->dbug['str'] .= "\n(Added jp div)";
		}
	}
			
/*	Picks a random selection of n tracks from 
	the playlist while preserving track running order. */
	function pick_from_playlist( $slicesize, $plist ) {
		$no = trim($slicesize);
		if ( $no > $plist['count'] ) { $no = $plist['count']; } 
		$order = $plist['order'];
		shuffle($order);
		$order = array_slice($order, 0, $no);
		natsort($order);
		$plist['order'] = array_values($order);
		$plist['count'] = count($plist['order']);
		return $plist;
	}
	
/*	Looks for any active widget that isn't ruled out by 
	the page filter. Returns true if finds a widget that will be building. */		
	function has_allowed_widget( $type ) {
		$SBsettings = get_option('sidebars_widgets');
		if ( empty($SBsettings) || is_null($SBsettings) ) { return false; }
		
		$active = array();
		$scripts = false;
		foreach ( $SBsettings as $key => $arr ) { 
			if ( is_array($arr) && $key != "wp_inactive_widgets" ) {
				foreach ( $arr as $i => $widget ) {
					if ( strchr($widget, $type) ) {
						$active[] = $widget;
					} 
				}
			}
		}
		$this->dbug['arr'][] = $active;
		if ( !empty($active) ) { 
			$name = "widget_". $type;
			$ops = get_option($name);
			foreach ( $active as $i => $widget ) {
				$wID = strrchr( $widget, "-" );
				$wID = str_replace( "-", "", $wID );
				foreach ( $ops as $j => $arr ) {
					if ( $j == $wID ) {
						if ( !$this->page_filter($arr['restrict_list'], $arr['restrict_mode']) ) {
							$scripts = true;
							break 2;
						}
					}	
				}
			}
		}
		return $scripts;
	}

/*	Builds mode-3 widget
	playlist. */		
	function make_widget_playlist( $instance ) {
		$Vs = array();
		$Ks = array();
	// Grab meta from ID
		if ( !empty($instance['id_to_play']) && $instance['play_page'] == "true" ) {
			$id = trim($instance['id_to_play']);
			if ( ($meta = $this->meta_to_KVs($id)) ) {
				$Vs = $meta['Vs'];
				$Ks = $meta['Ks'];
			}
		}
	// Add library
		if ( $instance['play_library'] == "true" ) {
			$library = $this->grab_library_info();
			if ( $library['count'] >= 1 ) {
				foreach ( $library['filenames'] as $i => $v ) {
					$Vs[] = $v;
					$Ks[] = $library['excerpts'][$i];
				}
			}
		}
	// Add a local folder
		if ( $instance['play_folder'] == "true" ) {
			$folder = ( $instance['folder_to_play'] == "" ) ? $this->theSettings['mp3_dir'] : $instance['folder_to_play'];
			$tracks = $this->grab_local_folder_mp3s( $folder );
			if ( $tracks !== true && $tracks !== false && count($tracks) > 0 ) {
				foreach ( $tracks as $i => $v ) {
					$Vs[] = $v;
					$Ks[] = "";
				}
			}
		}
		if ( count($Vs) < 1 ) { return false; }
	// Make the playlist
		$thePlayList = $this->generate_playlist( $Ks, $Vs );
		if ( $thePlayList['count'] < 1 ) { return false; }
		return $thePlayList;
	}
		
		
/*	Checks current page against widget page-filter settings.
	returns true if widget should be filtered out. */	
	function page_filter( $list, $mode ) {
		$f = false;
		if ( !empty($list) ) {
			$pagelist = explode( ",", $list );
			if ( !empty($pagelist) ) {
				foreach ( $pagelist as $i => $id ) { 
					$pagelist[$i] = str_replace( " ", "", $id ); 
				}
			}
			if ( !is_singular() ) { //look for 'index' or 'archive' or 'search'
				if ( $mode == "include" ) {
					if ( is_home() ) {
						if ( strpos($list, "index") === false ) { $f = true; }
					}
					if ( is_archive() ) {
						if ( strpos($list, "archive") === false ) { $f = true; }
					}
					if ( is_search() ) {
						if ( strpos($list, "search") === false ) { $f = true; }
					}
				}
				if ( $mode == "exclude" ) {
					if ( is_home() ) {
						if ( strpos($list, "index") !== false ) { $f = true; }
					}
					if ( is_archive() ) {
						if ( strpos($list, "archive") !== false ) { $f = true; }
					}
					if ( is_search() ) {
						if ( strpos($list, "search") !== false ) { $f = true; }
					}
				}
			} else { //check the id's against current page
				global $post;
				$thisID = $post->ID;
				if ( $mode == "include" ) {
					$f = true;
					foreach ( $pagelist as $i => $id ) {
						if ( $id == $thisID ) { $f = false; }
					}
					
					if ( is_single() ) {
						if ( strpos($list, "post") !== false ) {
							$f = false;
						}
					}
				}
				if ( $mode == "exclude" ) {
					foreach ( $pagelist as $i => $id ) {
						if ( $id == $thisID ) { $f = true; }
					}
					
					if ( is_single() ) {
						if ( strpos($list, "post") !== false ) {
							$f = true;
						}
					}
				}
			}
		}
		return $f;
	}		
		
		
/*	Checks whether current post ID 
	content contains a shortcode. */
	function has_shortcodes ( $shtype = "" ) { 
		global $wpdb;
		global $post;
		if ( empty($post->ID) ) {
			return false;
		}
		$content = $wpdb->get_results("SELECT post_content FROM $wpdb->posts WHERE ID=" . $post->ID );
		$con = $content[0]->post_content;
		if ( $shtype != "" ) { //check for it
			if ( strpos($con, $shtype) !== false ) { 
				return true;
			}
		} else { //check for all player making shortcodes
			if ( strpos($con, "[mp3-jplayer") !== false || strpos($con, "[mp3j") !== false || strpos($con, "[mp3t") !== false || strpos($con, "[mp3-popout") !== false ) {
				return true;
			}
		}
		return false;
	}
	
/* Checks for a string in post content */
	function post_has_string ( $str = "" ) { 
		global $wpdb;
		global $post;
		if ( empty($post->ID) ) {
			return false;
		}
		$content = $wpdb->get_results("SELECT post_content FROM $wpdb->posts WHERE ID=" . $post->ID );
		$con = $content[0]->post_content;
		if ( strpos($con, $str) !== false ) {
			return true;
		}
		return false;
	}


/*	Swaps out links for player 
	shortcodes, hooked to the_content. */
	function replace_links ( $stuff = '' ) {
		if ( ( is_home() || is_archive() || is_search() ) && $this->theSettings['player_onblog'] == "false"	) {
			return $stuff;
		}
		$needles = array( '\"', '{TEXT}', '{URL}' );
		$replacers = array( '"', '$5', '$2' );
		$remove = "/<a ([^=]+=['\"][^\"']+['\"] )*href=['\"](([^\"']+\.mp3))['\"]( [^=]+=['\"][^\"']+['\"])*>([^<]+)<\/a>/i";
		$add = str_replace($needles, $replacers, $this->theSettings['make_player_from_link_shcode'] );
		
		return preg_replace( $remove, $add, $stuff );
	}


/*	Enqueues js and css scripts. */
	function add_Scripts( $theme ) {
		$version = substr( get_bloginfo('version'), 0, 3);
	//jquery and jquery-ui
		if ( $this->theSettings['disable_jquery_libs'] != "yes" ) {
			if ( $version >= 3.1 ) {
				wp_enqueue_script( 'jquery-ui-slider', $this->PluginFolder . '/js/ui.slider.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse' ), '1.8.10' );
			} else { //pre WP 3.1
				wp_enqueue_script( 'jquery-ui-widget', $this->PluginFolder . '/js/ui.widget.js', array( 'jquery', 'jquery-ui-core' ), '1.8.10' );
				wp_enqueue_script( 'jquery-ui-mouse', $this->PluginFolder . '/js/ui.mouse.js', false, '1.8.10' );
				wp_enqueue_script( 'jquery-ui-slider', $this->PluginFolder . '/js/ui.slider.js', false, '1.8.10' );
			}
			if ( $this->theSettings['touch_punch_js'] == "true" ) { // add ui patch for touch screens
				wp_enqueue_script( 'jquery-touch-punch', $this->PluginFolder . '/js/jquery.ui.touch-punch.min.js', false, '0.2.2' );
			}
			$this->dbug['str'] .= "\nScript request added (jQuery & UI)"; //TODO sort 1st thing in dbug!
		} else {
			$this->dbug['str'] .= "\nScripts are OFF (jQuery & UI)";
		}
	//jplayer and plugin js
		wp_enqueue_script( 'jquery.jplayer.min', $this->PluginFolder . '/js/jquery.jplayer.min.js', false, '2.3.0' );
		wp_enqueue_script( 'mp3-jplayer', $this->PluginFolder . '/js/mp3-jplayer-1.8.3.js', false, '1.8.3' );
	//css
		if ( $theme == "styleF" ) { $themepath = $this->PluginFolder . "/css/players-1-8-silver.css"; }
		elseif ( $theme == "styleG" ) { $themepath = $this->PluginFolder . "/css/players-1-8-dark.css"; }
		elseif ( $theme == "styleH" ) { $themepath = $this->PluginFolder . "/css/players-1-8-text.css"; }
		elseif ( $theme == "styleI" ) {	$themepath = ( $this->theSettings['custom_stylesheet'] == "/" ) ? $this->newCSScustom : $this->theSettings['custom_stylesheet']; }
		else { $themepath = $theme; }
		$name = strrchr( $themepath, "/");
		$name = str_replace( "/", "", $name);
		$name = str_replace( ".css", "", $name);
		wp_enqueue_style( $name, $themepath );
		
		$this->dbug['str'] .= "\nScript request added (MP3-jPlayer and css)\n";
		
		$this->write_user_style( $theme );
		$this->PP_css_url = ( strpos($themepath, "http://") === false ) ? $this->WPinstallpath . $themepath : $themepath;
		return;
	}
	
	
/*	Writes user colour settings, and 
	creates js property of popout css. */
	function write_user_style( $theme ) {
		$settings = $this->theSettings;
		if ( $settings['use_fixed_css'] == "false" ) { 	
			$pluginpath = $this->PluginFolder . "/";
			$colours = $this->set_colours( $settings['colour_settings'], $theme, $pluginpath );
			
			$screen_opac = "; opacity:" . $colours['screen_opacity']*0.01 . "; filter:alpha(opacity=" . $colours['screen_opacity'] . ")";
			$loaderbar_opac = "; opacity:" . $colours['loadbar_opacity']*0.01 . "; filter:alpha(opacity=" . $colours['loadbar_opacity'] . ")";
			$posbar_opac = "; opacity:" . $colours['posbar_opacity']*0.01 . "; filter:alpha(opacity=" . $colours['posbar_opacity'] . ")";
			$playlist_opac = "; opacity:" . $colours['playlist_opacity']*0.01 . "; filter:alpha(opacity=" . $colours['playlist_opacity'] . ")";
			
			switch( $colours['posbar_tint'] ) {
				case "soften": $posbar_tint = " url('" . $pluginpath . "css/images/posbar-soften-2.png') repeat-y right top"; break;
				case "softenT":	$posbar_tint = " url('" . $pluginpath . "css/images/posbar-soften-tipped-2.png') repeat-y right top"; break;
				case "darken": $posbar_tint = " url('" . $pluginpath . "css/images/posbar-darken2-2.png') repeat-y right top"; break;
				case "none": $posbar_tint = "";
			}
			switch( $colours['playlist_tint'] ) {
				case "lighten1": $playlist_img = " url('" . $pluginpath . "css/images/pl-lighten1.png') repeat-x left 0px";	break;
				case "lighten2": $playlist_img = " url('" . $pluginpath . "css/images/pl-lighten2.png') repeat-x left 0px";	break;
				case "darken1": $playlist_img = " url('" . $pluginpath . "css/images/pl-gradlong10g.png') repeat-x left -130px"; break;
				case "darken2": $playlist_img = " url('" . $pluginpath . "css/images/pl-darken1.png') repeat-x left 0px"; break;
				case "none": $playlist_img = "transparent";
			}
			switch( $colours['list_divider'] ) {
				case "light": $playlist_divider = "transparent url('" . $pluginpath . "css/images/t60w.png') repeat-x left bottom";	break;
				case "med": $playlist_divider = "transparent url('" . $pluginpath . "css/images/t75e.png') repeat-x left bottom"; break;
				case "dark": $playlist_divider = "transparent url('" . $pluginpath . "css/images/t50g.png') repeat-x left bottom"; break;
				case "none": $playlist_divider = "transparent; background-image:none";
			}
			
			$listBGa = "none";
			$vol_grad = ( $colours['volume_grad'] == "light" ) ? "transparent url('" . $pluginpath . "css/images/vol-grad60w2.png') repeat-y -15px top" : "transparent url('" . $pluginpath . "css/images/vol-grad60b2.png') repeat-y 0px top";
			$opac = ( $colours['indicator'] == "tint" ) ? "35" : "100";
			$indicator = ( $colours['indicator'] == "tint" ) ? "#ccc" : $colours['posbar_colour'];
			$gif_opac = "opacity:" . $opac*0.01 . "; filter:alpha(opacity=" . $opac . ")";
							
			echo "\n\n<style type=\"text/css\">
	div.jp-interface { color:" . $colours['screen_text_colour'] . "; }
	div.innertab { background:" . $colours['screen_colour'] . $screen_opac . "; }
	span.mp3-tint { background:" . $indicator . "; } 
	div.playlist-colour { background:" . $colours['playlist_colour'] . $playlist_opac . "; }
	div.loadMI_mp3j, span.loadB_mp3j, span.load_mp3j { background:" . $colours['loadbar_colour'] . $loaderbar_opac . "; } 
	div.poscolMI_mp3j { background:" . $colours['posbar_colour'] . $posbar_tint . $posbar_opac ."; } 
	div.MIsliderVolume .ui-widget-header { background:" . $vol_grad . "; } 
	ul.UL-MI_mp3j { background:" . $playlist_img . " !important; } 
	ul.UL-MI_mp3j li { background:" . $playlist_divider . " !important; } 
	ul.UL-MI_mp3j li a { background-image:none !important; color:" . $colours['list_text_colour'] . " !important; } 
	ul.UL-MI_mp3j li a:hover { background-image:none !important; color:" . $colours['list_hover_colour'] . " !important; background:" . $colours['listBGa_hover'] . " !important; } 
	ul.UL-MI_mp3j li a.mp3j_A_current { background-image:none !important; color:" . $colours['list_current_colour'] . " !important; background:" . $colours['listBGa_current'] . " !important; } 
	span.mp3j-link-play, span.textbutton_mp3j:hover, div.transport-MI div { color:" . $colours['list_hover_colour'] . "; } 
	span.mp3j-link-play:hover, span.textbutton_mp3j, div.transport-MI div:hover { color:" . $colours['list_current_colour'] . "; }
	div.transport-MI div, div.mp3j-popout-MI:hover { background-color:" . $colours['list_current_colour'] . "; }
	.MI-image a:hover img { background:" . $colours['list_current_colour'] . ";}
</style>";
								
			if ( $settings['enable_popout'] == "true" ) {
				$popout_bg = ( $settings['popout_background'] == "" ) ? "#fff" : $settings['popout_background'];
				$this->PP_css_settings = "
	MP3_JPLAYER.popout_css = {
		body_col: \"" . $popout_bg . "\",
		body_img: \"" . $settings['popout_background_image'] . "\",
		screen_bg: \"" . $colours['screen_colour'] . "\",
		screen_opac: \"" . $colours['screen_opacity']*0.01 . "\",
		screen_text: \"" . $colours['screen_text_colour'] . "\",
		indi_tint: \"" . $indicator . "\",
		loader_col: \"" . $colours['loadbar_colour'] . "\",
		loader_opac: \"" . $colours['loadbar_opacity']*0.01 . "\",
		posbar_col: \"" . $colours['posbar_colour'] . $posbar_tint . "\",
		posbar_opac: \"" . $colours['posbar_opacity']*0.01 . "\",
		vol_slider_bg: \"" . $vol_grad . "\",
		list_col: \"" . $colours['playlist_colour'] . "\",
		list_img: \"" . $playlist_img . "\",
		list_opac: \"" . $colours['playlist_opacity']*0.01 . "\",
		list_divider: \"" . $playlist_divider . "\",
		list_text: \"" . $colours['list_text_colour'] . "\",
		list_current_text: \"" . $colours['list_current_colour'] . "\",
		list_hover_text: \"" . $colours['list_hover_colour'] . "\",
		list_current_bg: \"" . $colours['listBGa_current'] . "\",
		list_hover_bg: \"" . $colours['listBGa_hover'] . "\"
	};";

			}// end if enable popout
		}// end if not fixed_css
		return;
	}		

		
/*	Sets up the colours array prior to writing
	according to style / user colours / defaults. */
	function set_colours( $current, $style, $pluginpath ) {
		$silver = array( // defaults
			'screen_colour' => '#a7a7a7', 'screen_opacity' => '35',
			'loadbar_colour' => '#34A2D9', 'loadbar_opacity' => '70',
			'posbar_colour' => '#5CC9FF', 'posbar_opacity' => '80', 'posbar_tint' => 'softenT',
			'playlist_colour' => '#f1f1f1', 'playlist_opacity' => '100', 'playlist_tint' => 'darken1', 'list_divider' => 'med',
			'screen_text_colour' => '#525252', 
			'list_text_colour' => '#525252', 'list_current_colour' => '#47ACDE', 'list_hover_colour' => '#768D99',
			'listBGa_current' => '#f4f4f4', 'listBGa_hover' => '#f7f7f7',
			'indicator' => 'colour',
			'volume_grad' => 'light'
		);
		$darkgrey = array( // defaults
			'screen_colour' => '#333', 'screen_opacity' => '15',
			'loadbar_colour' => '#34A2D9', 'loadbar_opacity' => '70',
			'posbar_colour' => '#5CC9FF', 'posbar_opacity' => '100', 'posbar_tint' => 'darken',
			'playlist_colour' => '#fafafa', 'playlist_opacity' => '100', 'playlist_tint' => 'darken2', 'list_divider' => 'none',
			'screen_text_colour' => '#525252', 
			'list_text_colour' => '#525252', 'list_current_colour' => '#34A2D9', 'list_hover_colour' => '#768D99',
			'listBGa_current' => "transparent url('" . $pluginpath . "css/images/t40w.png') repeat", 'listBGa_hover' => "transparent url('" . $pluginpath . "css/images/t30w.png') repeat",
			'indicator' => 'colour',
			'volume_grad' => 'dark'
		);
		$text = array( // defaults
			'screen_colour' => 'transparent', 'screen_opacity' => '100',
			'loadbar_colour' => '#aaa', 'loadbar_opacity' => '20',
			'posbar_colour' => '#fff', 'posbar_opacity' => '58', 'posbar_tint' => 'none',
			'playlist_colour' => '#f6f6f6', 'playlist_opacity' => '100', 'playlist_tint' => 'lighten2', 'list_divider' => 'none',
			'screen_text_colour' => '#869399',
			'list_text_colour' => '#777', 'list_current_colour' => '#47ACDE', 'list_hover_colour' => '#829FAD',
			'listBGa_current' => 'transparent', 'listBGa_hover' => 'transparent',
			'indicator' => 'tint',
			'volume_grad' => 'dark'
		);
		switch( $style ) {
			case "styleG": $colours = $darkgrey; break;
			case "styleH": $colours = $text; break;
			default: $colours = $silver;
		}
		if ( !empty($current) ) {
			foreach ( $current as $key => $val ) {
				if ( $val != "" ) {
					$colours[$key] = $val;
				} 
			}
		}
		return $colours;	
	}
						
/*	Makes js playlist array and stores it for echoing out in footer,
	adds list name to js listref array  */
	function write_playlist_js( $list, $name = "noname", $numbering = false ) {
		if ( $list['count'] < 1 ) { return; }
		if ( $this->theSettings['encode_files'] == "true" ) {
			foreach ( $list['files'] as $k => $file ) { 
				$list['files'][$k] = base64_encode($file);
			}
		}
		$no = 1;
		
		$js = $name . ": [";
		foreach ( $list['order'] as $ik => $i ) {
			$js .= "\n\t\t{ name: \"";
			if ( $this->theSettings['add_track_numbering'] == "true" ) { 
				//$js .= $no . ". ";
				$numdisplay = ( $numbering === false ) ? $no : $numbering; 
				$js .= $numdisplay . ". ";
			}
			//$js .= $list['titles'][$i]. "\", mp3: \"" .$list['files'][$i]. "\", artist: \"" .$list['artists'][$i]. "\" }";
			$js .= $list['titles'][$i]. "\", mp3: \"" .$list['files'][$i]. "\", artist: \"" .$list['artists'][$i]. "\", image: \"" .$list['images'][$i]. "\", imgurl: \"" .$list['imgurls'][$i]. "\" }";
			
			if ( $no != $list['count'] ) { 
				$js .= ","; 
			}
			$no++;
		}
		$js .= "\n\t]";
		$this->JS['playlists'][] = $js;
		$this->JS['listref'][] = $name;
		$c = count($this->JS['playlists']);
		return;
	}

/*	Writes [mp3-jplayer] 
	player html */
	function write_primary_player( $pID, $pos, $width, $addclass, $dload_html, $title = "", $play_h, $stop_h, $prevnext, $height = "", $list, $npl_count, $cssclass = "" ) {
		$pad_t = $this->theSettings['paddings_top'];
		$pad_b = $this->theSettings['paddings_bottom'];
		$pad_i = $this->theSettings['paddings_inner'];
		if ( $pos == "left" ) { 
			$floater = "float:left; padding:" . $pad_t . " " . $pad_i . " " . $pad_b . " 0px;";
		} else if ( $pos == "right" ) { 
			$floater = "float:right; padding:" . $pad_t . " 0px " . $pad_b . " " . $pad_i . ";";
		} else if ( $pos == "absolute" ) {
			$floater = "position:absolute;";
		} else if ( $pos == "rel-C" ) { 
			$floater = "position:relative; padding:" . $pad_t . " 0px " . $pad_b . " 0px; margin:0px auto 0px auto;"; 
		} else if ( $pos == "rel-R" ) { 
			$floater = "position:relative; padding:" . $pad_t . " 0px " . $pad_b . " 0px; margin:0px 0px 0px auto;"; 
		} else { 
			$floater = "position: relative; padding:" . $pad_t . " 0px " . $pad_b . " 0px; margin:0px;";
		}
		$width = ( $width == "" ) ? " width:" . $this->theSettings['player_width'] . ";" : " width:" . $width . ";";
		$height = ( !empty($height) && $height != "" ) ? " style=\"height:" . $height . ";\"" : ""; //will just use css sheet setting if empty
		$title = ( $title == "" ) ? "" : "<h2>" . $title . "</h2>";
		$Tpad = ( $this->theSettings['add_track_numbering'] == "false" ) ? " style=\"padding-left:6px;\"" : "";
		$showpopoutbutton = ( $this->theSettings['enable_popout'] == "true" ) ? "visibility: visible;" : "visibility: hidden;";
		$popouttext = ( $this->theSettings['player_theme'] == "styleH" && $this->theSettings['popout_button_title'] == "") ? "Pop-Out" : $this->theSettings['popout_button_title'];
		$PLscroll = ( $this->theSettings['max_list_height'] != "" ) ? " style=\"overflow:auto; max-height:" . $this->theSettings['max_list_height'] . "px;\"" : "";
		$list = ( $list == "true" ) ? "HIDE" : "SHOW";
		$listtog_html = ( $npl_count > 1 ) ? "<div class=\"playlist-toggle-MI" . $addclass . "\" id=\"playlist-toggle_" . $pID. "\">" . $list . " PLAYLIST</div>" : "";
		
		$img_html = '<div class="MI-image" id="MI_image_' . $pID . '"></div>';
				
		$dlframe = '
			<div id="mp3j_finfo_' . $pID . '" class="mp3j-finfo" style="display:none;">
				<div class="mp3j-finfo-sleeve">
					<div id="mp3j_finfo_gif_' . $pID . '" class="mp3j-finfo-gif"></div>
					<div id="mp3j_finfo_txt_' . $pID . '" class="mp3j-finfo-txt"></div>
					<div class="mp3j-finfo-close" id="mp3j_finfo_close_' . $pID . '">X</div>
				</div>
			</div>
			<div id="mp3j_dlf_' . $pID . '" class="mp3j-dlframe" style="display:none;"></div>';			
			
		$dlframe_html = ( $this->theSettings['force_browser_dload'] == "true" ) ? $dlframe : "";
			
		$list_html = "
	<div class=\"listwrap_mp3j\" id=\"L_mp3j_" . $pID . "\"" . $PLscroll . ">
		<div class=\"playlist-wrap-MI\">
			<div class=\"playlist-colour\"></div>
			<div class=\"playlist-wrap-MI\">
					<ul class=\"UL-MI_mp3j" . $addclass . "\" id=\"UL_mp3j_" . $pID . "\"><li></li></ul>
			</div>
		</div>
	</div>";
		
		$player = "\n
<div id=\"wrapperMI_" . $pID . "\" class=\"wrap-MI " . $cssclass . "\" style=\"" . $floater . $width . "\">" . $title . "
	<div class=\"jp-innerwrap\">
		<div class=\"innerx\"></div>
		<div class=\"innerleft\"></div>
		<div class=\"innerright\"></div>
		<div class=\"innertab\"></div>\n
		<div class=\"jp-interface\"" . $height . " id=\"interfaceMI_" . $pID . "\">
			" . $img_html . "
			<div id=\"T_mp3j_" . $pID . "\" class=\"player-track-title" . $addclass . "\"" . $Tpad . "></div>
			<div class=\"MIsliderVolume\" id=\"vol_mp3j_" . $pID . "\"></div>
			<div class=\"bars_holder\">
				<div class=\"loadMI_mp3j\" id=\"load_mp3j_" . $pID . "\"></div>
				<div class=\"poscolMI_mp3j\" id=\"poscol_mp3j_" . $pID . "\"></div>
				<div class=\"posbarMI_mp3j\" id=\"posbar_mp3j_" . $pID . "\"></div>
			</div>
			<div id=\"P-Time-MI_" . $pID . "\" class=\"jp-play-time\"></div>
			<div id=\"T-Time-MI_" . $pID . "\" class=\"jp-total-time\"></div>
			<div id=\"statusMI_" . $pID . "\" class=\"statusMI" . $addclass . "\"></div>
			<div class=\"transport-MI\">" . $play_h . $stop_h . $prevnext . "</div>
			" . $dload_html . "
			" . $listtog_html . "
			" . $dlframe_html . "
			<div class=\"mp3j-popout-MI" . $addclass . "\" id=\"lpp_mp3j_" . $pID. "\" style=\"" .$showpopoutbutton. "\">" . $popouttext . "</div>
		</div>
	</div>
	" . $list_html . "
</div>\n";
		
		return $player;
	}
		
/*	Stores and returns 
	updated compatible options. */
	function getAdminOptions() {
		$colour_keys = array(
			'screen_colour' => '',
			'screen_opacity' => '',
			'loadbar_colour' => '',
			'loadbar_opacity' => '',
			'posbar_colour' => '',
			'posbar_opacity' => '',
			'posbar_tint' => '',
			'playlist_colour' => '',
			'playlist_opacity' => '',
			'playlist_tint' => '',
			'list_divider' => '',
			'screen_text_colour' => '', 
			'list_text_colour' => '',
			'list_current_colour' => '',
			'list_hover_colour' => '',
			'listBGa_current' => '',
			'listBGa_hover' => '',
			'indicator' => '',
			'volume_grad' => ''
		);
		$mp3FoxAdminOptions = array( // defaults
			'initial_vol' => '100',
			'auto_play' => 'false',
			'mp3_dir' => '/',
			'player_theme' => 'styleF',
			'allow_remoteMp3' => 'true',
			'player_float' => 'none',
			'player_onblog' => 'true',
			'playlist_show' => 'true',
			'remember_settings' => 'true',
			'hide_mp3extension' => 'false',
			'show_downloadmp3' => 'false',
			'disable_template_tag' => 'false',
			'db_plugin_version' => $this->version_of_plugin,
			'custom_stylesheet' => $this->newCSScustom,
			'echo_debug' => 'false',
			'add_track_numbering' => 'false',
			'enable_popout' => 'true',
			'playlist_repeat' => 'false',
			'player_width' => '40%',
			'popout_background' => '',
			'popout_background_image' => '',
			'colour_settings' => $colour_keys,
			'use_fixed_css' => 'false',
			'paddings_top' => '5px',
			'paddings_bottom' => '40px',
			'paddings_inner' => '35px',
			'popout_max_height' => '600',
			'popout_width' => '400',
			'popout_button_title' => '',
			'max_list_height' => '450',
			'encode_files' => 'true',
			'animate_sliders' => 'false',
			'library_sortcol' => 'filename',
			'library_direction' => 'ASC',
			'disable_jquery_libs' => '',
			'run_shcode_in_excerpt' => 'false',
			'admin_toggle_1' => 'false',
			'f_separator' => ',',
			'c_separator' => ';',
			'volslider_on_singles' => 'false',
			'volslider_on_mp3j' => 'false',
			'dload_text' => 'DOWNLOAD MP3',
			'loggedout_dload_text' => 'LOG IN TO DOWNLOAD',
			'loggedout_dload_link' => $this->WPinstallpath . '/wp-login.php',
			'touch_punch_js' => 'true',
			'force_browser_dload' => 'true',
			//'force_browser_dload_remote' => 'false',
			'dloader_remote_path' => '',
			'make_player_from_link' => 'true',
			'make_player_from_link_shcode' => '[mp3j track="{TEXT}@{URL}" volslider="y" style="outline"]'
		);
		$theOptions = get_option($this->adminOptionsName);							
		if ( !empty($theOptions) ) {
	//backwards compat with v1.4 style
			$xfer = $this->transfer_old_colours( $theOptions['player_theme'], $colour_keys, $theOptions['custom_stylesheet'] ); 
			if ( $xfer[0] ) {
				$theOptions['player_theme'] = $xfer[0];
				$theOptions['custom_stylesheet'] = $xfer[2];
			}			
	//ditch un-needed stored settings
			foreach ( $theOptions as $key => $option ){
				if ( array_key_exists( $key, $mp3FoxAdminOptions) ) {
					$mp3FoxAdminOptions[$key] = $option;
				}
			}
			$mp3FoxAdminOptions['db_plugin_version'] = $this->version_of_plugin; //set last!
		}
		update_option($this->adminOptionsName, $mp3FoxAdminOptions);
		return $mp3FoxAdminOptions;
	}
		
		
/*	translates colour style from old options 
	to the 1.7 format prior to saving them. */
	function transfer_old_colours ( $s, $keys, $path = "" ) {
		$csspath = $this->PluginFolder . "/css/mp3jplayer-cyanALT.css"; //orig alternative stylesheet name in v1.4.x 
		$path = ( $path == $csspath || $path == "" ) ? $this->newCSScustom : $path;
		if ( $s == "styleA" ) { //orig 'neutral'
			$s = "styleF";
		} elseif ( $s == "styleB" ) { //orig 'green'
			$s = "styleF";
		} elseif ( $s == "styleC" ) { //orig 'blu'
			$s = "styleF";
		} elseif ( $s == "styleD" ) { //orig 'cyanALT', or custom css
			$s = "styleI";
		} elseif ( $s == "styleE" ) { //orig 'text'
			$s = "styleH";
		} else { 
			$s = false; 
		}
		return array( $s, $keys, $path );
	}

/*	Adds css to settings page. */
	function mp3j_admin_header() {
		echo "\n<link rel=\"stylesheet\" href=\"" .  $this->PluginFolder . "/css/mp3j-admin-1.8.css\" type=\"text/css\" media=\"screen\" />\n";
	}
		
/*	Adds js to settings page. */
	function mp3j_admin_footer() {
		echo "\n<script type=\"text/javascript\" src=\"" . $this->PluginFolder . "/js/mp3j-admin-1.8.js\"></script>";
	}

/*	Preps path/uri option on settings page prior to saving. */
	function prep_path ( $field ) {
		$option = preg_replace( "!^www*\.!", "http://www.", $field );
		if ( strpos($option, "http://") === false && strpos($option, "https://") === false) {
			if (preg_match("!^/!", $option) == 0) { 
				$option = "/" . $option; 
			} else { 
				$option = preg_replace("!^/+!", "/", $option); 
			} 
		}
		if (preg_match("!.+/+$!", $option) == 1) {
			$option = preg_replace("!/+$!", "", $option); 
		}
		if ($option == "") {
			$option = "/";
		}
		return $option;
	}
	
/*	Debug output via mp3j_debug() or admin settings. */	
	function debug_info( $display = "" ) {	
		echo "\n\n<!-- *** MP3-jPlayer - " . "version " . $this->version_of_plugin . " ***\n";
		if ( is_singular() ) { echo "\nTemplate: Singular "; }
		if ( is_single() ) { echo "Post"; }
		if ( is_page() ) { echo "Page"; }
		if ( is_search() ) { echo "\nTemplate: Search"; }
		if ( is_home() ) { echo "\nTemplate: Posts index"; }
		if ( is_front_page() ) { echo " (Home page)"; }
		if ( is_archive() ) { echo "\nTemplate: Archive"; }
		echo "\nUse tags: ";
		if ( $this->theSettings['disable_template_tag'] == "false" ) { 
			echo "Yes";
		} else { 
			echo "No";
		}
		echo "\n" . $this->dbug['str'] . "\n";
		echo "\nPlayer count: " . $this->Player_ID;
		echo "\n\nAdmin Settings:\n"; 
		print_r($this->theSettings);
		$this->grab_library_info();
		echo "\nMP3's in Media Library: " . $this->LibraryI['count'];
		echo "\n\nOther arrays:\n";
		foreach ( $this->dbug['arr'] as $i => $a ) {
			if ( is_array($a) ) {
				echo "\n" . $i . "\n";
				print_r($a);
			}
		}
		echo "\n-->\n\n";
		return;	
	}
}} // close class, close if.
?>