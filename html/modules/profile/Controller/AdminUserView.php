<?php
class Profile_Controller_AdminUserView extends Profile_Abstract_AdminController
{
	protected $useModels = array('Property', 'Set', 'User');
	protected $setId = 1;
	protected $propertyModels = array();
	protected $userModel = null;

	public function __construct()
	{
		parent::__construct();

		$this->propertyModels = $this->propertyHandler->findBySetId($this->setId);
		$this->userModel      = $this->userHandler->load($this->get('user_id'));
		$this->setModel       = $this->setHandler->load($this->setId);

		if ( $this->userModel == false ) {
			$this->root->redirect("Not Found", 'user_list');
		}

		$this->pageTitle = t("{1}'s profile", $this->userModel->get('uname'));
	}

	protected function _defaultAction()
	{
		$propertyModels = $this->propertyModels;
		$propertyModels = $this->_removeNotDisplayProperties($propertyModels);

		$pluginManager = new Profile_Plugin_Manager();

		foreach ( $propertyModels as $propertyModel ) {
			$type = $propertyModel->get('type');
			$name = $propertyModel->get('name');
			$value = $this->userModel->get($name);
			$propertyModel->value = $pluginManager->call($type, 'render', $value);
		}

		$this->output['properties'] = $propertyModels;
		$this->output['user'] = $this->userModel;
		$this->output['set'] = $this->setModel;

		$this->_view();
	}

	/**
	 * 表示機能がないプラグインを除外する.
	 * 
	 * @access protected
	 * @param array $propertyModels
	 * @return array $propertyModels
	 */
	protected function _removeNotDisplayProperties(array $propertyModels)
	{
		$pluginManager = new Profile_Plugin_Manager();

		foreach ( $propertyModels as $key => $propertyModel ) {
			$hasRender = $pluginManager->hasRender($propertyModel->get('type'));

			if ( $hasRender === false ) {
				unset($propertyModels[$key]);
			}
		}

		return $propertyModels;
	}
}
