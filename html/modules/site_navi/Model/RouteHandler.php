<?php
class SiteNavi_Model_RouteHandler extends Pengin_Model_AbstractHandler
{
	const TOP_ID = 1;
	const TOP_CONTENT_ID = '/site_navi/top/';
	const MODULE_TYPE_CONTENT_ID_PREFIX = '/site_navi/modules/';
	const MODULE_TYPE_CONTENT_ID_FORMAT = '/site_navi/modules/%u/'; // %u = mid

	/**
	 * @param int $id
	 * @return bool
	 */
	public function exists($id)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('id', $id);
		return ( $this->count($criteria) > 0 );
	}

	/**
	 * 親IDから子ノードを返す
	 * @param integer $parentId
	 * @return array
	 */
	public function findChildrenByParentId($parentId, $privateFlag = false, $invisibleInMenuFlag = false)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('parent_id', $parentId);
		if($privateFlag !== false){
			$criteria->add('private_flag', $privateFlag);
		}
		if($invisibleInMenuFlag !== false){
			$criteria->add('invisible_in_menu_flag', $invisibleInMenuFlag);
		}
		return $this->find($criteria, 'weight', 'ASC');
	}

	/**
	 * コンテンツIDでロードする
	 * @param string $contentId
	 * @return SiteNavi_Model_Route
	 */
	public function loadByContentId($contentId)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('content_id', $contentId);
		$model = $this->find($criteria, null, null, 1);
		
		if ( is_object($model) === false ) {
			return false;
		}

		return $model;
	}

	/**
	 * コンテンツIDでページタイトルを更新する
	 * @param string $contentId
	 * @param string $title
	 * @return bool
	 */
	public function updateTitleByContentId($contentId, $title)
	{
		$model = $this->loadByContentId($contentId);

		if ( $model === false ) {
			return false;
		}

		$model->set('title', $title);

		return $this->save($model);
	}

	/**
	 * コンテンツIDでページを削除する
	 * @param string $contentId
	 * @return bool
	 */
	public function deleteByContentId($contentId)
	{
		$model = $this->loadByContentId($contentId);

		if ( $model === false ) {
			return true; // 削除するものがないから true ってことでいいかな
		}

		if ( $this->delete($model->get('id')) === false ) {
			return false;
		}

		if ( $this->updateChildren($model->get('parent_id')) === false ) {
			return false;
		}
		
		return true;
	}

	/**
	 * モデルを配列に変換する
	 * @param array $models
	 * @return array
	 */
	public static function toArray(array $models)
	{
		foreach ( $models as &$model ) {
			$model = $model->getVars();
		}
		
		return $models;
	}

	/**
	 * ノードを作成する
	 * @param SiteNavi_Model_Route $model
	 * @param integer $parentId
	 * @return bool
	 */
	public function createRoute(SiteNavi_Model_Route $model, $parentId)
	{
		$parentModel = $this->load($parentId);
		
		if ( $parentModel === false ) {
			return false;
		}

		$weight = $parentModel->get('children') + 1;
		$weightPath = sprintf('%03d', $weight);

		$model->set('parent_id', $parentModel->get('id'));
		$model->set('id_path', $parentModel->get('id_path').$model->get('id').'/');
		$model->set('level', $parentModel->get('level') + 1);
		$model->set('weight', $weight);
		$model->set('weight_path', $parentModel->get('weight_path').$weightPath.'/');
		$model->set('children', 0);

		// IDを確定する
		if ( $this->save($model) === false ) {
			return false;
		}

		$model->set('id_path', $parentModel->get('id_path').$model->get('id').'/');

		if ( $this->save($model) === false ) {
			return false;
		}

		if ( $this->updateChildren($parentId) === false ) {
			return false;
		}

		return true;
	}

	/**
	 * 子ノード数を最計算して更新する
	 * @param integer $parentId
	 * @return bool
	 */
	public function updateChildren($parentId)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('parent_id', $parentId);
		$total = $this->count($criteria);
		
		$query = "UPDATE %s SET children = %u WHERE id = %u";
		$query = sprintf($query, $this->table, $total, $parentId);
		return $this->_query($query);
	}

	/**
	 * 子供をすべて削除する (親自身も含む)
	 * @param SiteNavi_Model_Route $model
	 * @return bool
	 */
	public function deleteChildren(SiteNavi_Model_Route $model)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('id_path', '^=', $model->get('id_path'));
		
		if ( $this->deleteAll($criteria) === false ) {
			return false;
		}
		
		if ( $this->updateChildren($model->get('parent_id')) === false ) {
			return false;
		}

		return true;
	}

	/**
	 * 
	 * @param string $idPath
	 * @return array
	 */
	public function getModuleContentsByIdPath($idPath)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('id_path', '^=', $idPath);

		$query = "SELECT module_id, content_id FROM %s WHERE %s";
		$query = sprintf($query, $this->table, $criteria);

		$result = $this->_query($query);

		$modules = array();

		while ( $row = $this->db->fetchArray($result) ) {
			$moduleId = $row['module_id'];
			$modules[$moduleId][] = $row['content_id'];
		}

		return $modules;
	}

	/**
	 * サイトマップに配置されているモジュールのIDをすべて返す
	 * @return array
	 */
	public function getPlacedModules()
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('content_id', '^=', '/site_navi/modules/');
		$models = $this->find($criteria);

		$modules = array();

		foreach ( $models as $model ) {
			$contentId = $model->get('content_id');
			$contentId = trim($contentId, '/');
			$moduleId  = substr($contentId, strrpos($contentId, '/') + 1);
			$moduleId  = intval($moduleId);
			$modules[] = $moduleId;
		}

		return $modules;
	}

	/**
	 * サイトマップに配置されているモジュールを返す
	 * @param string $dirname
	 * @return SiteNavi_Model_Route 見つからなかった場合FALSE
	 */
	public function getPlacedModuleByDirname($dirname)
	{
		$moduleHandler = xoops_gethandler('module');
		$moduleObject  = $moduleHandler->getByDirname($dirname);

		if ( is_object($moduleObject) === false ) {
			return false;
		}

		$moduleId = $moduleObject->get('mid');
		$contentId = sprintf(self::MODULE_TYPE_CONTENT_ID_FORMAT, $moduleId);

		return $this->loadByContentId($contentId);
	}

	/**
	 * サイトマップオプションを返す (重いので非推奨)
	 * @deprecated
	 * @return array
	 */
	public function getSitemapOptions()
	{
		$query = "SELECT content_id, title, level FROM %s ORDER BY weight_path";
		$query = sprintf($query, $this->table);
		$result = $this->_query($query);
		
		$options = array();
		
		while ( $row = $this->db->fetchArray($result) ) {
			$padding = $row['level'] - 1;
			$title   = $row['title'];
			$contentId = $row['content_id'];
			$padding = str_repeat('--', $padding);
			$title   = $padding.' '.$title;
			$options[$contentId] = $title;
		}

		return $options;
	}

	/**
	 * ページを別のフォルダに移動する
	 * @param SiteNavi_Model_Route $page 動かすページ
	 * @param SiteNavi_Model_Route $folder 移動先フォルダ
	 * @param string $reason 例外理由
	 * @return bool
	 */
	public function move(SiteNavi_Model_Route $page, SiteNavi_Model_Route $folder, &$reason = '')
	{
		try {
			$move = new SiteNavi_Model_RouteHandler_Move($this, $this->db, $this->table);
			$move->execute($page, $folder);
		} catch ( RuntimeException $e ) {
			$reason = $e->getMessage();
			return false;
		}

		return true;
	}

	/**
	 * ページを並び替える
	 * @param SiteNavi_Model_Route $source 移動するページ
	 * @param SiteNavi_Model_Route $target 並び替えの基準ページ
	 * @param string $position targetに対する相対位置 after or before
	 * @param string $reason 例外理由
	 * @return bool
	 */
	public function sort(SiteNavi_Model_Route $source, SiteNavi_Model_Route $target, $position, &$reason = '')
	{
		try {
			$sort = new SiteNavi_Model_RouteHandler_Sort($this, $this->db, $this->table);
			$sort->execute($source, $target, $position);
		} catch ( Exception $e ) {
			$reason = $e->getMessage();
			return false;
		}

		return true;
	}

	/**
	 * 同じフォルダにあるページかを返す
	 * @param SiteNavi_Model_Route $route1
	 * @param SiteNavi_Model_Route $route2
	 * @return bool
	 */
	public function isInSameFolder(SiteNavi_Model_Route $route1, SiteNavi_Model_Route $route2)
	{
		return ( $route1->get('parent_id') == $route2->get('parent_id') );
	}
}
