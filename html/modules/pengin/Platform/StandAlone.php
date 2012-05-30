<?php
class Pengin_Platform_StandAlone extends Pengin_Platform_Abstract
{
	public function __construct()
	{
	}

	public function getThisModuleName()
	{
		return $this->moduleName;
	}

	public function getThisModuleDirname()
	{
		return $this->moduleDirname;
	}

	public function getThisModulePath()
	{
		return $this->rootPath;
	}

	public function getThisModuleUrl()
	{
		return $this->url;
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

	public function getInstaller()
	{
		return null;
	}
}

?>
