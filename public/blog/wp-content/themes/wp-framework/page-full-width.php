<?php
/**
 * Template Name: Full-width, no sidebar
 * Description: A full-width template with no sidebar
 *
 * @package WP Framework
 * @subpackage Template
 */

get_template_part( 'header' ); ?>

				<div id="content">

					<?php do_action( 'content_open' ); ?>

					<?php the_post(); ?>

					<?php do_action( 'loop_open' ); ?>

					<div class="hfeed">

						<?php do_action( 'hfeed_open' ); ?>

						<?php get_template_part( 'pagination' ); ?>

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
									<?php edit_post_link( __( 'Edit', t() ), '<span class="edit-link">', '</span>' ); ?></span>
								</footer><!-- .entry-meta -->

								<?php comments_template( '', true ); ?>

							</article><!-- #post-<?php the_ID(); ?> -->

						<?php get_template_part( 'pagination' ); ?>

						<?php do_action( 'hfeed_close' ); ?>

					</div><!-- .hfeed -->

					<?php do_action( 'loop_close' ); ?>

					<?php do_action( 'content_close' ); ?>

				</div><!-- #content -->

<?php get_template_part( 'footer' ); ?>