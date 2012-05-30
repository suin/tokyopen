<?php
class NiceAdmin_Model_ModuleHandler extends XoopsModuleHandler
{
	public function __construct()
	{
		$pengin =& Pengin::getInstance();
		$db =& $pengin->cms->database();
		parent::__construct($db);
	}

	public function findHasAdminModules($isSiteOwner, $userGroups)
	{
		$pengin =& Pengin::getInstance();

		$mod  = $this->db->prefix("modules");
		$perm = $this->db->prefix("group_permission");
		$groups = implode(',', $userGroups);

		if ( $isSiteOwner )
		{
			$sql = "SELECT DISTINCT mid FROM ${mod} WHERE isactive=1 AND hasadmin=1 ORDER BY weight, mid";
		}
		else
		{
			$sql = "SELECT DISTINCT ${mod}.mid FROM ${mod},${perm} " .
			       "WHERE ${mod}.isactive=1 AND ${mod}.mid=${perm}.gperm_itemid AND ${perm}.gperm_name='module_admin' AND ${perm}.gperm_groupid IN (${groups}) " .
			       "AND ${mod}.hasadmin=1 " .
			       "ORDER BY ${mod}.weight, ${mod}.mid";
		}

		$result = $this->db->query($sql);

		if ( $result === false )
		{
			return false;
		}

		$models = array();

		while ( $row = $this->db->fetchArray($result) )
		{
			$models[] =& $this->get($row['mid']);
		}

		return $models;
	}
}
