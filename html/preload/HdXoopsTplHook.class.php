<?php

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

class HdXoopsTplHook extends XCube_ActionFilter
{
    function preBlockFilter()
    {
        $this->mRoot->mDelegateManager->add( 'XoopsTpl.New' , array( $this , 'hook' ) ) ;
    }

    function hook( &$xoopsTpl )
    {
        global $xoopsConfig ;

        $compile_id = $xoopsConfig['template_set'] . '-' . $xoopsConfig['theme_set'] ;
        $xoopsTpl->compile_id = $compile_id ;
        $xoopsTpl->_compile_id = $compile_id ;
    }
}
