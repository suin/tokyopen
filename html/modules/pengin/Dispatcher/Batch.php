<?php
/**
 * バッチ処理で使われるディスパッチャー
 */

class Pengin_Dispatcher_Batch extends Pengin_Dispatcher_User
{
	protected function _checkControllerName()
	{
		parent::_checkControllerName();
		$this->controller = 'batch_'.$this->controller;
	}

	protected function _checkControllerClass()
	{
		if ( !class_exists($this->controllerClass) )
		{
			trigger_error("Batch controller {$this->controllerClass} not found", E_USER_ERROR);
			die;
		}
	}
}

