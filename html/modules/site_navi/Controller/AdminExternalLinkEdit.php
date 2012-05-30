<?php

class SiteNavi_Controller_AdminExternalLinkEdit extends SiteNavi_Abstract_ThreeStepAjaxFormController
{
	protected $useModels = array('Route', 'External');

	protected $parentRoute = null;
	protected $routeModel = null;

	protected function _setUp()
	{
		parent::_setUp();
		$this->_setUpParentRoute();
	}

	protected function _setUpParentRoute()
	{
		$parentId = $this->get('parent_id');
		$parentRoute = $this->routeHandler->load($parentId);

		if ( $parentRoute === false or $parentRoute->isNew() === true ) {
			$this->_error("Invalid parent ID.");
		}
		
		$this->parentRoute = $parentRoute;
	}

	protected function _getModelHandler()
	{
		return $this->externalHandler;
	}

	protected function _getForm()
	{
		return new SiteNavi_Form_AdminExternalLinkEdit();
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
		parent::_updateData();

		$contentId = sprintf('/site_navi/external/%u/', $this->model->get('id'));
		$this->routeModel = $this->routeHandler->create();
		$this->routeModel->set('title', $this->model->get('title'));
		$this->routeModel->set('url', $this->model->get('url'));
		$this->routeModel->set('content_id', $contentId);
		$this->routeModel->set('module_id', $this->root->cms->getThisModuleId());
		$this->routeModel->set('type', SiteNavi_Model_Route::TYPE_EXTERNAL_LINK);

		if ( $this->routeHandler->createRoute($this->routeModel, $this->parentRoute->get('id')) === false ) {
			throw new Exception(t("Failed to save."));
		}
	}

	/**
	 * フォームのトランザクション終了時の処理用メソッド.
	 * 
	 * @access protected
	 * @return void
	 * @note データベースのトランザクションではない
	 */
	protected function _afterTransaction()
	{
		$this->result['html'] = t("Sucessfully updated.");
		$this->result['end']  = 1;
		$this->result['data'] = $this->routeModel->getVars();
	}
}
