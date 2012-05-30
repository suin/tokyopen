<?php
/**
 *
 * @package Legacy
 * @version $Id: install_dbform.inc.php,v 1.1 2007/05/15 02:34:40 minahito Exp $
 * @copyright Copyright 2005, 2006 XOOPSCube.org <http://xoopscube.org/> 
 * @license http://www.gnu.org/licenses/gpl.txt GNU GENERAL PUBLIC LICENSE Version 2
 *
 */

if ( file_exists(_TP_DEFINITION_CUSTOM_FILE) === true ) {
	require_once _TP_DEFINITION_CUSTOM_FILE;
}

include_once dirname(__FILE__).'/class/settingmanager_hd.php';
$sm = new setting_manager_hd();
$sm->readConstant();
$wizard->setContent($sm->editform());
$wizard->render();
