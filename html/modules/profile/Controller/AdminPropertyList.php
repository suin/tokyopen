<?php
class Profile_Controller_AdminPropertyList extends Profile_Abstract_AdminController
{
	protected $useModels = array('Property');

	public function __construct()
	{
		parent::__construct();
		$this->pageTitle = t("Manage properties");
	}

	protected function _defaultAction()
	{
		$this->output['properties'] = $this->propertyHandler->find();
		$this->_view();
	}
}
