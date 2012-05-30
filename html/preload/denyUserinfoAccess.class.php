<?php

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

if ( ! class_exists('denyUserinfoAccess') ) {
    class denyUserinfoAccess extends XCube_ActionFilter
    {
        function preBlockFilter()
        {
            $xcRoot =& XCube_Root::getSingleton();
            $xcRoot->mDelegateManager->add( 'Legacypage.Userinfo.Access',
                array($this, '_denyUserinfo'), XCUBE_DELEGATE_PRIORITY_FIRST);
        }

        function _denyUserinfo()
        {
            $xcRoot =& XCube_Root::getSingleton();
            if ( ! isset($xcRoot->mContext->mXoopsUser) || ! is_object($xcRoot->mContext->mXoopsUser) ) {
                $xcRoot->mController->executeRedirect(XOOPS_URL . '/user.php', 1, _NOPERM);
            }
        }
    }
}
