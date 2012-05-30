<?php
/**
 * 削除ページ画面コントローラ
 */

abstract class Pengin_Controller_AbstractDeleteForm extends Pengin_Controller_Abstract
{
	protected $errors = array();
	protected $token  = null;

	/**
	 * main function.
	 * 
	 * @access public
	 * @return void
	 */
	public function main()
	{
		try {

			if ( $this->post('delete') ) {
				$this->_deleteAction();
			} elseif ( $this->post('cancel') ) {
				$this->_cancelAction();
			}

		} catch ( Pengin_Form_InvalidTransactionException $e ) {
			// おかしなページ遷移のとき
			$this->_addError(t("Invalid page transaction."));
		}

		$this->_confirmAction();
	}

	/**
	 * データを削除する.
	 * 
	 * @access protected
	 * @abstract
	 * @return void
	 * @note 下位クラス用インタフェース(実装必須)
	 */
	abstract protected function _deleteData();

	/**
	 * 戻り先URIを返す.
	 * 
	 * @access protected
	 * @abstract
	 * @return string 戻り先URI
	 * @note 下位クラス用インタフェース(実装必須)
	 */
	abstract protected function _getReturnUri();

	/**
	 * 確認.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _confirmAction()
	{
		$this->_useDeleteConfirmTemplate();
		$this->_issueToken();
		$this->_bindOutput();
		$this->_view();
	}

	/**
	 * キャンセル.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _cancelAction()
	{
		$this->root->location($this->_getReturnUri());
	}

	/**
	 * 削除確認画面用テンプレートを使う.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _useDeleteConfirmTemplate()
	{
		$this->template = 'pen:pengin.form_delete.tpl';
	}

	/**
	 * ワンタイムトークンを発行する.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _issueToken()
	{
		$this->token = Pengin_Token::issue();
	}

	/**
	 * 出力用の変数をバインドする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _bindOutput()
	{
		$this->output['errors'] = $this->errors;
		$this->output['token']  = $this->token;
	}

	/**
	 * 削除.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _deleteAction()
	{
		$this->_validateToken();

		try {
			$this->_beforeBeginTransaction();
			$this->_beginTransaction();
			$this->_afterBeginTransaction();

			$this->_beforeDeleteData();
			$this->_deleteData();
			$this->_afterDeleteData();

			$this->_beforeCommit();
			$this->_commit();
			$this->_afterCommit();

			$this->_afterTransaction();
		} catch ( Exception $e ) {
			$this->_addError($e->getMessage());
			$this->_beforeRollback();
			$this->_rollback();
			$this->_afterRollback();
		}
	}

	/**
	 * ワンタイムトークンのチェック.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _validateToken()
	{
		$stub = $this->post('token');

		if ( Pengin_Token::check($stub) === false ) {
			throw new Pengin_Form_InvalidTransactionException("Invalid transaction.");
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
	 * _beforeDeleteData function.
	 * 
	 * @access protected
	 * @return void
	 * @note 下位クラス用インタフェース(実装任意)
	 */
	protected function _beforeDeleteData()
	{
	}

	/**
	 * _afterDeleteData function.
	 * 
	 * @access protected
	 * @return void
	 * @note 下位クラス用インタフェース(実装任意)
	 */
	protected function _afterDeleteData()
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
	 * フォームのトランザクション終了時の処理用メソッド.
	 * 
	 * @access protected
	 * @return void
	 * @note データベースのトランザクションではない
	 */
	protected function _afterTransaction()
	{
		$this->root->redirect(t("Sucessfully deleted."), $this->_getReturnUri());
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

	/**
	 * エラーメッセージを追加する.
	 * 
	 * @access protected
	 * @param mixed $message
	 * @return void
	 */
	protected function _addError($message)
	{
		$this->errors[] = $message;
	}
}
