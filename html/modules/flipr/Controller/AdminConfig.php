<?php
class Flipr_Controller_AdminConfig extends Flipr_Abstract_Controller
{
	protected $configHandler = null;
	protected $configModels  = null;

	protected $input  = array();
	protected $form   = array();
	protected $errors = array();

	public function __construct()
	{
		parent::__construct();

		$this->configHandler =& $this->root->getModelHandler('Config');
		$this->configModels  = $this->configHandler->loadConfigs();

		$this->_initializeInput();
		$this->_initializeForm();

		$this->output['input'] =& $this->input;
		$this->output['form']  =& $this->form;

		$this->pageTitle = "Preference";
	}

	public function main()
	{
		try
		{
			if ( $this->post('save') )
			{
				$this->_save();
			}
		}
		catch ( Exception $e )
		{
		}

		$this->_default();
	}

	protected function _default()
	{
		$this->_view();
	}

	protected function _save()
	{
		$this->_fetchInput();
		$this->_validate();

		foreach ( $this->configModels as $configModel )
		{
			$name = $configModel->getVar('name');

			$configModel->setVar('value', $this->input[$name]);

			$isSuccess = $this->configHandler->save($configModel);

			if ( !$isSuccess )
			{

			}
		}

		$this->root->redirect("Successaly update preference.", 'config');
	}

	protected function _initializeInput()
	{
		foreach ( $this->configModels as $configModel )
		{
			$name = $configModel->getVar('name');

			$this->input[$name] = $configModel->getVar('value');
		}
	}

	protected function _initializeForm()
	{
		foreach ( $this->configModels as $configModel )
		{
			$name = $configModel->getVar('name');

			$this->form[$name] = array(
				'name'    => $name,
				'title'   => $configModel->data['title'],
				'desc'    => $configModel->data['desc'],
				'type'    => $configModel->data['form_type'],
				'options' => $configModel->data['options'],
			);
		}
	}

	protected function _fetchInput()
	{
		foreach ( $this->configModels as $configModel )
		{
			$name = $configModel->getVar('name');

			$this->input[$name] = $this->post($name);
		}
	}

	protected function _validate()
	{
	}
}
