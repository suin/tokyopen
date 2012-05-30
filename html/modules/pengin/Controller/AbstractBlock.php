<?php
abstract class Pengin_Controller_AbstractBlock extends Pengin_Controller_Abstract
{
	protected $options = array(); // block option

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
		$this->options    = $this->root->context->options;

		$this->output['url']         =& $this->url;
		$this->output['dirname']     =& $this->dirname;
		$this->output['controller']  =& $this->controller;
		$this->output['action']      =& $this->action;
		$this->output['options']     =& $this->options;

		$this->_setUpModelHandlers();
	}

	public function main()
	{
		if ( method_exists($this, $this->Action) )
		{
			call_user_func(array(&$this, $this->Action));
		}
		else
		{
			throw new RuntimeException("Such an action {$this->Action} is not found");
		}
	}

	protected function _show()
	{
		// おもて表示用アクション
	}

	protected function _edit()
	{
		// 管理画面用アクション
	}

	protected function _view()
	{
		$view = Pengin_View::getView('Smarty');
		$view->render($this->template, $this->output);
	}

	protected function _hideBlock() // ブロックを非表示にする
	{
		throw new Exception;
	}
}

