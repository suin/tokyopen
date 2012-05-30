<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.4 or Upper version
 *
 * @package    Jquery
 * @author     Hidehito NOZAWA aka Suin <http://suin.asia/>
 * @copyright  2010 Hidehito NOZAWA
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 *
 */

if ( !defined('XOOPS_ROOT_PATH') ) exit;

class Jquery extends XCube_ActionFilter
{
	function preBlockFilter()
	{
		$this->mRoot->mDelegateManager->add('Legacy_RenderSystem.SetupXoopsTpl', array(&$this, 'hook'));
	}

	function hook(&$xoopsTpl)
	{
		$jQuery = new Jquery_HeaderScript();
		XCube_DelegateUtils::call('Site.JQuery.AddFunction', new XCube_Ref($jQuery));
		$jQuery->addStylesheet(TP_JQUERY_UI_THEME_URL, false); // TODO >> 選択できるようにする
		$moduleHeader = $xoopsTpl->get_template_vars('xoops_module_header');
		$moduleHeader =  $jQuery->getScriptConstants().$jQuery->createLibraryTag() . $moduleHeader . $jQuery->createOnloadFunctionTag();
		$xoopsTpl->assign('xoops_module_header', $moduleHeader);
	}
}

class Jquery_HeaderScript
{
	var $mMainLibrary = 'local';
	var $mMainVersion = "1";
	var $mUIVersion = "1";
	var $mMainUrl = TP_JQUERY_URL;	//url of jQuery Main library file
	var $mUIUrl = TP_JQUERY_UI_URL;	//url of jQuery UI library file

	var $_mLibrary = array();
	var $_mScript = array();
	var $_mStylesheet = array();

	var $mUsePrototype = false;	//use prototype.js ?
	var $mFuncNamePrefix = "";	//jQuery $() function's name prefix for compatibility with prototype.js

    /**
     * __construct
     * 
     * @param   void
     * 
     * @return  void
    **/
	public function __construct()
	{
		$root = XCube_Root::getSingleton();
		$mainLibrary = $root->getSiteConfig('jQuery', 'library');

		// suin fix
		if ( $mainLibrary )
		{
			$this->mMainLibrary = $mainLibrary;

			if($this->mMainLibrary=="google"){
				$this->mMainVersion = $root->getSiteConfig('jQuery', 'MainVersion');
				$this->mUIVersion = $root->getSiteConfig('jQuery', 'UIVersion');
			}
			elseif($this->mMainLibrary=="local"){
				$this->mMainUrl = $root->getSiteConfig('jQuery', 'MainUrl');
				$this->mUIUrl = $root->getSiteConfig('jQuery', 'UIUrl');
			}
		}
	
		//use compatibility mode with prototype.js ?
		if($root->getSiteConfig('jQuery', 'usePrototype')==1){
			$this->mUsePrototype = true;
			$this->mPrototypeUrl = $root->getSiteConfig('jQuery', 'prototypeUrl');
			$this->mFuncNamePrefix = $root->getSiteConfig('jQuery', 'funcNamePrefix');
		}
	}

    /**
     * addLibrary
     * 
     * @param   string $url
     * @param   bool $xoopsUrl
     * 
     * @return  void
    **/
	public function addLibrary($url, $xoopsUrl=true)
	{
		$libUrl = ($xoopsUrl==true) ? XOOPS_URL. $url : $url;
		if(! in_array($libUrl, $this->_mLibrary)){
			 $this->_mLibrary[] = $libUrl;
		}
	}

    /**
     * addStylesheet
     * 
     * @param   string $url
     * @param   bool $xoopsUrl
     * 
     * @return  void
    **/
	public function addStylesheet($url, $xoopsUrl=true)
	{
		$libUrl = ($xoopsUrl==true) ? XOOPS_URL. $url : $url;
		if(! in_array($libUrl, $this->_mStylesheet)){
			 $this->_mStylesheet[] = $libUrl;
		}
	}

	public function getScriptConstants()
	{
		$constants = array(
			'XOOPS_URL' => XOOPS_URL,
		);

		$script = '<script type="text/javascript"><!--'."\n";
		
		foreach ( $constants as $name => $value ) {
			$script .= sprintf('%s = "%s";', $name, addslashes($value));
		}
		
		$script .= "\n".'//--></script>';
		return $script;
	}

    /**
     * addScript
     * 
     * @param   string $script
     * 
     * @return  void
    **/
	public function addScript($script)
	{
		$this->_mScript[] = $script;
	}

    /**
     * getLibraryArr
     * 
     * @param   void
     * 
     * @return  string[]
    **/
	public function getLibraryArr()
	{
		return $this->_mLibrary;
	}

    /**
     * getScriptArr
     * 
     * @param   void
     * 
     * @return  string[]
    **/
	public function getScriptArr()
	{
		return $this->_mScript;
	}

    /**
     * createLibraryTag
     * 
     * @param   void
     * 
     * @return  string
    **/
	public function createLibraryTag()
	{
		$html = "";
	
		//prototype.js compatibility
		if($this->mUsePrototype){
			$html .= '<script type="text/javascript" src="'. $this->mPrototypeUrl .'"></script>';
		}
		
		//load main library
		if($this->mMainLibrary=='google'){
			$html .= $this->_loadGoogleJQueryLibrary();
		}
		elseif($this->mMainLibrary=='local'){
			$html .= $this->_loadLocalJQueryLibrary();
		}
	
		//load plugin libraries
		foreach($this->_mLibrary as $lib){
			$html .= "<script type=\"text/javascript\" src=\"". $lib ."\"></script>\n";
		}
	
		//load css
		foreach($this->_mStylesheet as $css){
			$html .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"". $css ."\" />\n";
		}
	
		return $html;
	}

    /**
     * _loadGoogleJQueryLibrary
     * 
     * @param   void
     * 
     * @return  string
    **/
	protected function _loadGoogleJQueryLibrary()
	{
		return '<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript"><!--
google.load("language", "1"); 
google.load("jquery", "'. $this->mMainVersion .'");
google.load("jqueryui", "'. $this->mUIVersion .'");
//-->
</script>
';
	}

    /**
     * _loadLocalJQueryLibrary
     * 
     * @param   void
     * 
     * @return  string
    **/
	protected function _loadLocalJQueryLibrary()
	{
		$html = "";
		if($this->mMainUrl) $html .= '<script type="text/javascript" src="'. $this->mMainUrl .'"></script>';
		if($this->mUIUrl) $html .= '<script type="text/javascript" src="'. $this->mUIUrl .'"></script>';
	
		return $html;
	}

    /**
     * createOnloadFunctionTag
     * 
     * @param   void
     * 
     * @return  string
    **/
	public function createOnloadFunctionTag()
	{
		$html = null;
		if(count($this->_mScript)>0){
			$html = "<script type=\"text/javascript\"><!--\n";
			if($this->mMainLibrary == "google"){
				$html .= "google.setOnLoadCallback(function() {\n";
			}
			else{
				$html .= "$(document).ready(function(){\n";
			}
			$html .= $this->_makeScript();
			$html .= "\n});\n";
			$html .= "// --></script>";
		}
		return $html;
	}

    /**
     * _makeScript
     * 
     * @param   void
     * 
     * @return  string
    **/
	protected function _makeScript()
	{
		$html = null;
		foreach($this->_mScript as $script){
			$html .= $this->_convertFuncName($script);
		}
		return $html;
	}

    /**
     * _convertFuncName
     * 
     * @param   string $script
     * 
     * @return  string
    **/
	protected function _convertFuncName($script)
	{
		if($this->mFuncNamePrefix){
			$script = str_replace("$(", $this->mFuncNamePrefix."$(", $script);
		}
		return $script;
	}
}

?>