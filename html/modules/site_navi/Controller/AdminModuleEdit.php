<?php

class SiteNavi_Controller_AdminModuleEdit extends SiteNavi_Abstract_ThreeStepAjaxFormController
{
	protected $useModels = array('Route');

	protected $parentRoute = null;
	protected $routeModel = null;

	protected function _setUp()
	{
		parent::_setUp();
		$this->_setUpParentRoute();
		$this->_checkAvailableModuleExists();
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

	protected function _checkAvailableModuleExists()
	{
		$moduleOptions = $this->form->getModuleIdOptions();

		if ( is_array($moduleOptions) === false or count($moduleOptions) === 0 ) {
			$this->_error(t("There are no available modules to place."));
		}
	}

	protected function _getModelHandler()
	{
		return $this->routeHandler;
	}

	protected function _getForm()
	{
		return new SiteNavi_Form_AdminModuleEdit();
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
		$data = $this->_updateDataRoute($data);
	}

	protected function _updateDataRoute(array $data)
	{
		$moduleHandler = xoops_gethandler('module');
		$moduleObject  = $moduleHandler->get($data['module_id']);

		$url   = sprintf('%s/modules/%s/', TP_URL, $moduleObject->get('dirname'));
		$title = $moduleObject->get('name');
		$contentId = sprintf(SiteNavi_Model_RouteHandler::MODULE_TYPE_CONTENT_ID_FORMAT, $data['module_id']);

		$this->model->setVars($data);
		$this->model->set('type', SiteNavi_Model_Route::TYPE_MODULE);
		$this->model->set('module_id', $this->root->cms->getThisModuleId());
		$this->model->set('content_id', $contentId);
		$this->model->set('url', $url);
		$this->model->set('title', $title);

		if ( $this->modelHandler->createRoute($this->model, $this->parentRoute->get('id')) === false ) {
			throw new Exception(t("Failed to save."));
		}

		return $data;
	}
}
