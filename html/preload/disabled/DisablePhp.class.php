<?php

class DisablePhp extends XCube_ActionFilter
{
    function preFilter()
	{
		$this->mRoot->mDelegateManager->add('Legacy_RenderSystem.SetupXoopsTpl', array( &$this , 'hookSetupXoopsTpl' ) ) ;
  		$this->mRoot->mDelegateManager->add('Legacy_ActionFrame.CreateAction', array(&$this, 'hookCreateAction'));  
	}

	function hookSetupXoopsTpl( &$xoopsTpl )
	{
        // $xoopsTpl->security = true;
        // $xoopsTpl->security_settings['MODIFIER_FUNCS'][] = 't';
        // $xoopsTpl->security_settings['ALLOW_CONSTANTS'] = TRUE;
	    
	    // prefilterで$smarty.const.XOOPS_DB*を削除かなぁ
	}
	
	public function hookCreateAction(&$action)
	{
		switch ( $action->mActionName )
		{
			case 'CustomBlockEdit':
			    // カスタムブロック編集で強制的にコンテンツタイプをHTMLにする（PHPを選ばせない
			    $_REQUEST['c_type'] = 'H';
			    $_POST['c_type'] = 'H';
    			break;
        }
	}
}