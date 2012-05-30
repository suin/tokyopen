<?php
class Profile_Controller_ProfileEdit extends Pengin_Controller_AbstractThreeStepForm
{
	protected $useModels = array('Property', 'Set', 'SetPropertyLink', 'User');
	protected $setId = null;
	protected $userModel = null;

	/**
	 * _setUpModel function.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _setUpModel()
	{
		$this->_setUpSetId();
		$this->_setUpUserModel();
	}

	/**
	 * _setUpSetId function.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _setUpSetId()
	{
		$this->setId = $this->get('id');

		if ( $this->setHandler->exists($this->setId) == false ) {
			$this->root->redirect("Not Found", $this->root->cms->url);
		}
	}

	/**
	 * _setUpUserModel function.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _setUpUserModel()
	{
		$this->userModel = $this->userHandler->load($this->root->cms->getUserId());

		if ( $this->userModel === false ) {
			$this->root->redirect("Please login", $this->root->cms->url);
		}
	}

	/**
	 * フォームをセットアップする.
	 * 
	 * @access protected
	 * @abstract
	 * @return void
	 */
	protected function _setUpForm()
	{
		$this->form = new Profile_Form_Profile($this->setId);
		$this->form->useModelData($this->userModel);
	}

	/**
	 * データを更新する.
	 * 
	 * @access protected
	 * @abstract
	 * @return void
	 */
	protected function _updateData()
	{
		$this->form->updateModel($this->userModel);

		if ( $this->userHandler->save($this->userModel) === false ) {
			throw new RuntimeException("Failed to save.");
		}
	}

	/**
	 * 戻り先URIを返す.
	 * 
	 * @access protected
	 * @abstract
	 * @return string 戻り先URI
	 */
	protected function _getReturnUri()
	{
		return $this->root->url(null, null);
	}
}
