<?php
/**
 * WordPress Template: 404
 *
 * The 404 template is used when a user visits an invalid URI on your site,
 * or if WordPress can't find anything that matches the requested query.
 *
 * Template Hierarchy
 * - 404.php
 * - index.php
 *
 * For more information on how WordPress handles 404 errors:
 * @link http://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WP Framework
 * @subpackage Template
 */

@header( 'HTTP/1.1 404 Not found', true, 404 );

get_template_part( 'header' ); ?>

				<div id="content" class="column-7">

					<?php do_action( 'content_open' ); ?>

					<div class="hfeed">

						<?php do_action( 'hfeed_open' ); ?>

						<article id="post-0" class="hentry error404 not-found">

							<header class="entry-header">
								<h1 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', t() ); ?></h1>
							</header><!-- .entry-header -->

							<div class="entry-content">
								<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', t() ); ?></p>

								<?php get_search_form(); ?>

								<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

								<div class="widget">
									<h2 class="widgettitle"><?php _e( 'Most Used Categories', t() ); ?></h2>
									<ul>
									<?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'show_count' => true, 'title_li' => '', 'number' => '10' ) ); ?>
									</ul>
								</div>

								<?php
								$archive_content = '<p>' . sprintf( __( 'Try looking in the monthly archives. %1$s', t() ), convert_smilies( ':)' ) ) . '</p>';
								the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );
								?>

								<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>

							</div><!-- .entry-content -->

						</article><!-- #post-0 -->

						<?php do_action( 'hfeed_close' ); ?>

					</div><!-- .hfeed -->

					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar' ); ?>

<?php get_template_part( 'footer' ); ?>