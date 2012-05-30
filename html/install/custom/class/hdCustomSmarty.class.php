<?php
/// only to use in custom installer
class HdCustomSmarty extends Smarty
{
	function HdCustomSmarty()
	{		parent::Smarty();
		$this->compile_id = null;
		$this->force_compile    = true;
		$this->compile_check = false;
		$this->left_delimiter =  '<{';
		$this->right_delimiter =  '}>';
		$this->cache_dir = TP_CACHE_PATH;
		$this->compile_dir = TP_TEMPLATE_COMPILE_PATH;
		$this->template_dir = dirname(dirname(__FILE__)).'/templates';
		$this->plugins_dir = array(
			TP_SMARTY_VENDOR_PLUGIN_PATH,
			TP_SMARTY_PLUGIN_PATH,
		);
		$this->use_sub_dirs = false;
	}
}
