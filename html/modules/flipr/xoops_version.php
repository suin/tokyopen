<?php
$dirname  = dirname(__FILE__);
$basename = basename($dirname);

$modversion['name']        = "Flipr";
$modversion['version']     = 1.00;
$modversion['description'] = "This is a photo album module.";
$modversion['credits']     = 'RYUS Inc.';
$modversion['author']      = 'Hidehito NOZAWA aka Suin <http://ryus.co.jp/>';
$modversion['help']	       = 'Readme/japanese.html';
$modversion['license']     = 'GNU GPL v2 see LISENCE';
$modversion['image']       = 'public/images/module_icon.png';
$modversion['nice_image']  = 'public/images/module_icon_square.png';
$modversion['dirname']     = $basename;

$modversion['cube_style'] = true;

$modversion['hasMain'] = 1;

$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

$modversion['hasSearch'] = 0;

$modversion['hasNotification'] = 0;

$modversion['hasComments'] = 0;

$modversion['blocks'][] = array(
	'file'      => 'block.php',
	'name'      => "Slide Show",
	'show_func' => $basename.'_block_show',
	'edit_func' => $basename.'_block_edit',
	'options'   => 'slide_show|1',
	'func_num'  => 1,
);

$modversion['onInstall']   = 'Platform/Installer.php';
$modversion['onUpdate']    = 'Platform/Installer.php';
$modversion['onUninstall'] = 'Platform/Installer.php';
