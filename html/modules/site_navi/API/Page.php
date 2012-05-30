<?php
/*
 * 外部モジュール用API
 */
class SiteNavi_API_Page
{
	/**
	 * ページタイトルを更新する
	 * @param string $contentId
	 * @param string $title
	 * @return bool 成功したとき TRUE 失敗したとき FALSE
	 */
	public function updateTitle($contentId, $title)
	{
		$pengin = Pengin::getInstance();
		$routeHandler = $pengin->getModelHandler('Route', 'site_navi');
		return $routeHandler->updateTitleByContentId($contentId, $title);
	}

	/**
	 * モジュールタイプのページタイトルを更新する
	 * @param integer $moduleId
	 * @param string $title
	 * @return bool 成功したとき TRUE 失敗したとき FALSE
	 */
	public function updateModuleTitle($moduleId, $title)
	{
		$contentId = sprintf(SiteNavi_Model_RouteHandler::MODULE_TYPE_CONTENT_ID_FORMAT, $moduleId);
		return $this->updateTitle($contentId, $title);
	}

	/**
	 * モジュールタイプのページを削除する (非推奨：このメソッドはLegacyモジュールだけのために存在します。)
	 * @deprecated
	 * @param string $contentId
	 * @return bool 成功したとき TRUE 失敗したとき FALSE
	 */
	public function deleteModule($moduleId)
	{
		$contentId = sprintf(SiteNavi_Model_RouteHandler::MODULE_TYPE_CONTENT_ID_FORMAT, $moduleId);
		$pengin = Pengin::getInstance();
		$routeHandler = $pengin->getModelHandler('Route', 'site_navi');
		return $routeHandler->deleteByContentId($contentId);
	}
}
