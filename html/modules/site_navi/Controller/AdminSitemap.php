<?php

class SiteNavi_Controller_AdminSitemap extends SiteNavi_Abstract_AdminController
{
	protected $useModels = array('Route');

	protected function _setUpPageTitle()
	{
		$this->pageTitle = t("Sitemap");
	}

	protected function _defaultAction()
	{
		$routeId   = SiteNavi_Model_RouteHandler::TOP_ID;
		$routeJson = $this->_getRouteJson($routeId);
	
		$this->output['parent']   = $routeJson['parent'];
		$this->output['children'] = $routeJson['children'];

		$this->_view();
	}

	protected function _listAction()
	{
		$routeId = $this->get('route_id');
		$children = $this->routeHandler->findChildrenByParentId($routeId);
		$children = $this->routeHandler->toArray($children);

		header("Content-Type: application/json; charset=utf-8");
		$result = json_encode($children);
		echo $result;
		die;
	}

	protected function _nodeAction()
	{
		$routeId = $this->get('route_id');
		
	}

	protected function _getRouteJson($routeId)
	{
		$parent = $this->routeHandler->load($routeId);

		if ( $parent->hasChildren() === true ) {
			$children = $this->routeHandler->findChildrenByParentId($routeId);
			$children = $this->routeHandler->toArray($children);
		} else {
			$children = array();
		}

		$parent = $parent->getVars();

		$route = array(
			'parent'   => json_encode($parent),
			'children' => json_encode($children),
		);

		return $route;
	}
}
