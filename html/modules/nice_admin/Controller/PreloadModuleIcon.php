<?php
class NiceAdmin_Controller_PreloadModuleIcon extends Pengin_Controller_AbstractPreload
{
	protected $moduleModels = array();

	public function __construct()
	{
		parent::__construct();
	}

	public function main()
	{	
		$this->_default();
	}

	protected function _default()
	{
		$this->_loadModulesModels();
		$this->_makeIconList();
		$this->_view();
	}

	protected function _loadModulesModels()
	{
		$moduleHandler = $this->root->getModelHandler('Module');
		$isSiteOwner = $this->_isSiteOwner();
		$userGroups  = $this->_getGroups();
		$this->moduleModels = $moduleHandler->findHasAdminModules($isSiteOwner, $userGroups);
	}

	protected function _makeIconList()
	{
		$modules = array();

		$defaultIcon = $this->url.'/public/images/default_nice_icon.png';

		foreach ( $this->moduleModels as $moduleModel )
		{
            $dirname    = $moduleModel->get('dirname');
			$moduleName = $moduleModel->get('name');
			$moduleUrl  = XOOPS_URL.'/modules/'.$dirname.'/admin/';

			$iconUrl = $moduleModel->getNiceIconUrl();

			$modules[] = array(
				'name' => $moduleName,
				'icon' => $iconUrl,
				'url'  => $moduleUrl,
			);
		}

		$this->output['modules'] = $modules;
	}

	protected function _getGroups()
	{
		$root =& XCube_Root::getSingleton();
		$userModel =& $root->mController->mRoot->mContext->mXoopsUser;
		return $userModel->getGroups();
	}

	protected function _isSiteOwner()
	{
		$root =& XCube_Root::getSingleton();
		return $root->mController->mRoot->mContext->mUser->isInRole('Site.Owner');
	}
}

