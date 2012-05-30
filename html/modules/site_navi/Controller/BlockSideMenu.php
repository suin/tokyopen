<?php

class SiteNavi_Controller_BlockSideMenu extends SiteNavi_Abstract_BlockController
{
	protected $useModels = array('Route');

	protected function _showAction()
	{
		$contentId  = (string) $this->options[1];
		$showParent = (bool)   $this->options[2];

		if ( $contentId === '' ) {
			$finder = new SiteNavi_Library_RouteFinder();
			$parent = $finder->resolve($this->root->getUrl());
		} else {
			$parent = $this->routeHandler->loadByContentId($contentId);
		}

		if ( $parent === false ) {
			$parent = $this->routeHandler->load(SiteNavi_Model_RouteHandler::TOP_ID);
		}

		$parentId = $parent->get('id');
		// 非公開のセット　モジュールadminだったらどちらでもいい　それ以外は0の時だけ
		$privateFlag = false;
		if($this->root->cms->isAdmin() == false){
			$privateFlag = 0;
		}
		// メニューに非表示のセット　
		$invisibleInMenuFlag = 0;
		
		$children = $this->routeHandler->findChildrenByParentId($parentId , $privateFlag ,$invisibleInMenuFlag);

		if ( count($children) > 0 or $showParent === true ) {
			$hasMenu = true;
		} else {
			$hasMenu = false;
		}

		$this->output['showParent'] = $showParent;
		$this->output['parent']     = $parent;
		$this->output['children']   = $children;
		$this->output['hasMenu']    = $hasMenu;

		$this->_view();
	}

	protected function _editAction()
	{
		$routeOptions = array(
			'' => t("Show children of current page"),
		);

		$routeOptions += $this->routeHandler->getSitemapOptions();

		$this->output['routeOptions'] = $routeOptions;

		$this->output['showParentOptions'] = array(
			1 => t("Show"),
			0 => t("Hide"),
		);

		$this->_view();
	}
}
