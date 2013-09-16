<?php
/**
 * WordPress Template: Footer
 * 
 * The footer template is used as the primary footer for your website.
 * Generally, all other WordPress templates rely on this file as it
 * contains all the closing HTML tags opened in the header.php file.
 * It also executes key functions needed by WordPress,
 * the parent/child theme, and/or plugins.
 *
 * @package WP Framework
 * @subpackage Template
 */
?>
				<?php do_action( 'main_wrap_close' ); ?>
				
			</div><!--#main.wrap-->

			<?php do_action( 'main_close' ); ?>

		</div><!--#main-->

		<?php do_action( 'between_main_footer' ); ?>

		<footer id="footer" role="contentinfo">

			<?php do_action( 'footer_open' ); ?>

			<div id="colophon" class="wrap">
				<?php do_action( 'colophon_open' ); ?>

				<div id="site-info" class="column-6" role="navigation">
					<?php wpf_footer_nav_menu(); ?>
				</div><!--#site-info-->

				<div id="site-credits" class="before-2 column-4 last">
					<span id="site-generator"><a href="http://wordpress.org"><?php _e( 'Powered by WordPress', t() ); ?></a> <?php _e( '&amp;', t() ) ?></span>
					<span id="site-framework"><a href="http://devpress.com/theme/wp-framework/"><?php _e( 'Built on WP Framework', t() ); ?></a></span>
				</div><!--#site-credits-->

				<?php do_action( 'colophon_close' ); ?>
			</div><!--#colophon-->

			<?php do_action( 'footer_close' ); ?>

		</footer><!--footer-->

		<?php do_action( 'container_close' ); ?>

	</div><!--#container-->

	<?php do_action( 'body_close' ); ?>
	<?php wp_footer(); ?>

</body>
</html>