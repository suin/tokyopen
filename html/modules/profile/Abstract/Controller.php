<?php
abstract class Profile_Abstract_Controller extends Pengin_Controller_Abstract
{
	protected $configs = array();

	public function __construct()
	{
		parent::__construct();
//		$this->_loadConfigs();
//		$this->_assignConfigs();

		$this->moduleName = $this->root->cms->getThisModuleName();
		$this->output['module_name'] = $this->moduleName;
	}

	public function main()
	{
	}

	protected function _loadConfigs()
	{
		$configHandler = $this->root->getModelHandler('Config');
		$this->configs = $configHandler->getConfigs();
	}

	protected function _assignConfigs()
	{
		$this->output['configs'] =& $this->configs;
	}
}
