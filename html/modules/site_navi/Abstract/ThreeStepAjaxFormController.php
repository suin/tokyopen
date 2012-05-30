<?php

abstract class SiteNavi_Abstract_ThreeStepAjaxFormController extends Pengin_Controller_AbstractThreeStepSimpleForm
{
	protected $result = array(
		'title' => '', // フォームタイトル
		'error' => 0, // エラーの有無, 1:あり, 0:なし
		'html'  => '', // 内容
		'end'   => 0, // フォームの処理が終了したかどうか, 1:終了している, 0:続いている
		'data'  => array(), // 保存されたデータ
	);

	/**
	 * 入力画面のテンプレートを使うようにする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _useInputTemplate()
	{
		$this->template = 'pen:'.$this->dirname.'.ajax_form_input.tpl';
	}

	/**
	 * 確認画面のテンプレートを使うようにする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _useConfirmTemplate()
	{
		$this->template = 'pen:'.$this->dirname.'.ajax_form_confirm.tpl';
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
		parent::_setUpForm();
		$this->form->action($this->root->getUrl());
	}

	/**
	 * エラーを返して終了する.
	 * 
	 * @access protected
	 * @param string $message
	 * @return void
	 */
	protected function _error($message)
	{
		if ( $this->get('ajaxmode') == 1 ) {
			$this->result['error'] = 1;
			$this->result['html']  = $message;
			$this->_view();
			die;
		} else {
			$this->root->redirect($message, $this->root->url());
		}
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
		if ( $this->get('ajaxmode') == 1 ) {
			$this->result['html'] = t("Sucessfully updated.");
			$this->result['end']  = 1;
			$this->result['data'] = $this->model->getVars();
		} else {
			parent::_afterTransaction();
		}
	}

	protected function _view()
	{
		if ( $this->get('ajaxmode') == 1 ) {
			$this->_viewAjax();
		} else {
			parent::_view();
		}
	}

	protected function _viewAjax()
	{
		if ( $this->result['html'] == '' ) {
			ob_start();
			$view = Pengin_View::getView('Smarty');
			$view->render($this->template, $this->output);
			$this->result['html'] = ob_get_clean();
		}

		if ( $this->result['title'] == '' ) {
			$this->result['title'] = $this->form->getTitle();
		}

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($this->result);
		die;
	}
}