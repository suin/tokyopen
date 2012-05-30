<?php
class NiceAdmin_Controller_PreloadTaskBar extends Pengin_Controller_AbstractPreload
{
	protected $moduleModels = array();
	protected $adminTaskBar = null;

	public function __construct()
	{
		parent::__construct();
		$this->adminTaskBar =& XoopsAdminTaskBar::getInstance();
	}

	public function main()
	{	
		$this->_default();
	}

	protected function _default()
	{
		if ( $this->root->cms->isAdmin() === false )
		{
			throw new Exception;
		}

		$this->_addAdminLinks();
		$this->_assignLinks();
		$this->_addTaskBarStyleSheets();
		$this->_addTaskBarJavaScripts();
		$this->_view();
	}

	protected function _addAdminLinks()
	{
		$this->output['control_panel_url'] = $this->root->cms->url.'/admin.php';
		$this->output['logout_url']        = $this->root->cms->url.'/user.php?op=logout';
	}

	protected function _assignLinks()
	{
		//$this->output['links'] = $this->adminTaskBar->getLinks();
		//$this->output['subLinks'] = $this->adminTaskBar->getSubLinks();
	}

	protected function _addTaskBarStyleSheets()
	{
		$this->root->cms->addStyleSheet($this->url.'/public/css/admin_task_bar.css');
		$this->_addStyleSheets($this->adminTaskBar->getStyleSheets());
	}

	protected function _addTaskBarJavaScripts()
	{
		$this->_addJavaScripts($this->adminTaskBar->getJavaScripts());
	}

	protected function _addStyleSheets($styleSheets)
	{
		foreach ( $styleSheets as $styleSheet )
		{
			$this->root->cms->addStyleSheet($styleSheet);
		}
	}

	protected function _addJavaScripts($javaScripts)
	{
		foreach ( $javaScripts as $javaScript )
		{
			$this->root->cms->addJavaScript($javaScript);
		}
	}
}

