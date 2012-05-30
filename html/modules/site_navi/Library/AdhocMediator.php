<?php
/**
 * モジュール間連携を取り急ぎ作った暫定クラスです.
 * tpbaseにモジュールメディエイターを実装したら、このクラスは削除してください。
 */

class SiteNavi_Library_AdhocMediator
{
	public function getSupportModules()
	{
		$apiClasses = array();

		$moduleHandler = xoops_gethandler('module');
		$criteria      = new Criteria('isactive', 1);
		$moduleObjects = $moduleHandler->getObjects($criteria);

		foreach ( $moduleObjects as $moduleObject ) {
			$dirname = $moduleObject->get('dirname');

			if ( $this->_isAdmin($dirname) === false ) {
				continue;
			}

			$className = $this->_getApiClass($dirname);

			if ( $className === false ) {
				continue;
			}

			$apiClasses[$dirname] = $className;
		}

		return $apiClasses;
	}

	public function deleteConents($moduleId, array $contentIds)
	{
		$moduleObject = $this->_getModuleObject($moduleId);

		if ( $moduleObject === false ) {
			return false;
		}

		$dirname  = $moduleObject->get('dirname');
		$apiClass = $this->_getApiClass($dirname);

		if ( $apiClass === false ) {
			return false;
		}

		$api = new $apiClass();
		return $api->delete($contentIds);
	}

	public function getContentId($dirname, $url, array $parameters)
	{
		if ( $dirname == '' ) {
			return false;
		}

		$apiClass = $this->_getApiClass($dirname);

		if ( $apiClass === false ) {
			return false;
		}

		$api = new $apiClass();
		return $api->getContentId($url, $parameters);
	}

	protected function _getModuleObject($moduleId)
	{
		$moduleHandler = xoops_gethandler('module');
		$moduleObject  = $moduleHandler->get($moduleId);
		
		if ( is_object($moduleObject) == false ) {
			return false;
		}

		return $moduleObject;
	}

	protected function _isAdmin($dirname)
	{
		$user = XCube_Root::getSingleton()->mContext->mUser;
		if ( $user->isInRole('Site.Owner') === true ) {
			return true;
		}
		// 20120201 refs #7655
		if ( $user->isInRole('Module.site_navi.Admin') === true ) {
		//if ( $user->isInRole('Module.'.$dirname.'.Admin') === true ) {
			return true;
		}

		return false;
	}

	protected function _getApiClass($dirname)
	{
		$filename = TP_MODULE_PATH.'/'.$dirname.'/API/SiteNavi.php';

		if ( file_exists($filename) === false ) {
			return false;
		}

		$pengin = Pengin::getInstance();
		$pengin->path(TP_MODULE_PATH.'/'.$dirname);

		$namespace = Pengin_Inflector::pascalize($dirname);
		$className = sprintf('%s_API_SiteNavi', $namespace);

		require_once $filename;

		if ( class_exists($className) === false ) {
			return false;
		}

		// TODO >> SiteNavi の interface を実装したクラスかチェックする

		return $className;
	}
	
	 /**
	 * 外部モジュールのAPIクラスを返す
	 * @param integer $moduleId
	 * @return string クラス名, 失敗時はFALSE
	 */
	public function getApiClassByModuleId($moduleId)
	{
		$moduleHandler=xoops_gethandler('module');
		$moduleObject=$moduleHandler->get($moduleId);

		if(is_object($moduleObject)===false){
		returnfalse;
		}

		$dirname=$moduleObject->get('dirname');
		return$this->_getApiClass($dirname);
	}
}
