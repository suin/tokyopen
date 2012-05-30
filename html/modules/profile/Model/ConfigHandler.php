<?php
class Profile_Model_ConfigHandler extends Pengin_Model_AbstractHandler
{
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
}
