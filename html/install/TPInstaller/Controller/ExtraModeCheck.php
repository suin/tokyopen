<?php

class TPInstaller_Controller_ExtraModeCheck extends TPInstaller_Abstract_Controller
{
	protected $settingManager = null;
	protected $checker = null;

	protected $rootPath  = '';
	protected $trustPath = '';
	protected $filePath  = '';

	protected $subdirectories = array(
		'cache',
		'log',
		'session',
		'templates_c',
		'tmp',
		'uploads',
	);

	public function setUp()
	{
		$this->_loadDefinitionCustom();
		$this->_setUpSettingManager();
		$this->_setUpPaths();
		$this->_setUpFileModeChecker();
	}

	public function run()
	{
		try {
			$this->_checkFilePath();
			$this->_checkSiteFilePath();
			$this->_checkSubdirectories();
// 既存モジュールはsiteFilePathに対応できていないので、どう解決するか決まったら実装する
//			$this->_checkModules();
		} catch ( Exception $e ) {
			// do nothing
		}

		$this->_render();
	}

	protected function _setUpSettingManager()
	{
		require_once TP_CUSTOM_DIR.'/class/settingmanager_hd.php';
		$this->settingManager = new setting_manager_hd();
		$this->settingManager->readConstant();
	}

	protected function _setUpPaths()
	{
		$this->rootPath     = $this->settingManager->root_path;
		$this->trustPath    = $this->settingManager->trust_path;
		$this->filePath     = $this->trustPath.'/file';
		$this->siteFilePath = $this->filePath.'/'.$this->settingManager->salt;
	}

	protected function _setUpFileModeChecker()
	{
		$this->checker = new TPInstaller_FileModeChecker($this->rootPath, $this->trustPath);
	}

	protected function _checkFilePath()
	{
		$this->checker->addDirectory($this->filePath);
		$this->checker->check();
		
		if ( $this->checker->hasError() === true ) {
			throw new RuntimeException();
		}
	}

	protected function _checkSiteFilePath()
	{
		$this->checker->addDirectory($this->siteFilePath);
		$this->checker->check();
		
		if ( $this->checker->hasError() === true ) {
			throw new RuntimeException();
		}
	}

	protected function _checkSubdirectories()
	{
		foreach ( $this->subdirectories as $subdirectory ) {
			$this->checker->addDirectory($this->siteFilePath.'/'.$subdirectory);
		}

		$this->checker->check();
		
		if ( $this->checker->hasError() === true ) {
			throw new RuntimeException();
		}
	}

	protected function _checkModules()
	{
		$files = glob($this->trustPath.'/modules/*/include/install_extramodcheck.inc.php');

		foreach ( $files as $file ) {
			require_once $file;

			$dirname  = basename(dirname(dirname($file)));
			$callback = 'get_writeoks_from_'.$dirname;

			if ( function_exists($callback) === false ) {
				continue;
			}

			$targets = $callback($this->rootPath, $dirname, $this->checker);
			
			if ( $targets === false ) {
				continue;
			}

			$this->checker->add($targets);
		}

		$this->checker->check();
		
		if ( $this->checker->hasError() === true ) {
			throw new RuntimeException();
		}
	}

	protected function _render()
	{
		$this->checker->report($this->wizard);

		if ( $this->checker->hasError() === true ) {
			$message = sprintf('hint:<br /><textarea onfocus="this.select();" style="width:100%%;height:60px;" readonly>%s</textarea><br />%s', $this->checker->getHints() , _INSTALL_L46);
			$this->wizard->assign('message', $message);
			$this->wizard->setReload(true);
		
		} else {
			$this->wizard->assign('message',_INSTALL_L87);
		}
		
		$this->wizard->render('install_modcheck.tpl.php');
	}
}
