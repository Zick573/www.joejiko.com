<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package landscape
 */
?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php do_action( 'before_sidebar' ); ?>
			<div id="left-sidebar">
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

				<aside id="search" class="widget widget_search">
					<?php get_search_form(); ?>
				</aside>

			<?php endif; // end sidebar widget area ?>
			</div><!-- #left-sidebar -->
			
			<div id="middle-sidebar">
			<?php if ( ! dynamic_sidebar( 'sidebar-2' ) ) : ?>
				<aside id="archives" class="widget">
					<h1 class="widget-title"><?php _e( 'Archives', 'landscape' ); ?></h1>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>
			<?php endif; // end sidebar widget area ?>
			</div><!-- #middle-sidebar -->
			
			<div id="right-sidebar">
			<?php if ( ! dynamic_sidebar( 'sidebar-3' ) ) : ?>
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
