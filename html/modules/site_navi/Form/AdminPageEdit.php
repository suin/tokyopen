<?php

class SiteNavi_Form_AdminPageEdit extends Pengin_Form
{
	public function setUpForm()
	{
		$this->title(t("Edit Page"));
		$this->name('SiteNavi_Form_AdminPageEdit');
	}

	public function setUpProperties()
	{

		$this->add('private_flag', 'RadioYesNo')
			->label("PrivateFlag")
			->required();

		$this->add('invisible_in_menu_flag', 'RadioYesNo')
			->label("InvisibleInMenuFlag")
			->required();
	}

}
