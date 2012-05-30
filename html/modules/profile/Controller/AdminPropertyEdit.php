<?php
class Profile_Controller_AdminPropertyEdit extends Pengin_Controller_AbstractThreeStepSimpleForm
{
	protected $useModels = array('Property', 'SetPropertyLink', 'User');

	protected function _getModelHandler()
	{
		return $this->propertyHandler;
	}

	protected function _getForm()
	{
		return new Profile_Form_AdminProperty();
	}

	protected function _setUpForm()
	{
		parent::_setUpForm();

		if ( $this->isNew === false ) {
			// 編集のときは name を変更させない、表示しない
			$this->form->remove('name');
		}

		$this->form->useDataForSets($this->id);
	}

	protected function _getReturnUri()
	{
		return $this->root->url('property_list');
	}

	protected function _afterUpdateData()
	{
		$this->_linkSetAndProperty();
		$this->_modifyUserTable();
	}

	/**
	 * setとpropertyをリンクする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _linkSetAndProperty()
	{
		$sets = $this->form->property('sets')->getValue();
		$this->setPropertyLinkHandler->addLinks($this->model->get('id'), $sets);
	}

	/**
	 * usersテーブルのカラムを書き換える.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _modifyUserTable()
	{
		if ( $this->isNew === false ) {
			return;
		}

		$name = $this->form->property('name')->getValue();
		$type = $this->form->property('type')->getValue();

		$isSuccess = $this->userHandler->addProperty($name, $type);

		if ( $isSuccess === false ) {
			throw new Exception('Failed to modify table.');
		}
	}
}
