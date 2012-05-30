<?php

class SiteNavi_Controller_AdminModuleDelete extends SiteNavi_Abstract_DeleteAjaxFormController
{
	protected $useModels = array('Route');

	public function __construct()
	{
		parent::__construct();
		$this->pageTitle = t("Unplace Module");
	}

	protected function _getModelHandler()
	{
		return $this->routeHandler;
	}

	protected function _getReturnUri()
	{
		return $this->root->url();
	}

	/**
	 * 出力用の変数をバインドする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _bindOutput()
	{
		parent::_bindOutput();
		$this->output['extraMessage'] = t("This module will be unplaced from sitemap.");
	}

	/**
	 * モデルセットをセットアップする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _setUpModel()
	{
		$this->id           = $this->get($this->idKey, null);
		$this->modelHandler = $this->_getModelHandler();
		$this->model        = $this->modelHandler->load($this->id);

		if ( is_object($this->model) === false or $this->model->isNew() === true ) {
			$this->_error("Page not found.");
		}

		if ( $this->model->get('type') != SiteNavi_Model_Route::TYPE_MODULE ) {
			$this->_error("Page not found.");
		}
	}

	/**
	 * データを削除する.
	 * 
	 * @access protected
	 * @abstract
	 * @return void
	 */
	protected function _deleteData()
	{
		$success = $this->routeHandler->deleteChildren($this->model);

		if ( $success == false ) {
			throw new RuntimeException("Failed to delete.");
		}
	}
}
