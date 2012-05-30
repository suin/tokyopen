<?php

abstract class TPInstaller_Abstract_Controller
{
	protected $wizard = null;

	public function __construct()
	{
	}

	public function setWizard(SimpleWizard $wizard)
	{
		$this->wizard = $wizard;
	}

	public function setUp()
	{
		// Template method
	}

	protected function _loadDefinitionCustom()
	{
		if ( file_exists(_TP_DEFINITION_CUSTOM_FILE) === true ) {
			require_once _TP_DEFINITION_CUSTOM_FILE;
		}
	}

	abstract public function run();
}
