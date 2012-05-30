<?php
/**
 *
 * @package Legacy
 * @version $Id: ThemeListAction.class.php,v 1.5 2008/09/25 15:11:47 kilica Exp $
 * @copyright Copyright 2005-2007 XOOPS Cube Project  <http://xoopscube.sourceforge.net/> 
 * @license http://xoopscube.sourceforge.net/license/GPL_V2.txt GNU GENERAL PUBLIC LICENSE Version 2
 *
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

//require_once XOOPS_MODULE_PATH . "/legacy/admin/forms/ThemeSelectForm.class.php";

/***
 * @internal
 * This action shows the list of selectable themes to user.
 * 
 * [Notice]
 * In XOOPS Cube Legacy which can have many themes with different render-
 * systems, that one render-system has the control to change themes is wrong,
 * because this action can't list up themes of other render-systems.
 * The action to change themes should be in Legacy. And, each render-systems
 * should send theme informations through delegate-mechanism.
 * 
 * Therefore, this class is test for that we may move this action from
 * LegacyRender module. If you want to check the concept of this strategy, see 
 * ThemeSelect preload in Legacy module.
 */
class Legacy_ThemeFinderAction extends Legacy_Action
{
	const THEME_FINDER_API_VERSION = '1';

	protected $themeFinderUrl = "http://cmsthemefinder.com/store/enter_store.php";

	function prepare(&$controller, &$xoopsUser)
	{
		if ( defined('TP_THEME_FINDER_URL') === true ) {
			$this->themeFinderUrl = TP_THEME_FINDER_URL; // デバッグ用
		}
	}
	
	function _setupObject()
	{
	}

	function _setupActionForm()
	{
	}
	
	function getDefaultView(&$controller, &$xoopsUser)
	{
		return LEGACY_FRAME_VIEW_INDEX;
	}
	
	function execute(&$controller, &$xoopsUser)
	{
	}
	
	function executeViewIndex(&$controller, &$xoopsUser, &$render)
	{
		$render->setTemplateName("theme_finder.html");
		$render->setAttribute("themeFinderUrl", $this->themeFinderUrl);
		$render->setAttribute("themeFinderApiVersion", self::THEME_FINDER_API_VERSION);
		$render->setAttribute("addonManagerInstallUrl", XOOPS_URL.'/modules/addon_manager/admin/index.php?controller=install&action=default&target_type=Theme&target_key=');
	}
}
