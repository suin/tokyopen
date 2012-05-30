<?php

require_once TP_RYUS_PATH.'/File.php';

class AddonManager_Controller_AdminInstall extends AddonManager_Abstract_Controller
{
    protected $tmpDirname = 'addon_manager';
    
    protected $installer;
    
    protected $targetKey;
    
    protected $targetType;
    
	public function __construct()
	{
	    global $xoopsModuleConfig;
		parent::__construct();
		
		$ftpUser = $this->_getConfig('ftp_user');
		$ftpPass = $this->_getConfig('ftp_pass');
		$addonDownloadUrlFormat = $this->_getConfig('addon_download_url_format');
        $themeDownLoadUrlFormat = $this->_getConfig('theme_download_url_format');
        
        $this->targetKey = $this->get('target_key');
        $this->targetType = $this->get('target_type');

        switch ($this->targetType) {
            case 'X2Module':
                $this->installer = new AddonManager_Library_X2ModuleInstaller($ftpUser, $ftpPass, $addonDownloadUrlFormat, $this->targetKey);
                break;
            case 'TrustModule':
                $this->installer = new AddonManager_Library_TrustModuleInstaller($ftpUser, $ftpPass, $addonDownloadUrlFormat, $this->targetKey);
                break;
            case 'Theme':
                $this->installer = new AddonManager_Library_ThemeInstaller($ftpUser, $ftpPass, $themeDownLoadUrlFormat, $this->targetKey);
                break;
            default:
                die('unknown install type');
                break;
        }
        
        
	    $this->output['target_key'] = $this->targetKey;
	    $this->output['target_type'] = $this->targetType;
	}
	
	protected function _setting()
	{
	    define('TP_ADDON_MANAGER_TMP_PATH', TP_TEMPORARY_PATH . '/' . $this->tmpDirname);
       
	}

	public function main()
	{
	    $this->_setting();
	    try {
	        
    	    if(method_exists($this, $this->Action)){

    	        call_user_func(array($this, $this->Action));
    	    }else{
        	    // なければ_default実行
    	        $this->_default();
    	    }
	       
	    } catch (Exception $e) {
	        $this->_cleanup();
            $this->template = 'pen:addon_manager.admin_error.tpl';
    	    $this->output['error_message'] = $e->getMessage();
            $this->_view();
	    }
	}
	
	protected function _cleanup()
	{
        Ryus_File::removeDirectory(TP_ADDON_MANAGER_TMP_PATH);
	}
	
	protected function _makeTmpDir()
	{
	    // 最初の1回だけ作成
	    
	    chdir(TP_TEMPORARY_PATH);
	    if(mkdir($this->tmpDirname) === false){
	        exit();
            throw new Exception(t("make temporary directory fail"), 1);
	    }
	}
	
	protected function _default()
	{
	    if($this->targetType == 'Theme'){
	        $this->output['title'] = t("Theme Install");
	    }else{
	        $this->output['title'] = t("Module Install");
	    }
	    $this->_view();
	}


	protected function _unzip()
	{

	    $this->installer->unzipFile($this->targetKey);
		$this->_view();
	}
	
	protected function _download()
	{
	    $this->_makeTmpDir();
	    $this->installer->downloadFile($this->targetKey);
		$this->_view();
	}
	
	protected function _copy()
	{
	    $this->installer->copyFiles($this->targetKey);
	    $this->output['next_link'] = $this->installer->getNextLink();
	    $this->_cleanup();
		$this->_view();
	}
    
}