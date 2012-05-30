<?php
class Pengin_Platform_Xoops20 extends Pengin_Platform_Abstract
{
	public function __construct()
	{
		$this->rootPath   = XOOPS_ROOT_PATH;
		$this->modulePath = $this->rootPath.'/modules';
		$this->uploadPath = $this->rootPath.'/uploads';

		$this->url       = XOOPS_URL;
		$this->moduleUrl = $this->url.'/modules';
		$this->uploadUrl = $this->url.'/uploads';

		if ( defined('XOOPS_TRUST_PATH') )
		{
			$this->trustPath = XOOPS_TRUST_PATH;
		}

		if ( $this->trustPath === null )
		{
			$this->cachePath = $this->rootPath.'/cache';
		}
		else
		{
			$cachePath = $this->trustPath.'/cache';

			if ( file_exists($cachePath) )
			{
				$this->cachePath = $cachePath;
			}
		}

		$this->charset  = _CHARSET;
		$this->langcode = _LANGCODE;
	}

	public function getThisModuleName()
	{
		global $xoopsModule;

		if ( is_object($xoopsModule) )
		{
			return $xoopsModule->getVar('name');
		}

		return false;
	}

	public function getThisModuleDirname()
	{
		global $xoopsModule;

		if ( is_object($xoopsModule) )
		{
			return $xoopsModule->getVar('dirname');
		}

		return false;
	}

	public function getThisModuleId()
	{
		global $xoopsModule;

		if ( is_object($xoopsModule) )
		{
			return $xoopsModule->getVar('mid');
		}

		return false;
	}

	public function getThisModulePath()
	{
		$dirname = $this->getThisModuleDirname();

		if ( $dirname === false )
		{
			return false;
		}

		$path    = $this->modulePath.DS.$dirname;
		return $path;
	}

	public function getThisModuleUrl()
	{
		$dirname = $this->getThisModuleDirname();

		if ( $dirname === false )
		{
			return false;
		}

		$url     = $this->moduleUrl.'/'.$dirname;
		return $url;
	}

	public function getSmarty()
	{
		global $xoopsTpl;
		return $xoopsTpl;
	}

	public function &getConfig()
	{
		global $xoopsConfig;
		return $xoopsConfig;
	}

	public function getModuleConfig($dirname = null, $name = null)
	{
		if ( $dirname === null )
		{
			global $xoopsModuleConfig;
			$config = $xoopsModuleConfig;
		}
		else
		{
			$configHandler = xoops_gethandler('config');
			$config = $configHandler->getConfigsByDirname($dirname);
		}

		if ( $name === null )
		{
			return $config;
		}

		if ( !isset($config[$name]) )
		{
			return false;
		}

		return $config[$name];
	}

	public function setTemplate($template)
	{
		global $xoopsOption;
		$xoopsOption['template_main'] =& $template;
	}

	public function redirect($url, $time, $msg)
	{
		redirect_header($url, $time, $msg);
	}

	public function error($msg)
	{
		xoops_error($msg);
		exit();
	}

	public function &database()
	{
		static $db;

		if ( $db == null )
		{
			$db =& database::getInstance(); 
		}

		return $db;
	}

	public function addHeader($head)
	{
		global $xoopsTpl;
		$xoopsModuleHeader = $xoopsTpl->get_template_vars('xoops_module_header');
		$xoopsTpl->assign('xoops_module_header', $xoopsModuleHeader."\n".$head);
	}

	public function addStyleSheet($url)
	{
		$css = '<link rel="stylesheet" type="text/css" media="screen" href="'.$url.'" />';
		$this->addHeader($css);
	}

	public function addJavaScript($url)
	{
		$js = '<script type="text/javascript" src="'.$url.'"></script>';
		$this->addHeader($js);
	}

	public function setPageTitle($title)
	{
		global $xoopsTpl;
		$xoopsTpl->assign('xoops_pagetitle', $title);
	}

	public function setBreadCrumbs($breadcrumbs)
	{
		global $xoopsTpl;
		$xoopsTpl->assign('xoops_breadcrumbs', $breadcrumbs);

	}

	public function isGuest()
	{
		global $xoopsUser;
		return ( !is_object($xoopsUser) );
	}

	public function isUser()
	{
		global $xoopsUser;
		return is_object($xoopsUser);
	}

	public function isAdmin($moduleId = null)
	{
		if ( !$this->isUser() )
		{
			return false;
		}

		global $xoopsUser;
		return $xoopsUser->isAdmin($moduleId);
	}

	public function isInGroup($groupId)
	{
		if ( !$this->isUser() )
		{
			return false;
		}

		global $xoopsUser;

		$groups = $xoopsUser->getGroups();
		return in_array($groupId, $groups);
	}

	public function getUserId()
	{
		if ( $this->isUser() )
		{
			global $xoopsUser;
			return $xoopsUser->getVar('uid');
		}

		return 0;
	}

	public function getUserName($uid = null)
	{
		if ( $uid === null )
		{
			if ( $this->isUser() )
			{
				global $xoopsUser;
				return $xoopsUser->getVar('uname');
			}

			return '';
		}

		$memberHandler =& xoops_gethandler('member');
		$userObject    =& $memberHandler->getUser($uid);
		$userName      = $userObject->getVar('uname');

		return $userName;
	}

	public function getInstaller()
	{
		$class = 'Pengin_Platform_'.$this->name.'_Installer';
		$installer = new $class;
		return $installer;
	}
	
	public function isMobile()
	{
		if ( class_exists('Wizin_User') )
		{
			$wizinUser =& Wizin_User::getSingleton();
			
			if ( $wizinUser->bIsMobile )
			{
				return true;
			}
		}
	
		return false;
	}
}
