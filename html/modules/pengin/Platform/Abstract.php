<?php
abstract class Pengin_Platform_Abstract
{
	public $name = '';

	public $rootPath   = null;
	public $modulePath = null;
	public $uploadPath = null;
	public $trustPath  = null;
	public $cachePath  = null;

	public $url       = null;
	public $moduleUrl = null;
	public $uploadUrl = null;

	public $charset  = null;
	public $langcode = null;

	public $moduleName    = null;
	public $moduleDirname = null;

	public function __construct()
	{
	}

	public function getThisModuleName()
	{
		return '';
	}

	public function getThisModuleDirname()
	{
		return '';
	}

	public function getThisModulePath()
	{
		return '';
	}

	public function getThisModuleUrl()
	{
		return '';
	}

	public function getSmarty()
	{
		return null;
	}

	public function &getConfig()
	{
		return array();
	}

	public function getModuleConfig($dirname = null, $name = null)
	{
		return null;
	}

	public function setTemplate($template)
	{
	}

	public function redirect($url, $time, $msg)
	{
	}

	public function error($msg)
	{
	}

	public function &database()
	{
		return null;
	}

	public function addHeader($head)
	{
	}

	public function addStyleSheet($url)
	{
	}

	public function addJavaScript($url)
	{
	}

	public function setPageTitle($title)
	{
	}

	public function setBreadCrumbs($breadcrumbs)
	{
	}

	public function isGuest()
	{
		return true;
	}

	public function isUser()
	{
		return true;
	}

	public function isAdmin($moduleId = null)
	{
		return true;
	}

	public function isInGroup($groupId)
	{
		return true;
	}

	public function getUserName($uid = null)
	{
		return '';
	}

	public function getUserId()
	{
		return 0;
	}

	public function getInstaller()
	{
		return null;
	}
	
	public function isMobile()
	{
		return false;
	}
}
