<?php
class Pengin
{
	public $cms     = null;
	public $context = null;
	public $translator = null;

	protected $autoloadPath = array();

	protected function __construct()
	{
		if ( !defined('DS') )
		{
			define('DS', DIRECTORY_SEPARATOR);
		}

		define('PENGIN_DIR', basename(PENGIN_PATH));
		define('PENGIN_TEMPLATE_PATH', PENGIN_PATH.DS.'templates');

		spl_autoload_register(array($this, 'import'));

		require_once PENGIN_PATH.DS.'Inflector.php';

		$this->path(PENGIN_PATH);

		$this->cms =& Pengin_Platform::getInstance();
		$this->context = new Pengin_Context;
		$this->_setUpTranslator();

		require_once PENGIN_PATH.DS.'version.php';
		require_once PENGIN_PATH.DS.'function.php';

		define('PENGIN_LOADED', true);
	}

	public static function &getInstance()
	{
		static $instance;

		if ( $instance === null )
		{
			$instance = new self;
		}

		return $instance;
	}

	public function main($mode = null, array $options = array())
	{
		if ( $mode === null )
		{
			$mode = 'user';
		}

		$Mode = $this->pascalize($mode);

		$dispatchClass = 'Pengin_Dispatcher_'.$Mode;

		if ( class_exists($dispatchClass) === false )
		{
			throw new RuntimeException("Invalid mode '$mode' is used");
		}

		$dispatcher = new $dispatchClass($this, $mode, $options);
		$dispatcher->main();

		unset($dispatcher);
	}

	public function path($path = null)
	{
		if ( $path === null )
		{
			return $this->autoloadPath;
		}

		$basename = basename($path);
		$basename = $this->pascalize($basename);

		$this->autoloadPath[$basename] = $path;
	}

	public function camelize($string)
	{
		return Pengin_Inflector::camelize($string);
	}

	public function pascalize($string)
	{
		return Pengin_Inflector::pascalize($string);
	}

	public function snakeCase($string)
	{
		return Pengin_Inflector::snakeCase($string);
	}

	public function import($class)
	{
		if ( class_exists($class, false) === true )
		{
			return false;
		}

		$parts  = explode('_', $class);
		$module = array_shift($parts);
		//$module = $this->pascalize($module);

		if ( isset($this->autoloadPath[$module]) === false )
		{
			return false;
		}

		$class  = implode('/', $parts);
		$path   = sprintf('%s/%s.php', $this->autoloadPath[$module], $class);

		if ( file_exists($path) === false )
		{
			return false;
		}

		require $path;
	}

	/**
	 * Usefull functions
	 */
	public function get($name, $default = null)
	{
		$request = ( isset($_GET[$name]) ) ? $_GET[$name] : $default;
		return $request;
	}

	public function post($name, $default = null)
	{
		$request = ( isset($_POST[$name]) ) ? $_POST[$name] : $default;
		return $request;
	}

	public function request($name, $default = null)
	{
		return $this->post($name, $this->get($name, $default));
	}

	/**
	 * リダイレクト画面を出す.
	 * 
	 * @access public
	 * @param string $msg リダイレクトメッセージ
	 * @param string $controller リダイレクト先コントローラ または URL
	 * @param string $action リダイレクト先アクション
	 * @param array $params QueryString
	 * @param int $sec 待ち秒数
	 * @return void
	 */
	public function redirect($msg, $controller = null, $action = null, $params = array(), $sec = 3)
	{
		if ( preg_match('/^[a-z0-9_]+$/', $controller) == false )
		{
			$url = $controller; // コントローラの書式ではないときはURLとみなす
		}
		else
		{
			$url = $this->url($controller, $action, $params);
		}

		$msg = t($msg);

		$this->cms->redirect($url, $sec, $msg);
	}

	public function location($controller = null, $action = null, $params = array())
	{
		if ( preg_match('/^[a-z0-9_]+$/', $controller) == false )
		{
			$url = $controller; // コントローラの書式ではないときはURLとみなす
		}
		else
		{
			$url = $this->url($controller, $action, $params);
		}

		header('Location: '.$url);
		die;
	}

	public function url($controller = null, $action = null, $extra = array())
	{
		return Pengin_Url::makeUrl($this->cms->moduleUrl, $this->context->dirname, $controller, $action, $extra, $this->context->mode);
	}

	public function error($msg)
	{
		$this->cms->error($msg);
	}

	public function escapeHtml($string)
	{
		return htmlspecialchars($string, ENT_QUOTES);
	}

	public function session($name, $value = '')
	{
		if ( $value === '' )
		{
			if ( isset($_SESSION[$this->context->dirname][$name]) )
			{
				return $_SESSION[$this->context->dirname][$name];
			}

			return false;
		}
		elseif ( $value === null )
		{
			unset($_SESSION[$this->context->dirname][$name]);
			return true;
		}

		$_SESSION[$this->context->dirname][$name] = $value;
		return true;
	}

	public function &getModelHandler($name, $dirname = null)
	{
		static $handlers = array();

		if ( $dirname === null ) {
			$dirname = $this->context->dirname;
		}

		$dirnamePascal = $this->pascalize($dirname);

		$className = $dirnamePascal.'_Model_'.$name.'Handler';

		if ( !isset($handlers[$className]) )
		{
			$handlers[$className] = new $className($dirname);
		}

		return $handlers[$className];
	}

	public function getUrl()
	{
		return Pengin_Url::getUrl();
	}

	// for debug
	public function dump()
	{
		$args = func_get_args();

		foreach ( $args as $arg )
		{
			echo '<pre style="border:1px dotted black;">';
			var_dump($arg);
			echo '</pre>';
		}
	}

	protected function _setUpTranslator()
	{
		if ( $this->translator === null ) {
			$this->translator = new Pengin_Translator(
				$this->cms->modulePath,
				$this->cms->cachePath,
				$this->cms->charset
			);
		}
	}
}
