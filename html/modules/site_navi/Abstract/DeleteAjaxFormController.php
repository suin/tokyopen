<?php

abstract class SiteNavi_Abstract_DeleteAjaxFormController extends Pengin_Controller_AbstractDeleteSimpleForm
{
	protected $result = array(
		'title' => '', // フォームタイトル
		'error' => 0, // エラーの有無, 1:あり, 0:なし
		'html'  => '', // 内容
		'end'   => 0, // フォームの処理が終了したかどうか, 1:終了している, 0:続いている
		'data'  => array(), // 削除したデータが入る
	);

	/**
	 * 削除確認画面用テンプレートを使う.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _useDeleteConfirmTemplate()
	{
		$this->template = 'pen:'.$this->dirname.'.ajax_form_delete.tpl';
	}

	/**
	 * 出力用の変数をバインドする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _bindOutput()
	{
		parent::_bindOutput();
		$this->output['formAction'] = $this->root->getUrl();
		$this->output['formId']     = get_class($this);
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
			$this->result['html'] = t("Sucessfully deleted.");
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
			$this->result['title'] = $this->pageTitle;
		}

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($this->result);
		die;
	}
}