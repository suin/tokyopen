<?php
/**
 * 一つのレコードの編集にインタフェースを特化したフォームコントローラ
 */
/*
	最も小さな実装方法

	class Blog_Controller_BlogEdit extends Pengin_Controller_AbstractThreeStepSimpleForm
	{
		protected $useModels = array('Entry'); // 使用するモデルを定義
	
		protected function _getModelHandler()
		{
			return $this->entryHandler; // モデルハンドラー
		}
	
		protected function _getForm()
		{
			return new Profile_Form_Entry(); // フォーム
		}
	}
*/
/*
	URLに注意してください
	最低限 id を GETパラメータ として渡す必要があります。（もちろん、オーバライドすれば換装可能）
*/

abstract class Pengin_Controller_AbstractThreeStepSimpleForm extends Pengin_Controller_AbstractThreeStepForm
{
	protected $idKey        = 'id';
	protected $id           = null; // モデルのプライマリーキー
	protected $model        = null; // メインのモデル
	protected $form         = null; // フォーム
	protected $modelHandler = null; // メインのモデルハンドラー

	protected $isNew = true; // 新規登録か否か
	                         // true: 新規登録
	                         // false: 編集
	                         // Pengin_Model_AbstractModel::isNew()は保存時に必ずfalseになるので
	                         // 新旧を判定するためにはこれが必要。

	/**
	 * メインのモデルハンドラーを返す.
	 * 
	 * @access protected
	 * @abstract
	 * @return Pengin_Model_AbstractHandlerのサブクラス
	 * @note サブクラス用インタフェース
	 */
	abstract protected function _getModelHandler();

	/**
	 * フォームオブジェクトを返す.
	 * 
	 * @access protected
	 * @abstract
	 * @return Pengin_Fromのサブクラス
	 * @note サブクラス用インタフェース
	 */
	abstract protected function _getForm();

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

		if ( is_object($this->model) === false ) {
			$this->root->redirect("Page not found.", $this->_getReturnUri());
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
		$this->form = $this->_getForm();

		if ( $this->model->isNew() === false ) {
			$this->form->useModelData($this->model);
			$this->isNew = false;
		}
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
		$this->form->updateModel($this->model);

		if ( $this->modelHandler->save($this->model) === false ) {
			throw new Exception(t("Failed to save."));
		}
	}
}
