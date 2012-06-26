<?php

class Mailform_Controller_FormEdit extends Pengin_Controller_AbstractThreeStepSimpleForm
{
	protected $useModels = array('Form'); // 使用するモデルを定義

	/**
	 * main function.
	 */
	public function main()
	{
		$this->_checkPermission();
		parent::main();
		$this->_adminTaskBar();
	}

	/**
	 * 権限チェック
	 */
	protected function _checkPermission()
	{
		if ( $this->root->cms->isAdmin() === false ) {
			$this->root->redirect("Permission denied.", $this->root->cms->url);
		}
	}

	/**
	 * モデルセットをセットアップする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _setUpModel()
	{
		parent::_setUpModel();

		if ( $this->model->isNew() === true ) {
			$this->root->redirect("Page not found.", $this->root->cms->url);
		} 
	}

	/**
	 * モデルハンドラーを返す.
	 * @return Mailform_Model_FormHandler
	 */
	protected function _getModelHandler()
	{
		return $this->formHandler; // モデルハンドラー
	}

	/**
	 * フォームオブジェクトを返す.
	 * @return Mailform_Form_Form
	 */
	protected function _getForm()
	{
		return new Mailform_Form_Form(); // フォーム
	}

	/**
	 * 戻り先URIを返す.
	 * @return string 戻り先URI
	 */
	protected function _getReturnUri()
	{
		return $this->url.'/index.php?id='.$this->id;
	}
	
	/**
	 * NiceAdmin タスクバー 連携
	 */
	protected function _adminTaskBar()
	{
		$root = XCube_Root::getSingleton();
		$adminTaskBar = $root->mAdminTaskBar;

		if ( is_object($adminTaskBar) === true ) {
			// adminメニューバー第一階層への表示（モジュール名＋管理）
			$adminTaskBar->addLink('MailformAdmin',  t('Mailform') , '' , 1);

			$url = $this->root->url('form_edit', null, array('id' => $this->id));
			//　パラメータ1　モジュール名（1文字目大文字）＋Admin
			//　パラメータ2　表示方法＋サブのid。表示方法　tpModal：モーダルで出す。tpNoModal:メインに出す。
			//　パラメータ3　サブメニューに表示する名前
			//　パラメータ4　クリックしたときに表示するurl
			$adminTaskBar->addSubLink('MailformAdmin','tpNoModalMailformFormEdit', t("Form Preference"), $url);


			$url = $this->root->url('field_edit', null, array('id' => $this->id));
			//　パラメータ1　モジュール名（1文字目大文字）＋Admin
			//　パラメータ2　表示方法＋サブのid。表示方法　tpModal：モーダルで出す。tpNoModal:メインに出す。
			//　パラメータ3　サブメニューに表示する名前
			//　パラメータ4　クリックしたときに表示するurl
			$adminTaskBar->addSubLink('MailformAdmin','tpNoModalMailformFieldEdit', t("Screen Preference"), $url);
		}
	}

}
