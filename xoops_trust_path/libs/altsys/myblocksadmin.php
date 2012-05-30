<?php
// ------------------------------------------------------------------------- //
//                       myblocksadmin.php (altsys)                          //
//                - XOOPS block admin for each modules -                     //
//                       GIJOE <http://www.peak.ne.jp/>                      //
// ------------------------------------------------------------------------- //

require_once dirname(__FILE__).'/class/AltsysBreadcrumbs.class.php' ;
require_once dirname(__FILE__).'/include/gtickets.php' ;
include_once dirname(__FILE__).'/include/altsys_functions.php' ;
include_once dirname(__FILE__).'/include/mygrouppermform.php' ;
include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php' ;


// language file
altsys_include_language_file( 'myblocksadmin' ) ;

include_once dirname(__FILE__).'/class/MyBlocksAdminForXCL21.class.php' ;
$myba =& MyBlocksAdminForXCL21::getInstance() ;

// permission
$myba->checkPermission() ;

// set parameters target_mid , target_dirname etc.
$myba->init( $xoopsModule ) ;


//
// transaction stage
//

if( ! empty( $_POST ) ) {
	$myba->processPost() ;
}

//
// form stage
//

// header
xoops_cp_header() ;

// mymenu
altsys_include_mymenu() ;

$myba->processGet() ;

// footer
xoops_cp_footer() ;


?>