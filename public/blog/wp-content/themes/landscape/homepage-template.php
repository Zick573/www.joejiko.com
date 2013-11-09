<?php
/*
 * Template Name: Homepage Template
 *
 * @package landscape
*/

get_header(); ?>

		<div id="homepage-primary" class="content-area">
			<div id="intro" class="home-site-content" role="main">

				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); global $more;
$more = 0; ?>

						<div class="home-entry-content">
							<?php the_content('<a href="'. get_permalink($post->ID) . '">' . 'Read More' . '</a>.'); ?>
							
							<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'landscape' ), 'after' => '</div>' ) ); ?>

						</div><!-- .entry-content -->

				<?php endwhile; ?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_sidebar('homepage'); ?>
<?php get_footer(); ?>