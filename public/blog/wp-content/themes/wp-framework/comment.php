<?php
/**
 * Comment Template
 *
 * The comment template displays an individual comment. This can be overwritten by templates specific
 * to the comment type (comment.php, comment-{$comment_type}.php, comment-pingback.php, 
 * comment-trackback.php) in a child theme.
 *
 * @package WP Framework
 * @subpackage Template
 */

global $post, $comment; ?>

	<li id="li-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>

		<?php do_action( 'li_comment_open' ); ?>

		<div id="comment-<?php comment_ID(); ?>" class="comment-wrap">

			<?php do_action( 'comment_open' ); ?>

			<div class="comment-author vcard">
				<?php
				if ( $args['avatar_size'] != 0 )
					wpf_wrap( 'span', get_avatar( $comment, $args['avatar_size'] ), array( 'class' => 'comment-author-avatar' ) );
				?>
				<span class="comment-author-info"><?php printf( __('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link() ); ?></span>
			</div><!-- .comment-author .vcard -->

			<?php if ( '0' == $comment->comment_approved )
				wpf_wrap( 'div', wpf_wrap( 'em', __( 'Your comment is awaiting moderation.', t() ), null, false ), array( 'class' => 'comment-moderation' ) );
			?>

			<div class="comment-meta comment-meta-data">
				<a href="<?php echo esc_attr( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'&nbsp;&nbsp;','' ); ?>
			</div><!-- .comment-meta .comment-meta-data -->

			<div class="comment-content comment-text">
				<?php comment_text( $comment->comment_ID ); ?>
			</div><!-- .comment-content .comment-text -->

			<div class="reply">
				<?php comment_reply_link( array_merge( wpf_comment_reply_strings(), $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth']) ) ); ?>
			</div><!-- .reply -->

			<?php do_action( 'comment_close' ); ?>

		</div><!-- #comment-<?php comment_ID(); ?> -->

		<?php do_action( 'comment_before_children' ); ?>

	<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>