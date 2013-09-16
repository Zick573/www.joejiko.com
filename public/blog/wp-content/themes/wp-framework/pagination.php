<?php
/**
 * Pagination for Posts.
 *
 * This function displays page navigation for your theme.
 *
 * Optional $args contents:
 *
 * page_count_label		: The text/HTML to display before the list of pages.
 * current_page_label	: The text/HTML to display for the current page. Defaults to the current page number.
 * first_label			: The label to display for the first page link.
 * last_label			: The label to display for the last page link.
 * prev_label			: The label to display for the previous page link.
 * next_label			: The label to display for the next page link.
 * page_range			: The number of page links to show before and after the current page. Defaults to 3.
 * page_anchors			: The number of links to always show at beginning and end of pagination. Defaults to 1.
 * before				: Optional. Insert HTML before the pagination links.
 * after				: Optional. Insert HTML after the pagination links.
 * style 				: Controls the format of the returned links.
 * type					: Whether to show pagination links for posts or comments.
 * dots					: The display of the ellipsis.
 * before_prev_post_link: Optional. Insert text before the previous post link.
 * before_next_post_link: Optional. Insert text before the next post link.
 * show_dots			: Whether to show the dots or not.
 * show_page_count		: Whether to show the page count label.
 * always_show_prev_next_links: Whether to always show Next and Previous links.
 * show_first_last_links: Whether to show First and Last links.
 * format_callback		: Callback function for handling the display of the pagination links.
 */
wpf_paginate_posts( array(
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
	'always_show_prev_next_links' => false,
	'show_first_last_links' => false,
) );