<?php
class Profile_Model_UserHandler extends Pengin_Model_AbstractDynamicHandler
{
	protected $object  = 'Profile_Model_User';
	protected $table   = 'users';
	protected $primary = 'uid';

	public function __construct()
	{	
		parent::__construct();
		$this->table = $this->db->prefix('users');
	}

	/**
	 * テーブルにユーザ属性を追加する.
	 * 
	 * @access public
	 * @param string $name 属性名
	 * @param int $type タイプ
	 * @return bool 
	 */
	public function addProperty($name, $type)
	{
		$name   = mysql_real_escape_string($name); // あんま意味ないかも
		$pluginManager = new Profile_Plugin_Manager();
		$column = $pluginManager->call($type, 'getDatabaseColumn');
		$query = "ALTER TABLE `%s` ADD `%s` %s";
		$query = sprintf($query, $this->table, $name, $column);
		return $this->db->queryF($query);
		// ALTERはトランザクション効かないので注意 http://dev.mysql.com/doc/refman/5.1/ja/implicit-commit.html
	}

	/**
	 * テーブルからユーザ属性を削除する.
	 * 
	 * @access public
	 * @param string $name
	 * @return bool
	 */
	public function removeProperty($name)
	{
		$name = mysql_real_escape_string($name);
		$query = "ALTER TABLE `%s` DROP `%s`";
		$query = sprintf($query, $this->table, $name);
		return $this->db->queryF($query);
		// ALTERはトランザクション効かないので注意 http://dev.mysql.com/doc/refman/5.1/ja/implicit-commit.html
	}

	/**
	 * ユーザ属性が存在しているかチェックする.
	 * 
	 * @access public
	 * @param string $name 属性名
	 * @return bool
	 */
	public function existsProperty($name)
	{
		$name  = $this->db->quoteString($name);
		$query = "SHOW COLUMNS FROM `%s` LIKE %s";
		$query = sprintf($query, $this->table, $name);
		$result = $this->db->queryF($query);
		$result = $this->db->fetchRow($result);

		if ( $result === false ) {
			return false;
		} else {
			return true;
		}
	}
}
