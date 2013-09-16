<?php

if ( ! isset( $content_width ) ) $content_width = 550;

add_theme_support('automatic-feed-links');
add_theme_support('custom-background');
add_action('widgets_init', 'fiver_sidebar');

function fiver_sidebar() {
  register_sidebar(array(
    'name' => 'sidebar',
    'id' => 'sidebar',    
  	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
  	'after_widget' => '</aside>',
  	'before_title' => '<h3 class="widgettitle">',
  	'after_title' => '</h3>',
  ));
}


// hack to add a class to the body tag when the sidebar is active
function fiver_has_sidebar($classes) {
	if (is_active_sidebar('sidebar')) {
		// add 'class-name' to the $classes array
		$classes[] = 'has_sidebar';		
	}
	// return the $classes array
	return $classes;
}
add_filter('body_class','fiver_has_sidebar');
 
?>
