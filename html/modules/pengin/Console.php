<?php
class Pengin_Console
{
	public static function prepare($dirname)
	{
		define('PENGIN_CONSOLE', true);

		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
		$_SERVER['SERVER_NAME'] = 'localhost';
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_SESSION['xoopsUserId'] = 1;
		$_SESSION['xoopsUserGroups'] = array(1,2,3);

		define('_LEGACY_PREVENT_EXEC_COMMON_', true);

		require(dirname(__FILE__).'/../../../html/mainfile.php');
		$_SERVER['REQUEST_URI'] = XOOPS_URL.'/modules/'.$dirname.'/index.php';
		$_SERVER['SCRIPT_NAME'] = $_SERVER['REQUEST_URI'];
		$_SERVER['PHP_SELF'] = $_SERVER['REQUEST_URI'];

		$root=&XCube_Root::getSingleton();
		$xoopsController=&$root->getController();
		$xoopsController->_setupFilterChain();
		$xoopsController->_processFilter();
		$xoopsController->_setupErrorHandler();
		$xoopsController->_setupEnvironment();
		$xoopsController->_setupLogger();
		$xoopsController->_setupDB();
		$xoopsController->_setupLanguage();
		$xoopsController->_setupTextFilter();
		$xoopsController->_setupConfig();
		$xoopsController->_setupDebugger();
		$xoopsController->_processPreBlockFilter();
		// $xoopsController->_setupSession();
		$xoopsController->_setupUser();
		$xoopsController->setupModuleContext();
		$xoopsController->_processModule();
		$xoopsController->_processPostFilter();
		
		set_time_limit(0);
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', true);
	}

	public static function getArgs()
	{
		global $argv;
		return $argv;
	}

	public static function getArgc()
	{
		global $argc;
		return $argc;
	}
}

?>
