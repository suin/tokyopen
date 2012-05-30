<?php

class SiteNavi_Controller_AdminPageNew extends SiteNavi_Abstract_ThreeStepAjaxFormController
{
	protected $useModels = array('Route');

	protected $parentRoute = null;
	protected $routeModel = null;

	protected function _setUp()
	{
		parent::_setUp();
		$this->_setUpParentRoute();
	}

	protected function _setUpParentRoute()
	{
		$parentId    = $this->get('parent_id');
		$parentRoute = $this->routeHandler->load($parentId);

		if ( $parentRoute === false or $parentRoute->isNew() === true ) {
			$this->_error("Invalid parent ID.");
		}

		$this->parentRoute = $parentRoute;
	}

	protected function _getModelHandler()
	{
		return $this->routeHandler;
	}

	protected function _getForm()
	{
		return new SiteNavi_Form_AdminPageNew();
	}

	/**
	 * データを更新する.
	 * 
	 * @access protected
	 * @abstract
	 * @return void
	 */
	protected function _updateData()
	{
		$data = $this->form->getInput();
		$data = $this->_updateDataCilent($data);
		$data = $this->_updateDataRoute($data);
	}

	protected function _updateDataCilent(array $data)
	{
		$mediator = new SiteNavi_Library_AdhocMediator();
		$modules = $mediator->getSupportModules();
		$class = $modules[$data['type']];

		$api = new $class();
		$data = $api->create($data);

		if ( $data === false ) {
			throw new RuntimeException(t("Failed to save."));
		}

		// モジュールID取得
		$moduleHandler = xoops_gethandler('module');
		$moduleObject  = $moduleHandler->getByDirname($data['type']);

		if ( is_object($moduleObject) === false ) {
			throw new RuntimeException(t("Failed to save."));
		}

		$data['module_id'] = $moduleObject->get('mid');

		return $data;
	}

	protected function _updateDataRoute(array $data)
	{
		$this->model->setVars($data);
		$this->model->set('type', SiteNavi_Model_Route::TYPE_PAGE);

		if ( $this->modelHandler->createRoute($this->model, $this->parentRoute->get('id')) === false ) {
			throw new Exception(t("Failed to save."));
		}

		return $data;
	}
}
