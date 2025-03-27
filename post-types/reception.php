<?php

/**
 * Registers the `reception` post type.
 */
function reception_init() {
	register_post_type(
		'reception',
		[
			'labels'                => [
				'name'                  => __( '日付別予約受付', 'kichijoji-breast-clinic' ),
				'singular_name'         => __( '日付別予約受付', 'kichijoji-breast-clinic' ),
				'all_items'             => __( '日付別予約受付一覧', 'kichijoji-breast-clinic' ),
				'archives'              => __( '日付別予約受付アーカイブ', 'kichijoji-breast-clinic' ),
				'attributes'            => __( '日付別予約受付の属性', 'kichijoji-breast-clinic' ),
				'insert_into_item'      => __( '日付別予約受付に挿入', 'kichijoji-breast-clinic' ),
				'uploaded_to_this_item' => __( 'この日付別予約受付へのアップロード', 'kichijoji-breast-clinic' ),
				'featured_image'        => _x( 'アイキャッチ画像', '日付別予約受付', 'kichijoji-breast-clinic' ),
				'set_featured_image'    => _x( 'アイキャッチ画像を設定', '日付別予約受付', 'kichijoji-breast-clinic' ),
				'remove_featured_image' => _x( 'アイキャッチ画像を削除', '日付別予約受付', 'kichijoji-breast-clinic' ),
				'use_featured_image'    => _x( 'アイキャッチ画像として使用', '日付別予約受付', 'kichijoji-breast-clinic' ),
				'filter_items_list'     => __( '日付別予約受付一覧を絞り込む', 'kichijoji-breast-clinic' ),
				'items_list_navigation' => __( '日付別予約受付リストナビゲーション', 'kichijoji-breast-clinic' ),
				'items_list'            => __( '日付別予約受付リスト', 'kichijoji-breast-clinic' ),
				'new_item'              => __( '新規日付別予約受付を追加', 'kichijoji-breast-clinic' ),
				'add_new'               => __( '新規追加', 'kichijoji-breast-clinic' ),
				'add_new_item'          => __( '新しい日付別予約受付を追加', 'kichijoji-breast-clinic' ),
				'edit_item'             => __( '日付別予約受付を編集', 'kichijoji-breast-clinic' ),
				'view_item'             => __( '日付別予約受付を表示', 'kichijoji-breast-clinic' ),
				'view_items'            => __( '日付別予約受付一覧を表示', 'kichijoji-breast-clinic' ),
				'search_items'          => __( '日付別予約受付を検索', 'kichijoji-breast-clinic' ),
				'not_found'             => __( '日付別予約受付が見つかりませんでした。', 'kichijoji-breast-clinic' ),
				'not_found_in_trash'    => __( 'ゴミ箱内に日付別予約受付が見つかりませんでした。', 'kichijoji-breast-clinic' ),
				'parent_item_colon'     => __( '親の 日付別予約受付', 'kichijoji-breast-clinic' ),
				'menu_name'             => __( '日付別予約受付', 'kichijoji-breast-clinic' ),
			],
			'public'                => false,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => ['revisions'],
			'has_archive'           => true,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-admin-post',
			'show_in_rest'          => true,
			'rest_base'             => 'reception',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		]
	);

}

add_action( 'init', 'reception_init' );

/**
 * Sets the post updated messages for the `reception` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `reception` post type.
 */
function reception_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['reception'] = [
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( '日付別予約受付 updated. <a target="_blank" href="%s">View 日付別予約受付</a>', 'kichijoji-breast-clinic' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'kichijoji-breast-clinic' ),
		3  => __( 'Custom field deleted.', 'kichijoji-breast-clinic' ),
		4  => __( '日付別予約受付 updated.', 'kichijoji-breast-clinic' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( '日付別予約受付 restored to revision from %s', 'kichijoji-breast-clinic' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		/* translators: %s: post permalink */
		6  => sprintf( __( '日付別予約受付 published. <a href="%s">View 日付別予約受付</a>', 'kichijoji-breast-clinic' ), esc_url( $permalink ) ),
		7  => __( '日付別予約受付 saved.', 'kichijoji-breast-clinic' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( '日付別予約受付 submitted. <a target="_blank" href="%s">Preview 日付別予約受付</a>', 'kichijoji-breast-clinic' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( '日付別予約受付 scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview 日付別予約受付</a>', 'kichijoji-breast-clinic' ), date_i18n( __( 'M j, Y @ G:i', 'kichijoji-breast-clinic' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( '日付別予約受付 draft updated. <a target="_blank" href="%s">Preview 日付別予約受付</a>', 'kichijoji-breast-clinic' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	];

	return $messages;
}

add_filter( 'post_updated_messages', 'reception_updated_messages' );

/**
 * Sets the bulk post updated messages for the `reception` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `reception` post type.
 */
function reception_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
	global $post;

	$bulk_messages['reception'] = [
		/* translators: %s: Number of 日付別予約受付. */
		'updated'   => _n( '%s 日付別予約受付 updated.', '%s 日付別予約受付 updated.', $bulk_counts['updated'], 'kichijoji-breast-clinic' ),
		'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 日付別予約受付 not updated, somebody is editing it.', 'kichijoji-breast-clinic' ) :
						/* translators: %s: Number of 日付別予約受付. */
						_n( '%s 日付別予約受付 not updated, somebody is editing it.', '%s 日付別予約受付 not updated, somebody is editing them.', $bulk_counts['locked'], 'kichijoji-breast-clinic' ),
		/* translators: %s: Number of 日付別予約受付. */
		'deleted'   => _n( '%s 日付別予約受付 permanently deleted.', '%s 日付別予約受付 permanently deleted.', $bulk_counts['deleted'], 'kichijoji-breast-clinic' ),
		/* translators: %s: Number of 日付別予約受付. */
		'trashed'   => _n( '%s 日付別予約受付 moved to the Trash.', '%s 日付別予約受付 moved to the Trash.', $bulk_counts['trashed'], 'kichijoji-breast-clinic' ),
		/* translators: %s: Number of 日付別予約受付. */
		'untrashed' => _n( '%s 日付別予約受付 restored from the Trash.', '%s 日付別予約受付 restored from the Trash.', $bulk_counts['untrashed'], 'kichijoji-breast-clinic' ),
	];

	return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'reception_bulk_updated_messages', 10, 2 );
