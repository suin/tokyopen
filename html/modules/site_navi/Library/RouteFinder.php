<?php
/**
 * URLなどからルート情報を解決することを担当するクラス
 */
class SiteNavi_Library_RouteFinder
{
	/**
	 * URLを解決する
	 * @param string $url
	 * @return SiteNavi_Model_Route 失敗した場合FALSE
	 */
	public function resolve($url)
	{
		$requestData = $this->_getRequestData($url);

		// モジュールを当たってみる
		$result = $this->_askModule($requestData);

		if ( is_object($result) === true ) {
			return $result;
		}

		// 自己解決できないか試してみる
		$result = $this->_resolveBySelf($requestData);

		if ( is_object($result) === true ) {
			return $result;
		}

		// すべてのモジュールに片っ端から当たってみる
		$result = $this->_askAllModules($requestData);

		if ( is_object($result) === true ) {
			return $result;
		}

		// それでも解決できない場合
		return false;
	}

	/**
	 * モジュールに問い合わせる
	 * @param array $requestData
	 * @return SiteNavi_Model_Route 失敗した場合FALSE
	 */
	protected function _askModule(array $requestData)
	{
		$dirname    = $requestData['dirname'];
		$url        = $requestData['url'];
		$parameters = $requestData['parameters'];

		if ( $dirname === false ) {
			return false;
		}

		$mediator = new SiteNavi_Library_AdhocMediator();
		$contentId = $mediator->getContentId($dirname, $url, $parameters);

		if ( $contentId === false ) {
			return false;
		}

		$pengin       = Pengin::getInstance();
		$routeHandler = $pengin->getModelHandler('Route', 'site_navi');

		return $routeHandler->loadByContentId($contentId);
	}

	/**
	 * すべてのモジュールに問い合わせる
	 * @param array $requestData
	 * @return SiteNavi_Model_Route 失敗した場合FALSE
	 */
	protected function _askAllModules(array $requestData)
	{
		// 片っ端から問い合わせる
		return false; // 未実装
	}

	/**
	 * 自己解決する
	 * @param array $requestData
	 * @return SiteNavi_Model_Route 失敗した場合FALSE
	 */
	protected function _resolveBySelf(array $requestData)
	{
		$pengin       = Pengin::getInstance();
		$routeHandler = $pengin->getModelHandler('Route', 'site_navi');

		if ( defined('TP_TOP_ACCESS') === true ) {
			return $routeHandler->loadByContentId(SiteNavi_Model_RouteHandler::TOP_CONTENT_ID);
		}

		if ( $requestData['dirname'] !== false ) {
			return $routeHandler->getPlacedModuleByDirname($requestData['dirname']);
		}

		return false;
	}

	protected function _getRequestData($url)
	{
		$dirname    = $this->_detectModuleDirname($url);
		$parameters = $this->_parseQueryString($url);

		$data = array(
			'url'        => $url,
			'dirname'    => $dirname,
			'parameters' => $parameters,
		);

		return $data;
	}

	protected function _detectModuleDirname($url)
	{
		$tpHost  = parse_url(TP_MODULE_URL, PHP_URL_HOST);
		$urlHost = parse_url($url, PHP_URL_HOST);

		if ( $tpHost !== $urlHost ) {
			return false;
		}

		$tpModulePath = parse_url(TP_MODULE_URL, PHP_URL_PATH);
		$urlPath      = parse_url($url, PHP_URL_PATH);

		if ( strpos($urlPath, $tpModulePath) !== 0 ) {
			return false; // パスが '/modules' で始まらない場合
		}

		$tpModulePath = trim($tpModulePath, '/');
		$urlPath      = trim($urlPath, '/');

		$urlPath = substr($urlPath, strlen($tpModulePath));
		$urlPath = trim($urlPath, '/');
		$tokens  = explode('/', $urlPath);

		return $tokens[0];
	}

	protected function _parseQueryString($url)
	{
		$queryString = parse_url($url, PHP_URL_QUERY);
		$query = array();
		parse_str($queryString, $query);
		return $query;
	}
}
