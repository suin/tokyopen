<?php
abstract class Pengin_Controller_AbstractDeleteSimpleForm extends Pengin_Controller_AbstractDeleteForm
{
	protected $idKey        = 'id';
	protected $id           = null; // モデルのプライマリーキー
	protected $model        = null; // メインのモデル
	protected $modelHandler = null; // メインのモデルハンドラー

	public function __construct()
	{
		parent::__construct();
		$this->_setUp();
	}

	/**
	 * メインのモデルハンドラーを返す.
	 * 
	 * @access protected
	 * @abstract
	 * @return Pengin_Model_AbstractHandlerのサブクラス
	 * @note サブクラス用インタフェース
	 */
	abstract protected function _getModelHandler();

	protected function _setUp()
	{
		$this->_setUpModel();
	}

	/**
	 * モデルセットをセットアップする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _setUpModel()
	{
		$this->id           = $this->get($this->idKey, null);
		$this->modelHandler = $this->_getModelHandler();
		$this->model        = $this->modelHandler->load($this->id);

		if ( is_object($this->model) === false or $this->model->isNew() === true ) {
			$this->root->redirect("Page not found.");
		}
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
		$this->output['model']  = $this->model; // これはオブジェクトなのでエスケープされないことに注意してください。
		$this->output['data']   = $this->model->getVars();
	}

	/**
	 * データを削除する.
	 * 
	 * @access protected
	 * @abstract
	 * @return void
	 */
	protected function _deleteData()
	{
		if ( $this->modelHandler->delete($this->id) === false ) {
			throw new Exception(t("Failed to delete."));
		}
	}
}
