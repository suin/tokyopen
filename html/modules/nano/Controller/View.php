<?php
class Nano_Controller_View extends Nano_Abstract_Controller
{
	protected $id = null;

	public function main()
	{
		$this->_viewAction();
	}

	protected function _viewAction()
	{
		$this->id = $this->get('id');

		// テーブルを見る
		$contentsHandler = $this->root->getModelHandler('Contents');
		$contentsModel = $contentsHandler->load($this->id);

		if ( is_object($contentsModel) === false or $contentsModel->isNew() === true ) {
			$this->root->redirect("no contents here", $this->root->cms->url);
		}

		$this->pageTitle = $contentsModel->get('title');

		$this->output['content'] = $contentsModel;
		$this->_view();

		$this->_adminTaskBar();
	}

	protected function _adminTaskBar()
	{
		$root = XCube_Root::getSingleton();
		$adminTaskBar = $root->mAdminTaskBar;

		if ( is_object($adminTaskBar) === true ) {
			$url = $this->root->url('edit', null, array('id' => $this->id));
			// adminメニューバー第一階層への表示（モジュール名＋管理）
			$root->mAdminTaskBar->addLink('NanoAdmin',  t('Nano') , '' , 1);
			//　パラメータ1　モジュール名（1文字目大文字）＋Admin
			//　パラメータ2　表示方法＋サブのid。表示方法　tpModal：モーダルで出す。tpNoModal:メインに出す。
			//　パラメータ3　サブメニューに表示する名前
			//　パラメータ4　クリックしたときに表示するurl
			$root->mAdminTaskBar->addSubLink('NanoAdmin','tpNoModalNanoEdit', t("Edit Contents"), $url);
		}
	}
}
