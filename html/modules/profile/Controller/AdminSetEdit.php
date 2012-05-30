<?php
class Profile_Controller_AdminSetEdit extends Pengin_Controller_AbstractThreeStepSimpleForm
{
	protected $useModels = array('Set');

	protected $setId    = null;
	protected $setModel = null;

	protected function _getModelHandler()
	{
		return $this->setHandler;
	}

	protected function _getForm()
	{
		return new Profile_Form_AdminSet();
	}
}
