<?php
/**
 * おもて画面で使われるディスパッチャー
 */

class Pengin_Dispatcher_User extends Pengin_Dispatcher_Abstract
{
	protected function _fetchControllerName()
	{
		$this->controller = $this->root->get('controller', 'default');
	}

	protected function _checkControllerName()
	{
		$this->controller = preg_replace('/[^a-z0-9_]/', '', $this->controller);
		
		$invalidPrefixPattern = '/^(admin|batch|block|preload)_/';

		while ( preg_match($invalidPrefixPattern, $this->controller) ) {
			$this->controller = preg_replace($invalidPrefixPattern, '', $this->controller);
		}
	}

	protected function _pascalizeControllerName()
	{
		$this->Controller = $this->root->pascalize($this->controller);
	}

	protected function _fetchActionName()
	{
		$this->action = $this->root->get('action', 'default');
	}

	protected function _checkActionName()
	{
		$this->action = preg_replace('/[^a-z0-9_]/', '', $this->action);
	}

	protected function _pascalizeActionName()
	{
		$this->Action = $this->root->camelize($this->action);
		$this->Action = '_'.$this->Action;
	}

	protected function _addPath()
	{
		$path = $this->root->cms->getThisModulePath();
		$this->root->path($path);
	}

	protected function _fetchModule()
	{
		$this->module = $this->root->cms->getThisModuleDirname();

		if ( !preg_match('/^[a-z0-9_]+$/', $this->module) )
		{
			throw new RuntimeException("Module directory {$this->module} name must be lower snake case");
		}

		$this->Module = $this->root->pascalize($this->module);
	}

	protected function _setControllerClass()
	{
		$this->controllerClass = $this->Module.'_Controller_'.$this->Controller;
	}

	protected function _checkControllerClass()
	{
		if ( !class_exists($this->controllerClass) )
		{
			$this->root->redirect("ページが見つかりません。", $this->root->cms->url); // TODO >> japanese
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
		$this->root->context->url        = $this->root->cms->getThisModuleUrl();
		$this->root->context->path       = $this->root->cms->getThisModulePath();
		$this->root->context->name       = $this->root->cms->getThisModuleName();
	}

	protected function _setupTranslator()
	{
		$this->root->translator->useTranslation(
			$this->root->context->dirname,
			$this->root->cms->langcode,
			'translation'
		);
	}

	protected function _run()
	{
		$class = $this->controllerClass;
		$instance = new $class();
		$instance->main();

		unset($instance);
	}
}
