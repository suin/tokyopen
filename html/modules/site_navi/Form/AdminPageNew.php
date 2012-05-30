<?php

class SiteNavi_Form_AdminPageNew extends Pengin_Form
{
	public function setUpForm()
	{
		$this->title(t("New Page"));
		$this->name('SiteNavi_Form_AdminPageNew');
	}

	public function setUpProperties()
	{
		$this->add('title', 'Text')
			->label("Title")
			->required()
			->longest(255);

		$typeOptions = $this->_getTypeOptions();

		$this->add('type', 'Radio')
			->label("Type")
			->options($typeOptions)
			->value('nano')
			->required();

		$this->add('private_flag', 'RadioYesNo')
			->label("PrivateFlag")
			->required();

		$this->add('invisible_in_menu_flag', 'RadioYesNo')
			->label("InvisibleInMenuFlag")
			->required();
	}

	protected function _getTypeOptions()
	{
		$mediator = new SiteNavi_Library_AdhocMediator();
		$modules = $mediator->getSupportModules();

		$options = array();

		foreach ( $modules as $module => $class ) {
			$api = new $class();
			$title = $api->getTitle();
			$options[$module] = $title;
		}

		return $options;
	}
}
