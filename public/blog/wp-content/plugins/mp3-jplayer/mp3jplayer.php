<?php 
/* 
Plugin Name: MP3-jPlayer
Plugin URI: http://sjward.org/jplayer-for-wordpress
Description: Add mp3 players to posts, pages, and sidebars. HTML5 with Flash fall back. Shortcodes, widgets, and template tags. See the help on the Settings Page for a full list of options. 
Version: 1.8.4
Author: Simon Ward
Author URI: http://www.sjward.org
License: GPL2
  	
	Copyright 2012 Simon Ward
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$mp3j_path = dirname(__FILE__);
include_once( $mp3j_path . '/mp3j_main.php');
include_once( $mp3j_path . '/mp3j_frontend.php'); //extends main

if ( class_exists("MP3j_Front") ) {
	$mp3_fox = new MP3j_Front();
}

if ( isset($mp3_fox) ) {
	include_once( $mp3j_path . '/mp3j_widget.php'); //ui widget
	include_once( $mp3j_path . '/mp3j_sc-widget.php'); //sh Widget
	
	if ( is_admin() ) {
		include_once( $mp3j_path . '/mp3j_admin.php'); //settings page

		function mp3j_adminpage() { //add a settings menu page	
			global $mp3_fox;
			if ( function_exists('add_options_page') ) {
				$pluginpage = add_options_page('MP3 jPlayer', 'MP3 jPlayer', 'manage_options', basename(__FILE__), 'mp3j_print_admin_page');  
				add_action( 'admin_head-'. $pluginpage, array(&$mp3_fox, 'mp3j_admin_header') ); 
				add_action( 'admin_footer-'. $pluginpage, array(&$mp3_fox, 'mp3j_admin_footer') );
				add_filter( 'plugin_action_links', 'mp3j_plugin_links', 10, 2 );
			}
		}

		function mp3j_plugin_links( $links, $file ) { //add a settings link on plugins page 
			if( $file == 'mp3-jplayer/mp3jplayer.php' ) {
				$settings_link = '<a href="options-general.php?page=mp3jplayer.php">'.__('Settings').'</a>';
				array_unshift( $links, $settings_link );
			}
			return $links;
		}
		
		add_action('deactivate_mp3-jplayer/mp3jplayer.php',  array(&$mp3_fox, 'uninitFox'));
		add_action('admin_menu', 'mp3j_adminpage');
	}
	
// template tags
	function mp3j_addscripts( $style = "" ) {
		do_action('mp3j_addscripts', $style);
	}

	function mp3j_div() {
		do_action('mp3j_div');
	}
	
	function mp3j_put( $shortcodes = "" ) {
		do_action( 'mp3j_put', $shortcodes );
	}

	function mp3j_debug( $display = "" ) {
		do_action('mp3j_debug', $display);
	}
	
	function mp3j_grab_library( $format = "" ) { 
		$lib = apply_filters('mp3j_grab_library', '' );
		return $lib;
	}
	
// widgets
	function mp3jplayer_widget_init() {
		register_widget( 'MP3_jPlayer' );
	}
	add_action( 'widgets_init', 'mp3jplayer_widget_init' );
	
	function mp3jshortcodes_widget_init() { 
		register_widget( 'MP3j_single' ); //silly name but can't change it now!
	}
	add_action( 'widgets_init', 'mp3jshortcodes_widget_init' );
	
// shortcodes
	add_shortcode('mp3t', array(&$mp3_fox, 'inline_play_handler'));
	add_shortcode('mp3j', array(&$mp3_fox, 'inline_play_graphic'));
	add_shortcode('mp3-jplayer', array(&$mp3_fox, 'primary_player'));
	add_shortcode('mp3-popout', array(&$mp3_fox, 'popout_link_player'));
	
// template hooks
	add_action('wp_head', array(&$mp3_fox, 'header_scripts_handler'), 2);
	add_action('wp_footer', array(&$mp3_fox, 'footercode_handler'));
	add_action('mp3j_put', array(&$mp3_fox, 'template_tag_handler'), 10, 1 );
	add_action('mp3j_addscripts', array(&$mp3_fox, 'scripts_tag_handler'), 1, 1 );
	add_filter('mp3j_grab_library', array(&$mp3_fox, 'grablibrary_handler'), 10, 1 );
	add_action('mp3j_debug', array(&$mp3_fox, 'debug_info'), 10, 1 );
	add_action('mp3j_div', array(&$mp3_fox, 'write_jp_div'));
	if ( $mp3_fox->theSettings['make_player_from_link'] == "true" ) {
		add_filter('the_content', array(&$mp3_fox, 'replace_links'), 1);
	}
	
// retired
	function mp3j_set_meta( $tracks, $captions = "", $startnum = 1 ) { } //since 1.7
	function mp3j_flag( $set = 1 ) { } //since 1.6
}
?>