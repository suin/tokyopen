<?php
if ( !defined('PENGIN_PATH') ) exit;

require_once PENGIN_PATH.'/include/preload.php';

class Nice_admin_AdminIcon extends XCube_ActionFilter
{
	public function preBlockFilter()	
	{
		$this->mRoot->mDelegateManager->add('Legacypage.Admin.SystemCheck', array($this, 'displayAdminIcons'), XCUBE_DELEGATE_PRIORITY_FINAL);
	}

	public function displayAdminIcons()
	{
		$dirname = basename(dirname(dirname(__FILE__)));
		$result = pengin_call_preload_dispatcher('module_icon', 'default', $dirname);

		if ( $result === false )
		{
			return;
		}

		echo $result;
	}
}
