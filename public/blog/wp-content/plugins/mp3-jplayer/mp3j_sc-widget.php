<?php
/*
 *	SHORTCODES WIDGET
 *	add players via shortcodes. 
 */
if ( class_exists("WP_Widget") ) { 
	if ( !class_exists("MP3j_single") ) {
	
		class MP3j_single extends WP_Widget {
		
/*	Constructor (required by api) */
			function MP3j_single() {
				$widget_ops = array( 
					'classname' => 'mp3jplayerwidget2', 
					'description' => __('Add mp3 players by writing shortcodes.', 
					'mp3jplayerwidget2') 
				);
				$control_ops = array( 
					'id_base' => 'mp3mi-widget',
					'width' => 800 
				);
				$this->WP_Widget( 'mp3mi-widget', __('MP3j-sh', 'mp3jplayerwidget2'), $widget_ops, $control_ops );
			}
		
/*	Runs the shortcodes and writes the players (required by api) */
			function widget( $args, $instance ) {
				if ( !is_home() && !is_archive() && !is_singular() && !is_search() ) { return; }
				global $mp3_fox;
				if ( $mp3_fox->page_filter( $instance['restrict_list'], $instance['restrict_mode'] ) ) { return; }
				
				$mp3_fox->Caller = "widget";
				$shortcodes_return = do_shortcode( $instance['arb_text'] );
				$mp3_fox->Caller = false;
				
				extract( $args ); // supplied WP theme vars 
				echo $before_widget;
				if ( $instance['title'] ) { echo $before_title . $instance['title'] . $after_title; }
				echo $shortcodes_return;
				echo $after_widget;
				return;
			}
	   
/*	Updates the widget settings (required by api) */			
			function update( $new_instance, $old_instance ) {
				$instance = $old_instance;
				$instance['title'] = $new_instance['title'];
				$instance['restrict_list'] = $new_instance['restrict_list'];
				$instance['restrict_mode'] = $new_instance['restrict_mode'];
				$instance['arb_text'] = $new_instance['arb_text'];
				return $instance;
			}

/*	Creates defaults and writes widget panel (required by api) */						
			function form( $instance ) {
				$defaultvalues = array(
					'title' => '',
					'restrict_list' => '',
					'restrict_mode' => 'exclude',
					'arb_text' => ''
				);
				$instance = wp_parse_args( (array) $instance, $defaultvalues );
				?>
					<h3 style="text-align:right; font-size: 11px; margin-bottom:0px;"><a href="options-general.php?page=mp3jplayer.php">Plugin Options and Help</a></h3>
					<p style="margin-top:-18px; margin-bottom:4px;">Shortcodes:</p>
					<!-- Arbitrary text/shortcodes -->
					<p style="margin:8px 0 10px 0; font-size: 11px;"><textarea class="widefat" style="font-size:11px;" rows="8" cols="85" id="<?php echo $this->get_field_id( 'arb_text' ); ?>" name="<?php echo $this->get_field_name( 'arb_text' ); ?>"><?php echo $instance['arb_text']; ?></textarea></p>
					<!-- Page Filter -->
					<p style="font-size: 11px; margin:10px 0px 4px 0px;">
						Include <input type="radio" id="<?php echo $this->get_field_id( 'restrict_mode' ); ?>" name="<?php echo $this->get_field_name( 'restrict_mode' ); ?>" value="include" <?php if ($instance['restrict_mode'] == "include") { _e('checked="checked"', "mp3jplayerwidget2"); }?> />
						or <input type="radio" id="<?php echo $this->get_field_id( 'restrict_mode' ); ?>" name="<?php echo $this->get_field_name( 'restrict_mode' ); ?>" value="exclude" <?php if ($instance['restrict_mode'] == "exclude") { _e('checked="checked"', "mp3jplayerwidget2"); }?> />
						Exclude pages &nbsp;<input class="widefat" style="font-size:11px; width:200px;" type="text" id="<?php echo $this->get_field_id( 'restrict_list' ); ?>" name="<?php echo $this->get_field_name( 'restrict_list' ); ?>" value="<?php echo $instance['restrict_list']; ?>" /></p>
					<p class="description" style="font-size:11px; color:#999999; margin-top:4px; margin-bottom:1px;">A comma separated list, can use post ID's, <code>index</code>, <code>archive</code>, <code>post</code>, and <code>search</code>.</p> 
					<!-- Widget Heading -->
					<p style="margin: -43px 0px 35px 420px; font-size:11px;">Widget Heading: <input style="width:260px;"class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" /></p>
				
				<?php	
			}
		} //close class
	}	
}
?>