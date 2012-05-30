<?php

class Footer_Controller_AdminMenuList extends Footer_Abstract_Controller
{
	protected $useModels = array('Menu');
	protected $menuHandler,$menuModel;
	
	protected $errors = array();
	protected $menuList = array();
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function main()
	{
		if(isset($_GET['update']) == true) {
			$this->_updateAction();
		}
		$this->_default();
	}

	protected function _default()
	{
		$criteria = new Pengin_Criteria();

		if(isset($_GET['update']) == false) {
			// 初期画面の時　テーブルを見る
			$this->menuHandler = $this->root->getModelHandler('Menu');
			$this->menuModel = $this->menuHandler->find($criteria, 'weight');
			$i = 0;
			foreach($this->menuModel as $menu){
				$this->menuList[$i]['title'] = $menu->get('title');
				$this->menuList[$i]['url'] = $menu->get('url');
				$i++;
			}
			//pen_dump($this->menuModel);
		//	pen_dump($this->menuList);exit;
		} else {
			// 更新の時
		}
		$this->output['menuList'] = $this->menuList;
		$this->output['has_error'] = $this->_hasError();
		$this->output['errors'] = $this->errors;
		$this->_view();
	}
	
	protected function _updateAction()
	{
		// 入力値チェック
		$this->_formValidate();
		if($this->_hasError() == true){
			return;
		}
		
		$this->menuHandler = $this->root->getModelHandler('Menu');
		try {
			// トランザクション開始
			$this->root->cms->database()->queryF('BEGIN');

			// 一括削除
			if ($this->menuHandler->deleteAll() == false) {
				throw new Exception(t('Database error'));
			}

			// 全件挿入
			if ($this->menuHandler->insertMenus($_GET) == false) {
				throw new Exception(t('Database error'));
			}
			
			// コミット
			$this->root->cms->database()->queryF('COMMIT');
			// 画面遷移
//			$redirect = split('&',$root->getUrl());
			redirect_header(XOOPS_URL.'/modules/footer/admin/index.php?controller=menu_list',1,t('fotter updated'));
		} catch (Exception $e){
			$this->errors[] = $e->getMessage();
			$this->root->cms->database()->queryF('ROLLBACK');
		}
	}
	
	
	protected function _formValidate()
	{
		if(isset($_GET['titles']) == true){
			$titles = explode(',',substr($_GET['titles'], 0, -1));
		} else {
			$this->errors[] = t('parameter error');
			return false;
		}
		if(isset($_GET['urls']) == true){
			$urls = explode(',',substr($_GET['urls'], 0, -1));
		} else {
			$this->errors[] = t('parameter error');
			return false;
		}
		$i = 0;
		foreach($titles as $value){
			// 
			$this->menuList[$i]['title'] = $titles[$i];
			$this->menuList[$i]['url'] = $urls[$i];
			// チェック
			if($titles[$i] == ''){
				$this->errors[] = t('{1} is required. at line {2}.',t('Title'),$i+1);
			} else {
				if(strlen($titles[$i]) > 255){
					$this->errors[] = t('{1} is too long. at line {2}.',t('Title'),$i+1);
				}
			}
			if($urls[$i] == ''){
				$this->errors[] = t('{1} is required. at line {2}.',t('URL'),$i+1);
			}
			$i++;
		}
		return true;
		/*
		$obj->set('title', $this->post('uname'));
		$obj->set('url', $this->post('email'));
		$obj->set('weight',$i);
		*/
		
	}
	
	private function _hasError()
	{
		if(count($this->errors) > 0){
			return true;
		}
		return false;
	}
}
