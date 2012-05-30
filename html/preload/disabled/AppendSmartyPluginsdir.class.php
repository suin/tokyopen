<?php

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

class AppendSmartyPluginsdir extends XCube_ActionFilter
{
	function preFilter()
	{
		  $this->mRoot->mDelegateManager->add('Legacy_RenderSystem.SetupXoopsTpl',
											  array($this, 'appendPluginsdir'));
	}

	function appendPluginsdir(&$xoopsTpl)
	{
		$plugins_dir = $xoopsTpl->plugins_dir;
		if (!is_array($plugins_dir)){
			$plugins_dir = array($plugins_dir);
		}
		$root =& XCube_Root::getSingleton();
		$append_setting = $root->getSiteConfig('HdSetting','SmartyPluginsdir');
		if ($append_setting && is_string($append_setting)){
			$smarty_pluginsdir_setting = explode(',', $append_setting);
			foreach ($smarty_pluginsdir_setting as $append_dir){
				if ($append_dir = trim($append_dir)){
					if ($this->is_absolute_path($append_dir)){
						$plugins_dir[] = sprintf('%s%s', XOOPS_TRUST_PATH, $append_dir);
					} else {
						$plugins_dir[] = sprintf('%s/%s', $plugins_dir[0], $append_dir);
					}
				}
			}
			$xoopsTpl->plugins_dir =& $plugins_dir;
		}
	}


	/**
	 *  絶対パス風味かどうかを返す
	 *
	 *  @param  string  $path               ファイル名
	 *  @return bool    true:絶対 false:相対
	 */
	function is_absolute_path($path)
	{
		if ($path{0} == '/') {
			return true;
		}
		return false;
	}
}
