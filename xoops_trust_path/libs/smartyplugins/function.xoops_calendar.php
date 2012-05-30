<?php

/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.xoops_calendar.php
* Type:     function
* Name:     link_to
* Purpose:  カレンダー（要calendar_js）
* -------------------------------------------------------------
*/
function smarty_function_xoops_calendar($params, &$smarty)
{
	$name = $params['name'];
	$value = $params['value'];

	require_once XOOPS_ROOT_PATH . '/class/xoopsform/formelement.php';
	require_once XOOPS_ROOT_PATH . '/class/xoopsform/formelementtray.php';
	require_once XOOPS_ROOT_PATH . '/class/xoopsform/formtext.php';
	require_once XOOPS_ROOT_PATH . '/class/xoopsform/formselect.php';
	require_once XOOPS_ROOT_PATH . '/class/xoopsform/formtextdateselect.php';
	require_once XOOPS_ROOT_PATH . '/class/xoopsform/formdatetime.php';
	require_once XOOPS_ROOT_PATH . '/class/xoopsform/formdhtmltextarea.php';

	$cal_form = new XoopsFormTextDateSelect(
		$name,
		$name,
		15,
		strtotime($value)
	);
	$contents = $cal_form->render();

	return $contents; 
}
