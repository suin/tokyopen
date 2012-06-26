<?php

require '../../mainfile.php';
require_once PENGIN_PATH.'/Pengin.php';

require_once XOOPS_ROOT_PATH.'/header.php';

if ( isset($_GET['controller']) === false )
{
	$_GET['controller'] = 'form';
}

$pengin =& Pengin::getInstance();
$pengin->main();

require_once XOOPS_ROOT_PATH.'/footer.php';
