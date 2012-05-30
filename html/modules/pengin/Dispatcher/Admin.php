<?php
/**
 * 管理画面で使われるディスパッチャー
 */

class Pengin_Dispatcher_Admin extends Pengin_Dispatcher_User
{
	protected function _checkControllerName()
	{
		parent::_checkControllerName();
		$this->controller = 'admin_'.$this->controller;
	}
}

