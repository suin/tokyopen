<?php
$dirname  = dirname(__FILE__);
$basename = basename($dirname);

$modversion['name']        = "Profile";
$modversion['version']     = 1.00;
$modversion['description'] = "";
$modversion['credits']     = 'RYUS Inc.';
$modversion['author']      = 'Hidehito NOZAWA aka Suin <http://ryus.co.jp/>';
$modversion['license']     = 'GNU GPL v2 see LISENCE';
$modversion['image']       = 'public/images/module_icon.png';
$modversion['nice_image']  = 'public/images/module_icon_square.png';
$modversion['dirname']     = $basename;

$modversion['cube_style'] = true;

$modversion['hasMain'] = 0;

$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

$modversion['hasSearch'] = 0;

$modversion['hasNotification'] = 0;

$modversion['hasComments'] = 0;


$modversion['onInstall']   = 'PlatForm/Installer.php';
$modversion['onUpdate']    = 'PlatForm/Installer.php';
$modversion['onUninstall'] = 'PlatForm/Installer.php';
