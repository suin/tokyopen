<?php

class SiteNavi_Form_AdminModuleEdit extends Pengin_Form
{
	public function setUpForm()
	{
		$this->title(t("Place Module"));
		$this->name('SiteNavi_Form_AdminModuleEdit');
	}

	public function setUpProperties()
	{
		$moduleIdOptions = $this->getModuleIdOptions();

		$this->add('module_id', 'Radio')
			->label("Module")
			->options($moduleIdOptions)
			->required();
			
		$this->add('private_flag', 'RadioYesNo')
			->label("PrivateFlag")
			->required();

		$this->add('invisible_in_menu_flag', 'RadioYesNo')
			->label("InvisibleInMenuFlag")
			->required();
	}

	public function getModuleIdOptions()
	{
		static $moduleIds = null;

		if ( $moduleIds === null ) {
			$moduleIds = $this->_getModuleIdOptions();
		}

		return $moduleIds;
	}

	protected function _getModuleIdOptions()
	{
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('isactive', 1));
		$criteria->add(new Criteria('hasmain', 1));

		$moduleHandler = xoops_gethandler('module');
		$moduleObjects = $moduleHandler->getObjects($criteria);

		$moduleIds = array();

		foreach ( $moduleObjects as $moduleObject ) {
			$moduleId   = $moduleObject->get('mid');
			$moduleName = $moduleObject->get('name');

			$apiFile = TP_MODULE_PATH.'/'.$moduleObject->get('dirname').'/API/SiteNavi.php';

			if ( file_exists($apiFile) === true ) {
				continue;
			}

			$moduleIds[$moduleId] = $moduleName;
		}

		$moduleIds = $this->_filterModuleIdOptions($moduleIds);

		return $moduleIds;
	}

	protected function _filterModuleIdOptions(array $modules)
	{
		$routeHandler = Pengin::getInstance()->getModelHandler('Route');
		$placedModuleIds = $routeHandler->getPlacedModules();

		foreach ( $modules as $moduleId => $v ) {
			if ( in_array($moduleId, $placedModuleIds) === true ) {
				unset($modules[$moduleId]);
			}
			
			
		}

		return $modules;
	}
}
