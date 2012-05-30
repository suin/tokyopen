<?php
/**
 *
 * @package Legacy
 * @version $Id: definition.inc.php,v 1.3 2008/09/25 15:12:47 kilica Exp $
 * @copyright Copyright 2005-2007 XOOPS Cube Project  <http://xoopscube.sourceforge.net/> 
 * @license http://xoopscube.sourceforge.net/license/GPL_V2.txt GNU GENERAL PUBLIC LICENSE Version 2
 *
 */

// URLs
define('TP_URL', XOOPS_URL); // TODO >> remove
define('TP_MODULE_URL', TP_URL.'/modules');
define('TP_UPLOAD_URL', TP_URL.'/uploads');
define('TP_THEME_URL',  TP_URL.'/themes');
define('TP_JS_URL',     TP_URL.'/js');

// Paths in Public
define('TP_ROOT_PATH', XOOPS_ROOT_PATH); // TODO >> remove
define('TP_MODULE_PATH', TP_ROOT_PATH.'/modules');
define('TP_UPLAOD_PATH', TP_ROOT_PATH.'/uploads');
define('TP_THEME_PATH',  TP_ROOT_PATH.'/themes');
define('TP_JS_PATH',     TP_ROOT_PATH.'/js');

// Paths in Trust
define('TP_TRUST_PATH', XOOPS_TRUST_PATH); // TODO >> remove
define('TP_TRUST_MODULE_PATH', TP_TRUST_PATH.'/modules');
define('TP_RYUS_PATH',         TP_TRUST_PATH.'/Ryus');
define('TP_VENDER_PATH',       TP_TRUST_PATH.'/vendor');
define('TP_LIBRARY_PATH',      TP_TRUST_PATH.'/libs');

// File Paths in Trust
define('TP_FILE_PATH', TP_TRUST_PATH.'/file/'.XOOPS_SALT);
define('TP_TRSUT_UPLAOD_PATH',     TP_FILE_PATH.'/uploads');
define('TP_CACHE_PATH',            TP_FILE_PATH.'/cache');
define('TP_LOG_PATH',              TP_FILE_PATH.'/log');
define('TP_SESSION_PATH',          TP_FILE_PATH.'/session');
define('TP_TEMPORARY_PATH',        TP_FILE_PATH.'/tmp');
define('TP_TEMPLATE_COMPILE_PATH', TP_FILE_PATH.'/templates_c');

// JavaScripts and jQuery
define('TP_JS_VENDOR_PATH', TP_JS_PATH.'/vendor');
define('TP_JS_VENDOR_URL', TP_JS_URL.'/vendor');
define('TP_JQUERY_URL', TP_JS_VENDOR_URL.'/jquery-1.6.4.min.js');
define('TP_JQUERY_UI_URL', TP_JS_VENDOR_URL.'/jquery-ui-1.8.16/js/jquery-ui-1.8.16.custom.min.js');
define('TP_JQUERY_UI_THEME_URL', TP_JS_VENDOR_URL.'/jquery-ui-1.8.16/css/smoothness/jquery-ui-1.8.16.custom.css');

// Addon Store
define('TP_ADDON_STORE_DOWNLOAD_URL_FORMAT', 'http://tokyopen.jp/addonstore/download.php?key=%s');

// Smarty
define('SMARTY_DIR', TP_VENDER_PATH.'/smarty/');
define('TP_SMARTY_CLASS_PATH', SMARTY_DIR.'/Smarty.class.php');
define('TP_SMARTY_PLUGIN_PATH', SMARTY_DIR.'/plugins');
define('TP_SMARTY_VENDOR_PLUGIN_PATH', TP_LIBRARY_PATH.'/smartyplugins');

// PHPMailer
define('TP_PHPMAILER_PATH', TP_VENDER_PATH.'/phpmailer');
define('TP_PHPMAILER_CLASS_PATH', TP_PHPMAILER_PATH.'/class.phpmailer.php');

// Snoopy
define('TP_SNOOPY_PATH', TP_VENDER_PATH.'/snoopy');
define('TP_SNOOPY_CLASS_PATH', TP_SNOOPY_PATH.'/snoopy.php');

//
// Following are the constants for compatibility.
//

// Enum
define("XOOPS_SIDEBLOCK_LEFT",0);
define("XOOPS_SIDEBLOCK_RIGHT",1);
define("XOOPS_SIDEBLOCK_BOTH",2);
define("XOOPS_CENTERBLOCK_LEFT",3);
define("XOOPS_CENTERBLOCK_RIGHT",4);
define("XOOPS_CENTERBLOCK_CENTER",5);
define("XOOPS_CENTERBLOCK_ALL",6);
define("XOOPS_BLOCK_INVISIBLE",0);
define("XOOPS_BLOCK_VISIBLE",1);

define("XOOPS_MATCH_START",0);
define("XOOPS_MATCH_END",1);
define("XOOPS_MATCH_EQUAL",2);
define("XOOPS_MATCH_CONTAIN",3);

// Smarty
define("XOOPS_COMPILE_PATH", TP_TEMPLATE_COMPILE_PATH);

// Path
define("XOOPS_CACHE_PATH", TP_CACHE_PATH);
define("XOOPS_MODULE_PATH", TP_MODULE_PATH);
define("XOOPS_UPLOAD_PATH", TP_UPLAOD_PATH);
define("XOOPS_THEME_PATH", TP_THEME_PATH);

// URL
define("XOOPS_MODULE_URL", TP_MODULE_URL);
define("XOOPS_UPLOAD_URL", TP_UPLOAD_URL);
define("XOOPS_THEME_URL", TP_THEME_URL);

define("XOOPS_LEGACY_PROC_NAME", "legacy");

// USER
define("XCUBE_CORE_USER_MODULE_NAME","user");
define("XCUBE_CORE_USER_UTILS_CLASS","UserAccountUtils");	// not use


define("XCUBE_CORE_PM_MODULE_NAME","pm");

define('LEGACY_SYSTEM_COMMENT', 14);

//
// A name of the render-system used by the embedded template of XoopsForm.
//
define('XOOPSFORM_DEPENDENCE_RENDER_SYSTEM', 'Legacy_RenderSystem');
