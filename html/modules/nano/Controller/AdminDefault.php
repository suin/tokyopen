<?php
class Nano_Controller_AdminDefault extends Nano_Abstract_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function main()
	{
		$this->_default();
	}

	protected function _default()
	{
		$this->_view();
	}
}
