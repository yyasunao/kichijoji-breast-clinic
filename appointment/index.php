<?php
/**
 * TransmitMail
 *
 * @package   TransmitMail
 * @license   MIT License
 * @copyright TAGAWA Takao, dounokouno@gmail.com
 * @link      https://github.com/dounokouno/TransmitMail
 */
require_once get_template_directory().'/appointment/lib/TransmitMail.php';
$tm = new TransmitMail();
$tm->init(get_template_directory().'/appointment/config/config.yml');

//テンプレート内php変数セット
$topics_reception = get_field('topics_reception','options');
$topics_reception_flg = 0;
if (!empty($topics_reception)) {
	$topics_reception_flg = 1;
}
$form_action_url = home_url().'/appointment/';

$year = "";
if (!empty($_POST["年"])) {
	$year = $_POST["年"];
}
$year_select = year_config($year,90,-10);
$month = "";
if (!empty($_POST["月"])) {
	$month = $_POST["月"];
}
$month_select = month_config($month);
$day = "";
if (!empty($_POST["日"])) {
	$day = $_POST["日"];
}
$day_select = day_config($day);

$card_number_flg = 0;
if (!empty($_POST["診察券"])) {
	$card_number_flg = 1;
}

$examination_contens_flg = 0;
if ( isset( $_POST["検診・人間ドックの内容"] ) && is_array($_POST["検診・人間ドックの内容"])) {
	if (!empty($_POST["検診・人間ドックの内容"][0])) {
		$examination_contens_flg = 1;
	}
}

// wp contant
$get_the_content = get_the_content();

$tm->tpl->set('topics_reception', $topics_reception);
$tm->tpl->set('topics_reception_flg', $topics_reception_flg);
$tm->tpl->set('form_action_url', $form_action_url);
$tm->tpl->set('year_select', $year_select);
$tm->tpl->set('month_select', $month_select);
$tm->tpl->set('day_select', $day_select);
$tm->tpl->set('card_number_flg', $card_number_flg);
$tm->tpl->set('examination_contens_flg', $examination_contens_flg);
$tm->tpl->set('get_the_content', $get_the_content);

$tm->run();


function year_config($year,$min=10,$max=10)
{
	if($year=="")
	{
		$year = date("Y");
	}
	$str = "  <option value=\"\">-</option>\r\n";
	

	for($i=date("Y")-$min; $i<=date("Y")+$max; $i++)
	{
		if((int)$i==(int)$year)
		{
			$str .= "  <option value=\"" .$i. "\" selected>" .$i. "</option>\r\n";
		}
		else
		{
			$str .= "  <option value=\"" .$i. "\">" .$i. "</option>\r\n";
		}
	}
	
	return $str;
}

//月バインド処理
function month_config($month)
{
	if($month=="")
	{
		//$month = date("m");
	}
	
	$str = "  <option value=\"\">-</option>\r\n";

	for($i=1; $i<=12; $i++)
	{
		if((int)$i==(int)$month)
		{
			$str .= "  <option value=\"" .$i. "\" selected>" .$i. "</option>\r\n";
		}
		else
		{
			$str .= "  <option value=\"" .$i. "\">" .$i. "</option>\r\n";
		}
	}

	return $str;
}

//日バインド処理
function day_config($day)
{
	if($day=="")
	{
		//$day = date("d");
	}

	$str = "  <option value=\"\">-</option>\r\n";
	
	for($i=1; $i<=31; $i++)
	{
		if((int)$i==(int)$day)
		{
			$str .= "  <option value=\"" .$i. "\" selected>" .$i. "</option>\r\n";
		}
		else
		{
			$str .= "  <option value=\"" .$i. "\">" .$i. "</option>\r\n";
		}
	}

	return $str;
}