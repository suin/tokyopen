<?php
class Pengin_View_PhpTemplate
{
	public $name = ''; 

	protected $root = null;

	protected $dirname    = null;
	protected $controller = null;
	protected $action     = null;

	protected $template  = null;
	protected $output    = null;

	public function __construct()
	{
		$this->root =& Pengin::getInstance();

		$this->dirname    = $this->root->context->dirname;
		$this->controller = $this->root->context->controller;
		$this->action     = $this->root->context->action;
	}

	public function render(&$template, &$output)
	{
		$this->template =& $template;
		$this->output   =& $output;

		if ( !$this->template )
		{
			$this->template = $this->_getDefaultTemplate();
		}

		$this->_escapeHtml();
		$this->_assign();
		$this->_registerHelpers();
		$this->_display();
	}

	protected function _escapeHtml()
	{
		$this->output = Pengin_TextFilter::escapeHtmlArray($this->output);
	}

	protected function _assign()
	{
	}

	protected function _registerHelpers()
	{
		// TODO
		function e($string)
		{
			echo $string;
		}
	}

	protected function _display()
	{
		extract($this->output);

		if ( file_exists($this->template) )
		{
			require $this->template;
		}
		else
		{
			echo t("Template File Not Found: {1}", $this->template);
		}
	}

	protected function _getDefaultTemplate()
	{
		$schema = Pengin_Schema::getLocalSchema($this->controller, $this->action, $this->root->context->adminMode);
		$file   = $this->root->context->path.DS.'templates'.DS.$schema.'.php';
		return $file;
	}

	public function raw($str)
	{
		return htmlspecialchars_decode($str, ENT_QUOTES);
	}

	public function t()
	{
	
	}

	public function url($params, &$smarty)
	{
		$root =& Pengin::getInstance();
		return $root->url(null, null, $params);
	}

	public function currency($num)
	{
		$decimals     = '.';
		$decPoint     = ',';
		$thousandsSep = ',';
		return number_format($num, $decimals, $decPoint, $thousandsSep);
	}
}

?>
