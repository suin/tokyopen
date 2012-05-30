<?php
class Profile_Plugin_Manager
{
	protected $namespaces = array(
		'Profile_Plugin_Vendor_',
		'Profile_Plugin_Core_',
	);

	protected $dirs = array(
		'Vendor',
		'Core',
	);

	protected $pluginDir = '';

	public function __construct()
	{
		$pengin    = Pengin::getInstance();
		$pluginDir = $pengin->cms->modulePath.'/'.$pengin->context->dirname.'/Plugin';
		$this->pluginDir = $pluginDir;
	}

	public function getOptions($name, $optionText)
	{
		if ( $this->call($name, 'hasOptions') === false ) {
			return false;
		}

		return $this->call($name, 'unserializeOptions', $optionText);
	}

	public function hasRender($name)
	{
		return $this->call($name, 'hasRender');
	}

	public function call($class, $method)
	{
		$arguments = func_get_args();
		$class  = array_shift($arguments);
		$method = array_shift($arguments);

		if ( is_object($class) === true ) {

			if ( $this->isImplementedPlugin($class) === false ) {
				return false;
			}

		} else {

			$class = $this->getClass($class);

			if ( $class === false ) {
				return false;
			}
		}

		return call_user_func_array(array($class, $method), $arguments);
	}

	public function getClass($name)
	{
		foreach ( $this->namespaces as $namespace ) {
			$className = $namespace.$name;
			
			if ( $this->isImplementedPlugin($className) === true ) {
				return $className;
			}
		}

		return false;
	}

	public function getClasses()
	{
		$plugins = $this->getPlugins();
		$classess = array_map(array($this, 'getClass'), $plugins);
		return $classes;
	}

	public function getPlugins()
	{
		$pluginFiles = $this->_getPluginFiles();
		$plugins = array_map(array($this, '_getPlguinName'), $pluginFiles);
		$plugins = array_filter($plugins, array($this, 'getClass'));
		return $plugins;
	}

	public function getPluginInfo()
	{
		$pluginInfo = array();
		$plugins = $this->getPlugins();

		foreach ( $plugins as $plugin ) {
			$pluginInfo[$plugin] = array(
				'label'  => $this->call($plugin, 'getPluginName'),
				'column' => $this->call($plugin, 'getDatabaseColumn'),
				'group'  => $this->call($plugin, 'getGroup'),
			);
		}

		$groups = array();
		$labels = array();

		foreach ( $pluginInfo as $key => $plugin ) {
			$groups[$key] = $plugin['group'];
			$labels[$key] = $plugin['label'];
		}

		array_multisort($groups, SORT_ASC, $labels, SORT_ASC, $pluginInfo);

		return $pluginInfo;
	}

	public function getLabels()
	{
		$labels = array();
		$pluginInfo = $this->getPluginInfo();

		foreach ( $pluginInfo as $name => $info ) {
			$labels[$name] = $info['label'];
		}

		return $labels;
	}

	protected function _getPluginFiles()
	{
		$files = array();

		foreach ( $this->dirs as $dir ) {
			$candidates = glob($this->pluginDir.'/'.$dir.'/*.php');

			if ( is_array($candidates) === false ) {
				continue;
			}

			$candidates = array_filter($candidates, 'is_file');
			$files = array_merge($files, $candidates);
		}
		
		return $files;
	}

	protected function _getPlguinName($filepath)
	{
		return pathinfo($filepath, PATHINFO_FILENAME);
	}

	/**
	 * Profile_Plugin_ProfileInterfaceを実装しているかを返す.
	 * 
	 * @access public
	 * @static
	 * @param string|object $class
	 * @return bool
	 */
	public function isImplementedPlugin($class)
	{
		if ( is_object($class) === true ) {
			return ( $class instanceof Profile_Plugin_PluginInterface );
		}

		if ( class_exists($class) === false ) {
			return false;
		}

		$classImplements = class_implements($class);
		return in_array('Profile_Plugin_PluginInterface', $classImplements);
	}
}
