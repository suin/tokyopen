<?php
class Pengin_View_Smarty
{
	public $name = ''; 

	protected $root = null;

	protected $dirname    = null;
	protected $controller = null;
	protected $action     = null;

	protected $template  = null;
	protected $output    = null;

	protected $smarty = null;

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

		$this->smarty = clone $this->root->cms->getSmarty();

		if ( !$this->template )
		{
			$this->template = $this->_getDefaultTemplate();
		}

		$this->_escapeHtml();

		$this->smarty->assign($this->output);

		$this->_registerHelpers();

		$this->smarty->display($this->template);
	}

	protected function _escapeHtml()
	{
		$this->output = Pengin_TextFilter::escapeHtmlArray($this->output);
	}

	protected function _getDefaultTemplate()
	{
		$schema = Pengin_Schema::getGlobalSchema($this->dirname, $this->controller, $this->action);
		$template = 'pen:'.$schema.'.tpl';

		if ( $this->root->cms->isMobile() === true )
		{
			$mobileTemplate = $schema.'.mobile.tpl';

			if ( $this->_isMobileTemplateEnable($mobileTemplate) === true )
			{
				$template = 'pen:'.$mobileTemplate;
			}
		}

		return $template;
	}

	protected function _isMobileTemplateEnable($templateName)
	{
		$templateName = substr($templateName, strlen($this->dirname.'.'));
		$mobileFileTemplate = $this->_getModuleFileTemplate($this->dirname, $templateName);
		return file_exists($mobileFileTemplate);
	}

	protected function _registerHelpers()
	{
		$pluginPath = PENGIN_PATH.'/View/Smarty/Plugin';
		$plugins = glob($pluginPath.'/*.php');

		foreach ( $plugins as $plugin ) {
			$filename  = basename($plugin);
			$filename  = preg_replace('/\.php$/', '', $filename);
			$className = sprintf('Pengin_View_Smarty_Plugin_%s', $filename);
			$type      = call_user_func(array($className, 'getType'));
			$name      = call_user_func(array($className, 'getName'));
			$method    = sprintf('register_%s', $type);
			$this->smarty->$method($name, array($className, 'run'));
		}

		$this->smarty->register_resource('pen', array(
			array(__CLASS__, 'getTemplate'),
			array(__CLASS__, 'getTimestamp'),
			array(__CLASS__, 'getSecure'),
			array(__CLASS__, 'getTrusted')));
	}

	public static function getTemplate($tplName, &$tplSource, &$smartyObj)
	{
		if ( self::_getDbTemplate($tplName, $tplSource, $smartyObj) )
		{
			return true;
		}

		list($dirname, $tplName) = self::_parseTemplateName($tplName);

		$moduleFileTpl = self::_getModuleFileTemplate($dirname, $tplName);

		// if file base template exists in that module, use it.
		if ( self::_include($moduleFileTpl, $tplSource) )
		{
			return true;
		}

		$penginDbTpl = self::_getPenginDbTemplate($tplName);

		// if database template exists in Pengin module, use it.
		if ( self::_getDbTemplate($penginDbTpl, $tplSource, $smartyObj) )
		{
			return true;
		}

		$penginFileTpl = self::_getPenginFileTemplate($tplName);

		// if file base template exists in that Pengin module, use it.
		if ( self::_include($penginFileTpl, $tplSource) )
		{
			return true;
		}

		// you are unlucky :(
		return false;
	}
	
	public static function getTimestamp($tplName, &$tplTimestamp, &$smartyObj)
	{	
		// If database template which name like dirname.controller.action.tpl exists, use it.
		// This is XOOPS ordinary way.
		if ( self::_getDbTimestamp($tplName, $tplTimestamp, $smartyObj) )
		{
			return true;
		}

		list($dirname, $tplName) = self::_parseTemplateName($tplName);

		$moduleFileTpl = self::_getModuleFileTemplate($dirname, $tplName);

		// If file template which name like controller.action.tpl exists in *dirname*, use it.
		if ( self::_fliemtime($moduleFileTpl, $tplTimestamp) )
		{
			return true;
		}

		$penginDbTpl = self::_getPenginDbTemplate($tplName);

		// if database template exists in Pengin module, use it.
		if ( self::_getDbTimestamp($penginDbTpl, $tplTimestamp, $smartyObj) )
		{
			return true;
		}

		$penginFileTpl = self::_getPenginFileTemplate($tplName);

		// if file base template exists in that Pengin module, use it.
		if ( self::_fliemtime($penginFileTpl, $tplTimestamp) )
		{
			return true;
		}

		// you are unlucky :(
		return false;
	}
	
	public static function getSecure($tplName, &$smartyObj)
	{
	    return true; // TODO この時点でsmarty_resource_db_secureが未定義のことがあるので、とりあえずtrueにした
		return smarty_resource_db_secure($tplName, $smartyObj); // TODO >> put this Platform/Xoops20.php
	}
	
	public static function getTrusted($tplName, &$smartyObj)
	{
		smarty_resource_db_trusted($tplName, $smartyObj); // TODO >> put this Platform/Xoops20.php
	}

	protected static function _getModuleDbTemplate($tplName)
	{
		$root =& Pengin::getInstance();
		return $root->context->dirname.'.'.$tplName;
	}

	protected static function _getModuleFileTemplate($dirname, $tplName)
	{
		$root =& Pengin::getInstance();
		$template = $root->cms->modulePath.DS.$dirname.DS.'templates'.DS.$tplName;
		return $template;
	}

	protected static function _getPenginDbTemplate($tplName)
	{
		return PENGIN_DIR.'.'.$tplName;
	}

	protected static function _getPenginFileTemplate($tplName)
	{
		return PENGIN_TEMPLATE_PATH.DS.$tplName;
	}

	protected static function _include($template, &$tplSource)
	{
		if ( file_exists($template) )
		{
			$content = file_get_contents($template);

			if ( $content !== false )
			{
				$tplSource = $content;
				return true;
			}
		}

		return false;
	}

	protected static function _fliemtime($template, &$tplTimestamp)
	{
		if ( file_exists($template) )
		{
			$tplTimestamp = filemtime($template);
			return true;
		}

		return false;
	}

	protected static function _getDbTemplate($tplName, &$tplSource, &$smartyObj)
	{
		return self::smarty_resource_db_source($tplName, $tplSource, $smartyObj); // TODO >> put this Platform/Xoops20.php
	}

	protected static function _getDbTimestamp($tplName, &$tplTimestamp, &$smartyObj)
	{
		return self::smarty_resource_db_timestamp($tplName, $tplTimestamp, $smartyObj); // TODO >> put this Platform/Xoops20.php
	}

	// TODO >> too dirty code!! clean up!!
	protected static function smarty_resource_db_systemTpl($tpl_name)
	{
	  // Replace Legacy System Template name to Legacy Module Template name
	  static $patterns = null;
	  static $replacements = null;
	  if (!$patterns) {
	    $root =& XCube_Root::getSingleton();
	    $systemTemplates = explode(',', $root->getSiteConfig('Legacy_RenderSystem', 'SystemTemplate',''));
	    $prefix = $root->getSiteConfig('Legacy_RenderSystem', 'SystemTemplatePrefix', 'legacy');
	    $patterns = preg_replace('/^\s*([^\s]*)\s*$/e', '"/".preg_quote("\1","/")."/"', $systemTemplates);
	    $replacements = preg_replace('/^\s*system_([^\s]*)\s*/', $prefix.'_\1', $systemTemplates);
	  }
	  if ($patterns) {
	    $tpl_name = preg_replace($patterns, $replacements,$tpl_name);
	  }
	  return $tpl_name;
	}
	
	protected static function smarty_resource_db_source($tpl_name, &$tpl_source, &$smarty)
	{
	  $tpl_name = self::smarty_resource_db_systemTpl($tpl_name);
	  $tplfile_handler =& xoops_gethandler('tplfile');
	  $tplobj =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], null, null, null, $tpl_name, true);
	  
	  if (count($tplobj) > 0) {
	    $tpl_source = $tplobj[0]->getVar('tpl_source');
	    return true;
	  } else {
	    return false;
	  }
	}
	
	protected static function smarty_resource_db_timestamp($tpl_name, &$tpl_timestamp, &$smarty)
	{
	  $tpl_name = self::smarty_resource_db_systemTpl($tpl_name);
	  $tplfile_handler =& xoops_gethandler('tplfile');
	  $tplobj =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], null, null, null, $tpl_name, false);
	
	  if (count($tplobj) > 0) {
	    $tpl_timestamp = $tplobj[0]->getVar('tpl_lastmodified');
	    return true;
	  } else {
	    return false;
	  }
	}
	
	protected static function smarty_resource_db_secure($tpl_name, &$smarty)
	{
	    // assume all templates are secure
	    return true;
	}
	
	protected static function smarty_resource_db_trusted($tpl_name, &$smarty)
	{
	    // not used for templates
	}

	protected static function _parseTemplateName($tplName)
	{
		$schema = preg_replace('/\.tpl$/', '', $tplName);
		$parts  = explode('.', $schema);

		if ( strpos($parts[1], '_') === 0 and count($parts) === 2 )
		{
			// private template like _pager.tpl
			$dirname = $parts[0];
			$tplName = $parts[1].'.tpl';
		}
		elseif ( preg_match("/^([a-z0-9_]+)\.(.+)$/", $schema, $matches) )
		{
			$dirname = $matches[1];
			$tplName = $matches[2].'.tpl';
		}
		else
		{
			$dirname = 'pengin';
			$tplsName = 'default.default.tpl';
		}

		return array($dirname, $tplName);
	}
}
