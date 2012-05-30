<?php

define('TP_INSTALL_DIR', dirname(__FILE__));
define('TP_INSTALLER_DIR', TP_INSTALL_DIR.'/TPInstaller');
define('TP_CUSTOM_DIR', TP_INSTALL_DIR.'/custom');
define('_TP_ROOT_PATH', dirname(TP_INSTALL_DIR));
define('_TP_SETTING_PATH', _TP_ROOT_PATH.'/settings');
define('_TP_DEFINITION_CUSTOM_FILE', _TP_SETTING_PATH.'/definition.custom.php');
define('_TP_DEFINITION_CUSTOM_DIST_FILE', _TP_SETTING_PATH.'/definition.custom.dist.php');
define('_TP_DEFINITION_INC_FILE', _TP_SETTING_PATH.'/definition.inc.php');

require_once TP_INSTALLER_DIR.'/Core/ClassLoader.php';

$classLoader = new TPInstaller_Core_ClassLoader();
$classLoader->setIncludePath(TP_INSTALL_DIR);
$classLoader->register();
