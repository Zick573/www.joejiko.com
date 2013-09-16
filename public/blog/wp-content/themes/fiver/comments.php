<?php if ( post_password_required() ) { ?>
  <p class="nocomments">This post is password protected. Enter the password to view comments.</p>
<?php return; } ?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	<h3 id="comments"><?php comments_number('No Comments', 'One Comment', '% Comments' );?> </h3>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>


	<ol class="commentlist">
	<?php wp_list_comments(array('avatar_size' => 70)); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
	  <?php if(!is_page()) { ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>
    <?php } ?>

	<?php endif; ?>
<?php endif; ?>


<?php if (comments_open()) : ?>
  <?php comment_form(); ?>
<?php endif; // if you delete this the sky will fall on your head ?>
