<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package landscape
 */
?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php do_action( 'before_sidebar' ); ?>
			<div id="homepage-left-sidebar">
			<?php if ( ! dynamic_sidebar( 'homepage-left-sidebar' ) ) : ?>

				<aside id="search" class="widget widget_search">
					<?php get_search_form(); ?>
				</aside>
			<?php endif; // end sidebar widget area ?>
			</div><!-- #left-sidebar -->
			
			<div id="homepage-middle-sidebar">
			<?php if ( ! dynamic_sidebar( 'homepage-middle-sidebar' ) ) : ?>
				<aside id="archives" class="widget">
					<h1 class="widget-title"><?php _e( 'Archives', 'landscape' ); ?></h1>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>
			<?php endif; // end sidebar widget area ?>
			</div><!-- #middle-sidebar -->
			
			<div id="homepage-right-sidebar">
			<?php if ( ! dynamic_sidebar( 'homepage-right-sidebar' ) ) : ?>
				<aside id="meta" class="widget">
					<h1 class="widget-title"><?php _e( 'Meta', 'landscape' ); ?></h1>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</aside>
			<?php endif; // end sidebar widget area ?>
			</div><!-- #right-sidebar -->
		</div><!-- #secondary .widget-area -->
