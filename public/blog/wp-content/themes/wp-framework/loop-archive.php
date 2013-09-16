<?php
/**
 * Custom Template: Archive
 * 
 * The archive template is the general template used to display the WordPress 
 * loop on archive-base queries.
 *
 * @package WP Framework
 * @subpackage Template
 */

if ( have_posts() ) : ?>

	<?php do_action( 'loop_open' ); ?>

		<div class="hfeed">

			<?php do_action( 'hfeed_open' ); ?>

			<h1 class="page-title"><?php wpf_archive_title(); ?></h1>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php do_action( 'loop_while_before' ); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<header class="entry-header">
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', t() ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

						<div class="entry-meta">
							<?php
								printf( __( '<span class="sep">Posted on </span><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s" pubdate>%3$s</time></a> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%4$s" title="%5$s">%6$s</a></span>', 'toolbox' ),
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

					<div class="entry-summary">
						<?php the_excerpt(); ?>
					</div><!-- .entry-content -->

					<footer class="entry-meta">
						<span class="taxonomy-lists"><?php wpf_the_taxonomies(); ?></span>
						<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', t() ), __( '1 Comment', t() ), __( '% Comments', t() ) ); ?></span>
						<?php edit_post_link( __( 'Edit', t() ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-meta -->

				</article><!-- #post-<?php the_ID(); ?> -->

				<?php do_action( 'loop_while_after' ); ?>

			<?php endwhile; ?>

		<?php get_template_part( 'pagination' ); ?>

		<?php do_action( 'hfeed_close' ); ?>

	</div><!-- .hfeed -->

	<?php do_action( 'loop_close' ); ?>

<?php else : ?>

	<?php get_template_part( 'loop-404' ); ?>

<?php endif; ?>