<?php
/**
 * WordPress Template: Sidebar
 *
 * The sidebar template is used as the primary sidebar for your website.
 * This template is optional and may or may not be called from your other
 * theme's template files.
 *
 * @package WP Framework
 * @subpackage Template
 */

do_action( 'sidebar_before' ); ?>

				<div id="sidebar" class="before-1 column-4 last">

					<?php do_action( 'sidebar_open' ); ?>

					<aside role="complementary">

						<?php do_action( 'aside_open' ); ?>
						<?php
						/* When we call the dynamic_sidebar() function, it'll spit out
						 * the widgets for that widget area. If it instead returns false,
						 * then the sidebar simply doesn't exist, so we'll hard-code in
						 * some default sidebar stuff just in case.
						 */
						if ( !is_active_sidebar( 'aside-widget-area' ) ) : ?>

						<section id="search" class="widget widget_search">
							<?php get_search_form(); ?>
						</section>
						
						<section id="pages" class="widget">
							<h3 class="widgettitle"><?php _e( 'Pages', t() ); ?></h3>
							<?php wp_page_menu(); ?>
						</section>

						<section id="meta" class="widget">
							<h3 class="widgettitle"><?php _e( 'Meta', t() ); ?></h3>
							<ul>
								<?php wp_register(); ?>
								<li><?php wp_loginout(); ?></li>
								<?php wp_meta(); ?>
							</ul>
						</section>

						<?php else : ?>
							<?php dynamic_sidebar( 'aside-widget-area' ); ?>
						<?php endif; ?>

						<?php do_action( 'aside_close' ); ?>

					</aside><!--aside-->

					<?php do_action( 'sidebar_close' ); ?>

				</div><!--#sidebar-->

<?php do_action( 'sidebar_after' ); ?>