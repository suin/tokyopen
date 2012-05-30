<?php
class Flipr_Model_ConfigHandler extends Pengin_Model_AbstractHandler
{

	protected $configs = array();

	public function __construct()
	{
		parent::__construct();

		$this->configs = $this->_getConfigs();
	}

	public function load($id = null)
	{
		$model = parent::load($id);
		$this->_setData($model);

		return $model;
	}

	public function find($criteria = null, $orderby = null, $sort = 'ASC', $limit = 0, $start = 0)
	{
		$models = parent::find($criteria, $orderby, $sort, $limit, $start);

		if ( is_array($models) )
		{
			foreach ( $models as &$model )
			{
				$this->_setData($model);
			}
		}

		return $models;
	}


	public function loadConfigs()
	{
		$configNames = array_keys($this->configs);

		$criteria = new Pengin_Criteria;
		$criteria->add('name', 'IN', $configNames);

		return $this->find($criteria);
	}

	public function loadByName($name)
	{
		$name = mysql_real_escape_string($name);
		$sql = "SELECT * FROM `%s` WHERE `name` = '%s'";
		$sql = sprintf($sql, $this->table, $name);

		$result = $this->_query($sql);

		$vars = $this->db->fetchArray($result);
		$obj = $this->create();
		$obj->setVar('name', $name);

		if ( is_array($vars) )
		{
			$obj->unsetNew();
			$obj->setVars($vars);
		}

		return $obj;
	}

	public function getConfigs()
	{
		$configModels = $this->find();
		$configs = array();

		foreach ( $configModels as $configModel )
		{
			$name  = $configModel->getVar('name');
			$value = $configModel->getVar('value');
			$configs[$name] = $value;
		}
		
		unset($configModels, $configModel);

		return $configs;
	}

	protected function _getConfigs()
	{
		$root =& Pengin::getInstance();

		require $root->cms->getThisModulePath().DS.'config.php';

		if ( !isset($configs) or !is_array($configs) )
		{
			return array();
		}

		$newConfigs = array();

		foreach ( $configs as $k => $config )
		{
			if ( !isset($config['name']) or !strlen($config['name']) )
			{
				continue;
			}
			
			$newConfigs[$config['name']] = $config;
		}

		return $newConfigs;
	}

	protected function _setData(&$model)
	{
		$name = $model->getVar('name');

		if ( isset($this->configs[$name]) )
		{
			$model->setData($this->configs[$name]);
		}
	}
}
