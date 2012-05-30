<?php
class NiceBlock_Controller_AdminDefault extends NiceBlock_Abstract_Controller
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
