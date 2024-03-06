<?php
/**
 * post type
 */
require get_template_directory() . '/post-types/reception.php';

/**
 * 投稿画面 項目非表示
 *
 * @return void
 */
function my_remove_post_editor_support() {
	remove_post_type_support( 'reception', 'title' );
	remove_post_type_support( 'reception', 'editor' );
}
add_action( 'init' , 'my_remove_post_editor_support' );

/**
 * init
 */
// add_action('init','breast_init');
if(!function_exists('breast_init')) :
function breast_init() {
	// 日付別予約受付post
	register_post_type( 'reception', array(
		'labels'            => array(
			'name'                => __( '日付別予約受付', 'shima-plugin' ),
			'singular_name'       => __( '日付別予約受付', 'shima-plugin' ),
			'all_items'           => __( '日付別予約受付一覧', 'shima-plugin' ),
			'new_item'            => __( '新規投稿を追加', 'shima-plugin' ),
			'add_new'             => __( '新規追加', 'shima-plugin' ),
			'add_new_item'        => __( '新しい日付別予約受付を追加', 'shima-plugin' ),
			'edit_item'           => __( '日付別予約受付を編集', 'shima-plugin' ),
			'view_item'           => __( '日付別予約受付を表示', 'shima-plugin' ),
			'search_items'        => __( '日付別予約受付を検索', 'shima-plugin' ),
			'not_found'           => __( '日付別予約受付が見つかりませんでした。', 'shima-plugin' ),
			'not_found_in_trash'  => __( 'ゴミ箱内に日付別予約受付が見つかりませんでした。', 'shima-plugin' ),
			'parent_item_colon'   => __( 'Parent reception', 'shima-plugin' ),
			'menu_name'           => __( '日付別予約受付', 'shima-plugin' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => [],
		'has_archive'       => true,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_icon'         => 'dashicons-admin-post',
		'menu_position'     => 5,
		'show_in_rest'      => true,
		'rest_base'         => 'reception',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
endif; // breast_init

//予約投稿機能を無効化
add_action('save_post', 'futuretopublish', 99);
add_action('edit_post', 'futuretopublish', 99);
function futuretopublish()
{
global $wpdb;
$sql = 'UPDATE `'.$wpdb->prefix.'posts` ';
$sql .= 'SET post_status = "publish" ';
$sql .= 'WHERE post_status = "future"';
$wpdb->get_results($sql);
}

/**
 * 管理画面 投稿一覧カスタマイズ
 */
//日付別予約受付
function add_posts_columns($columns) {
	$columns['set_date'] = '日付';
	return $columns;
}
function add_posts_columns_list($column_name, $post_id) {
	$screen = get_current_screen();
	if ( $screen ->post_type == 'reception' ) {
		if ( 'set_date' == $column_name ) {
			$set_date = get_field('set_date',$post_id);
			$set_date = date_i18n( 'Y/m/d', strtotime($set_date) ). " (".week_jp(date_i18n( 'w', strtotime($set_date) )).")";
			echo ( $set_date ) ? $set_date : '?';
		}
	}
}
add_filter( 'manage_edit-reception_columns', 'add_posts_columns' );
add_action( 'manage_reception_posts_custom_column', 'add_posts_columns_list', 10, 2 );
//日付別予約受付 表示項目順
function sort_list($ch){
	$ch = array(
		'cb' => '<input type="checkbox" />',
		'set_date' => '日付',
		'date' => '日時',
	);
	return $ch;
}
add_filter( 'manage_reception_posts_columns', 'sort_list');

// 追加したフィールド項目のソートの設定
function column_orderby_set_date( $vars ) {
	if ( isset( $vars['orderby'] ) && 'set_date' == $vars['orderby'] ) { 
		$vars = array_merge( $vars, array(
		'meta_key' => 'set_date',
		'orderby' => 'meta_value'
		));
	}
	return $vars;
}
add_filter( 'request', 'column_orderby_set_date' );
// ソートする項目を登録
function set_date_register_sortable( $sortable_column ) {
	$sortable_column['set_date'] = 'set_date';
	return $sortable_column;
}
add_filter( 'manage_edit-reception_sortable_columns', 'set_date_register_sortable' );

//保存時処理
function replace_post_data($data, $postarr){
	global $post;

	 if('reception' == $data['post_type'] && isset($data['post_type'])) {

		//日付取得
		if (!empty($post->ID)) {
			$set_date = $_POST['acf']['field_5819a92cef2d7'];
			if ( $data['post_status'] != 'trash' ) {
				$data['post_status'] ="publish";
			}

			//過去の同じ日付設定を削除
			$old_id = "";
			$list_query = new WP_Query( array(
			'post_type' => "reception",
			'posts_per_page' => -1,
			'orderby' => 'date',
			'order' => 'DESC',
			'post__not_in' => array($post->ID),
			'meta_query' => array(
					array(
						'key' => 'set_date',
						'value' => $set_date,
						'compare' => '='
					),

				)
			));
			$i = 0;
			if( $list_query->have_posts() ) :
			while( $list_query->have_posts() ) : $list_query->the_post();
				//重複データを削除
				wp_delete_post( get_the_ID(), true );

			endwhile;
			wp_reset_postdata();
			endif;
		}
	 }
	return $data;
}
add_filter('wp_insert_post_data', 'replace_post_data', '99', 2);



/**
 * ACF
 */
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page( array(
	'page_title' => '基本設定',
	'menu_title' => '基本設定',
	'menu_slug' => 'theme-options',
	'capability' => 'edit_posts',
	'parent_slug' => '',
	'position' => 4,
	'icon_url' => false,
	'redirect' => false
	) );
}



/**
 * 予約受付状況取得
 */
function reception_situation( $post_name ) {
	if ( !function_exists( 'get_field' ) )
		return;

	$data = array();
	$status = "";
	//今日以降の指定休業日
	$currnet_date = date_i18n( 'y/m/d' );
	$list_query = new WP_Query( array(
	'post_type' => "reception",
	'posts_per_page' => -1,
	'orderby' => 'meta_value',
	'meta_key' => 'set_date',
	'order' => 'ASC',
	'meta_query' => array(
			array(
				'key' => 'set_date',
				'value' => $currnet_date,
				'compare' => '>=',
				'type' => 'DATE'
			)
		)
	));
	if( $list_query->have_posts() ) :
	while( $list_query->have_posts() ) : $list_query->the_post();

		$group = get_field( $post_name . '-examination', $list_query->ID );

		//予約不可日時
		$status = $group[$post_name . '-status'];

		if ( !empty($status) && $status[0] == "1" ) {
			$data['date_stop'][] = get_field('set_date',$list_query->ID);
		}
		//予約可
		else
		{
			$data['date'][] = get_field('set_date',$list_query->ID);
		}

	endwhile;
	wp_reset_postdata();
	endif;

	//休業曜日
	$sutatus = "";
	for ($i=0; $i <= 6 ; $i++) { 
		$sutatus = get_field('status_'.$i,'options');
		if ( !empty($sutatus[0]) ) {
			$data['week_stop'][] = $i;//曜日ナンバーを代入
		}
	}

	return $data;
}

/**
 * 予約受付時間取得
 */
function reception_time( $date = "" ) {
	if ( !function_exists( 'get_field' ) )
		return;

	$date_data = date_i18n( 'y/m/d', strtotime($date) );

	// 個別指定取得
	$time = array();
	$list_query = new WP_Query( array(
	'post_type' => "reception",
	'posts_per_page' => 1,
	'orderby' => 'date',
	'order' => 'DESC',
	'meta_query' => array(
			array(
				'key' => 'set_date',
				'value' => $date_data,
				'compare' => '=',
				'type' => 'DATE'
			)
		)
	));
	if( $list_query->have_posts() ) :
	while( $list_query->have_posts() ) : $list_query->the_post();

		$first_group = get_field('first-examination',$list_query->ID);
		$time["first"] = $first_group['first-time'];

		$revisit_group = get_field('revisit-examination',$list_query->ID);
		$time["revisit"] = $revisit_group['revisit-time'];

	endwhile;
	wp_reset_postdata();
	endif;

	if ( empty($time) ) {
		//曜日指定取得
		$week_num = date_i18n( 'w', strtotime($date) );

		$first_group = get_field('first-examination-basic','options');
		$time["first"] = $first_group['first-time_'.$week_num];

		$revisit_group = get_field('revisit-examination-basic','options');
		$time["revisit"] = $revisit_group['revisit-time_'.$week_num];
	}

	return $time;

}

/**
 * 曜日変換
 */
function week_jp($num = "")
{
	$week = "";
	if (!empty($num) || $num == 0) {
		$weekjp_array = array('日', '月', '火', '水', '木', '金', '土');
		$week = $weekjp_array[$num];
	}
	return $week;
}

/**
 * 管理画面 footer javascript
 */
function my_admin_javascript(){
	$screen = get_current_screen();
	if ( $screen->post_type == 'reception' && $screen->base == "post" ) :
	?>
	<script type="text/javascript">
		jQuery(function($){
			$(".hasDatepicker").datepicker({
				onSelect: function(dateText) {
			}
		});

		$(document).on('change',".post-type-reception form .hasDatepicker",function(el){

			date = $("*[name='acf[field_5819a92cef2d7]").val();

			if (date !== undefined && date !== "") {
			$.ajax({
				url: "<?php echo get_stylesheet_directory_uri() ?>/lib/ajax/time_set.php",
				type:'POST',
				dataType: 'json',
				data : {wp_directory : "<?php echo ABSPATH ?>", date: date},
				timeout:10000,
				success: function(data) {
					$("*[name='acf[field_65c18d21bf5ce][field_5819aa5fe918a]']").val(data["first"]);
					$("*[name='acf[field_65c19db926fa2][field_65c19db926fa5]']").val(data["revisit"]);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("error");
				}
			});
			}


		});

	});

	</script>
	<?php
	endif;
}
add_action('admin_print_footer_scripts', 'my_admin_javascript');

/**
 * 管理画面 日付別予約受付一覧 デフォルト昇順
 *
 * @param [type] $wp_query
 * @return void
 */
function add_pre_get_posts($wp_query) {
	if( is_admin() ) {
		$post_type = $wp_query->query['post_type'];
		if($post_type == 'reception') { // 投稿タイプ名
			$wp_query->set('meta_key', 'set_date');
			$wp_query->set('orderby', 'meta_value'); // 並び順を指定
			//   $wp_query->set('order', 'DESC'); // 降順（デフォルト） = 3 → 2 → 1
			$wp_query->set('order', 'ASC'); // 昇順 = 1 → 2 → 3
		}
	}
}
add_filter('pre_get_posts', 'add_pre_get_posts');