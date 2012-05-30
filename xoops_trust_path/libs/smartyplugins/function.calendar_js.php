<?php

/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.calendar_js.php
* Type:     function
* Name:     link_to
* Purpose:  カレンダー用JavaScript
* -------------------------------------------------------------
*/
function smarty_function_calendar_js($params, &$smarty)
{
	ob_start();
	include_once XOOPS_ROOT_PATH.'/include/calendarjs.php';
	$js = ob_get_contents();
	ob_end_clean();
	
	return $js;
}
