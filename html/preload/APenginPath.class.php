<?php

if ( defined('XOOPS_ROOT_PATH') === false ) die;
define('PENGIN_PATH', XOOPS_ROOT_PATH.'/modules/pengin');
define('PENGIN_URL', XOOPS_URL.'/modules/pengin');

// A-Z順でpreloadが実行されるのでAで始めた
class APenginPath extends XCube_ActionFilter
{
	public function preBlockFilter()
	{
		// パス通し
		require_once PENGIN_PATH.'/Pengin.php';
		$pengin =& Pengin::getInstance();
//		$pengin->path(XOOPS_ROOT_PATH.'/modules/r_core');
	}
}
