<?php
/**
 * WordPress Template: Single
 *
 * The single template is the general template used when a singular 'post',
 * or custom post type is requested.
 * 
 * If the attachments.php or more specific attachment-based template is not 
 * found, attachments also make use of this template.
 * 
 * To use a custom template for a specfic post type,
 * create a single-{post_type}.php file in the your theme's root directory.
 *
 * Template Hierarchy
 * - single-{post_type}.php
 * - single.php
 * - index.php
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-7">

					<?php do_action( 'content_open' ); ?>

					<?php if ( have_posts() ) : the_post(); ?>

						<?php do_action( 'loop_open' ); ?>

						<div class="hfeed">

							<?php do_action( 'hfeed_open' ); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								
								<header class="entry-header">
									<h1 class="entry-title"><?php the_title(); ?></h1>

									<div class="entry-meta">
										<?php
											printf( __( '<span class="sep">Posted on </span><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s" pubdate>%3$s</time></a> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%4$s" title="%5$s">%6$s</a></span>', t() ),
												get_permalink(),
												get_the_date( 'c' ),
												get_the_date(),
												get_author_posts_url( get_the_author_meta( 'ID' ) ),
												sprintf( esc_attr__( 'View all posts by %s', t() ), get_the_author() ),
												get_the_author()
											);
										?>
									</div><!-- .entry-meta -->
								</header><!-- .entry-header -->

								<div class="entry-content">
									<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', t() ) ); ?>
									<?php wp_link_pages( array( 'before' => '<div class="page-link"><span class="page-link-meta">' . __( 'Pages:', t() ) . '</span>', 'after' => '</div>', 'next_or_number' => 'number' ) ); ?>
								</div><!-- .entry-content -->

								<footer class="entry-meta">
									<span class="tax-link"><?php wpf_the_taxonomies(); ?></span>
									<span class="bookmark-link"><?php printf( __( 'Bookmark the <a href="%s" title="Permalink to %s" rel="bookmark">permalink</a>.', t() ), get_permalink(), the_title_attribute( 'echo=0' ) ); ?></span>
									<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', t() ), __( '1 Comment', t() ), __( '% Comments', t() ) ); ?></span>
									<?php edit_post_link( __( 'Edit', t() ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
								</footer><!-- .entry-meta -->

							</article><!-- #post-<?php the_ID(); ?> -->	

							<?php comments_template( '', true ); ?>

							<?php get_template_part( 'pagination' ); ?>

							<?php do_action( 'hfeed_close' ); ?>

						</div><!-- .hfeed -->

						<?php do_action( 'loop_close' ); ?>

					<?php else : ?>

						<?php get_template_part( 'loop-404' ); ?>

					<?php endif; ?>

					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

				<?php get_template_part( 'sidebar' ); ?>

<?php get_template_part( 'footer' ); ?>