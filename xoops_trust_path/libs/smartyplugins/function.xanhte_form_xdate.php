<?php
// {{{ smarty_function_xanhte_form_xdate
/**
 *	smarty function:XOOPSdateフォーム生成
 *
 *
 *	sample:
 *	<code>
 *	{radio name="yesno" list="$app.radio_list" value="$form.yesno"}
 *	</code>
 *
 *	@param	array	$params フォームの属性
 *	@param	object	$smarty Smartyオブジェクト
 */
function smarty_function_xanhte_form_xdate($params, &$smarty)
{
	$c =& Ethna_Controller::getInstance();
	$af =& $c->getActionForm();
	$def = $af->getDef();
	$ae =& $c->getActionError();
	
	extract($params);	// $name = input_name , $attr = input_attributes
	if (!isset($name)){
		return '';
	}
	require_once 'formelements/formdatetimehelper.php';

	$r = '' ;
	!isset($addtimeselect) and $addtimeselect = false ;
	!isset($attr) and $attr = '';
	!isset($textsize) and $textsize = 15;
	!isset($maxlength) and $maxlength = 15;
	!isset($key_value) and $key_value = null;
	!isset($minute_interval) and $minute_interval = 30;
	
	$date_name = $name . '_date'; 
	$time_name = $name . '_time'; 
	// arrayとして扱うかどうかのフラグ
	$is_array = is_array($def[$date_name]['type']) && is_array($def[$time_name]['type']) &&	!is_null($key_value);
	if ($is_array){
		$name .=  sprintf('[%s]', $key_value);
	}
	
	$value = 0;
	if (!$ae->isError($name)){
		// form_value
		if ($is_array){
			$_tmp = $af->get($date_name);
			$formvalue_date = $_tmp[$key_value];
			if ($addtimeselect){
				$_tmp = $af->get($time_name);
				$formvalue_time = $_tmp[$key_value];
			}
		} else {
			$formvalue_date = $af->get($date_name);
			if ($addtimeselect){
				$formvalue_time = $af->get($time_name);
			}
		}
		if (preg_match('/^(\d{4})-?(\d{2})-?(\d{2})$/', $formvalue_date, $matchdate)){
			list($dump, $y, $m, $d) = $matchdate ;
			if (@mktime(0, 0, 0, $m, $d, $y)>0){
				$value = mktime(0, 0, 0, $m, $d, $y) + $formvalue_time;
			}
		}
	}
	$r = DateFormHelper::create($value, $name, $textsize, $maxlength, $addtimeselect, $minute_interval);
	
	print $r ;
}
// }}}
?>