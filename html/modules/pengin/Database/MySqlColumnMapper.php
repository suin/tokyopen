<?php
class Pengin_Database_MySqlColumnMapper
{
	protected $db = null;
	protected $tables = array();

	protected $typeMap = array(
		'tinyint'   => Pengin_Model_AbstractModel::INTEGER,
		'smallint'  => Pengin_Model_AbstractModel::INTEGER,
		'mediumint' => Pengin_Model_AbstractModel::INTEGER,
		'int'       => Pengin_Model_AbstractModel::INTEGER,
		'integer'   => Pengin_Model_AbstractModel::INTEGER,
		'bigint'    => Pengin_Model_AbstractModel::INTEGER,
		'float'     => Pengin_Model_AbstractModel::FLOAT,
		'double'    => Pengin_Model_AbstractModel::FLOAT,
		'time'      => Pengin_Model_AbstractModel::STRING,
		'string'    => Pengin_Model_AbstractModel::STRING,
		'varchar'   => Pengin_Model_AbstractModel::STRING,
		'char'      => Pengin_Model_AbstractModel::STRING,
		'tinytext'  => Pengin_Model_AbstractModel::TEXT,
		'text'      => Pengin_Model_AbstractModel::TEXT,
		'mediumtext'=> Pengin_Model_AbstractModel::TEXT,
		'longtext'  => Pengin_Model_AbstractModel::TEXT,
		'tinyblob'  => Pengin_Model_AbstractModel::TEXT,
		'blob'      => Pengin_Model_AbstractModel::TEXT,
		'mediumblob'=> Pengin_Model_AbstractModel::TEXT,
		'longblob'  => Pengin_Model_AbstractModel::TEXT,
		'timestamp' => Pengin_Model_AbstractModel::DATETIME,
		'datetime'  => Pengin_Model_AbstractModel::DATETIME,
		'date'      => Pengin_Model_AbstractModel::DATE,
		'binary'    => Pengin_Model_AbstractModel::STRING, // TODO >> pengin does not support those data types... orz
		'varbinary' => Pengin_Model_AbstractModel::STRING,
		'set'       => Pengin_Model_AbstractModel::STRING,
		'enum'      => Pengin_Model_AbstractModel::STRING,

		'???'       => Pengin_Model_AbstractModel::STRING,
	);

	protected function __construct()
	{
		$root =& Pengin::getInstance();
		$this->db =& $root->cms->database();
	}

	public static function &getInstance()
	{
		static $instance = null;

		if ( $instance === null )
		{
			$instance = new self;
		}

		return $instance;
	}

	public function getColumns($table)
	{
		if ( !isset($this->tables[$table]) )
		{
			$this->tables[$table] = $this->_loadColumns($table);
		}

		return $this->tables[$table];
	}

	public function getTypeSize($columnType)
	{
		$type = 'varchar';
		$size = null;

		if ( preg_match('/^([a-zA-Z0-9]+)(\(([0-9]+)\))?/', $columnType, $matches) )
		{
			if ( isset($matches[1]) )
			{
				$type = strtolower($matches[1]);
			}

			if ( isset($matches[3]) )
			{
				$size = intval($matches[3]);
			}
		}

		if ( strpos(strtolower($columnType), 'zerofill') !== false )
		{
			$type = 'varchar';
		}

		$data = array(
			'type' => $this->_getModelType($type),
			'size' => $size,
		);

		return $data;
	}

	protected function _getModelType($type)
	{
		if ( isset($this->typeMap[$type]) )
		{
			return $this->typeMap[$type];
		}

		return $this->typeMap['???'];
	}

	protected function _loadColumns($table)
	{
		$sql = "SHOW COLUMNS FROM `%s`";
		$sql = sprintf($sql, $table);
		$result = $this->db->queryF($sql);

		$columns = array();

		while ( $column = $this->db->fetchArray($result) )
		{
			$columns[] = $column;
		}

		return $columns;
	}
}

?>
