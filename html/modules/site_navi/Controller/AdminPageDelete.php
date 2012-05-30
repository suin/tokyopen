<?php

class SiteNavi_Controller_AdminPageDelete extends SiteNavi_Abstract_DeleteAjaxFormController
{
	protected $useModels = array('Route');

	public function __construct()
	{
		parent::__construct();
		$this->pageTitle = t("Delete Page");
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
		$this->output['extraMessage'] = t("This page and all child pages will be deleted.");
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

		if ( $this->model->get('type') != SiteNavi_Model_Route::TYPE_PAGE ) {
			$this->_error("Page not found.");
		}

		if ( $this->model->get('parent_id') == 0 ) {
			$this->_error("You cant delete top page.");
		}
	}

	protected function _beforeDeleteData()
	{
		$idPath = $this->model->get('id_path');
		$moduleContents = $this->routeHandler->getModuleContentsByIdPath($idPath);

		$siteNaviModuleId = $this->root->cms->getThisModuleId();

		// SiteNaviが直轄するコンテンツを削除する
		if ( array_key_exists($siteNaviModuleId, $moduleContents) === true ) {
			$siteNaviContents = $moduleContents[$siteNaviModuleId];
			$this->_deleteSiteNaviContents($siteNaviContents);
			unset($moduleContents[$siteNaviModuleId]);
		}

		// 管理権限があるかチェックする
		foreach ( $moduleContents as $moduleId => $contents ) {
			if ( $this->root->cms->isAdmin($moduleId) === false ) {
				throw new RuntimeException("Failed to delete. Permission denied: module ID: ".$moduleId);
			}
		}

		// 各モジュールに自分のコンテンツを削除させる
		$mediator = new SiteNavi_Library_AdhocMediator();

		foreach ( $moduleContents as $moduleId => $contentIds ) {
			$result = $mediator->deleteConents($moduleId, $contentIds);

			if ( $result === false ) {
				throw new RuntimeException("Failed to delete. Database error: module ID: ".$moduleId);
			}
		}
	}

	/**
	 * サイトナビが直轄するコンテンツを削除する
	 * @param array $siteNaviContents
	 * @return void
	 * @throws RuntimeException
	 */
	protected function _deleteSiteNaviContents(array $siteNaviContents)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('content_id', 'IN', $siteNaviContents);
		$result = $this->routeHandler->deleteAll($criteria);
		
		if ( $result === false ) {
			throw new RuntimeException('Failed to delete. Database error: site_navi');
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
