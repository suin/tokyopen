<?php

class SiteNavi_Form_AdminExternalLinkEdit extends Pengin_Form
{
	public function setUpForm()
	{
		$this->title("Edit External Link");
		$this->name('SiteNavi_Form_AdminExternalLinkEdit');
	}

	public function setUpProperties()
	{
		$this->add('title', 'Text')
			->label("Title")
			->required()
			->longest(255);

		$this->add('url', 'Text')
			->label("URL")
			->value('http://')
			->required()
			->longest(256);
	}
}
