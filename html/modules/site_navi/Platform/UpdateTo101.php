<?php

class SiteNavi_Platform_UpdateTo101
{
	protected $dirname = '';

	/** @var XoopsMySQLDatabase */
	protected $db;

	public function __construct($dirname)
	{
		$this->dirname = $dirname;
		$this->db = $this->_getDatabase();
	}

	public function update()
	{
		$routeTable = $this->_getTableName('route');
		$this->_query("ALTER TABLE `$routeTable` ADD `invisible_in_menu_flag` tinyint(1) unsigned NOT NULL default '0' AFTER `url`");
		$this->_query("ALTER TABLE `$routeTable` ADD `private_flag` tinyint(1) unsigned NOT NULL default '0' AFTER `url");
	}

	protected function _query($query)
	{
		return $this->db->query($query);
	}

	protected function _getTableName($table)
	{
		return $this->db->prefix($this->dirname.'_'.$table);
	}

	/**
	 * @return XoopsMySQLDatabase
	 */
	protected function _getDatabase()
	{
		$root = XCube_Root::getSingleton();
		return $root->mController->mDB;
	}
}
