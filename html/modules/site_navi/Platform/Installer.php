<?php

if ( !defined('PENGIN_PATH') ) die;

$dirname = basename(dirname(dirname(__FILE__)));

require_once PENGIN_PATH.'/Pengin.php';

$pengin =& Pengin::getInstance();
$pengin->path(TP_MODULE_PATH.'/'.$dirname);
$installer = new SiteNavi_Platform_TP_Installer();
$installer->prepareAPI($dirname);
