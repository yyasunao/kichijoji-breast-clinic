(function($){
	/**
	 * Validation with snow monkey forms
	 */
	if($('button[data-action="confirm"]').length){
		$('form').find('button[data-action="confirm"]').get(0).type = 'button';
	}
	if($('button[data-action="complete"]').length){
		$('form').find('button[data-action="complete"]').get(0).type = 'button';
	}
	$("form").validationEngine('attach',{
		promptPosition:"inline"
	});
	$('form').on('change', function() {
		if (this.checkValidity()) {
			validationCheck();
		}
	});
	$(document).on('click','button[data-action="confirm"], button[data-action="complete"]',function(){
		let isValid = $("form").validationEngine('validate',{
			promptPosition:"inline"
		});
	});
	function validationCheck() {
		let isValid = $("form").validationEngine('validate',{
			promptPosition:"inline",
			scroll:false,
			focusFirstField: false,
			showOneMessage: true
		});
		if (isValid) {
			if($('button[data-action="confirm"]').length){
				$('form').find('button[data-action="confirm"]').get(0).type = 'submit';
			}
			if($('button[data-action="complete"]').length){
				$('form').find('button[data-action="complete"]').get(0).type = 'submit';
			}
		}
		else {
			if($('button[data-action="confirm"]').length){
				$('form').find('button[data-action="confirm"]').get(0).type = 'button';
			}
			if($('button[data-action="complete"]').length){
				$('form').find('button[data-action="complete"]').get(0).type = 'button';
			}
		}
	}
	// 確認画面無し
	if($('button[data-action="complete"]').length && $('input[name="check-confirmed[]"]').length){
		$('input[name="check-confirmed[]"]').addClass('validate[required]');
	}


	/**
	 * datepicker with snow monkey forms
	 */
	$(document).ready(function(){
		date_readonly();
		time_disabled();
		date_set();
	});
	$(document).on('click load','button[data-action="back"]',function(){
		date_readonly();
		time_disabled();
		date_set();
	});

	//予約可能時間取得
	$(document).on('change input', '.datepicker', function() {
		var date = $(this).val();
		var inpuutName = $(this).attr('name');
		time_set(date, inpuutName);
	});

	//受付不可取得
	function date_set() {
		$.ajax({
			url: kbc_localize_data.get_stylesheet_directory_uri + "/lib/ajax/date_set.php",
			type:'POST',
			dataType: 'json',
			data : {wp_directory : kbc_localize_data.ABSPATH, post_name : kbc_localize_data.post_name},
			timeout:10000,
			success: function(data) {
				datepicker_set(data);
				time_load();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("error");
			}
		});
	}


	//カレンダーセット
	function datepicker_set(data) {
		var date_stop = data["date_stop"];//個別指定休業
		var week_stop = data["week_stop"];//定休日
		var date_open = data["date"];//個別指定予約可

		$(".datepicker").datepicker({
				minDate: '+1d',
				beforeShowDay: function(date) {
				var stop_flg = 0;

				// 祝日
				let japan_holiday = JapaneseHolidays.isHoliday(date);

				if ( japan_holiday ) {
					stop_flg = 1;
				}

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

		var currentDate = new Date();
		var defaultYear = currentDate.getFullYear() - 25;
		var defaultMonth = currentDate.getMonth() + 1;
		var defaultDay = currentDate.getDate();
		$(".datepicker--select").datepicker({
			changeYear: true,
			changeMonth: true,
			yearRange: '-120:+0',
			defaultDate: defaultYear + "/" + defaultMonth + "/" + defaultDay
		});
	}

	// 時間取得
	function time_set(date, inpuutName) {

		$("*[name='" + inpuutName + "_time'] > option").remove();
		$("*[name='" + inpuutName + "_time']").prop("disabled", true).append($('<option>').html("時間を選択してください").val(""));

		if (date !== undefined && date !== "") {

			$.ajax({
				url: kbc_localize_data.get_stylesheet_directory_uri + "/lib/ajax/time_set.php",
				type:'POST',
				dataType: 'json',
				data : {wp_directory : kbc_localize_data.ABSPATH, date: date},
				timeout:10000,
				success: function(data) {
					if (data) {
						$("*[name='" + inpuutName + "_time'] > option").remove();
						$("*[name='" + inpuutName + "_time']").prop("disabled", false).append($('<option>').html("当院指定の時刻で可").val("当院指定の時刻で可"));
						//時間セット
						if ( data[kbc_localize_data.post_name] != null ) {
							data_arr = data[kbc_localize_data.post_name].split("\r\n");
							jQuery.each(data_arr, function(key, val) {
								disabled = false;
								if ( ~val.indexOf('*')) {
									disabled = true;
									val = val.replace(/\*/g,'');
								}
								$("*[name='" + inpuutName + "_time']").append($('<option>').html(val).val(val).prop("disabled", disabled));
							});
						}
					}
				},
				complete:  function(XMLHttpRequest, textStatus){
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
	function time_load() {
		var inpuutName = "";
		var date = "";
		for (var i=1; i<=3; i++) {
			inpuutName = 'preferred' + i;
			date = $("*[name='" + inpuutName + "'].datepicker").val();
			if (date !== undefined && date !== "") {
				time_set(date, inpuutName);
			}
		}
	}

	// 日付 input readonly
	function date_readonly() {
		var inpuutName = "";
		var date = "";
		for (var i=1; i<=3; i++) {
			inpuutName = 'preferred' + i;
			$("*[name='" + inpuutName + "']").prop("readonly", true);
		}
		$("*[name='date-birth']").prop("readonly", true);
	}
	// 時間 select disabled
	function time_disabled() {
		var inpuutName = "";
		var date = "";
		for (var i=1; i<=3; i++) {
			inpuutName = 'preferred' + i + "_time";
			$("*[name='" + inpuutName + "']").prop("disabled", true);
		}
	}

	/**
	 * form カスタマイズ
	 */
	// 検診・人間ドックの内容 表示
	$(document).on("change",'[name="request"]', function() {
		examination_contens_dsp("slow");
	});
	$(window).on('load',function (){
		examination_contens_dsp();
	});
	function examination_contens_dsp(action) {
		if ( "検診や人間ドックで要再検査・要精密検査の判定を受けた" == $('[name="request"]:checked').val() ) {
			$('#examination-contens').show(action);
		}
		else
		{
			$('[name="medical-checkup[]"]').prop("checked", false);
			$('#examination-contens').hide(action);
		}
	}


})(jQuery);
