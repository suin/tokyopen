<?php
class Pengin_Platform_XoopsCubeLegacy_Installer extends Pengin_Platform_Xoops20_Installer
{
	protected function _message()
	{
		$Dirname = ucfirst($this->dirname);
		$callback = array(&$this, 'messageAppend');

		$root =& XCube_Root::getSingleton();
		$root->mDelegateManager->add('Legacy.Admin.Event.ModuleInstall.'.$Dirname.'.Success', $callback);
		$root->mDelegateManager->add('Legacy.Admin.Event.ModuleInstall.'.$Dirname.'.Fail', $callback);
		$root->mDelegateManager->add('Legacy.Admin.Event.ModuleUpdate.'.$Dirname.'.Success', $callback);
		$root->mDelegateManager->add('Legacy.Admin.Event.ModuleUpdate.'.$Dirname.'.Fail', $callback);
		$root->mDelegateManager->add('Legacy.Admin.Event.ModuleUninstall.'.$Dirname.'.Success', $callback);
		$root->mDelegateManager->add('Legacy.Admin.Event.ModuleUninstall.'.$Dirname.'.Fail', $callback);
	}

	public function messageAppend(&$moduleObj, &$log)
	{
		$lastMessage = array_pop($log->mMessages);

		foreach ( $this->messages as $message )
		{
			if ( $message['type'] == 'error' )
			{
				$log->addError(strip_tags($message['content']));
			}
			else
			{
				$log->add(strip_tags($message['content']));
			}
		}

		$log->mMessages[] = $lastMessage;
	}
}

?>
