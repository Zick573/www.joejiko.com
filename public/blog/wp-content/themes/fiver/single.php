<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
				
				<h2><?php the_title(); ?></h2>
				<time><?php the_time(get_option('date_format')) ?></time>
		
				<?php the_content('Read the rest of this entry &raquo;'); ?>

				<hr class="clearfix" />

        <?php wp_link_pages('before=<p class="pagination">&after=</p>&next_or_number=number&pagelink=page %'); ?>

        <p class="post_tags"><strong>Tagged with:</strong> <?php the_tags('', ', ' ,  ''); ?></p>
        <p class="post_categories"><strong>Categorised as:</strong> <?php the_category(', '); ?> </p>

				<?php edit_post_link('Edit', '<p>', '</p>'); ?>
				
				<hr class="clearfix" />
				
			</article>

			<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

	<?php endif; ?>

<?php get_footer(); ?>
