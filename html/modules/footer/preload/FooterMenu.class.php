<?php

/**
* 
*/
class Footer_FooterMenu extends XCube_ActionFilter
{
    public function preBlockFilter()
	{
		$this->mRoot->mDelegateManager->add('Legacy_RenderSystem.RenderTheme', array($this, 'addFooterMenu'));
	}
	public function addFooterMenu(& $xoopsTpl)
	{
	    $xoopsTpl->assign('footMenuList', $this->_getMenuList());
	}
    protected function _getMenuList()
    {
        $pengin = Pengin::getInstance();
        $pengin->path(XOOPS_ROOT_PATH.'/modules/footer');

        $menuHandler = $pengin->getModelHandler('Menu', 'footer');

	    $criteria = new Pengin_Criteria();
	    $menuList = $menuHandler->find($criteria, 'weight');

        $ret = array();
        foreach ($menuList as $key => $menu) {
            $ret[] = array(
                'text' => $menu->get('title'),
                'url'  => $menu->get('url')
            );
        }
        return $ret;
    }
}