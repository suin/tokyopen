<?php
/**
 * ブロックで使われるディスパッチャー
 */

class Pengin_Dispatcher_Block extends Pengin_Dispatcher_User
{
	protected function _fetchControllerName()
	{
		$this->controller = $this->options['controller'];
	}

	protected function _fetchActionName()
	{
		$this->action = $this->options['action'];
	}

	protected function _checkControllerName()
	{
		parent::_checkControllerName();
		$this->controller = 'block_'.$this->controller;
	}

	protected function _addPath()
	{
		$path = $this->root->cms->modulePath.DS.$this->options['dirname'];
		$this->root->path($path);
	}

	protected function _fetchModule()
	{
		$this->module = $this->options['dirname'];
		$this->Module = $this->root->pascalize($this->module);
	}

	protected function _checkControllerClass()
	{
		if ( !class_exists($this->controllerClass) )
		{
			throw new RuntimeException("Block controller {$this->controllerClass} not found");
		}
	}

	protected function _setupContext()
	{
		$this->root->context->Controller = $this->Controller;
		$this->root->context->Action     = $this->Action;
		$this->root->context->controller = $this->controller;
		$this->root->context->action     = $this->action;
		$this->root->context->Dirname    = $this->Module;
		$this->root->context->dirname    = $this->module;
		$this->root->context->mode       = $this->mode;
		$this->root->context->url        = $this->root->cms->moduleUrl.'/'.$this->options['dirname'];
		$this->root->context->path       = $this->root->cms->modulePath.DS.$this->options['dirname'];
		$this->root->context->name       = $this->_getThisModuleName();
		$this->root->context->options    = $this->options['options'];
	}

	protected function _getThisModuleName()
	{
		return $this->options['dirname']; // TODO >> load name from database
	}

	protected function _run()
	{
		$class = $this->controllerClass;
		$instance = new $class();
		$instance->main();

		unset($instance);
	}
}

