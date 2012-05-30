<?php
abstract class Pengin_Model_AbstractHandler
{
	protected $dirname = '';

	protected $object  = '';
	protected $table   = '';
	protected $primary = 'id';

	protected $db      = null;

	protected $errors = array();

	public function __construct($dirname = null)
	{	
		$root =& Pengin::getInstance();

		if ( $dirname === null ) {
			$dirname = $root->context->dirname;
		}

		$this->dirname = $dirname;
		$this->db =& $root->cms->database();

		if ( $this->object === '' )
		{
			$className = get_class($this);
			$this->object = preg_replace('/Handler$/', '', $className);
		}

		if ( $this->table === '' )
		{
			$classParts  = explode('_', $this->object);
			$endPart     = end($classParts);
			$endPart     = $root->snakeCase($endPart);
			$this->table = $endPart;
		}

		$this->table = $this->db->prefix($this->dirname.'_'.$this->table);
	}

	public function create()
	{
		$obj = $this->object;
		$obj = new $obj();
		$obj->setNew();
		return $obj;
	}

	public function load($id = null)
	{
		$id = intval($id);

		$obj = $this->create();

		if ( $id > 0 )
		{
			$sql = "SELECT * FROM `%s` WHERE `%s`='%u'";
			$sql = sprintf($sql, $this->table, $this->primary, $id);
			$rsrc = $this->_query($sql, 1);

			$vars = $this->db->fetchArray($rsrc);

			if ( $vars === false )
			{
				return false;
			}

			$obj->unsetNew();
			$obj->setVars($vars);
		}

		return $obj;
	}

	public function save(&$obj)
	{
		if ( $obj->isNew() )
		{
			$this->_insert($obj);
		}
		else
		{
			$this->_update($obj);
		}

		return ( count($this->errors) === 0 );
	}

	public function delete($id)
	{
		$id = (int) $id;
		$sql = "DELETE FROM `%s` WHERE `%s` = '%u'";
		$sql = sprintf($sql, $this->table, $this->primary, $id);
		return $this->_query($sql);
	}

	/**
	 * 一括削除.
	 * 
	 * @access public
	 * @param Pengin_Criteria $criteria
	 * @return bool 成功したときTRUE 失敗したときFALSE
	 */
	public function deleteAll(Pengin_Criteria $criteria = null)
	{
		$where = '';
		if(is_object($criteria))
		{
			$where = $criteria->render();
		}
		$sql = "DELETE FROM `%s`";
		$sql = sprintf($sql, $this->table);

		if ( $where !== '' )
		{
			$sql .= ' WHERE '.$where;
		}

		return $this->_query($sql);
	}

	public function count($criteria = null)
	{
		$where = '';

		if ( is_object($criteria) )
		{
			$where = $criteria->render();
		}

		$sql = "SELECT COUNT(*) FROM `%s`";
		$sql = sprintf($sql, $this->table);

		if ( $where !== '' )
		{
			$sql .= ' WHERE '.$where;
		}

		$result = $this->_query($sql);
		list($total) = $this->db->fetchRow($result);
		$total = intval($total);

		return $total;
	}

	public function find($criteria = null, $orderby = null, $sort = 'ASC', $limit = 0, $start = 0, $idAsKey = false)
	{
		$where = '';

		if ( is_object($criteria) )
		{
			$where = $criteria->render();
		}

		if ( $orderby === null )
		{
			$orderby = $this->primary;
		}

		if ( !in_array($sort, array('ASC', 'DESC')) )
		{
			$sort = 'ASC';
		}

		$limit = intval($limit);
		$start = intval($start);

		$sql = 'SELECT * FROM `'.$this->table.'`';

		if ( $where !== '' )
		{
			$sql .= ' WHERE '.$where;
		}

		$sql .= ' ORDER BY `'.$orderby.'` '.$sort;

		$result = $this->_query($sql, $limit, $start);

		$models = array();

		while ( $row = $this->db->fetchArray($result) )
		{
			$model = $this->create();
			$model->unsetNew();
			$model->setVars($row);

			if ( $idAsKey === true ) {
				$id = $model->get($this->primary);
				$models[$id] = $model;
			} else {
				$models[] = $model;
			}
		}

		if ( $limit === 1 and count($models) > 0 )
		{
			return reset($models);
		}
		else
		{
			return $models;
		}
	}

	public function getErrors()
	{
		return $this->errors;
	}

	protected function _insert(&$obj)
	{
		$this->_addCreated($obj);
		$this->_addModified($obj);

		$vars = $obj->getVarsSqlEscaped();
		if ( is_null($vars[$this->primary]) === true )
		{
			unset($vars[$this->primary]);
		}
		$data = $this->_buildData($vars);

		$sql = "INSERT INTO `%s` SET %s";
		$sql = sprintf($sql, $this->table, $data);

		if ( !$this->_query($sql) ) return;

		$newId = $this->db->getInsertId();
		$obj->setVar($this->primary, $newId);
		$obj->unsetNew();
	}

	protected function _update(&$obj)
	{
		$this->_addModified($obj);

		$id   = $obj->getVar($this->primary);
		$vars = $obj->getVarsSqlEscaped();
		$data = $this->_buildData($vars);

		$sql = "UPDATE `%s` SET %s WHERE `%s` = '%u'";
		$sql = sprintf($sql, $this->table, $data, $this->primary, $id);

		$this->_query($sql);
	}

	protected function _query($sql, $limit = null, $start = null)
	{
		$result = $this->db->queryF($sql, $limit, $start);
		if ( !$result ) $this->errors[] = $this->db->error();
		return $result;
	}

	protected function _buildData($vars)
	{
		$ret = array();

		foreach ( $vars as $name => $value )
		{
			$ret[] = sprintf("`%s` = '%s'", $name, $value);
		}

		$ret = implode(', ', $ret);

		return $ret;
	}

	protected function _addCreated(&$obj)
	{
		if ( $obj->isKeyExists('created') ) {
			$obj->setVar('created', time());
		}

		if ( $obj->isKeyExists('creator_id') ) {
			$obj->setVar('creator_id', Pengin::getInstance()->cms->getUserId());
		}
	}

	protected function _addModified(&$obj)
	{
		if ( $obj->isKeyExists('modified') ) {
			$obj->setVar('modified', time());
		}

		if ( $obj->isKeyExists('modifier_id') ) {
			$obj->setVar('modifier_id', Pengin::getInstance()->cms->getUserId());
		}
	}
}
