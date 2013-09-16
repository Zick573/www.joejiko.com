<?php
/**
 * Custom Template: Loop 404
 *
 * The loop 404 template is used when an no results where returned by the loop.
 *
 * @package WP Framework
 * @subpackage Template
 */
?>

			<?php do_action( 'loop_404_before' ); ?>

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

			<?php do_action( 'loop_404_after' ); ?>