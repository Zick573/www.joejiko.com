<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<title><?php wp_title('&laquo;', true, 'right'); bloginfo( 'name' ); ?></title>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php if (!is_admin()) { wp_enqueue_script('html5_shim',  get_template_directory_uri() . '/html5.js'); } ?>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
	  
	  <div id="wrapper">
	
  		<header>
  			<h1><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a></h1>
  			<!--<?php bloginfo('description'); ?>-->
  		</header>

  		<section>