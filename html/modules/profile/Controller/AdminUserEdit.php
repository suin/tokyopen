<?php
class Profile_Controller_AdminUserEdit extends Pengin_Controller_AbstractThreeStepForm
{
	protected $useModels = array('Property', 'Set', 'SetPropertyLink', 'User');
	protected $setId = null;
	protected $userId = null;
	protected $userModel = null;

	public function __construct()
	{
		parent::__construct();
		$this->moduleName = $this->root->cms->getThisModuleName();
		$this->output['module_name'] = $this->moduleName;
	}

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
		$this->userId = $this->get('user_id');

		$this->userModel = $this->userHandler->load($this->userId);

		if ( $this->userModel === false or $this->userModel->isNew() === true ) {
			$this->root->redirect("User not found.", 'user_list');
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
		$this->form->title(t("Edit {1}'s profile", $this->userModel->get('uname')));
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

	/**
	 * 入力画面のテンプレートを使うようにする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _useInputTemplate()
	{
		$this->template = 'pen:'.$this->dirname.'.'.$this->controller.'.input.tpl';
	}

	/**
	 * 確認画面のテンプレートを使うようにする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _useConfirmTemplate()
	{
		$this->template = 'pen:'.$this->dirname.'.'.$this->controller.'.confirm.tpl';
	}
}
