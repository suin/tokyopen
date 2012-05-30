<?php

if ( !defined('PENGIN_PATH') ) die;

$dirname = basename(dirname(dirname(__FILE__)));

require_once PENGIN_PATH.'/Pengin.php';

$pengin =& Pengin::getInstance();
$installer = $pengin->cms->getInstaller();
$installer->prepareAPI($dirname);
