<?php

abstract class Mailform_Abstract_Controller extends Pengin_Controller_Abstract
{
	public function __construct()
	{
		parent::__construct();
		$this->moduleName = $this->root->cms->getThisModuleName();
		$this->output['module_name'] = $this->moduleName;
	}

	public function main()
	{
		$actionMethod = $this->Action.'Action';

		if ( method_exists($this, $actionMethod) === false )
		{
			$this->root->redirect(t("Page not found."), $this->root->cms->url);
		}

		$this->$actionMethod();
	}
}
