<?php
abstract class Flipr_Abstract_Controller extends Pengin_Controller_Abstract
{
	protected $configs = array();
	protected $role = null;

	public function __construct()
	{
		parent::__construct();
		$this->_loadConfigs();
		$this->_assignConfigs();
		$this->_setRole();
		$this->_checkPermission();
		$this->_assignUserRoles();
		$this->_addAdminTaskBar();
	}

	public function main()
	{
	}

	protected function _loadConfigs()
	{
		$configHandler =& $this->root->getModelHandler('Config');
		$this->configs = $configHandler->getConfigs();
	}

	protected function _assignConfigs()
	{
		$this->output['configs'] =& $this->configs;
	}
	
	protected function _setRole()
	{
		$this->role = new Flipr_Library_Role;
	}

	protected function _assignUserRoles()
	{
		$this->output['user_roles'] = $this->role->getUserRoles();
	}

	protected function _checkPermission()
	{
		if ( $this->role->isInRole($this->controller) === false )
		{
			$this->root->redirect("Sorry, you don't have the permission to access this area.");
		}
	}

	protected function _addAdminTaskBar()
	{
		if ( $this->role->isInRole('photo_new') === true )
		{
			$this->_useAdminTaskBar('AlbumNew', "Upload Photos", $this->root->url('album_list', 'upload'));
		}
	}

	protected function _useAdminTaskBar($name, $title, $url)
	{
		$root = XCube_Root::getSingleton();
		if ( isset($root->mAdminTaskBar) === false )
		{
			return;
		}

		$root->mAdminTaskBar->addLink('FliprAdmin',  t('Flipr') , '' , 1);
		$root->mAdminTaskBar->addSubLink('FliprAdmin','tpNoModal'.$name, t($title), $url);
		$root->mAdminTaskBar->addStyleSheet($this->url.'/public/css/admin_task_bar.css');
	}

	protected function _validateUploadDirectory()
	{
		$updir = $this->root->cms->uploadPath.DS.$this->root->context->dirname; // TODO >> config

		if ( file_exists($updir) === false )
		{
			if ( @mkdir($updir, 0777) === false or @chmod($updir, 0777) === false )
			{
				trigger_error("Directory not exists and directory creation failed: $updir", E_USER_WARNING);
				$this->_addError("Upload directory doesn't exist.", true);
			}
		}

		if ( is_dir($updir) === false )
		{
			trigger_error("Not a directory: $updir", E_USER_WARNING);
			$this->_addError("Upload directory doesn't exist.", true);
		}

		if ( is_writable($updir) === false )
		{
			trigger_error("Not writable: $updir", E_USER_WARNING);
			$this->_addError("Upload directory doesn't exist.", true);
		}
	}
}
