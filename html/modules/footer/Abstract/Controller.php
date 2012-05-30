<?php

abstract class Footer_Abstract_Controller extends Pengin_Controller_Abstract
{
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
