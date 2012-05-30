<?php
/**
 * 入力・確認・送信の3ステップに特化したフォームコントローラ
 */

abstract class Pengin_Controller_AbstractThreeStepForm extends Pengin_Controller_Abstract
{
	protected $form = null;

	public function __construct()
	{
		parent::__construct();
		$this->_setUp();
	}

	/**
	 * main function.
	 * 
	 * @access public
	 * @return void
	 */
	public function main()
	{
		$this->_useInputTemplate();

		try {

			if ( $this->post('confirm') ) {
				$this->_confirmAction();
			} elseif ( $this->post('submit') ) {
				$this->_submitAction();
			} elseif ( $this->post('back') ) {
				$this->_backAction();
			}

		} catch ( Pengin_Form_InvalidTransactionException $e ) {
			// おかしなページ遷移のとき
			$this->form->addError(t("Data not found."));
		}

		$this->_inputAction();
	}

	/**
	 * フォームをセットアップする.
	 * 
	 * @access protected
	 * @abstract
	 * @return void
	 * @note 下位クラス用インタフェース(実装必須)
	 */
	abstract protected function _setUpForm();

	/**
	 * データを更新する.
	 * 
	 * @access protected
	 * @abstract
	 * @return void
	 * @note 下位クラス用インタフェース(実装必須)
	 */
	abstract protected function _updateData();

	/**
	 * クラスのメンバ変数をセットアップする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _setUp()
	{
		$this->_setUpModel();
		$this->_setUpForm();
	}

	/**
	 * モデルをセットアップする
	 * 
	 * @access protected
	 * @return void
	 * @note 下位クラス用インタフェース(実装任意)
	 */
	protected function _setUpModel()
	{
	}

	/**
	 * 入力.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _inputAction()
	{
		$this->_beginFromTransaction();
		$this->_bindOutput();
		$this->_view();
	}

	/**
	 * フォームのトランザクションを開始する.
	 * 
	 * @access protected
	 * @return void
	 * @note データベースのトランザクションではない
	 */
	protected function _beginFromTransaction()
	{
		if ( $_SERVER['REQUEST_METHOD'] !== 'GET' ) {
			return; // GET以外のときはトークを新規発行しない
		}

		$this->form->beginTransaction();
	}

	/**
	 * 出力用の変数をバインドする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _bindOutput()
	{
		$this->output['form'] = $this->form;
	}

	/**
	 * 確認.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _confirmAction()
	{
		$this->form->fetchInput()->validate();

		if ( $this->form->hasError() === true ) {
			return;
		}

		$this->form->preserveInput();
		$this->_useConfirmTemplate();
	}

	/**
	 * 入力画面のテンプレートを使うようにする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _useInputTemplate()
	{
		$this->template = 'pen:pengin.form_input.tpl';
	}

	/**
	 * 確認画面のテンプレートを使うようにする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _useConfirmTemplate()
	{
		$this->template = 'pen:pengin.form_confirm.tpl';
	}

	/**
	 * 戻るボタンを押したときの処理.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _backAction()
	{
		$this->form->usePreservedInput();
	}

	/**
	 * 送信.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _submitAction()
	{
		$this->form->usePreservedInput();

		try {
			$this->_beforeBeginTransaction();
			$this->_beginTransaction();
			$this->_afterBeginTransaction();

			$this->_beforeUpdateData();
			$this->_updateData();
			$this->_afterUpdateData();

			$this->_beforeCommit();
			$this->_commit();
			$this->_afterCommit();

			$this->_endFormTransaction();
			$this->_afterTransaction();
		} catch ( Exception $e ) {
			$this->form->addError($e->getMessage());

			$this->_beforeRollback();
			$this->_rollback();
			$this->_afterRollback();
		}
	}


	/**
	 * _beforeBeginTransaction function.
	 * 
	 * @access protected
	 * @return void
	 * @note 下位クラス用インタフェース(実装任意)
	 */
	protected function _beforeBeginTransaction()
	{
	}

	/**
	 * データベースのトランザクションを開始する.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _beginTransaction()
	{
		$this->root->cms->database()->queryF('BEGIN');
	}

	/**
	 * _afterBeginTransaction function.
	 * 
	 * @access protected
	 * @return void
	 * @note 下位クラス用インタフェース(実装任意)
	 */
	protected function _afterBeginTransaction()
	{
	}

	/**
	 * _beforeUpdateData function.
	 * 
	 * @access protected
	 * @return void
	 * @note 下位クラス用インタフェース(実装任意)
	 */
	protected function _beforeUpdateData()
	{
	}

	/**
	 * _afterUpdateData function.
	 * 
	 * @access protected
	 * @return void
	 * @note 下位クラス用インタフェース(実装任意)
	 */
	protected function _afterUpdateData()
	{
	}

	/**
	 * _beforeCommit function.
	 * 
	 * @access protected
	 * @return void
	 * @note 下位クラス用インタフェース(実装任意)
	 */
	protected function _beforeCommit()
	{
	}

	/**
	 * コミットする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _commit()
	{
		$this->root->cms->database()->queryF('COMMIT');
	}

	/**
	 * _afterCommit function.
	 * 
	 * @access protected
	 * @return void
	 * @note 下位クラス用インタフェース(実装任意)
	 */
	protected function _afterCommit()
	{
	}

	/**
	 * フォームのトランザクションを終了する.
	 * 
	 * @access protected
	 * @return void
	 * @note データベースのトランザクションではない
	 */
	protected function _endFormTransaction()
	{
		$this->form->endTransaction();
		$this->form->setUpProperties(); // リセット
	}

	/**
	 * フォームのトランザクション終了時の処理用メソッド.
	 * 
	 * @access protected
	 * @return void
	 * @note データベースのトランザクションではない
	 */
	protected function _afterTransaction()
	{
		$this->root->redirect(t("Sucessfully updated."), $this->_getReturnUri());
	}

	/**
	 * 戻り先URIを返す.
	 * 
	 * @access protected
	 * @return string 戻り先URI
	 * @note 下位クラス用インタフェース(実装任意)
	 *       リダイレクト画面で使われます
	 */
	protected function _getReturnUri()
	{
		return $this->root->url($this->controller, null);
	}

	/**
	 * _beforeRollback function.
	 * 
	 * @access protected
	 * @return void
	 * @note 下位クラス用インタフェース(実装任意)
	 */
	protected function _beforeRollback()
	{
	}

	/**
	 * ロールバックする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _rollback()
	{
		$this->root->cms->database()->queryF('ROLLBACK');
	}

	/**
	 * _afterRollback function.
	 * 
	 * @access protected
	 * @return void
	 * @note 下位クラス用インタフェース(実装任意)
	 */
	protected function _afterRollback()
	{
	}
}
