<?php
class Nano_Controller_Edit extends Nano_Abstract_Controller
{
	protected $input  = array();
	protected $errors = array();

	protected $contentsHandler = null;
	protected $contentsModel   = null;

	public function __construct()
	{
		parent::__construct();
		$this->_fetchId();
		$this->_getContentsModelHandler();
		$this->_getContentsModel();
		$this->_initInput();
		$this->_setUpPageTitle();
	}

	public function main()
	{
		$this->_checkPermission();

		if ( $this->post('save') ) {
			$this->_save();
		}

		$this->_default();
	}

	protected function _checkPermission()
	{
		if ( $this->_isAdmin() === false ) {
			$this->root->redirect('You do not have management authority', $this->root->cms->url);
		}
	}

	protected function _default()
	{
		$this->output['errors'] = $this->errors;
		$this->output['input']  = $this->input;
		$this->output['token']  = Pengin_Token::issue(10800); // 3 hours
		$this->_view();
		$this->_adminTaskBar();
	}

	protected function _save()
	{
		$this->_fetchInput();
		$this->_validate();

		if ( $this->_isError() === true ) {
			return;
		}

		try {
			$this->_beginTransaction();
			$this->_saveContents();
			$this->_notifyUpdateToSiteNavi();
			$this->_commitTransaction();
		} catch ( Exception $e ) {
			$this->_rollBackTransaction();
			return;
		}

		$this->_redirect();
	}

	protected function _fetchId()
	{
		$this->id = (int) $this->get('id');

		if ( $this->id < 1 ) {
			$this->root->redirect('no contents here', $this->root->cms->url);
		}
	}

	protected function _getContentsModelHandler()
	{
		$this->contentsHandler = $this->root->getModelHandler('Contents');
	}

	protected function _getContentsModel()
	{
		$this->contentsModel = $this->contentsHandler->load($this->id);

		if ( $this->contentsModel === false or $this->contentsModel->isNew() === true ) {
			$this->root->redirect('no contents here', $this->root->cms->url);
		}
	}

	/**
	 * 入力値の初期化
	 */
	protected function _initInput()
	{
		$this->input = array(
			'title'   => $this->contentsModel->getVar('title'),
			'content' => $this->contentsModel->getVar('content'),
		);
	}

	protected function _setUpPageTitle()
	{
		$this->pageTitle = t("Edit");
	}

	/**
	 * 入力値を取得する
	 */
	protected function _fetchInput()
	{
		foreach ( $this->input as $name => $value )
		{
			$this->input[$name] = $this->post($name);
		}
	}

	protected function _validate()
	{
		$this->_validateToken();
		$this->_validateTitle();
		$this->_validateContent();
	}

	protected function _validateToken()
	{
		$token = $this->post('token');

		if ( Pengin_Token::check($token) === false ) {
			$this->_addError("ERROR: Please confirm the from and submit again.");
		}
	}

	protected function _validateTitle()
	{
		if ( isset($this->input['title'][0]) === false ) {
			$this->_addError(t("{1} is required", t('Title')));
		}

		if ( isset($this->input['title'][255]) ) {
			$this->_addError(t("{1} is too long", t('Title')));
		}
	}

	protected function _validateContent()
	{
		if ( isset($this->input['content'][0]) === false ) {
			$this->_addError(t("{1} is required", t('Content')));
		}
	}

	protected function _beginTransaction()
	{
		$this->root->cms->database()->queryF('BEGIN');
	}

	protected function _commitTransaction()
	{
		$this->root->cms->database()->queryF('COMMIT');
	}

	protected function _rollBackTransaction()
	{
		$this->root->cms->database()->queryF('ROLLBACK');
	}

	/**
	 * コンテンツを保存する
	 */
	protected function _saveContents()
	{
		$this->contentsModel->setVar('title', $this->input['title']);
		$this->contentsModel->setVar('content', $this->input['content']);

		$isSaved = $this->contentsHandler->save($this->contentsModel);

		if ( $isSaved === false ) {
			$this->_addError("Failed to add new contents");
			throw new Exception();
		}
	}

	protected function _notifyUpdateToSiteNavi()
	{
		// TODO >> この部分は モジュールメディエイター を作って移植する
		// refs #7403
		$pengin = Pengin::getInstance();
		$pengin->path(TP_MODULE_PATH.'/site_navi');
		
		if ( class_exists('SiteNavi_API_Page') === false ) {
			return;
		}

		$contentId = sprintf('/nano/%u/', $this->contentsModel->get('id'));
		$title     = $this->contentsModel->get('title');

		$page = new SiteNavi_API_Page();
		$isSuccess = $page->updateTitle($contentId, $title);
		return $isSuccess;
	}

	/**
	 * 保存成功時のリダイレクト
	 */
	protected function _redirect()
	{
		$this->root->redirect('Added contents successfully', 'view', null, array('id' => $this->contentsModel->get('id')));
	}

	protected function _addError($message)
	{
		$this->errors[] = t($message);
	}

	/**
	 * エラーかどうか
	 * @return bool
	 */
	protected function _isError()
	{
		return ( count($this->errors) > 0 );
	}
	
	protected function _isAdmin()
	{
		$moduleId = $this->root->cms->getThisModuleId();
		return $this->root->cms->isAdmin($moduleId);
	}

	/**
	 * NiceAdmin タスクバー 連携
	 */
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
			$root->mAdminTaskBar->addSubLink('NanoAdmin','tpNoModalNanoSave', t("Save Contents"), $url);
		}
	}
}
