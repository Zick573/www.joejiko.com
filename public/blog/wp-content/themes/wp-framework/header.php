<?php
/**
 * WordPress Template: Header
 *
 * The header template is used as the primary header for your website.
 * Generally, all other WordPress templates rely on this file as it
 * contains all the opening HTML tags closed in the footer.php file.
 * It also executes key functions needed by WordPress,
 * the parent/child theme, and/or plugins.
 *
 * @package WP Framework
 * @subpackage Template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php do_action( 'pre_wp_head' ); ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />

	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

	<?php do_action( 'body_open' ); ?>

	<div id="container" class="wrap">

		<?php do_action( 'container_open' ); ?>

		<header id="header" role="banner">

			<?php do_action( 'header_open' ); ?>

			<div id="branding" class="wrap">

				<?php do_action( 'branding_open' ); ?>

				<div id="site-title-wrap" class="column-7">
					<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
					<<?php echo $heading_tag; ?> id="site-title">
						<span>
							<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						</span>
					</<?php echo $heading_tag; ?>><!-- #site-title -->
				</div><!--#site-title-wrap-->
				
				<div id="site-description" class="before-1 column-4 last">
					<?php bloginfo( 'description' ); ?>
				</div><!--#site-description-->

				<?php do_action( 'branding_close' ); ?>

			</div><!--#branding-->
			
			<div id="custom-header">
				<?php wpf_custom_header(); ?>
			</div><!--#custom-header-->

			<div id="site-navigation" role="navigation">
				<?php /* Our navigation menu. If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assiged to the primary position is the one used. If none is assigned, the menu with the lowest ID is used. */ ?>
				<?php wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'nav-menu nav-menu-fat wrap', 'menu_class' => 'sf-menu', 'theme_location' => 'header', 'enable_bp_links' => true, 'show_home' => true ) ); ?>
			</div><!--#site-navigation-->

			<?php do_action( 'header_close' ); ?>

		</header><!--header-->

		<?php do_action( 'between_header_main' ); ?>

		<div id="main" role="main">
			
			<?php do_action( 'main_open' ); ?>

			<div class="wrap">
				
				<?php do_action( 'main_wrap_open' ); ?>