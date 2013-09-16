<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
				
				<h2><?php the_title(); ?></h2>
		
				<?php the_content('Read the rest of this entry &raquo;'); ?>

        <hr class="clearfix" />

        <?php wp_link_pages('before=<p>&after=</p>&next_or_number=number&pagelink=page %'); ?>

				<?php edit_post_link('Edit', '<p>', '</p>'); ?>
				
				<hr class="clearfix" />
				
			</article>

			<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

	<?php endif; ?>

<?php get_footer(); ?>