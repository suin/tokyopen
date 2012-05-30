<?php

abstract class SiteNavi_Abstract_Controller extends Pengin_Controller_Abstract
{
	protected $moduleName = '';

	public function __construct()
	{
		parent::__construct();
		$this->_setUp();
	}

	public function main()
	{
		$actionMethod = $this->Action.'Action';

		if ( method_exists($this, $actionMethod) === false )
		{
			$this->root->redirect(t("Page not found."), $this->root->cms->siteUrl);
		}

		$this->$actionMethod();
	}

	protected function _setUp()
	{
		$this->_setUpModuleName();
		$this->_setUpPageTitle();
	}

	protected function _setUpModuleName()
	{
		$this->moduleName = $this->root->cms->getThisModuleName();
		$this->output['module_name'] = $this->moduleName;
	}

	protected function _setUpPageTitle()
	{
		// nothing to do
	}
}
