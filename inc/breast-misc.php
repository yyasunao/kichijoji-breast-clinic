<?php
/**
 * init
 */
add_action('init','breast_init');
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
		'supports'          => array( '' ),
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
	//var_dump($postarr);
	 if('reception' == $data['post_type'] && isset($data['post_type'])) {

		//日付取得
		if (!empty($post->ID)) {
			//日付と投稿日時の同期
			$set_date = $_POST['acf']['field_5819a92cef2d7'];
			$data['post_date'] = date_i18n( 'Y-m-d 00:00:00', strtotime($set_date) );
			$data['post_date_gmt'] = date( 'Y-m-d 00:00:00', strtotime($set_date) );
			$data['post_status'] ="publish";

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
function reception_situation() {
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

		//予約不可日時
		$status = get_field('status',$list_query->ID);

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
function reception_time($date = "") {
	if ( !function_exists( 'get_field' ) )
		return;

	$date_data = date_i18n( 'y/m/d', strtotime($date) );

	// 個別指定取得
	$time = "";
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

		$time = get_field('time',$list_query->ID);
	endwhile;
	wp_reset_postdata();
	endif;

	if ( empty($time) ) {
		//曜日指定取得
		$week_num = date_i18n( 'w', strtotime($date) );
		$time = get_field('time_'.$week_num,'options');
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
 * footer javascript
 */
function my_javascript(){
	//予約フォーム
	if (is_page('appointment')):
	global $_POST;
	?>
	<script type="text/javascript">
	jQuery(function($){
/*
	//submit時 重複チェック
	$("form").submit(function(event){
		if (!overlap_check()) {
			$("html,body").animate({scrollTop:$('#reception-date').offset().top});
			return false;
		}
		else if (!samechara_num_check("#tel")) {
			$("html,body").animate({scrollTop:$('#tel').offset().top});
			return false;
		}
		else
		{
			$(this).submit();
		}
	});
*/
	//受付不可取得
	$(function(){
		$('.date-area').append('<p class="loader"></p>');
		$('.date-area input').hide();
		$.ajax({
			url: "<?php echo get_stylesheet_directory_uri() ?>/lib/ajax/date_set.php",
			type:'POST',
			dataType: 'json',
			data : {wp_directory : "<?php echo ABSPATH ?>"},
			timeout:10000,
			success: function(data) {
				datepicker_set(data);
				$('.loader').remove();
				$('.date-area input').show();
				time_load();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("error");
			}
		});
	});

	//予約可能時間取得
	$(".datepicker").on('change', function() {
		var date = $(this).val();
		var parent_id = ($(this).parent().parent().attr('id'));
		time_set(date,parent_id);
	});
/*
	//時間選択時 重複チェック
	$(document).on('change','.time',function(){
		overlap_check();
	});
*/
	//電話番号 連続文字チェック
	$(document).on('change','#tel',function(){
		samechara_num_check("#tel");
	});


	//重複チェック
	function overlap_check()
	{
		$(".overlap_err").remove();
		var data = "";
		var join = [];
		var flg = 0;
		var err = [];
		var err_cnt = 0;
		for (var i=0; i<3; i++) {
			flg = 0;
			tmp = $("#date-"+i).find(".datepicker").val();
			tmp2 = $("#date-"+i).find(".time").val();
			if (tmp2 !== undefined) {
				if( $.inArray(tmp+tmp2, join) === -1)
				{
					join.push( tmp+tmp2 );
				}
				else{
					err_cnt++;
					flg = 1;
				}
			}
			err.push(flg);
		}
		if (err_cnt > 0) {
			for (var i=0; i < err.length; i++) {
				if (err[i] === 1) {
					$("#date-"+i).append('<span class="overlap_err">選択済みです。。</span>');

				}
			}
			return false;
		}
		else{
			return true;
		}
	}
/*
	//連続同じ数字チェック
	function samechara_num_check(id){
		$(".samechara_num_err").remove();
		// 分割する数値
		var beforeText = $(id).val();
		// 数値を文字列に変換して、一文字ずつ分割
		var beforeTextArr = String(beforeText).split('');

		var num = 0;
		for (var i = 0; i < beforeTextArr.length; i++) {
			if (beforeTextArr[0] === beforeTextArr[i]) {
				num++;
			}
		}

		if (beforeTextArr.length === num) {
			$(id).parent().append('<span class="samechara_num_err">ご連絡可能な電話番号を入力してください。</span>');
			return false;
		}
		else{
			return true;
		}
	}
*/
	//カレンダーセット
	function datepicker_set(data)
	{
		var date_stop = data["date_stop"];//個別指定休業
		var week_stop = data["week_stop"];//定休日
		var date_open = data["date"];//個別指定予約可

		$(".datepicker").datepicker({
				minDate: '+1d',
				beforeShowDay: function(date) {
				var stop_flg = 0;

				if (week_stop instanceof Array) {
					for (var i = 0; i < week_stop.length; i++) {
						if (date.getDay() == week_stop[i]) {
							stop_flg = 1;
						}
					}
				}
				if (date_stop instanceof Array && !stop_flg) {
					for (var i = 0; i < date_stop.length; i++) {
						var htime = Date.parse(date_stop[i]);
						var holiday = new Date();
						holiday.setTime(htime);

						if (holiday.getYear() == date.getYear() &&
							holiday.getMonth() == date.getMonth() &&
							holiday.getDate() == date.getDate()) {
							stop_flg = 1;
						}
					}
				}

				if (date_open instanceof Array) {
					for (var i = 0; i < date_open.length; i++) {
						var htime = Date.parse(date_open[i]);
						var holiday = new Date();
						holiday.setTime(htime);

						if (holiday.getYear() == date.getYear() &&
							holiday.getMonth() == date.getMonth() &&
							holiday.getDate() == date.getDate()) {
							stop_flg = 0;
						}
					}
				}
				if (stop_flg) {
					return [false, 'holiday'];
				}
				else{
					return [true, ''];
				}
				
			}
		});
	}

	// 時間取得
	function time_set(date, parent_id)
	{
		$("#"+parent_id).find(".time").remove();

		if (date !== undefined && date !== "") {
			$("#"+parent_id).find(".time-area").append('<p class="loader"></p>');

			$.ajax({
				url: "<?php echo get_stylesheet_directory_uri() ?>/lib/ajax/time_set.php",
				type:'POST',
				dataType: 'json',
				data : {wp_directory : "<?php echo ABSPATH ?>", date: date, parent_id: parent_id},
				timeout:10000,
				success: function(data) {
					$("#"+parent_id).find(".time-area .loader").remove();
					if (data) {
						//時間セット
						data_arr = data.split("\r\n");
						parent_id_arr = parent_id.split("-");
						select = '<select id="time-'+parent_id_arr[1]+'" class="time validate[custom[overlap]]" name="時間'+parent_id_arr[1]+'">';
						select += '<option value="当院指定の時刻で可">当院指定の時刻で可</option>';
						jQuery.each(data_arr, function(key, val) {
							disabled = '';
							if ( ~val.indexOf('*')) {
								disabled = ' disabled="disabled"';
								val = val.replace(/\*/g,'');
							}
							select += '<option value="'+val+'"'+disabled+'>'+val+'</option>';
						});
						$("#"+parent_id).find(".time-area").append(select);
					}
					//overlap_check();
				},
				complete:  function(XMLHttpRequest, textStatus){
					<?php
					$time0 = ( isset( $_POST["時間0"] ) ) ? $_POST["時間0"] : '';
					$time1 = ( isset( $_POST["時間1"] ) ) ? $_POST["時間1"] : '';
					$time2 = ( isset( $_POST["時間2"] ) ) ? $_POST["時間2"] : '';
					?>
					//selected
					time0 = '<?php echo $time0 ?>';
					time1 = '<?php echo $time1 ?>';
					time2 = '<?php echo $time2 ?>';
					if (time0) {
						$('*[name="時間0"]').val('<?php echo $time0 ?>');
					}
					if (time1) {
						$('*[name="時間1"]').val('<?php echo $time1 ?>');
					}
					if (time2) {
						$('*[name="時間2"]').val('<?php echo $time2 ?>');
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("error");
				}
			});
		}
		else
		{
			$(".overlap_err").remove();
		}
	}

	// 時間一括取得
	function time_load()
	{
		var parent_id = "";
		var date = "";
		for (var i=0; i<3; i++) {
			parent_id = "date-"+i;
			date = $("#"+parent_id).find(".datepicker").val();
			if (date !== undefined && date !== "") {
				time_set(date,parent_id);
			}
		}
	}

	// 診察券番号表示
	$(document).on("change",'[name="当院へのご来院"]', function() {
		card_number_dsp("slow");
	});
	$(window).load(function () {
		card_number_dsp();
	});
	function card_number_dsp(action)
	{
		if ( "過去に受診したことがある" == $('[name="当院へのご来院"]:checked').val() ) {
			$('#card-number').show(action);
		}
		else
		{
			$('[name="診察券番号"]').val("");
			$('#card-number').hide(action);
		}
	}

	// 検診・人間ドックの内容 表示
	$(document).on("change",'[name="受信目的"]', function() {
		examination_contens_dsp("slow");
	});
	$(window).load(function () {
		examination_contens_dsp();
	});
	function examination_contens_dsp(action)
	{
		if ( "検診や人間ドックで要再検査・要精密検査の判定を受けた" == $('[name="受信目的"]:checked').val() ) {
			$('#examination-contens').show(action);
		}
		else
		{
			$('[name="検診・人間ドックの内容[]"]').prop("checked", false);
			$('#examination-contens').hide(action);
		}
	}

	});

	jQuery("#form").validationEngine('attach', {
		promptPosition:"inline"
	});


	</script>
	<?php
	endif;//if (is_page('appointment'))
}
add_action('wp_footer', 'my_javascript',999);


/**
 * 管理画面 footer javascript
 */
function my_admin_javascript(){
	?>
	<script type="text/javascript">
	jQuery(function($){
	$(".hasDatepicker").datepicker({
	  onSelect: function(dateText) {
	  }
	});

		$(document).on('change',".post-type-reception form .hasDatepicker",function(){
			date = $("*[name='acf[field_5819a92cef2d7]']").val();


			if (date !== undefined && date !== "") {
			$.ajax({
				url: "<?php echo get_stylesheet_directory_uri() ?>/lib/ajax/time_set.php",
				type:'POST',
				dataType: 'json',
				data : {wp_directory : "<?php echo ABSPATH ?>", date: date},
				timeout:10000,
				success: function(data) {
					$("*[name='acf[field_5819aa5fe918a]']").val(data);
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
}
add_action('admin_print_footer_scripts', 'my_admin_javascript');
