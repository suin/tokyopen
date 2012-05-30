<?php
$dirname  = dirname(__FILE__);
$basename = basename($dirname);

$modversion['name']        = "AddonManager";
$modversion['version']     = 1.00;
$modversion['description'] = "";
$modversion['credits']     = 'RYUS Inc.';
$modversion['author']      = 'Hidehito NOZAWA aka Suin <http://ryus.co.jp/>';
$modversion['help']	       = 'Readme/japanese.html';
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

$modversion['config'][] = array(
    'name'        => 'ftp_user',
    'title'       => 'FTP UserName',
    'description' => '',
    'formtype'    => 'text',
    'valuetype'   => 'string',
    'default'     => '',
    'options'     => array(),
);
$modversion['config'][] = array(
    'name'        => 'ftp_pass',
    'title'       => 'FTP password',
    'description' => '',
    'formtype'    => 'text',
    'valuetype'   => 'string',
    'default'     => '',
    'options'     => array(),
);

$modversion['config'][] = array(
    'name'        => 'addon_download_url_format',
    'title'       => 'Addon download Url format',
    'description' => '',
    'formtype'    => 'text',
    'valuetype'   => 'string',
    'default'     => 'http://tokyopen.jp/files/%s.zip',
    'options'     => array(),
);

$modversion['config'][] = array(
    'name'        => 'theme_download_url_format',
    'title'       => 'Theme download Url format',
    'description' => '',
    'formtype'    => 'text',
    'valuetype'   => 'string',
    'default'     => 'http://cmsthemefinder.com/modules/lica/index.php?controller=download&id=%u',
    'options'     => array(),
);

$modversion['config'][] = array(
    'name'        => 'addon_store_url',
    'title'       => 'addon store url',
    'description' => '',
    'formtype'    => 'text',
    'valuetype'   => 'string',
    'default'     => 'http://tokyopen.jp/addonstore/',
    'options'     => array(),
);


$modversion['onInstall']   = 'PlatForm/Installer.php';
$modversion['onUpdate']    = 'PlatForm/Installer.php';
$modversion['onUninstall'] = 'PlatForm/Installer.php';
