<?php

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

require_once XOOPS_ROOT_PATH.'/modules/legacy/class/Legacy_Debugger.class.php';

class ErrorHandlerOriginal extends XCube_ActionFilter
{
	var $original_error_reporting = 0 ;

	function preFilter()
	{
		$this->original_error_reporting = error_reporting() ;
		$this->mController->mSetupDebugger->add( array( &$this , 'createInstance' ) ) ;
	}

	function createInstance( &$instance , $debug_mode )
	{
		error_reporting( $this->original_error_reporting ) ;
		switch( $debug_mode ) {
			case XOOPS_DEBUG_MYSQL:
				$instance = new Legacy_MysqlDebugger();
				break;
			case XOOPS_DEBUG_SMARTY:
				$instance = new Legacy_SmartyDebugger();
				break;
			case XOOPS_DEBUG_OFF:
			case XOOPS_DEBUG_PHP:
			default:
				$instance = new Legacy_AbstractDebugger();
				break;
		}
	}
}

