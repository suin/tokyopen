<?php
abstract class Nano_Abstract_Controller extends Pengin_Controller_Abstract
{
	protected $configs = array();

	public function __construct()
	{
		parent::__construct();
		$this->moduleName = $this->root->cms->getThisModuleName();
		$this->output['module_name'] = $this->moduleName;
	}

	public function main()
	{
	}
}
