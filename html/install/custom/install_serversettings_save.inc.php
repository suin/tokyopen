<?php

/**
 * TP_Installer_InstallServerSettingSave class.
 */
class TP_Installer_InstallServerSettingSave
{
	/**
	 * settingFilePath
	 * @var string
	 */
	protected $settingFilePath = '';

	/**
	 * __construct function.
	 * @param string $settingFilePath
	 * @return void
	 */
	public function __construct($settingFilePath)
	{
		$this->settingFilePath = $settingFilePath;
	}

	/**
	 * run function.
	 * @return bool
	 */
	public function run()
	{
		if ( $this->_isSettingFileWritable() === false ) {
			return false;
		}

		$lines = $this->_getSettingFileContents();
		$lines = $this->_modifyConstants($lines);
		return $this->_putSettingFileContents($lines);
	}

	/**
	 * _isSettingFileWritable function.
	 * @return bool
	 */
	protected function _isSettingFileWritable()
	{
		return ( is_writable($this->settingFilePath) === true and is_readable($this->settingFilePath) === true );
	}

	/**
	 * _getSettingFileContents function.
	 * @return array
	 */
	protected function _getSettingFileContents()
	{
		return file($this->settingFilePath);
	}

	/**
	 * _post function.
	 * @param string $name
	 * @param mixed $default (default: null)
	 * @return mixed
	 */
	protected function _post($name, $default = null)
	{
		if ( isset($_POST[$name]) === true ) {
			return $_POST[$name];
		}
		
		return $default;
	}

	/**
	 * _modifyConstants function.
	 * @param array $lines
	 * @return array
	 */
	protected function _modifyConstants(array $lines)
	{
		foreach ( $lines as &$line ) {

			$line = trim($line); // 空白・改行コード除去

			if ( $this->_isComment($line) === true ) {
				continue;
			}

			if ( $this->_constantExists('XOOPS_COOKIE_PATH', $line) === true ) {
				$line = $this->_getCookiePathDefinition($line);
				continue;
			}

			if ( $this->_constantExists('XCL_MEMORY_LIMIT', $line) === true ) {
				$line = $this->_getMemoryLimitDefinition($line);
				continue;
			}

			if ( $this->_constantExists('LEGACY_INSTALLERCHECKER_ACTIVE', $line) === true ) {
				$line = $this->_getInstallerCheckActiveDefinition($line);
				continue;
			}
		}
		
		return $lines;
	}

	/**
	 * _isComment function.
	 * @param string $line
	 * @return bool
	 */
	protected function _isComment($line)
	{
		if ( strpos($line, '//') === 0 ) {
			return true;
		}
		
		return false;
	}

	/**
	 * _getCookiePathDefinition function.
	 * @param string $line
	 * @return string
	 */
	protected function _getCookiePathDefinition($line)
	{
		$cookiePath = $this->_post('cookie_path');
		$cookiePath = rtrim($cookiePath, '/');
		$cookiePath = addslashes($cookiePath);
		return sprintf("define('XOOPS_COOKIE_PATH', '%s');", $cookiePath);
	}

	/**
	 * _getMemoryLimitDefinition function.
	 * @param string $line
	 * @return string
	 */
	protected function _getMemoryLimitDefinition($line)
	{
		$memoryLimit = $this->_post('memory_limit');
		
		if ( preg_match('/^\d+M?$/i', $memoryLimit) == 0 ) {
			return $line;
		}

		return sprintf("define('XCL_MEMORY_LIMIT', '%s');", $memoryLimit);
	}

	/**
	 * _getInstallerCheckActiveDefinition function.
	 * @param string $line
	 * @return string
	 */
	protected function _getInstallerCheckActiveDefinition($line)
	{
		$installerCheckKill = $this->_post('ins_kill');

		if ( $installerCheckKill == 'Yes' ) {
			$installerCheckActive = 'false';
		} else {
			$installerCheckActive = 'true';
		}

		return sprintf("define('LEGACY_INSTALLERCHECKER_ACTIVE', %s);", $installerCheckActive);
	}

	/**
	 * _constantExists function.
	 * @param string $constant
	 * @param string $string
	 * @return bool
	 */
	protected function _constantExists($constant, $string)
	{
		$needle = sprintf("define('%s'", $constant);
		
		if ( strpos($string, $needle) !== false ) {
			return true;
		}
		
		return false;
	}

	/**
	 * _putSettingFileContents function.
	 * @param array $lines
	 * @return bool If sucess returns TRUE, otherwise FALSE
	 */
	protected function _putSettingFileContents(array $lines)
	{
		$contents = implode("\n", $lines);
		$writtenBytes = file_put_contents($this->settingFilePath, $contents);
		return ( $writtenBytes !== false );
	}
}

$settingFilePath = dirname(dirname(dirname(__FILE__))).'/settings/definition.custom.php';
$installServerSettingSave = new TP_Installer_InstallServerSettingSave($settingFilePath);
$mainfileAdded = $installServerSettingSave->run();

$wizard->assign('mainfile_add', $mainfileAdded ? _OKIMG._INSTALL_CL2_1 : _NGIMG._INSTALL_CL2_2); 
$wizard->setTemplatePath(dirname(__FILE__));
$wizard->render('templates/install_serversettings_save.html');
