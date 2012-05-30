<?php

class Profile_PlatForm_TP_Installer extends Pengin_Platform_TP_Installer
{
	protected function _install()
	{
		parent::_install();
		$this->_addAccessPermissionToAll();
	}

	/**
	 * すべてのグループにアクセス権限を与える
	 *
	 * xoops_version.php の read_any = true は hasMain = true でないと
	 * 適用されないので、カスタムインストーラで行う。
	 */
	protected function _addAccessPermissionToAll()
	{
		$memberHandler = xoops_gethandler('member');
		$gpermHandler = xoops_gethandler('groupperm');
		$groupObjects = $memberHandler->getGroups();
		//
		// Add a permission all group members and guest can read.
		//
		foreach ( $groupObjects as $group ) {
			$readPerm = $this->_createPermission($group->getVar('groupid'));
			$readPerm->setVar('gperm_name', 'module_read');

			if ( !$gpermHandler->insert($readPerm) ) {
				$this->_addError(_AD_LEGACY_ERROR_COULD_NOT_SET_READ_PERMISSION);
			}
		}
	}

	/**
	 * Create a permission object which has been initialized for admin.
	 * For flexibility, creation only and not save it.
	 * @access private
	 * @param $group
	 * @return mixed
	 */
	function _createPermission($group)
	{
		$gpermHandler = xoops_gethandler('groupperm');

		$perm = $gpermHandler->create();

		$perm->setVar('gperm_groupid', $group);
		$perm->setVar('gperm_itemid', $this->module->getVar('mid'));
		$perm->setVar('gperm_modid', 1);

		return $perm;
	}
}
