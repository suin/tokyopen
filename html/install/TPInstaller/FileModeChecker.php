<?php

class TPInstaller_FileModeChecker
{
	const STATUS_UNKNOWN   = 0;
	const STATUS_OK        = 1;
	const STATUS_NG        = -1;
	const STATUS_NOT_FOUND = -2;

	const FILETYPE_UNKNOWN   = 0;
	const FILETYPE_DIR       = 1;
	const FILETYPE_FILE      = 2;

	protected $rootPath  = '';
	protected $trustPath = '';
	protected $targtes = array();

	public function __construct($rootPath, $trustPath)
	{
		$this->rootPath  = $rootPath;
		$this->trustPath = $trustPath;
	}

	/**
	 * add function.
	 * 
	 * @param string|array $filename
	 * @param int $filetype
	 * @return void
	 */
	public function add($filename, $filetype = self::FILETYPE_UNKNOWN)
	{
		if ( is_array($filename) === true ) {
			$this->_addTargets($filename, $filetype);
		} else {
			$this->_addTarget($filename, $filetype);
		}
	}

	/**
	 * addFile function.
	 * @param string|array $filename
	 * @return void
	 */
	public function addFile($filename)
	{
		$this->add($filename, self::FILETYPE_FILE);
	}

	/**
	 * addDirectory function.
	 * @param string|array $filename
	 * @return void
	 */
	public function addDirectory($filename)
	{
		$this->add($filename, self::FILETYPE_DIR);
	}

	/**
	 * check function.
	 * @return void
	 */
	public function check()
	{
		$this->_setUpFiletypes();
		$this->_changeMode();
		$this->_checkMode();
	}

	/**
	 * hasError function.
	 * @param mixed $wizard
	 * @return bool
	 */
	public function hasError()
	{
		foreach ( $this->targets as $target ) {
			if ( $target['status'] === self::STATUS_OK ) {
				// No problem
			} else {
				return true;
			}
		}

		return false;
	}

	/**
	 * getReport function.
	 * @param SimpleWizard $wizard
	 * @return void
	 */
	public function report(SimpleWizard $wizard)
	{
		$messages = array(
			self::FILETYPE_DIR => array(
				self::STATUS_OK        => _INSTALL_L86,
				self::STATUS_NG        => _INSTALL_L85,
				self::STATUS_NOT_FOUND => _INSTALL_DIRECTORY_NOT_FOUND,
			),
			self::FILETYPE_FILE => array(
				self::STATUS_OK        => _INSTALL_L84,
				self::STATUS_NG        => _INSTALL_L83,
				self::STATUS_NOT_FOUND => _INSTALL_FILE_NOT_FOUND,
			),
			self::FILETYPE_UNKNOWN => array(
				self::STATUS_OK        => _INSTALL_L84,
				self::STATUS_NG        => _INSTALL_L83,
				self::STATUS_NOT_FOUND => _INSTALL_FILE_NOT_FOUND,
			),
		);

		foreach ( $this->targets as $target ) {
			$status   = $target['status'];
			$filetype = $target['filetype'];
			$message  = $messages[$filetype][$status];

			if ( $status === self::STATUS_OK ) {
				$wizard->addArray('checks', _OKIMG.sprintf($message, $target['filename']));
			}else{
				$wizard->addArray('checks', _NGIMG.sprintf($message, $target['filename']));
			}
		}
	}

	/**
	 * getHints function.
	 * @return string
	 */
	public function getHints()
	{
		$hints = array();

		$commands = array(
			self::FILETYPE_DIR => array(
				self::STATUS_OK        => '',
				self::STATUS_NG        => "chmod 777 {filename}",
				self::STATUS_NOT_FOUND => "mkdir {filename}\nchmod 777 {filename}",
			),
			self::FILETYPE_FILE => array(
				self::STATUS_OK        => '',
				self::STATUS_NG        => "chmod 666 {filename}",
				self::STATUS_NOT_FOUND => "touch {filename}\nchmod 666 {filename}",
			),
		);

		foreach ( $this->targets as $target ) {
			$status   = $target['status'];
			$filetype = $target['filetype'];

			if ( $filetype === self::FILETYPE_UNKNOWN ) {
				continue;
			}

			$command  = $commands[$filetype][$status];
			$command  = str_replace('{filename}', $target['filename'], $command);
			$hints[]  = $command;
		}
		
		$hints = implode("\n", $hints);
		
		return $hints;
	}

	/**
	 * reset function.
	 * @return void
	 */
	public function reset()
	{
		$this->targets = array();
	}

	/**
	 * _setUpFiletypes function.
	 * @return void
	 */
	protected function _setUpFiletypes()
	{
		foreach ( $this->targets as &$target ) {

			if ( file_exists($target['filename']) === false ) {
				continue;
			}

			if ( is_dir($target['filename']) === true ) {
				$target['filetype'] = self::FILETYPE_DIR;
			} else {
				$target['filetype'] = self::FILETYPE_FILE;
			}
		}
	}

	/**
	 * _changeMode function.
	 * @return void
	 */
	protected function _changeMode()
	{
		foreach ( $this->targets as &$target ) {
		
			if ( $target['filetype'] === self::FILETYPE_UNKNOWN ) {
				continue;
			}
		
			if ( $target['filetype'] === self::FILETYPE_DIR ) {
				$mode = 0777;
			} else {
				$mode = 0666;
			}

			if ( file_exists($target['filename']) === false ) {
				if ( $target['filetype'] === self::FILETYPE_DIR ) {
					mkdir($target['filename']);
				} else {
					touch($target['filename']);
				}
			}

			@chmod($target['filename'], $mode);
		}
	}

	/**
	 * _checkMode function.
	 * @return void
	 */
	protected function _checkMode()
	{
		foreach ( $this->targets as &$target ) {

			if ( $target['filetype'] === self::FILETYPE_UNKNOWN ) {
				$target['status'] = self::STATUS_NOT_FOUND;
				continue;
			}

			if ( file_exists($target['filename']) === false ) {
				$target['status'] = self::STATUS_NOT_FOUND;
				continue;
			}

			if ( is_writable($target['filename']) === true ) {
				$target['status'] = self::STATUS_OK;
			} else {
				$target['status'] = self::STATUS_NG;
			}
		}
	}

	/**
	 * _addTarget function.
	 * @param string $filename
	 * @param int $filetype
	 * @return void
	 */
	protected function _addTarget($filename, $filetype = self::FILETYPE_UNKNOWN)
	{
		$this->targets[] = array(
			'filename' => $filename,
			'status'   => self::STATUS_UNKNOWN,
			'filetype' => $filetype,
		);
	}

	/**
	 * _addTargets function.
	 * @param array $filenames
	 * @param int $filetype
	 * @return void
	 */
	protected function _addTargets(array $filenames, $filetype = self::FILETYPE_UNKNOWN)
	{
		foreach ( $filenames as $filename ) {
			$this->_addTarget($filename, $filetype);
		}
	}
}
