<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package landscape
 */
?>

</div><!-- #main .site-main -->
</div><!-- #page .hfeed .site -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'landscape_credits' ); ?>
			<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'landscape' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'landscape' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( '%1$s Theme by %2$s', 'landscape' ), 'Landscape', '<a href="http://blankthemes.com/" rel="designer">Blank Themes</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon .site-footer -->


<?php wp_footer(); ?>

</body>
</html>