<?php

class Mailform_Plugin_Manager
{
	protected $namespaces = array(
		'Mailform_Plugin_Vendor_',
		'Mailform_Plugin_Core_',
	);

	protected $dirs = array(
		'Vendor',
		'Core',
	);

	protected $pluginDir = '';

	public function __construct()
	{
		$this->pluginDir = dirname(__FILE__);
	}

	/**
	 * クラスがMailform_Plugin_PluginInterfaceを実装しているかを返す.
	 * @param string|object $class
	 * @return bool
	 */
	public function isImplementedPlugin($class)
	{
		if ( is_object($class) === true ) {
			return ( $class instanceof Mailform_Plugin_PluginInterface );
		}

		if ( class_exists($class) === false ) {
			return false;
		}

		$classImplements = class_implements($class);
		return in_array('Mailform_Plugin_PluginInterface', $classImplements);
	}

	/**
	 * プラグインを返す
	 * @param string $pluginName
	 * @return Mailform_Plugin_Interfaceを継承したオブジェクト
	 */
	public function getPlugin($pluginName)
	{
		$className = $this->_getPluginClass($pluginName);

		if ( $className === false ) {
			return false;
		}

		return new $className();
	}

	/**
	 * プラグインをすべて返す
	 * @return array
	 */
	public function getPlugins()
	{
		$pluginNames = $this->_getPluginNames();

		$plugins = array();

		foreach ( $pluginNames as $pluginName ) {
			$className = $this->_getPluginClass($pluginName);

			if ( $className === false ) {
				continue;
			}

			$plugins[$pluginName] = new $className();
		}

		return $plugins;
	}

	/**
	 * プラグイン情報を返す
	 * @return array
	 */
	public function getPluginInfo()
	{
		$plugins = $this->getPlugins();
		$pluginInfo = array();

		foreach ( $plugins as $pluginName => $plugin ) {
			$pluginInfo[$pluginName] = array(
				'name'     => $pluginName,
				'title'    => $plugin->getPluginName(),
				'mockHTML' => $plugin->getMockHTML(),
				'options'  => $plugin->getDefaultPluginOptions(),
			);
		}

		return $pluginInfo;
	}

	protected function _getPluginNames()
	{
		$dirs    = implode(',', $this->dirs);
		$pattern = sprintf('%s/{%s}/*.php', $this->pluginDir, $dirs);
		$files   = glob($pattern, GLOB_BRACE);

		$pluginNames = array();

		foreach ( $files as $file ) {
			$pluginNames[] = pathinfo($file, PATHINFO_FILENAME);
		}

		$pluginNames = array_unique($pluginNames);

		return $pluginNames;
	}

	protected function _getPluginClass($pluginName)
	{
		foreach ( $this->namespaces as $namespace ) {
			$className = $namespace.$pluginName;

			if ( class_exists($className) === false ) {
				continue;
			}

			if ( $this->isImplementedPlugin($className) === false ) {
				continue;
			}

			return $className; // 同名のプラグインがある場合、先の namespace のほうが勝つ
		}

		return false;
	}
}
