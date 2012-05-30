<?php
abstract class AddonManager_Abstract_Controller extends Pengin_Controller_Abstract
{
	protected $configs = array();

	public function __construct()
	{
		parent::__construct();
		$this->configs = $this->root->cms->getModuleConfig();
	}
	
	protected function _getConfig($key)
	{
        return $this->configs[$key];
	}
}
