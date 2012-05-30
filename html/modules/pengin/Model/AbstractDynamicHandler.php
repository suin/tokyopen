<?php
abstract class Pengin_Model_AbstractDynamicHandler extends Pengin_Model_AbstractHandler
{
	/** @var Pengin_Model_AbstractModel */
	protected $prototypeModel = null;

	public function create()
	{
		return clone $this->_getPrototypeModel();
	}

	protected function _getPrototypeModel()
	{
		if ( $this->prototypeModel === null ) {

			$this->prototypeModel = parent::create();

			// カラム情報を取得する
			$mapper =& Pengin_Database_MySqlColumnMapper::getInstance();
			$columns = $mapper->getColumns($this->table);

			foreach ( $columns as $column ) {
				$data = $mapper->getTypeSize($column['Type']);
				$this->prototypeModel->val($column['Field'], $data['type'], $column['Default'], $data['size']);
			}
		}

		return $this->prototypeModel;
	}
}

