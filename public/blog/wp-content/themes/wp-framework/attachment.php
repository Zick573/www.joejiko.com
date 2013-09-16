<?php
/**
 * WordPress Template: Attachment
 *
 * The attachment template is the general template used when a single 
 * attachment is queried.
 *
 * To use a custom template for a specfic attachment, create  
 * attachment-{mime_type}.php file in the parent/child theme's root directory, 
 * where {mime_type} can be the first part, second part, or both parts of the 
 * mime type seperated by an underscore.
 * 
 * Template Hierarchy
 * - attachment-{mime_type_1}.php (i.e. attachment-image.php)
 * - attachment-{mime_type_2}.php (i.e. attachment-png.php)
 * - attachment-{mime_type_1}_{mime_type_2}.php (i.e. attachment-image_png.php)
 * - attachment.php
 * - single.php
 * - index.php
 *
 * For more information on how WordPress handles attachments:
 * @link http://devpress.com/codex/theme-development/attachments/
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-7">

					<?php do_action( 'content_open' ); ?>

					<?php the_post(); ?>

					<?php do_action( 'loop_open' ); ?>

					<div class="hfeed">

						<?php do_action( 'hfeed_open' ); ?>

						<?php get_template_part( 'pagination' ); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

								<header class="entry-header">
									<h1 class="entry-title"><?php the_title(); ?></h1>

									<div class="entry-meta">
										<?php
											$metadata = wp_get_attachment_metadata();
											printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="entry-date"><abbr class="published" title="%1$s">%2$s</abbr></span>  at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'toolbox' ),
												esc_attr( get_the_time() ),
												get_the_date(),
												wp_get_attachment_url(),
												$metadata['width'],
												$metadata['height'],
												get_permalink( $post->post_parent ),
												get_the_title( $post->post_parent )
											);
										?>
									</div><!-- .entry-meta -->
								</header><!-- .entry-header -->

								<div class="entry-content">
									
									<div class="entry-attachment">
										<div class="attachment">
											<?php
												/**
												 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
												 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
												 */
												$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
												foreach ( $attachments as $k => $attachment ) {
													if ( $attachment->ID == $post->ID )
														break;
												}
												$k++;
												// If there is more than 1 attachment in a gallery
												if ( count( $attachments ) > 1 ) {
													if ( isset( $attachments[ $k ] ) )
														// get the URL of the next image attachment
														$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
													else
														// or get the URL of the first image attachment
														$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
												} else {
													// or, if there's only 1 image, get the URL of the image
													$next_attachment_url = wp_get_attachment_url();
												}
											?>
											<a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
											$attachment_size = apply_filters( 'theme_attachment_size',  800 );
											echo wp_get_attachment_image( $post->ID, array( $attachment_size, 9999 ) ); // filterable image width with, essentially, no limit for image height.
											?></a>
										</div><!-- .attachment -->

										<?php if ( ! empty( $post->post_excerpt ) ) : ?>
										<div class="entry-caption">
											<?php the_excerpt(); ?>
										</div>
										<?php endif; ?>
									</div><!-- .entry-attachment -->
									
									<?php if ( wp_attachment_is_image( get_the_ID() ) ) : ?>

										<p class="attachment-image">
											<img class="aligncenter" src="<?php echo wp_get_attachment_url(); ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>" />
										</p><!-- .attachment-image -->

									<?php else : ?>

										<?php wpf_display_attachment(); ?>

										<p class="download">
											<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php the_title_attribute(); ?>" rel="enclosure" type="<?php echo get_post_mime_type(); ?>"><?php printf( __( 'Download &quot;%1$s&quot;', hybrid_get_textdomain() ), the_title( '<span class="fn">', '</span>', false) ); ?></a>
										</p><!-- .download -->

									<?php endif; ?>

									<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', t() ) ); ?>
									<?php wp_link_pages( array( 'before' => '<div class="page-link"><span class="page-link-meta">' . __( 'Pages:', t() ) . '</span>', 'after' => '</div>', 'next_or_number' => 'number' ) ); ?>
								</div><!-- .entry-content -->

								<footer class="entry-meta">
									<span class="taxonomy-lists"><?php wpf_the_taxonomies(); ?></span>
									<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', t() ), __( '1 Comment', t() ), __( '% Comments', t() ) ); ?>
									<?php edit_post_link( __( 'Edit', t() ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?></span>
								</footer><!-- .entry-meta -->

								<?php comments_template( '', true ); ?>

							</article><!-- #post-<?php the_ID(); ?> -->

						<?php get_template_part( 'pagination' ); ?>

						<?php do_action( 'hfeed_close' ); ?>

					</div><!-- .hfeed -->

					<?php do_action( 'loop_close' ); ?>

					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar' ); ?>

<?php get_template_part( 'footer' ); ?>