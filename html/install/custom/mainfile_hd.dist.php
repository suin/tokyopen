<?php
// $Id: mainfile.dist.php,v 1.2 2007/09/22 06:43:42 minahito Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

if ( !defined("XOOPS_MAINFILE_INCLUDED") ) {
	define("XOOPS_MAINFILE_INCLUDED",1);

	require dirname(__FILE__).'/settings/definition.custom.php';

	if ( defined('_LEGACY_PREVENT_LOAD_CORE_') === false and XOOPS_ROOT_PATH !== '') {
		require XOOPS_TRUST_PATH.'/modules/protector/include/precheck.inc.php' ;
		@include_once XOOPS_ROOT_PATH.'/include/cubecore_init.php';

		if ( isset($xoopsOption['nocommon']) === false and defined('_LEGACY_PREVENT_EXEC_COMMON_') === false ) {
			include XOOPS_ROOT_PATH.'/include/common.php';
		}

		require XOOPS_TRUST_PATH.'/modules/protector/include/postcheck.inc.php' ;

		// check for ryusRender.
		require XOOPS_ROOT_PATH . '/modules/ryusRender/ini_checker.php';
		ryusRenderIniChecker::doCheck();
	}
}
?>