<?php
abstract class Pengin_Controller_Abstract
{
	protected $root = null;

	protected $Controller = null;
	protected $Action     = null;
	protected $controller = null;
	protected $action     = null;
	protected $dirname    = null;

	protected $url  = null;
	protected $path = null;

	protected $template    = null;
	protected $output      = array(); // テンプレートなどに出力する変数のリスト
	protected $breadcrumbs = array();
	protected $pageTitle   = null;

	protected $useModels = array(); // 使うモデルを定義するテンプレート変数

	public function __construct()
	{
		/*
		  下位クラスは__constructを継承する。
		  下位クラスはparent::__construct()すること。
		  __constructでは、クラス変数などの初期化を行う。
		  Actionを含めることはできない。
		*/

		$this->root =& Pengin::getInstance();

		$this->Controller = $this->root->context->Controller;
		$this->Action     = $this->root->context->Action;
		$this->controller = $this->root->context->controller;
		$this->action     = $this->root->context->action;
		$this->dirname    = $this->root->context->dirname;
		$this->url        = $this->root->context->url;
		$this->path       = $this->root->context->path;
		$this->name       = $this->root->context->name;

		$this->output['url']         =& $this->url;
		$this->output['dirname']     =& $this->dirname;
		$this->output['controller']  =& $this->controller;
		$this->output['action']      =& $this->action;
		$this->output['breadcrumbs'] =& $this->breadcrumbs;
		$this->output['page_title']  =& $this->pageTitle;


		$this->pageTitle = $this->root->cms->getThisModuleName();
		$this->_addBreadCrumb($this->pageTitle, $this->url);
		$this->_setUpModelHandlers();
	}

	public function main()
	{
		/*
		  下位クラスはここをオーバライドしActionを分岐する。
		  ひとつのActionはひとつのメソッドにする。
		  Actionのメソッドはprotectedである。
		*/
	}

	public function get($name, $default = null)
	{
		return $this->root->get($name, $default);
	}

	public function post($name, $default = null)
	{
		return $this->root->post($name, $default);
	}

	/**
	 * モデルハンドラーをセットアップする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _setUpModelHandlers()
	{
		foreach ( $this->useModels as $modelName ) {
			$variableName = $modelName.'Handler';
			$variableName[0] = strtolower($variableName[0]);
			$this->$variableName = $this->root->getModelHandler($modelName);
		}
	}

	protected function _view()
	{
		$view = Pengin_View::getView('Smarty');
		$view->render($this->template, $this->output);
		$this->root->cms->setPageTitle($this->pageTitle);
		$this->root->cms->setBreadCrumbs($this->breadcrumbs);
		$this->_head();
	}

	protected function _addBreadCrumb($name, $url)
	{
		$this->breadcrumbs[] = array(
			'name' => $name,
			'url'  => $url,
		);
	}

	protected function _head()
	{
		$this->root->cms->addStyleSheet(PENGIN_URL.'/public/css/main.css');
		$this->root->cms->addStyleSheet($this->url.'/public/css/main.css');

		if ( file_exists($this->path.DS.'public'.DS.'css'.DS.$this->controller.'.css') )
		{
			$this->root->cms->addStyleSheet($this->url.'/public/css/'.$this->controller.'.css');
		}

		if ( file_exists($this->path.DS.'public'.DS.'javascript'.DS.$this->controller.'.js') )
		{
			$this->root->cms->addJavaScript($this->url.'/public/javascript/'.$this->controller.'.js');
		}

		$this->root->cms->addHeader("<script type=\"text/javascript\">\n<!--\nsite_url = \"".$this->root->cms->url."\";\n//-->\n</script>");
	}
}

?>
