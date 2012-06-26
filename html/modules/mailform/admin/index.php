<?php

require '../../../mainfile.php';
require_once PENGIN_PATH.'/Pengin.php';

require_once XOOPS_ROOT_PATH.'/header.php';

if ( isset($_GET['controller']) === false )
{
	$_GET['controller'] = 'default';
}

$pengin =& Pengin::getInstance();
$pengin->main('admin');

require_once XOOPS_ROOT_PATH.'/footer.php';
