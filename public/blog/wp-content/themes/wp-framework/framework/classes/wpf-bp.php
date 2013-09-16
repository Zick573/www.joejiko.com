<?php
/**
 * Add BuddyPress support to WP Framework.
 *
 * Tested up to: BuddyPress 1.3-alpha
 *
 * @package WP Framework
 */
class WPF_BP {
	function WPF_BP() {
		add_action( 'after_setup_theme', array( $this, 'bp_init' ) );
		add_filter( 'bp_core_fetch_avatar', array( $this, 'backup_avatar' ), 10, 9 );

		// All of the following hooks enhance the responsiveness of the user interface in the theme.
		add_filter( 'bp_ajax_querystring', array( $this, 'wpf_bp_ajax_querystring' ), 10, 2 );
		add_action( 'wp_ajax_members_filter', array( $this, 'wpf_bp_object_template_loader' ) );
		add_action( 'wp_ajax_groups_filter', array( $this, 'wpf_bp_object_template_loader' ) );
		add_action( 'wp_ajax_blogs_filter', array( $this, 'wpf_bp_object_template_loader' ) );
		add_action( 'wp_ajax_forums_filter', array( $this, 'wpf_bp_object_template_loader' ) );
		add_action( 'wp_ajax_activity_widget_filter', array( $this, 'wpf_bp_activity_template_loader' ) );
		add_action( 'wp_ajax_activity_get_older_updates', array( $this, 'wpf_bp_activity_template_loader' ) );
		add_action( 'wp_ajax_post_update', array( $this, 'wpf_bp_post_update' ) );
		add_action( 'wp_ajax_new_activity_comment', array( $this, 'wpf_bp_new_activity_comment' ) );
		add_action( 'wp_ajax_delete_activity', array( $this, 'wpf_bp_delete_activity' ) );
		add_action( 'wp_ajax_delete_activity_comment', array( $this, 'wpf_bp_delete_activity_comment' ) );
		add_action( 'wp_ajax_activity_mark_fav', array( $this, 'wpf_bp_mark_activity_favorite' ) );
		add_action( 'wp_ajax_activity_mark_unfav', array( $this, 'wpf_bp_unmark_activity_favorite' ) );
		add_action( 'wp_ajax_groups_invite_user', array( $this, 'wpf_bp_ajax_invite_user' ) );
		add_action( 'wp_ajax_addremove_friend', array( $this, 'wpf_bp_ajax_addremove_friend' ) );
		add_action( 'wp_ajax_accept_friendship', array( $this, 'wpf_bp_ajax_accept_friendship' ) );
		add_action( 'wp_ajax_reject_friendship', array( $this, 'wpf_bp_ajax_reject_friendship' ) );
		add_action( 'wp_ajax_joinleave_group', array( $this, 'wpf_bp_ajax_joinleave_group' ) );
		add_action( 'wp_ajax_messages_close_notice', array( $this, 'wpf_bp_ajax_close_notice' ) );
		add_action( 'wp_ajax_messages_send_reply', array( $this, 'wpf_bp_ajax_messages_send_reply' ) );
		add_action( 'wp_ajax_messages_markunread', array( $this, 'wpf_bp_ajax_message_markunread' ) );
		add_action( 'wp_ajax_messages_markread', array( $this, 'wpf_bp_ajax_message_markread' ) );
		add_action( 'wp_ajax_messages_delete', array( $this, 'wpf_bp_ajax_messages_delete' ) );
		add_action( 'wp_ajax_messages_autocomplete_results', array( $this, 'wpf_bp_ajax_messages_autocomplete_results' ) );
	}

	function bp_init() {
		// Remove the bp admin bar so we can use our own
		wp_deregister_style( 'bp-admin-bar' );

		// Register a sidebar specific for BuddyPress component pages.
		register_sidebar( array(
			'name' => __( 'BuddyPress Aside', t() ),
			'id' => 'buddypress-widget-area',
			'description' => __( 'This widget area display only on BuddyPress pages.', t() ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>',
		) );

		// BuddyPress is ready to go!
		do_action( 'wpf_bp_init' );

		// The rest of the BP init is front-end code... so stop.
		if ( is_admin() )
			return;

		// Initial BP constants
		$this->initial_constants();

		// Add words that we need to use in JS to the end of the page so they can be translated and still used.
		$params = array(
			'my_favs'           => __( 'My Favorites', t() ),
			'accepted'          => __( 'Accepted', t() ),
			'rejected'          => __( 'Rejected', t() ),
			'show_all_comments' => __( 'Show all comments for this thread', t() ),
			'show_all'          => __( 'Show all', t() ),
			'comments'          => __( 'comments', t() ),
			'close'             => __( 'Close', t() )
		);

		global $bp;

		if ( !empty( $bp->displayed_user->id ) )
			$params['mention_explain'] = sprintf( __( '%s is a unique identifier for %s that you can type into any message on this site. %s will be sent a notification and a link to your message any time you use it.', t() ), '@' . bp_get_displayed_user_username(), bp_get_user_firstname( bp_get_displayed_user_fullname() ), bp_get_user_firstname( bp_get_displayed_user_fullname() ) );

		wp_localize_script( 'wpf-bp-ajax-js', 'WPF_BP', $params );

		add_filter( 'bp_get_activity_action_pre_meta', array( $this, 'activity_secondary_avatars' ), 10, 2 );

		// BP Buttons
		if ( bp_is_active( 'friends' ) )
			add_action( 'bp_member_header_actions',    'bp_add_friend_button' );

		if ( bp_is_active( 'activity' ) )
			add_action( 'bp_member_header_actions',    'bp_send_public_message_button' );

		if ( bp_is_active( 'messages' ) )
			add_action( 'bp_member_header_actions',    'bp_send_private_message_button' );

		if ( bp_is_active( 'groups' ) ) {
			add_action( 'bp_group_header_actions',     'bp_group_join_button' );
			add_action( 'bp_group_header_actions',     'bp_group_new_topic_button' );
			add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
		}

		if ( bp_is_active( 'blogs' ) )
			add_action( 'bp_directory_blogs_actions',  'bp_blogs_visit_blog_button' );
		
		// Add BP Component Links to Menus
		add_filter( 'wp_nav_menu_items', array( $this, 'inject_bp_menu_links' ), 10, 2 );
		add_filter( 'wp_list_pages', array( $this, 'inject_bp_menu_links' ), 10, 2 );
	}

	/**
	 * Templating constants that you can override before WP Framework is loaded.
	 *
	 * @since 0.3.0
	 */
	function initial_constants() {
		// WPF_BP_ALT_GRAVATAR_USER
		if ( !defined( 'WPF_BP_ALT_GRAVATAR_USER' ) )
			define( 'WPF_BP_ALT_GRAVATAR_USER', THEME_IMG . '/bp-avatar-user.jpg' );

		// WPF_BP_ALT_GRAVATAR_GROUP
		if ( !defined( 'WPF_BP_ALT_GRAVATAR_GROUP' ) )
			define( 'WPF_BP_ALT_GRAVATAR_GROUP', THEME_IMG . '/bp-avatar-group.jpg' );
		
		// WPF_BP_ALT_GRAVATAR_BLOG
		if ( !defined( 'WPF_BP_ALT_GRAVATAR_BLOG' ) )
			define( 'WPF_BP_ALT_GRAVATAR_BLOG', THEME_IMG . '/bp-avatar-blog.jpg' );
	}

	/**
	 * fallback avatar images
	 *
	 * @param string $html 
	 * @param string $params 
	 * @param string $item_id 
	 * @param string $avatar_dir 
	 * @param string $css_id 
	 * @param string $html_width 
	 * @param string $html_height 
	 * @param string $avatar_folder_url 
	 * @param string $avatar_folder_dir 
	 */
	function backup_avatar( $html, $params, $item_id, $avatar_dir, $css_id, $html_width, $html_height, $avatar_folder_url, $avatar_folder_dir ) {
		$constant = 'WPF_BP_ALT_GRAVATAR_' . strtoupper( $params['object'] );

		if ( !defined($constant) )
			return $html;

		$img_src = get_theme_part( constant($constant), 'url' );

		if ( !$img_src )
			return $html;

		return sprintf( '<img %s %s class="%s" id="%s" alt="%s" src="%s" />', $html_width, $html_height, "{$params['object']}-avatar avatar", "{$params['object']}-{$params['item_id']}-avatar", $params['alt'], $img_src );
	}
	
	/**
	 * activity_secondary_avatars()
	 *
	 * Add secondary avatar image to this activity stream's record, if supported
	 *
	 * @param string $action The text of this activity
	 * @param BP_Activity_Activity $activity Activity object
	 * @return string
	 */
	function activity_secondary_avatars( $action, $activity ) {
		switch ( $activity->component ) {
			case 'groups' :
			case 'friends' :
				// Only insert avatar if one exists
				if ( $secondary_avatar = bp_get_activity_secondary_avatar() ) {
					$reverse_content = strrev( $action );
					$position        = strpos( $reverse_content, 'a<' );
					$action          = substr_replace( $action, $secondary_avatar, -$position - 2, 0 );
				}
				break;
		}

		return $action;
	}

	function inject_bp_menu_links( $items, $args ) {
		$args = (object) $args;

		// FYI: wp_nav_menu() must have the param enable_bp_links set to true.
		if ( !isset($args->enable_bp_links) || !$args->enable_bp_links )
			return $items;

		$links = '';
		$active = array( 'class' => 'current-menu-item current_page_item menu-item' );
		$inactive = array( 'class' => 'menu-item' );

		if ( bp_is_active( 'activity' ) ) {
			$attrs = bp_is_page( BP_ACTIVITY_SLUG ) ? $active : $inactive;
			$links .= wpf_wrap( 'li', '<a href="'. site_url() .'/'. trailingslashit( BP_ACTIVITY_SLUG ) .'" title="'. __( 'Acitivty', t() ) .'">'. __( 'Acitivty', t() ) .'</a>', $attrs, false );
		}
		
		$callback = function_exists( 'bp_is_user' ) ? 'bp_is_user' : 'bp_is_member';

		$attrs = bp_is_page( BP_MEMBERS_SLUG ) || call_user_func($callback) ? $active : $inactive;
		$links .= wpf_wrap( 'li', '<a href="'. site_url() .'/'. trailingslashit( BP_MEMBERS_SLUG ) .'" title="'. __( 'Members', t() ) .'">'. __( 'Members', t() ) .'</a>', $attrs, false );

		if ( bp_is_active( 'groups' ) ) {
			$attrs = bp_is_page( BP_GROUPS_SLUG ) ? $active : $inactive;
			$links .= wpf_wrap( 'li', '<a href="'. site_url() .'/'. trailingslashit( BP_GROUPS_SLUG ) .'" title="'. __( 'Groups', t() ) .'">'. __( 'Groups', t() ) .'</a>', $attrs, false );
		}

		if ( bp_is_active( 'groups' ) && bp_is_active( 'forums' ) && ( function_exists( 'bp_forums_is_installed_correctly' ) && !(int) bp_get_option( 'bp-disable-forum-directory' ) ) && bp_forums_is_installed_correctly() ) {
			$attrs = bp_is_page( BP_FORUMS_SLUG ) ? $active : $inactive;
			$links .= wpf_wrap( 'li', '<a href="'. site_url() .'/'. trailingslashit( BP_FORUMS_SLUG ) .'" title="'. __( 'Forums', t() ) .'">'. __( 'Forums', t() ) .'</a>', $attrs, false );
		}

		if ( bp_is_active( 'blogs' ) && is_multisite() ) {
			$attrs = bp_is_page( BP_BLOGS_SLUG ) ? $active : $inactive;
			$links .= wpf_wrap( 'li', '<a href="'. site_url() .'/'. trailingslashit( BP_BLOGS_SLUG ) .'" title="'. __( 'Blogs', t() ) .'">'. __( 'Blogs', t() ) .'</a>', $attrs, false );
		}

		$links .= apply_filters( 'bp_nav_items', '' );
		
		return $items . $links;
	}
	
	/***
	 * This function looks scarier than it actually is. :)
	 * Each object loop (activity/members/groups/blogs/forums) contains default parameters to
	 * show specific information based on the page we are currently looking at.
	 * The following function will take into account any cookies set in the JS and allow us
	 * to override the parameters sent. That way we can change the results returned without reloading the page.
	 * By using cookies we can also make sure that user settings are retained across page loads.
	 */
	function wpf_bp_ajax_querystring( $query_string, $object ) {
		global $bp;

		if ( empty( $object ) )
			return false;

		/* Set up the cookies passed on this AJAX request. Store a local var to avoid conflicts */
		if ( !empty( $_POST['cookie'] ) )
			$_BP_COOKIE = wp_parse_args( str_replace( '; ', '&', urldecode( $_POST['cookie'] ) ) );
		else
			$_BP_COOKIE = &$_COOKIE;

		$qs = false;

		/***
		 * Check if any cookie values are set. If there are then override the default params passed to the
		 * template loop
		 */
		if ( !empty( $_BP_COOKIE['bp-' . $object . '-filter'] ) && '-1' != $_BP_COOKIE['bp-' . $object . '-filter'] ) {
			$qs[] = 'type=' . $_BP_COOKIE['bp-' . $object . '-filter'];
			$qs[] = 'action=' . $_BP_COOKIE['bp-' . $object . '-filter']; // Activity stream filtering on action
		}

		if ( !empty( $_BP_COOKIE['bp-' . $object . '-scope'] ) ) {
			if ( 'personal' == $_BP_COOKIE['bp-' . $object . '-scope'] ) {
				$user_id = ( $bp->displayed_user->id ) ? $bp->displayed_user->id : $bp->loggedin_user->id;
				$qs[] = 'user_id=' . $user_id;
			}
			if ( 'all' != $_BP_COOKIE['bp-' . $object . '-scope'] && empty( $bp->displayed_user->id ) && !$bp->is_single_item )
				$qs[] = 'scope=' . $_BP_COOKIE['bp-' . $object . '-scope']; // Activity stream scope only on activity directory.
		}

		/* If page and search_terms have been passed via the AJAX post request, use those */
		if ( !empty( $_POST['page'] ) && '-1' != $_POST['page'] )
			$qs[] = 'page=' . $_POST['page'];

		if ( !empty( $_POST['search_terms'] ) && __( 'Search anything...', t() ) != $_POST['search_terms'] && 'false' != $_POST['search_terms'] && 'undefined' != $_POST['search_terms'] )
			$qs[] = 'search_terms=' . $_POST['search_terms'];

		/* Now pass the querystring to override default values. */
		$query_string = empty( $qs ) ? '' : join( '&', (array) $qs );

		return apply_filters( 'wpf_bp_ajax_querystring', $query_string, $object, $_BP_COOKIE['bp-' . $object . '-filter'], $_BP_COOKIE['bp-' . $object . '-scope'], $_BP_COOKIE['bp-' . $object . '-page'], $_BP_COOKIE['bp-' . $object . '-search-terms'], $_BP_COOKIE['bp-' . $object . '-extras'] );
	}

	/* This function will simply load the template loop for the current object. On an AJAX request */
	function wpf_bp_object_template_loader() {
		$object = esc_attr( $_POST['object'] );
		locate_template( array( "$object/$object-loop.php" ), true );
	}

	/* This function will load the activity loop template when activity is requested via AJAX */
	function wpf_bp_activity_template_loader() {
		global $bp;

		/* We need to calculate and return the feed URL for each scope */
		$feed_url = site_url( BP_ACTIVITY_SLUG . '/feed/' );

		switch ( $_POST['scope'] ) {
			case 'friends':
				$feed_url = $bp->loggedin_user->domain . BP_ACTIVITY_SLUG . '/friends/feed/';
				break;
			case 'groups':
				$feed_url = $bp->loggedin_user->domain . BP_ACTIVITY_SLUG . '/groups/feed/';
				break;
			case 'favorites':
				$feed_url = $bp->loggedin_user->domain . BP_ACTIVITY_SLUG . '/favorites/feed/';
				break;
			case 'mentions':
				$feed_url = $bp->loggedin_user->domain . BP_ACTIVITY_SLUG . '/mentions/feed/';
				delete_user_meta( $bp->loggedin_user->id, 'bp_new_mention_count' );
				break;
		}

		/* Buffer the loop in the template to a var for JS to spit out. */
		ob_start();
		locate_template( array( 'activity/activity-loop.php' ), true );
		$result['contents'] = ob_get_contents();
		$result['feed_url'] = apply_filters( 'wpf_bp_activity_feed_url', $feed_url, $_POST['scope'] );
		ob_end_clean();

		echo json_encode( $result );
	}

	/* AJAX update posting */
	function wpf_bp_post_update() {
		global $bp;

		/* Check the nonce */
		check_admin_referer( 'post_update', '_wpnonce_post_update' );

		if ( !is_user_logged_in() ) {
			echo '-1';
			return false;
		}

		if ( empty( $_POST['content'] ) ) {
			echo '-1<div id="message" class="error"><p>' . __( 'Please enter some content to post.', t() ) . '</p></div>';
			return false;
		}

		if ( empty( $_POST['object'] ) && function_exists( 'bp_activity_post_update' ) ) {
			$activity_id = bp_activity_post_update( array( 'content' => $_POST['content'] ) );
		} elseif ( $_POST['object'] == 'groups' ) {
			if ( !empty( $_POST['item_id'] ) && function_exists( 'groups_post_update' ) )
				$activity_id = groups_post_update( array( 'content' => $_POST['content'], 'group_id' => $_POST['item_id'] ) );
		} else
			$activity_id = apply_filters( 'bp_activity_custom_update', $_POST['object'], $_POST['item_id'], $_POST['content'] );

		if ( !$activity_id ) {
			echo '-1<div id="message" class="error"><p>' . __( 'There was a problem posting your update, please try again.', t() ) . '</p></div>';
			return false;
		}

		if ( bp_has_activities ( 'include=' . $activity_id ) ) : ?>
			<?php while ( bp_activities() ) : bp_the_activity(); ?>
				<?php locate_template( array( 'activity/entry.php' ), true ) ?>
			<?php endwhile; ?>
		 <?php endif;
	}

	/* AJAX activity comment posting */
	function wpf_bp_new_activity_comment() {
		global $bp;

		/* Check the nonce */
		check_admin_referer( 'new_activity_comment', '_wpnonce_new_activity_comment' );

		if ( !is_user_logged_in() ) {
			echo '-1';
			return false;
		}

		if ( empty( $_POST['content'] ) ) {
			echo '-1<div id="message" class="error"><p>' . __( 'Please do not leave the comment area blank.', t() ) . '</p></div>';
			return false;
		}

		if ( empty( $_POST['form_id'] ) || empty( $_POST['comment_id'] ) || !is_numeric( $_POST['form_id'] ) || !is_numeric( $_POST['comment_id'] ) ) {
			echo '-1<div id="message" class="error"><p>' . __( 'There was an error posting that reply, please try again.', t() ) . '</p></div>';
			return false;
		}

		$comment_id = bp_activity_new_comment( array(
			'content' => $_POST['content'],
			'activity_id' => $_POST['form_id'],
			'parent_id' => $_POST['comment_id']
		));

		if ( !$comment_id ) {
			echo '-1<div id="message" class="error"><p>' . __( 'There was an error posting that reply, please try again.', t() ) . '</p></div>';
			return false;
		}

		if ( bp_has_activities ( 'include=' . $comment_id ) ) : ?>
			<?php while ( bp_activities() ) : bp_the_activity(); ?>
				<li id="acomment-<?php bp_activity_id() ?>">
					<div class="acomment-avatar">
						<?php bp_activity_avatar() ?>
					</div>

					<div class="acomment-meta">
						<?php echo bp_core_get_userlink( bp_get_activity_user_id() ) ?> &middot; <?php printf( __( '%s ago', t() ), bp_core_time_since( bp_core_current_time() ) ) ?> &middot;
						<a class="acomment-reply" href="#acomment-<?php bp_activity_id() ?>" id="acomment-reply-<?php echo esc_attr( $_POST['form_id'] ) ?>"><?php _e( 'Reply', t() ) ?></a>
						 &middot; <a href="<?php echo wp_nonce_url( $bp->root_domain . '/' . $bp->activity->slug . '/delete/' . bp_get_activity_id() . '?cid=' . $comment_id, 'bp_activity_delete_link' ) ?>" class="delete acomment-delete confirm"><?php _e( 'Delete', t() ) ?></a>
					</div>

					<div class="acomment-content">
						<?php bp_activity_content_body() ?>
					</div>
				</li>
			<?php endwhile; ?>
		 <?php endif;
	}

	/* AJAX delete an activity */
	function wpf_bp_delete_activity() {
		global $bp;

		/* Check the nonce */
		check_admin_referer( 'bp_activity_delete_link' );

		if ( !is_user_logged_in() ) {
			echo '-1';
			return false;
		}

		$activity = new BP_Activity_Activity( $_POST['id'] );

		/* Check access */
		if ( !is_super_admin() && $activity->user_id != $bp->loggedin_user->id )
			return false;

		if ( empty( $_POST['id'] ) || !is_numeric( $_POST['id'] ) )
			return false;

		/* Call the action before the delete so plugins can still fetch information about it */
		do_action( 'bp_activity_action_delete_activity', $_POST['id'], $activity->user_id );

		if ( !bp_activity_delete( array( 'id' => $_POST['id'], 'user_id' => $activity->user_id ) ) ) {
			echo '-1<div id="message" class="error"><p>' . __( 'There was a problem when deleting. Please try again.', t() ) . '</p></div>';
			return false;
		}

		return true;
	}

	/* AJAX delete an activity comment */
	function wpf_bp_delete_activity_comment() {
		global $bp;

		/* Check the nonce */
		check_admin_referer( 'bp_activity_delete_link' );

		if ( !is_user_logged_in() ) {
			echo '-1';
			return false;
		}

		$comment = new BP_Activity_Activity( $_POST['id'] );

		/* Check access */
		if ( !is_super_admin() && $comment->user_id != $bp->loggedin_user->id )
			return false;

		if ( empty( $_POST['id'] ) || !is_numeric( $_POST['id'] ) )
			return false;

		/* Call the action before the delete so plugins can still fetch information about it */
		do_action( 'bp_activity_action_delete_activity', $_POST['id'], $comment->user_id );

		if ( !bp_activity_delete_comment( $comment->item_id, $comment->id ) ) {
			echo '-1<div id="message" class="error"><p>' . __( 'There was a problem when deleting. Please try again.', t() ) . '</p></div>';
			return false;
		}

		return true;
	}

	/* AJAX mark an activity as a favorite */
	function wpf_bp_mark_activity_favorite() {
		global $bp;

		bp_activity_add_user_favorite( $_POST['id'] );
		_e( 'Remove Favorite', t() );
	}

	/* AJAX mark an activity as not a favorite */
	function wpf_bp_unmark_activity_favorite() {
		global $bp;

		bp_activity_remove_user_favorite( $_POST['id'] );
		_e( 'Favorite', t() );
	}

	/* AJAX invite a friend to a group functionality */
	function wpf_bp_ajax_invite_user() {
		global $bp;

		check_ajax_referer( 'groups_invite_uninvite_user' );

		if ( !$_POST['friend_id'] || !$_POST['friend_action'] || !$_POST['group_id'] )
			return false;

		if ( !groups_is_user_admin( $bp->loggedin_user->id, $_POST['group_id'] ) )
			return false;

		if ( !friends_check_friendship( $bp->loggedin_user->id, $_POST['friend_id'] ) )
			return false;

		if ( 'invite' == $_POST['friend_action'] ) {

			if ( !groups_invite_user( array( 'user_id' => $_POST['friend_id'], 'group_id' => $_POST['group_id'] ) ) )
				return false;

			$user = new BP_Core_User( $_POST['friend_id'] );

			echo '<li id="uid-' . $user->id . '">';
			echo $user->avatar_thumb;
			echo '<h4>' . $user->user_link . '</h4>';
			echo '<span class="activity">' . esc_attr( $user->last_active ) . '</span>';
			echo '<div class="action">
					<a class="remove" href="' . wp_nonce_url( $bp->loggedin_user->domain . $bp->groups->slug . '/' . $_POST['group_id'] . '/invites/remove/' . $user->id, 'groups_invite_uninvite_user' ) . '" id="uid-' . esc_attr( $user->id ) . '">' . __( 'Remove Invite', t() ) . '</a>
				  </div>';
			echo '</li>';

		} else if ( 'uninvite' == $_POST['friend_action'] ) {

			if ( !groups_uninvite_user( $_POST['friend_id'], $_POST['group_id'] ) )
				return false;

			return true;

		} else {
			return false;
		}
	}

	/* AJAX add/remove a user as a friend when clicking the button */
	function wpf_bp_ajax_addremove_friend() {
		global $bp;

		if ( 'is_friend' == BP_Friends_Friendship::check_is_friend( $bp->loggedin_user->id, $_POST['fid'] ) ) {

			check_ajax_referer('friends_remove_friend');

			if ( !friends_remove_friend( $bp->loggedin_user->id, $_POST['fid'] ) ) {
				echo __("Friendship could not be canceled.", t());
			} else {
				echo '<a id="friend-' . $_POST['fid'] . '" class="add" rel="add" title="' . __( 'Add Friend', t() ) . '" href="' . wp_nonce_url( $bp->loggedin_user->domain . $bp->friends->slug . '/add-friend/' . $_POST['fid'], 'friends_add_friend' ) . '">' . __( 'Add Friend', t() ) . '</a>';
			}

		} else if ( 'not_friends' == BP_Friends_Friendship::check_is_friend( $bp->loggedin_user->id, $_POST['fid'] ) ) {

			check_ajax_referer('friends_add_friend');

			if ( !friends_add_friend( $bp->loggedin_user->id, $_POST['fid'] ) ) {
				echo __("Friendship could not be requested.", t());
			} else {
				echo '<a href="' . $bp->loggedin_user->domain . $bp->friends->slug . '" class="requested">' . __( 'Friendship Requested', t() ) . '</a>';
			}
		} else {
			echo __( 'Request Pending', t() );
		}

		return false;
	}

	/* AJAX accept a user as a friend when clicking the "accept" button */
	function wpf_bp_ajax_accept_friendship() {
		check_admin_referer( 'friends_accept_friendship' );

		if ( !friends_accept_friendship( $_POST['id'] ) )
			echo "-1<div id='message' class='error'><p>" . __( 'There was a problem accepting that request. Please try again.', t() ) . '</p></div>';

		return true;
	}

	/* AJAX reject a user as a friend when clicking the "reject" button */
	function wpf_bp_ajax_reject_friendship() {
		check_admin_referer( 'friends_reject_friendship' );

		if ( !friends_reject_friendship( $_POST['id'] ) )
			echo "-1<div id='message' class='error'><p>" . __( 'There was a problem rejecting that request. Please try again.', t() ) . '</p></div>';

		return true;
	}

	/* AJAX join or leave a group when clicking the "join/leave" button */
	function wpf_bp_ajax_joinleave_group() {
		global $bp;

		if ( groups_is_user_banned( $bp->loggedin_user->id, $_POST['gid'] ) )
			return false;

		if ( !$group = new BP_Groups_Group( $_POST['gid'], false, false ) )
			return false;

		if ( 'hidden' == $group->status )
			return false;

		if ( !groups_is_user_member( $bp->loggedin_user->id, $group->id ) ) {

			if ( 'public' == $group->status ) {

				check_ajax_referer( 'groups_join_group' );

				if ( !groups_join_group( $group->id ) ) {
					_e( 'Error joining group', t() );
				} else {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="leave-group" rel="leave" title="' . __( 'Leave Group', t() ) . '" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'leave-group', 'groups_leave_group' ) . '">' . __( 'Leave Group', t() ) . '</a>';
				}

			} else if ( 'private' == $group->status ) {

				check_ajax_referer( 'groups_request_membership' );

				if ( !groups_send_membership_request( $bp->loggedin_user->id, $group->id ) ) {
					_e( 'Error requesting membership', t() );
				} else {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="membership-requested" rel="membership-requested" title="' . __( 'Membership Requested', t() ) . '" href="' . bp_get_group_permalink( $group ) . '">' . __( 'Membership Requested', t() ) . '</a>';
				}
			}

		} else {

			check_ajax_referer( 'groups_leave_group' );

			if ( !groups_leave_group( $group->id ) ) {
				_e( 'Error leaving group', t() );
			} else {
				if ( 'public' == $group->status ) {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="join-group" rel="join" title="' . __( 'Join Group', t() ) . '" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'join', 'groups_join_group' ) . '">' . __( 'Join Group', t() ) . '</a>';
				} else if ( 'private' == $group->status ) {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="request-membership" rel="join" title="' . __( 'Request Membership', t() ) . '" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'request-membership', 'groups_send_membership_request' ) . '">' . __( 'Request Membership', t() ) . '</a>';
				}
			}
		}
	}

	/* AJAX close and keep closed site wide notices from an admin in the sidebar */
	function wpf_bp_ajax_close_notice() {
		global $userdata;

		if ( !isset( $_POST['notice_id'] ) ) {
			echo "-1<div id='message' class='error'><p>" . __('There was a problem closing the notice.', t()) . '</p></div>';
		} else {
			$notice_ids = get_user_meta( $userdata->ID, 'closed_notices', true );

			$notice_ids[] = (int) $_POST['notice_id'];

			update_user_meta( $userdata->ID, 'closed_notices', $notice_ids );
		}
	}

	/* AJAX send a private message reply to a thread */
	function wpf_bp_ajax_messages_send_reply() {
		global $bp;

		check_ajax_referer( 'messages_send_message' );

		$result = messages_new_message( array( 'thread_id' => $_REQUEST['thread_id'], 'content' => $_REQUEST['content'] ) );

		if ( $result ) { ?>
			<div class="message-box new-message">
				<div class="message-metadata">
					<?php do_action( 'bp_before_message_meta' ) ?>
					<?php echo bp_loggedin_user_avatar( 'type=thumb&width=30&height=30' ); ?>

					<strong><a href="<?php echo $bp->loggedin_user->domain ?>"><?php echo $bp->loggedin_user->fullname ?></a> <span class="activity"><?php printf( __( 'Sent %s ago', t() ), bp_core_time_since( bp_core_current_time() ) ) ?></span></strong>

					<?php do_action( 'bp_after_message_meta' ) ?>
				</div>

				<?php do_action( 'bp_before_message_content' ) ?>

				<div class="message-content">
					<?php echo stripslashes( apply_filters( 'bp_get_the_thread_message_content', $_REQUEST['content'] ) ) ?>
				</div>

				<?php do_action( 'bp_after_message_content' ) ?>

				<div class="clear"></div>
			</div>
		<?php
		} else {
			echo "-1<div id='message' class='error'><p>" . __( 'There was a problem sending that reply. Please try again.', t() ) . '</p></div>';
		}
	}

	/* AJAX mark a private message as unread in your inbox */
	function wpf_bp_ajax_message_markunread() {
		global $bp;

		if ( !isset($_POST['thread_ids']) ) {
			echo "-1<div id='message' class='error'><p>" . __('There was a problem marking messages as unread.', t() ) . '</p></div>';
		} else {
			$thread_ids = explode( ',', $_POST['thread_ids'] );

			for ( $i = 0; $i < count($thread_ids); $i++ ) {
				BP_Messages_Thread::mark_as_unread($thread_ids[$i]);
			}
		}
	}

	/* AJAX mark a private message as read in your inbox */
	function wpf_bp_ajax_message_markread() {
		global $bp;

		if ( !isset($_POST['thread_ids']) ) {
			echo "-1<div id='message' class='error'><p>" . __('There was a problem marking messages as read.', t() ) . '</p></div>';
		} else {
			$thread_ids = explode( ',', $_POST['thread_ids'] );

			for ( $i = 0; $i < count($thread_ids); $i++ ) {
				BP_Messages_Thread::mark_as_read($thread_ids[$i]);
			}
		}
	}

	/* AJAX delete a private message or array of messages in your inbox */
	function wpf_bp_ajax_messages_delete() {
		global $bp;

		if ( !isset($_POST['thread_ids']) ) {
			echo "-1<div id='message' class='error'><p>" . __( 'There was a problem deleting messages.', t() ) . '</p></div>';
		} else {
			$thread_ids = explode( ',', $_POST['thread_ids'] );

			for ( $i = 0; $i < count($thread_ids); $i++ )
				BP_Messages_Thread::delete($thread_ids[$i]);

			_e( 'Messages deleted.', t() );
		}
	}

	/* AJAX autocomplete your friends names on the compose screen */
	function wpf_bp_ajax_messages_autocomplete_results() {
		global $bp;

		$friends = false;

		// Get the friend ids based on the search terms
		if ( function_exists( 'friends_search_friends' ) )
			$friends = friends_search_friends( $_GET['q'], $bp->loggedin_user->id, $_GET['limit'], 1 );

		$friends = apply_filters( 'bp_friends_autocomplete_list', $friends, $_GET['q'], $_GET['limit'] );

		if ( $friends['friends'] ) {
			foreach ( (array)$friends['friends'] as $user_id ) {
				$ud = get_userdata($user_id);
				$username = $ud->user_login;
				echo bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'thumb', 'width' => 15, 'height' => 15 ) ) . ' &nbsp;' . bp_core_get_user_displayname( $user_id ) . ' (' . $username . ')
				';
			}
		}
	}
}