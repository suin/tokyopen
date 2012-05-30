<?php
/**
 * ディスパッチャーの抽象クラス
 */

abstract class Pengin_Dispatcher_Abstract
{
	protected $root = null;

	protected $controller = '';
	protected $action     = '';
	protected $Controller = '';
	protected $Action     = '';
	protected $module     = '';
	protected $Module     = '';
	protected $mode       = '';

	protected $controllerClass = '';
	protected $options = array();

	public function __construct(Pengin &$root, $mode, array $options)
	{
		$this->root =& $root;
		$this->mode = $mode;
		$this->options = $options;
	}

	public function main($mode = null)
	{
		$this->_fetchControllerName();
		$this->_checkControllerName();
		$this->_pascalizeControllerName();
		$this->_fetchActionName();
		$this->_checkActionName();
		$this->_pascalizeActionName();
		$this->_addPath();
		$this->_fetchModule();
		$this->_setControllerClass();
		$this->_checkControllerClass();
		$this->_setupContext();
		$this->_setupTranslator();
		$this->_run();
	}

	abstract protected function _fetchControllerName();

	abstract protected function _checkControllerName();

	abstract protected function _pascalizeControllerName();

	abstract protected function _fetchActionName();

	abstract protected function _checkActionName();

	abstract protected function _pascalizeActionName();

	abstract protected function _addPath();

	abstract protected function _fetchModule();

	abstract protected function _setControllerClass();

	abstract protected function _checkControllerClass();

	abstract protected function _setupContext();
	
	abstract protected function _setupTranslator();

	abstract protected function _run();
}

