<?php
/**
 * Session callback preload XOOPS Cube Legacy2.1
 *
 * PHP Versions 4
 *
 * @package  Hodajuku distribution
 * @author  Makoto Hashiguchi a.k.a. gusagi<gusagi@gusagi.com>
 * @copyright 2009 Makoto Hashiguchi
 * @license GNU General Public License Version2
 *
 */

/**
 * GNU General Public License Version2
 *
 * Copyright (C) 2008  < Makoto Hashiguchi a.k.a. gusagi >
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

if ( ! class_exists('HdFileSession') ) {
	
	define('HD_SESSION_SAVE_DIR',TP_SESSION_PATH);
	
	
    class HdFileSession extends XCube_ActionFilter
    {
        function preBlockFilter()
        {
            $this->mRoot->mDelegateManager->add('XCube_Session.SetupSessionHandler', 'HdFileSession::setupSessionHandler', XCUBE_DELEGATE_PRIORITY_FINAL + 1);
        }

        function setupSessionHandler()
        {
            ini_set('session.save_handler', 'files');
            ini_set('session.save_path', HD_SESSION_SAVE_DIR);
        }
		
		function errorReport($msg, $hint)
		{
			$msg = sprintf('<html><body style="text-align:center;">
			<table style="margin:auto;width:400px;height:120px;border:1px dotted #ef9c99;background-color:#edfce5;">
			<tr><td style="text-align:center;vertical-align:middle;font-weight:bold;">%s</td></tr><tr><td>Hint:<br><textarea onfocus="select();" style="width:100%%;height:100%%;">%s</textarea></td></tr>
			</table></body></html>', $msg, $hint);
			exit($msg);
		}
    }
	
	if (!is_dir(HD_SESSION_SAVE_DIR)){
		HdFileSession::errorReport("SESSION directory is not exists!",
								   sprintf("mkdir %s\nchmod 0777 %s", HD_SESSION_SAVE_DIR, HD_SESSION_SAVE_DIR));
	}
	if (!is_writable(HD_SESSION_SAVE_DIR)){
		HdFileSession::errorReport("SESSION directory is not writable!", 
								   sprintf("chmod 0777 %s", HD_SESSION_SAVE_DIR, HD_SESSION_SAVE_DIR));
	}
}
