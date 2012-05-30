<?php
/**
 *
 * @package Legacy
 * @version $Id: cubecore_init.php,v 1.3 2008/09/25 15:12:45 kilica Exp $
 * @copyright Copyright 2005-2007 XOOPS Cube Project  <http://xoopscube.sourceforge.net/> 
 * @license http://xoopscube.sourceforge.net/license/GPL_V2.txt GNU GENERAL PUBLIC LICENSE Version 2
 *
 */

if (!defined("XOOPS_MAINFILE_INCLUDED")) exit();

define('TOKYOPEN', true);

/**
 * This constant is the sign which this system is XOOPS Cube, for module
 * developers.
 */
define('XOOPS_CUBE_LEGACY', true);

require_once XOOPS_ROOT_PATH . "/core/XCube_Root.class.php";
require_once XOOPS_ROOT_PATH . "/core/XCube_Controller.class.php";

//
// TODO We have to move the following lines to an appropriate place.
//      (We may not need the following constants)
//
define("XCUBE_SITE_SETTING_FILE", XOOPS_ROOT_PATH . "/settings/site_default.ini.php");
define("XCUBE_SITE_CUSTOM_FILE", XOOPS_ROOT_PATH . "/settings/site_custom.ini.php");

//
//@todo How does the system decide the main controller?
//
$root=&XCube_Root::getSingleton();
$root->loadSiteConfig(XCUBE_SITE_SETTING_FILE, XCUBE_SITE_CUSTOM_FILE);
$root->setupController();
?>
