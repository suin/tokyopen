<?php

/**
* 
*/
class Site_navi_HeadMenu extends XCube_ActionFilter
{
    public function preBlockFilter()
	{
		$this->mRoot->mDelegateManager->add('Legacy_RenderSystem.RenderTheme', array($this, 'addHeadMenu'));
	}
	public function addHeadMenu($xoopsTpl)
	{
	    $xoopsTpl->assign('headMenuList', $this->_getHeadMenuList());
	}
    protected function _getHeadMenuList()
    {
        $pengin = Pengin::getInstance();
        $pengin->path(XOOPS_ROOT_PATH.'/modules/site_navi');


        $routeHandler = $pengin->getModelHandler('Route', 'site_navi');

        // TODO ヘッダメニュ取得用メソッドを追加したらそちらに変更する
		// 非公開のセット　モジュールadminだったらどちらでもいい　それ以外は0の時だけ
		$privateFlag = false;
		$root =& Pengin::getInstance();
		if($root->cms->isAdmin() == false){
			$privateFlag = 0;
		}
		// メニューに非表示のセット　
		$invisibleInMenuFlag = 0;
		
        $routeList = $routeHandler->findChildrenByParentId(SiteNavi_Model_RouteHandler::TOP_ID ,$privateFlag , $invisibleInMenuFlag);
        
        $headMenuList = array();
        foreach ($routeList as $key => $route) {
            $headMenuList[] = array(
                'text' => $route->get('title'),
                'url'  => $route->get('url')
            );
        }
        return $headMenuList;
    }
}