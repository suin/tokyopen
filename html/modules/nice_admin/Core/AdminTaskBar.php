<?php
class NiceAdmin_Core_AdminTaskBar
{
	protected $links       = array();
	protected $subLinks       = array();
	protected $stylesheets = array();
	protected $javaScripts = array();

	protected function __construct()
	{
	}

	public function getInstance()
	{
		static $instance = null;

		if ( $instance === null ) {
			$instance = new self;
		}

		return $instance;
	}

	public function getLinks()
	{
		return $this->links;
	}

	public function getSubLinks()
	{
		return $this->subLinks;
	}

	public function addLink($name, $label, $url , $menu=0, $isModal = true)
	{
	    if($menu != 0){
	        $isModal = false;
	    }
		$this->links[$name] = array(
			'name'  => $name,
			'label' => $label,
			'url'   => $url,
			'menu'  => $menu,
			'is_modal' => $isModal,
		);
		return $this;
	}

	public function addSubLink($parentName, $name, $label, $url)
	{
		$this->subLinks[$parentName][$name] = array(
			'name'  => $name,
			'label' => $label,
			'url'   => $url,
		);
		return $this;
	}
	

	public function addStylesheet($url)
	{
		$this->stylesheets[] = $url;
		return $this;
	}

	public function addJavaScript($url)
	{
		$this->javaScripts[] = $url;
		return $this;
	}

	public function getStylesheets()
	{
		return $this->stylesheets;
	}

	public function getJavaScripts()
	{
		return $this->javaScripts;
	}
}
