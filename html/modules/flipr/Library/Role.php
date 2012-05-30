<?php
class Flipr_Library_Role
{
	protected $roles = array();

	public function __construct()
	{
		$this->addRole('album_new', array(XOOPS_GROUP_ADMIN));
		$this->addRole('album_edit', array(XOOPS_GROUP_ADMIN));
		$this->addRole('album_delete', array(XOOPS_GROUP_ADMIN));
		$this->addRole('photo_new', array(XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS));
		$this->addRole('photo_edit', array(XOOPS_GROUP_ADMIN));
		$this->addRole('photo_delete', array(XOOPS_GROUP_ADMIN));
	}

	public function getUserRoles()
	{
		$userRoles = array();

		foreach ( $this->roles as $roleName => $v )
		{
			$userRoles[$roleName] = $this->isInRole($roleName);
		}
		
		return $userRoles;
	}

	public function isInRole($roleName)
	{
		if ( $this->existsRole($roleName) === false )
		{
			return true;
		}

		global $xoopsUser;
		
		if ( is_object($xoopsUser) === false )
		{
			return false;
		}

		$groupIds = $xoopsUser->getGroups();

		foreach ( $groupIds as $groupId )
		{
			if ( in_array($groupId, $this->roles[$roleName]) )
			{
				return true;
			}
		}

		return false;
	}

	public function getRoles()
	{
		return $this->roles;
	}

	public function getRole($roleName)
	{
		return $this->roles[$roleName];
	}

	public function addRole($roleName, array $groupIds)
	{
		$this->roles[$roleName] = $groupIds;
	}
	
	public function removeRole($roleName)
	{
		unset($this->roles[$roleName]);
	}
	
	public function existsRole($roleName)
	{
		return isset($this->roles[$roleName]);
	}
}
