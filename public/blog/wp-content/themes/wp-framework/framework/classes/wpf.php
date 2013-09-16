<?php
/**
 * The Bread and butter of WP Framework.
 *
 * @since inception
 */
class WPF extends WPF_API {
	/**
	 * Stores the text domain for the active theme.
	 *
	 * @access public
	 * @since 0.3.0
	 * @see t()
	 * @var string
	 */
	var $textdomain;

	/**
	 * Sets the global $content_width variable.
	 *
	 * @access public
	 * @since 0.3.0
	 * @see wpf_get_content_width();
	 * @var int
	 */
	var $content_width;

	/**
	 * Front controller that hooks into the template files and bootstraps all
	 * the functionality.
	 *
	 * @since 0.3.0
	 */
	function WPF( $args = array() ) {
		global $content_width;

		// Register all the settings as native $variables
		$args = wp_parse_args( $args, array( 'content_width' => $content_width, 'textdomain' => t(), 'excerpt_length' => 55 ) );
        foreach ( $args as $key => $value ) {
			$this->$key = $value;
        }

		// Magic hooks. note: template_redirect is set with a priority of 1 for compability with BuddyPress.
		add_action( 'after_setup_theme', array( $this, '_after_setup_theme' ) );
		add_action( 'init', array( $this, '_init' ) );
		add_action( 'wp_loaded', array( $this, '_wp_loaded' ) );
		add_action( 'wp_loaded', array( $this, '_enqueue_assets' ) );
		add_action( 'widgets_init', array( $this, '_widgets_init' ) );
		add_action( 'wp_head', array( $this, '_wp_head' ) );
		add_action( 'wp_footer', array( $this, '_wp_footer' ) );

		// Magic hook for the admin.
		if ( is_admin() )
			$this->callback( $this, 'admin' );
		else
			$this->setup();

		/* Bug. WordPress has two conflicing actions 'taxonomy_template'. Hooking this function late to avoid collision */
		add_action( 'wp_head', array( $this, 'add_taxonomy_template_filter' ) );
	}

	/**
	 * Hooks all of the core WP Framework functionality into WordPress
	 * and the template files.
	 *
	 * @since 0.3.0
	 */
	function setup() {
		add_action( 'wp_title', array($this, 'filter_wp_title'), 9, 3 );

		// Remove recent comments style
		add_action( 'widgets_init', array( $this, 'remove_recent_comments_style' ) );

		// enqeue comment js
		add_action( 'template_redirect', array( $this, 'enqueue_comment_reply_js' ) );

		add_filter( 'excerpt_length', array( $this, 'get_excerpt_length' ) );
		add_filter( 'excerpt_more', array( $this, 'get_excerpt_more' ) );

		// Improve the template heirarchy
		add_filter( 'author_template', array( $this, 'filter_author_template' ) );
		add_filter( 'tag_template', array( $this, 'filter_taxonomy_template' ) );
		add_filter( 'category_template', array( $this, 'filter_taxonomy_template' ) );
		add_action( 'template_include', array( $this, 'catch_requested_template' ) );
		add_action( 'bp_load_template', array( $this, 'catch_requested_template' ) );

		// Filters
		add_action( 'comment_form_defaults', array( $this, 'filter_comment_form_strings' ) );
		add_filter( 'wp_sprintf_l', array( $this, 'filter_sprintf_l' ) );
		add_filter( 'wp_page_menu_args', array( $this, 'filter_wp_page_menu_args' ) );

		// Template hooks
		add_filter( 'next_posts_link_attributes', array( $this, 'filter_next_posts_link_attr' ) );
		add_filter( 'previous_posts_link_attributes', array( $this, 'filter_previous_posts_link_attr' ) );

		// Add Post type support for existing features
		add_post_type_support( 'page', 'excerpt' );
		add_post_type_support( 'attachment', array( 'comments', 'trackbacks' ) );

		// WPF extensions
		add_action( 'after_setup_theme', array( $this, 'include_extensions' ), 100 );

		/* Bug. WordPress has two conflicing actions 'taxonomy_template'. Hooking this function late to avoid collision */
		add_action( 'wp_head', array( $this, 'add_taxonomy_template_filter' ) );
	}

	/**
	 * Include WP Framework extentions.
	 *
	 * @since 0.3.0
	 */
	function include_extensions() {
		require_if_theme_supports( 'semantic-markup', PARENT_THEME_DIR . '/framework/extensions/semantic-markup.php' );
	}

	/**
	 * Magic hook: Define your own after_setup_theme method
	 *
	 * @since 0.3.0
	 */
	function _enqueue_assets() {
		if ( is_admin() )
			return;

		self::callback( $this, 'enqueue_assets' );
	}
	
	/**
	 * Magic hook: Define your own after_setup_theme method
	 *
	 * @since 0.3.0
	 */
	function _after_setup_theme() {
		self::callback( $this, 'after_setup_theme' );
	}

	/**
	 * Magic hook: Define your own init method
	 *
	 * @since 0.3.0
	 */
	function _init() {
		self::callback( $this, 'init' );
	}

	/**
	 * Magic hook: Define your own wp_loaded method
	 *
	 * @since 0.3.0
	 */
	function _wp_loaded() {
		self::callback( $this, 'wp_loaded' );
	}

	/**
	 * Magic hook: Define your own template_redirect method
	 *
	 * @since 0.3.0
	 */
	function _template_redirect() {
		self::callback( $this, 'template_redirect' );
	}

	/**
	 * Magic hook: Define your own widgets_init method
	 *
	 * @since 0.3.0
	 */
	function _widgets_init() {
		self::callback( $this, 'widgets_init' );
	}

	/**
	 * Magic hook: Define your own wp_head method
	 *
	 * @since 0.3.0
	 */
	function _wp_head() {
		self::callback( $this, 'wp_head' );
	}

	/**
	 * Magic hook: Define your own wp_footer method
	 *
	 * @since 0.3.0
	 */
	function _wp_footer() {
		self::callback( $this, 'wp_footer' );
	}

	/**
	 * Captures the template used to load the requested page.
	 *
	 * @since 0.3.0
	 *
	 * @return string path to the requested template.
	 */
	function catch_requested_template( $template ) {
		global $wpf_theme;

		$wpf_theme->requested_template = $template ? $template : null;

		return $wpf_theme->requested_template;
	}

	/**
	 * Filters the next post link attribute to add a class for customization.
	 *
	 * @since 0.3.0
	 */
	function filter_next_posts_link_attr( $attr ) {
		return $attr . ' class="nextpostlink"';
	}

	/**
	 * Filters the prev post link attribute to add a class for customization.
	 *
	 * @since 0.3.0
	 */
	function filter_previous_posts_link_attr( $attr ) {
		return $attr . ' class="prevpostlink"';
	}

	/**
	 * Returns the excerpt lenth used in the_excerpt().
	 *
	 * @since 0.3.0
	 */
	function get_excerpt_length() {
		return $this->excerpt_length;
	}

	/**
	 * Returns the excerpt more link used in the_excerpt().
	 *
	 * @since 0.3.0
	 */
	function get_excerpt_more( $more ) {
		return $more;
	}

	/**
	 * Searchs for the registered locale .mo language files and loads them up for translation.
	 *
	 * @since 0.3.0
	 * @uses t()
	 * @uses get_locale()
	 * @uses locate_template()
	 * @uses load_textdomain()
	 */
	function load_theme_translations( $locate = array() ) {
		$textdomain = t();
		$locale = get_locale();

		$files = wp_parse_args( $locate, array( THEME_I18N . "/{$textdomain}-{$locale}.mo", "{$textdomain}-{$locale}.mo" ) );		
		$translations = locate_template( $files );

		if ( $translations ) {
			load_textdomain( $textdomain, $translations );
		}
	}
	
	/**
	 * Extends the default wp_title() function adding more filters for
	 * plugin authors.
	 *
	 * @since 0.3.0
	 *
	 * @param string $old_title 
	 * @param string $sep 
	 * @param string $seplocation
	 */
	function filter_wp_title( $old_title, $sep, $seplocation ) {
		global $wp_query;
		
		$title = '';
		$queried_object = $wp_query->get_queried_object() ? $wp_query->get_queried_object() : false;
		$tagline = apply_filters( 'wpf_show_site_description', true ) ? " {$sep} " . get_bloginfo( 'description' ) : '';
		$branding_sep = apply_filters( 'wpf_wp_title_branding_sep', ' &#8212; ' );

		if ( is_singular() AND !is_front_page() )
			$title = $queried_object->post_title;

		elseif ( is_archive() OR is_search() )
			$title = str_replace( ':', __( ' for ', t() ), strip_tags( $this->archive_title(), 'span' ) );

		elseif ( is_404() )
			$title = __( '404 Not Found', t() );

		/* If paged. */
		if ( ( ( $page = $wp_query->get( 'paged' ) ) || ( $page = $wp_query->get( 'page' ) ) ) && $page > 1 )
			$title = sprintf( __( '%1$s Page %2$s', t() ), $title . " {$sep} ", $page );
		
		if ( $title )
			$title = ( 'right' == $seplocation ) ? "{$title} {$sep} " : " {$sep} {$title}";

		$branding = get_bloginfo( 'name' );
		$branding = ( is_front_page() OR is_home() ) ? $branding . " {$branding_sep} " . get_bloginfo( 'description' ) : $branding;

		$title = ( 'right' == $seplocation ) ? $title . $branding : $branding . $title;

		return apply_filters( 'wpf_wp_title', esc_attr( $title ) );
	}
	
	/**
	 * This method filters WP's default author-based templates and extends
	 * it with a 'user' prefix for better semantics.
	 *
	 * @since 0.3.0
	 * @uses locate_template() Checks for template in child and parent theme.
	 * @param string $template.
	 * @return string Full path to file.
	 */
	function filter_author_template( $template ) {
		$templates = array();
		$user_id = absint( get_query_var( 'author' ) );
		$name = get_the_author_meta( 'user_nicename', $user_id );
		$user = new WP_User( $user_id );

		$templates = array( "user-{$name}.php", "user-{$user_id}.php", "author-{$name}.php", "author-{$user_id}.php" );

		if ( !empty( $user->roles ) ) {
			foreach ( $user->roles as $role )
				$templates[] = "user-role-{$role}.php";
		}

		$templates[] = 'user.php';
		$templates[] = 'author.php';
		$templates[] = 'archive.php';

		return locate_template( $templates );
	}

	/**
	 * This method filters WP's default category and tag-based templates and
	 * reconfigures it to a taxonomy-category.php taxonomy-tag.php template
	 * so it's more inalign with the taxonomy templates.
	 *
	 * @since 0.3.0
	 * @uses locate_template() Checks for template in child and parent theme.
	 * @param string $template
	 * @return string Full path to file.
	 */
	function filter_taxonomy_template( $template ) {
		global $wp_query;

		$term = $wp_query->get_queried_object();

		$templates = array( "taxonomy-{$term->taxonomy}-{$term->slug}.php", "taxonomy-{$term->taxonomy}.php" );

		// Backwards Compat.
		if ( is_category() ) {
			$templates[] = "category-{$term->taxonomy}.php";
			$templates[] = "category-{$term->term_id}.php";
			$templates[] = 'category.php';
		}

		if ( is_tag() ) {
			$templates[] = "tag-{$term->taxonomy}.php";
			$templates[] = "tag-{$term->term_id}.php";
			$templates[] = 'tag.php';
		}

		$templates[] = 'taxonomy.php';
		$templates[] = 'archive.php';

		return locate_template( $templates );
	}

	/**
	 * Returns all the strings used throughout the comment_form() function.
	 * Takes advantage of the strings API.
	 *
	 * @since 0.3.0
	 *
	 * @param string $args Parameters from the comment_form() function.
	 * @return array $args
	 */
	function filter_comment_form_strings( $args ) {
		global $user_identity;

		$req = get_option( 'require_name_email' );

		$required_text = sprintf( ' ' . __( 'Required fields are marked %s', t() ), '<span class="required">*</span>' );

		$args['must_log_in'] = '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', t() ), wp_login_url( apply_filters( 'the_permalink', get_permalink( get_the_ID() ) ) ) ) . '</p>';
		$args['logged_in_as'] = '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', t() ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( get_the_ID() ) ) ) ) . '</p>';
		$args['comment_notes_before'] = '<p class="comment-notes">' . __( 'Your email address will not be published.', t() ) . ( $req ? $required_text : '' ) . '</p>';
		$args['comment_notes_after'] = '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', t() ), ' <code>' . allowed_tags() . '</code>' ) . '</p>';

		$args['title_reply'] = __( 'Leave a Reply', t() );
		$args['title_reply_to'] = __( 'Leave a Reply to %s', t() );
		$args['cancel_reply_link'] = __( 'Cancel reply', t() );
		$args['label_submit'] = __( 'Post Comment', t() );

		return $args;
	}

	/**
	 * Filters the 'sprintf_l' filter to wrap all the meta seperators
	 * in span elements so you can style them via CSS.
	 *
	 * @since 0.3.0
	 */
	function filter_sprintf_l() {
		return array(
			/* translators: used between list items, there is a space after the coma */
			'between'          => '<span class="meta-sep-between">' . __( ', ', t() ) . '</span>',
			/* translators: used between list items, there is a space after the and */
			'between_last_two' => '<span class="meta-sep-between_last_two">' . __( ', and ', t() ) . '</span>',
			/* translators: used between only two list items, there is a space after the and */
			'between_only_two' => '<span class="meta-sep-between_only_two">' . __( ' and ', t() ) . '</span>',
		);
	}

	/**
	 * 'taxonomy_template' is a conflicting hook in WordPress appearing in two places.
	 * This hook gets added after get_taxonomy_template() gets called.
	 *
	 * @since 0.3.0
	 */
	function add_taxonomy_template_filter() {
		add_filter( 'taxonomy_template', array( $this, 'filter_taxonomy_template_sprintf' ) );
	}

	/**
	 * Filters the 'taxonomy_template' filter and wraps all the data into
	 * semantic span elements so you can style them via CSS.
	 *
	 * @since 0.3.0
	 */
	function filter_taxonomy_template_sprintf() {
		return '<span class="tax-label">%s</span><span class="tax-meta-sep">:</span> <span class="tax-link">%l</span><span class="tax-meta-end">.</span>';
	}

	/**
	 * Filters the 'wp_page_menu_args' filter to override the menu_class
	 * parameter. This is so the nav menu styles can be consistent
	 * with the wp_nav_menu() function.
	 *
	 * @since 0.3.0
	 *
	 * @param array $args Parameters from wp_page_menu_args()
	 * @return array $args Parameters for wp_page_menu_args()
	 */
	function filter_wp_page_menu_args( $args ) {
		if ( isset($args['theme_location']) && 'header' == $args['theme_location'] )
			$args['menu_class'] = 'nav-menu nav-menu-fat wrap';

		return $args;
	}
	
	/**
	 * Uses the $comment_type to determine which comment template should be used. Once the 
	 * template is located, it is loaded for use. Child themes can create custom templates based off
	 * the $comment_type. The comment template hierarchy is comment-$comment_type.php, 
	 * comment.php.
	 *
	 * The templates are saved in cache so each comment template is only
	 * located once if it is needed. Following comments will use the saved template.
	 *
	 * @since 0.3.0
	 *
	 * @param $comment The comment variable
	 * @param $args Array of arguments passed from wp_list_comments()
	 * @param $depth What level the particular comment is
	 */
	function comments_callback( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		$GLOBALS['comment_depth'] = $depth;

		$comment_type = get_comment_type( $comment->comment_ID );

		$cache = wp_cache_get( 'comment_template' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( !isset( $cache[$comment_type] ) ) {
			$template = locate_template( array( "comment-{$comment_type}.php", 'comment.php' ) );

			$cache[$comment_type] = $template;
			wp_cache_set( 'comment_template', $cache );
		}

		if ( !empty( $cache[$comment_type] ) )
			require( $cache[$comment_type] );
	}
	
	/**
	 * Ends the display of individual comments. Uses the callback parameter for wp_list_comments(). 
	 * Needs to be used in conjunction with $wpf_theme->comments_callback().
	 *
	 * @since 0.3.0
	 */
	function comments_end_callback() {
		do_action( 'li_comment_close' );

		echo '</li><!-- .comment -->' . PHP_EOL;
	}
	
	/**
	 * Enqueue the comment reply js if the page is_singular() and if threaded
	 * comments is enabled for the site.
	 *
	 * @since 0.3.0
	 */
	function enqueue_comment_reply_js() {
		if ( is_singular() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
	}

	/**
	 * Removes the default styles that are packaged with the Recent Comments widget.
	 *
	 * To override this in a child theme, remove the filter and optionally add your own
	 * function tied to the widgets_init action hook.
	 *
	 * @since 0.3.0
	 */
	function remove_recent_comments_style() {
		global $wp_widget_factory;

		if ( isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments']) ) {
			remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
		}
	}
	
	/**
	 * Displays the archive title for archive pages.
	 *
	 * @todo add support for post type archives
	 * @since 0.3.0
	 */
	function archive_title() {
		global $wp_query;

		if ( have_posts() )
			the_post();

		$queried_object = $wp_query->get_queried_object();
				
		if ( is_category() || is_tag() || is_tax() ) {
			$tax_object = get_taxonomy( $queried_object->taxonomy );
			$archive_title = sprintf( __( '%s Archives<span class="meta-sep">:</span> <span>%s</span>', t() ), $tax_object->label, $queried_object->name );
		}

		elseif ( is_author() )
			$archive_title = sprintf( __( 'Author Archives<span class="meta-sep">:</span> <span>%s</span>', t() ), get_the_author_meta( 'display_name', get_query_var( 'author' ) ) );

		elseif ( is_date() ) {
			if ( is_day() )
				$archive_title = sprintf( __( 'Daily Archives<span class="meta-sep">:</span> <span>%s</span>', t() ), get_the_time( __( 'F jS, Y', t() ) ) );

			elseif ( get_query_var( 'w' ) )
				$archive_title = sprintf( __( 'Weekly Archives<span class="meta-sep">:</span> <span>%s</span> of <span>%1$s</span>', t() ), get_the_time( __( 'W', t() ) ) );

			elseif ( is_month() )
				$archive_title = sprintf( __( 'Monthly Archives<span class="meta-sep">:</span> <span>%s</span>', t() ), get_the_date( __( 'F Y', t() ) ) );

			elseif ( is_year() )
				$archive_title = sprintf( __( 'Yearly Archives<span class="meta-sep">:</span> <span>%s</span>', t() ), get_the_time( __( 'Y', t() ) ) );
		}

		elseif ( is_search() )
			$archive_title = sprintf( __( 'Search results for <span>&quot;%s&quot;</span>', t() ), esc_attr( get_search_query() ) );
		
		else
			$archive_title = __( 'Archives', t() );

		rewind_posts();

		return apply_filters( 'wpf_archive_title', $archive_title );
	}
	
	/**
	 * Outputs the custom header for the theme.
	 *
	 * @since 0.3
	 *
	 * @return void
	 */
	function get_custom_header() {
		// Check if this is a post or page, if it has a thumbnail, and if it's a big one.
		if ( is_singular() &&
			current_theme_supports( 'post-thumbnails' ) &&
			has_post_thumbnail( get_the_ID() ) &&
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'post-thumbnail' ) &&
			$image[1] >= HEADER_IMAGE_WIDTH ) :

			// Houston, we have a new header image!
			echo get_the_post_thumbnail( get_the_ID() );
		elseif ( get_header_image() ) : ?>
			<img src="<?php header_image(); ?>" />
		<?php
		endif;
	}

	/**
	 * Callback function when using custom header.
	 *
	 * Override this method to inject some styles for the image or something
	 * cool like that.
	 *
	 * @since 0.3.0
	 */
	function custom_header_frontend_callback() {
		return null;
	}

	/**
	 * Callback function when using custom header.
	 *
	 * Override this method to inject some styles for the image or something
	 * cool like that. Yeah, I said it twice.
	 *
	 * @since 0.3.0
	 */
	function custom_header_admin_callback() {
		return null;
	}

	/**
	 * Displays all taxonomies related to a post.
	 *
	 * @since 0.3.0
	 *
	 * @param string $args Parameteres to pass to get_the_taxonomies().
	 */
	function the_taxonomies( $args = array() ) {
		$args = wp_parse_args( $args, array( 'post' => 0, 'before' => '', 'sep' => ' ', 'after' => '' ) );
		extract( $args, EXTR_SKIP );

		$taxonomies = get_the_taxonomies();
		$tax_links = '';
		foreach ( $taxonomies as $tax_id => $taxonomy_link ) {
			$tax_links .= "<span class=\"{$tax_id}-links\">{$taxonomy_link}</span>{$sep}";
		}

		echo $before . $tax_links . $after;
	}

	/**
	 * Displays the footer navigation with nav menu support.
	 *
	 * @since 0.3.0
	 */
	function footer_nav_menu() {
		$nav_locations = get_nav_menu_locations();

		if ( !empty($nav_locations['footer']) ) {
			wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'nav-menu nav-menu-skinny', 'menu_class' => 'sf-menu', 'theme_location' => 'footer', 'show_home' => true, 'fallback_cb' => '' ) );
		} else {
			?>
			<nav class="nav-menu nav-menu-skinny">
				<ul>
					<li><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></li>
				</ul>
			</nav>
			<?php
		}
	}

	/**
	 * Pagination for Posts and Comments.
	 *
	 * @todo Still left:
	 * add support for displaying item count (e.g. Displaying 1–20 of 139).
	 * add support for previous/next comment links.
	 * add support for showing both types of pagination links (e.g. previous/next links and listing).
	 * add support for dropdown style ala wp_pagenavi();
	 *
	 * $args:
	 * page_count_label - The text/HTML to display before the list of pages.
	 * current_page_label - The text/HTML to display for the current page. Defaults to the current page number.
	 * first_label - The label to display for the first page link.
	 * last_label - The label to display for the last page link.
	 * prev_label - The label to display for the previous page link.
	 * next_label - The label to display for the next page link.
	 * page_range - The number of page links to show before and after the current page. Defaults to 3.
	 * page_anchors - he number of links to always show at beginning and end of pagination. Defaults to 1.
	 * before - Optional. Insert HTML before the pagination links.
	 * after - Optional. Insert HTML after the pagination links.
	 * style - Controls the format of the returned links.
	 * type - Whether to show pagination links for posts or comments.
	 * dots - The display of the ellipsis.
	 * before_prev_post_link - Optional. Insert text before the previous post link.
	 * before_next_post_link - Optional. Insert text before the next post link.
	 * show_dots - Whether to show the dots or not.
	 * show_page_count - Whether to show the page count label.
	 * always_show_prev_next_links - Whether to always show Next and Previous links.
	 * show_first_last_links - Whether to show First and Last links.
	 * format_callback - Callback function for handling the display of the pagination links.
	 *
	 * @param string|array $args Optional. Override defaults.
	 * @return void
	 */
	function paginate( $args = array() ) {
		global $wp_query;

		$defaults = array(
			'page_count' => __( 'Page %CURRENT_PAGE% of %TOTAL_PAGES%', t() ),
			'first_label' => __( 'First', t() ),
			'last_label' => __( 'Last', t() ),
			'prev_label' => __( '&laquo;', t() ),
			'next_label' => __( '&raquo;', t() ),
			'page_range' => 3,
			'page_anchors' => 2,
			'before' => '',
			'after' => '',
			'style' => 'formated',
			'type' => 'posts',
			'dots' => '...',
			'before_prev_post_link' => '',
			'after_prev_post_link' => '',
			'before_next_post_link' => '',
			'after_next_post_link' => '',
			'prev_post_label' => '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', t() ) . '</span> Previous post',
			'next_post_label' => 'Next post <span class="meta-nav">' . _x( '&rarr;', 'Next post link', t() ) . '</span>',
			'show_dots' => true,
			'show_page_count' => true,
			'always_show_prev_next_links' => true,
			'show_first_last_links' => true,
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$paginate = array();

		// Setup variables
		$query_var = 'posts' == $type ? 'paged' : 'cpage';
		$current_page = get_query_var( $query_var ) ? intval( get_query_var($query_var) ) : 1;

		$per_page_option = 'posts' == $type ? 'posts_per_page' : 'comments_per_page';
		$items_per_page = intval( get_query_var( $per_page_option ) );

		$total_pages = 'posts' == $type ? intval( ceil($wp_query->found_posts / $items_per_page) ) : get_comment_pages_count();

		$prevlink = 'posts' == $type ? esc_url( get_pagenum_link($current_page - 1) ) : esc_url( get_comments_pagenum_link( $current_page - 1 ) );
		$nextlink = 'posts' == $type ? esc_url( get_pagenum_link($current_page + 1) ) : esc_url( get_comments_pagenum_link( $current_page + 1 ) );
		$pagenum_callback = 'posts' == $type ? 'get_pagenum_link' : 'get_comments_pagenum_link';

		$paginate['type'] = $type;
		$paginate['style'] = $style;

		$paginate['before'] = wp_kses_post( $before );

		// If the total page count is greater than 1
		if ( $total_pages > 1 ) {

			// Insert the page count label into the pagination
			$paginate['page_count'] = str_replace(
				array( "%CURRENT_PAGE%", "%TOTAL_PAGES%" ),
				array(
					wpf_wrap( 'span', number_format_i18n( $current_page ), array( 'class' => 'meta-current' ), false ),
					wpf_wrap( 'span', number_format_i18n( $total_pages ), array( 'class' => 'meta-total' ), false ),
				),
				$page_count
			);

			if ( $show_page_count )
				$paginate['page_count'] = wpf_wrap( 'span', $paginate['page_count'], array( 'class' => 'page-count' ), false );
			else
				unset( $paginate['page_count'] );

			if ( $show_first_last_links ) {
				// Insert the first link label into the pagination
				$paginate['first_label'] = $first_label;

				// Insert the first link into the pagination
				if ( 1 == $current_page )
					$paginate['first_link'] = sprintf( '<span class="page-numbers first">%s</span>', $first_label );
				else
					$paginate['first_link'] = sprintf( '<a href="%s" class="page-numbers first">%s</a>', esc_url( call_user_func( $pagenum_callback, 1 ) ), $first_label );
			}

			// Insert the previous label into the pagination
			$paginate['prev_label'] = $prev_label;

			// Insert the previous link into the pagination
			if ( $always_show_prev_next_links && 1 == $current_page )
				$paginate['prev_link'] = sprintf( '<span class="page-numbers prev">%s</span>', $prev_label );
			elseif ( 1 < $current_page )
				$paginate['prev_link'] = sprintf( '<a href="%s" class="page-numbers prev">%s</a>', $prevlink, $prev_label );
			else
				unset( $paginate['prev_label'] );

			// Ranges
			$left_range = $current_page - $page_range;
			$right_range = $current_page + $page_range;

			// Anchor whitelist
			$anchors = array();
			for ( $anchor = 1; $anchor <= $page_anchors; $anchor++ ) { 
				$anchors[] = $anchor;
				$anchors[] = ( $total_pages + 1 ) - $anchor;
			}

			// Insert the current page.
			$paginate['current_page'] = number_format_i18n( $current_page );

			// Loop through all the links.
			for ( $number = 1; $number <= $total_pages; $number++ ) {
				$number_display = number_format_i18n( $number );

				// Insert all the page links.
				$paginate['pages'][$number] = sprintf( '<a href="%s" class="page-numbers">%s</a>', esc_url( call_user_func( $pagenum_callback, $number_display ) ), $number_display );

				// Remove page links that are not within the page_range but not the page_anchors
				if ( $number < $left_range - 1 && !in_array( $number, $anchors ) )
					unset( $paginate['pages'][$number] );

				// Remove page links that are not within the page_range but not the page_anchors
				if ( $number > $right_range + 1 && !in_array( $number, $anchors ) )
					unset( $paginate['pages'][$number] );

				// Insert the current page link.
				if ( $number == $current_page )
					$paginate['pages'][$number] = sprintf( '<span class="page-numbers current">%s</span>', $number_display );

				// (maybe) insert the dots to the left of the current page.
				if ( $left_range > 1 && ( $number == $left_range - 1 ) && !in_array( $number, $anchors ) )
					$paginate['pages'][$number] = sprintf( '<span class="page-numbers dots">%s</span>', $dots );

				// (maybe) insert the dots to the right of the current page.
				if ( $right_range > 1 && ( $number == $right_range + 1 ) && !in_array( $number, $anchors ) )
					$paginate['pages'][$number] = sprintf( '<span class="page-numbers dots">%s</span>', $dots );
			}

			$paginate['next_label'] = $next_label;

			// Insert the next link into the pagination
			if ( $always_show_prev_next_links && $current_page == $total_pages )
				$paginate['next_link'] = sprintf( '<span class="page-numbers next">%s</span>', $next_label );
			elseif ( $current_page < $total_pages )
				$paginate['next_link'] = sprintf( '<a href="%s" class="page-numbers next">%s</a>', $nextlink, $next_label );
			else
				unset( $paginate['next_label'] );

			if ( $show_first_last_links ) {
				// Insert the last label into the pagination
				$paginate['last_label'] = $last_label;

				// Insert the last link into the pagination
				if ( $current_page == $total_pages )
					$paginate['last_link'] = sprintf( '<span class="page-numbers last">%s</span>', $last_label );
				else
					$paginate['last_link'] = sprintf( '<a href="%s" class="page-numbers last">%s</a>', esc_url( call_user_func( $pagenum_callback, number_format_i18n( intval($total_pages) ) ) ), $last_label );
			}

		// Singular Pages
		} elseif ( 'posts' == $type ) {
			if ( wpf_has_prev_post() ) {
				ob_start();
				previous_post_link( '%link', $prev_post_label );
				$paginate['before_prev_post_link'] = esc_html( $before_prev_post_link );
				$paginate['prev_post'] = wpf_wrap( 'span', ob_get_clean(), array( 'class' => 'prev' ), false );
				$paginate['after_prev_post_link'] = esc_html( $after_prev_post_link );
			}

			if ( wpf_has_next_post() ) {
				ob_start();
				next_post_link( '%link', $next_post_label );
				$paginate['before_next_post_link'] = esc_html( $before_next_post_link );
				$paginate['next_post'] = wpf_wrap( 'span', ob_get_clean(), array( 'class' => 'next' ), false );
				$paginate['after_next_post_link'] = esc_html( $after_next_post_link );
			}
		}

		$paginate['after'] = wp_kses_post( $after );

		return $this->format_page_links( $paginate, $style );
	}

	/**
	 * Takes an array of pagination links and returns it formated.
	 *
	 * @todo needs to be able to be returned? Nah.
	 *
	 * @see wpf_paginate();
	 *
	 * @param array $paginate Data from wpf_paginate();
	 * @param string $style array|list|formatted Type of style to return.
	 */
	function format_page_links( $paginate, $style = 'formated' ) {
		$output = '';
		switch ( $style ) {
			case 'array':
				return $paginate;
				break;

			case 'list':
				$output .= '<ul class="paginate">';

				$output .= isset( $paginate['page_count'] ) ? '<li>' . $paginate['page_count'] . '</li>' : '';
				$output .= isset( $paginate['first_link'] ) ? '<li>' . $paginate['first_link'] . '</li>' : '';
				$output .= isset( $paginate['prev_link'] ) ? '<li>' . $paginate['prev_link'] . '</li>' : '';

				if ( isset($paginate['pages']) ) {
					foreach ( $paginate['pages'] as $page )
						$output .= '<li>' . $page . '</li>';
				}

				$output .= isset( $paginate['next_link'] ) ? '<li>' . $paginate['next_link'] . '</li>' : '';
				$output .= isset( $paginate['last_link'] ) ? '<li>' . $paginate['last_link'] . '</li>' : '';

				$output .= '</ul>';

				echo $output;
				break;
			case 'formated':
				// CSS Classes
				$classes[] = 'pagination';
				$classes[] = 'pagination-' . $paginate['type'];

				if ( 'posts' == $paginate['type'] ) {
					$classes[] = 'pagination-' . get_post_type();

					if ( is_singular() )
						$classes[] = 'singular-pagination';
				}

				$output .= $paginate['before'];
				$output .= isset( $paginate['page_count'] ) ? $paginate['page_count'] : '';
				$output .= isset( $paginate['first_link'] ) ? $paginate['first_link'] : '';
				$output .= isset( $paginate['prev_link'] ) ? $paginate['prev_link'] : '';

				// Singular pages
				$output .= isset( $paginate['prev_post'] ) ? $paginate['prev_post'] : '';
				$output .= isset( $paginate['next_post'] ) ? $paginate['next_post'] : '';

				if ( isset($paginate['pages']) ) {
					foreach ( $paginate['pages'] as $page )
						$output .= $page;
				}

				$output .= isset( $paginate['next_link'] ) ? $paginate['next_link'] : '';
				$output .= isset( $paginate['last_link'] ) ? $paginate['last_link'] : '';
				$output .= $paginate['after'];

				wpf_wrap( 'div', $output, array( 'class' => join( ' ', $classes ) ) );
				break;
		}
	}
}