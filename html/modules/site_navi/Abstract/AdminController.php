<?php

abstract class SiteNavi_Abstract_AdminController extends SiteNavi_Abstract_Controller
{
	protected function _defaultAction()
	{
		$this->_view();
	}
}
