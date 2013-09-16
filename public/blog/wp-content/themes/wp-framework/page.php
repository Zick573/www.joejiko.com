<?php
/**
 * WordPress Template: Page
 * 
 * The page template is the general template used when a singular 'page'
 * post type is queried.
 * 
 * This template can be overriden if the page is set to use a custom page 
 * template or if WordPress can match that page's slug or id with a 
 * page-{slug}.php or page-{id}.php file in the parent/child theme's directory.
 *
 * Template Hierarchy
 * - custom template
 * - page-{slug}.php
 * - page-{id}.php
 * - page.php
 * - index.php
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content" class="column-7">

					<?php do_action( 'content_open' ); ?>

					<?php if ( have_posts() ) : ?>

						<?php do_action( 'loop_open' ); ?>

						<div class="hfeed">

							<?php do_action( 'hfeed_open' ); ?>

								<?php while ( have_posts() ) : the_post(); ?>

									<?php do_action( 'loop_while_before' ); ?>

									<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
										<header class="entry-header">
											<?php if ( is_front_page() ) { ?>
												<h2 class="entry-title"><?php the_title(); ?></h2>
											<?php } else { ?>
												<h1 class="entry-title"><?php the_title(); ?></h1>
											<?php } ?>
										</header><!-- .entry-header -->

										<div class="entry-content">
											<?php the_content(); ?>
											<?php wp_link_pages( array( 'before' => '<div class="page-link"><span class="page-link-meta">' . __( 'Pages:', t() ) . '</span>', 'after' => '</div>', 'next_or_number' => 'number' ) ); ?>
										</div><!-- .entry-content -->

										<footer class="entry-meta">
											<?php edit_post_link( __( 'Edit', t() ), '<span class="edit-link">', '</span>' ); ?>
										</footer><!-- .entry-meta -->

										<?php comments_template( '', true ); ?>

									</article><!-- #post-<?php the_ID(); ?> -->

									<?php do_action( 'loop_while_after' ); ?>

								<?php endwhile; ?>

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