<?php
abstract class Pengin_Controller_AbstractPreload extends Pengin_Controller_Abstract
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

		$this->output['url']         =& $this->url;
		$this->output['dirname']     =& $this->dirname;
		$this->output['controller']  =& $this->controller;
		$this->output['action']      =& $this->action;
	}

	public function main()
	{
	}

	protected function _view()
	{
		$view = Pengin_View::getView('Smarty');
		$view->render($this->template, $this->output);
	}
}

