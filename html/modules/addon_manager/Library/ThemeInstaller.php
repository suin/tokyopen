<?php
require_once TP_RYUS_PATH.'/File.php';
/**
* 
*/
class AddonManager_Library_ThemeInstaller extends AddonManager_Abstract_Installer
{
    protected function _getDownloadFilePath()
    {
        $downloadPath = TP_ADDON_MANAGER_TMP_PATH .'/'.$this->targetKeyName.'/'. $this->targetKeyName . '.zip';
        return $downloadPath;
    }
    
    public function downloadFile()
    {
        chdir(TP_ADDON_MANAGER_TMP_PATH);
        mkdir($this->targetKeyName);
        
        parent::downloadFile();
    }
    
    
    public function unzipFile()
    {
        // local file name
        $downloadPath = $this->_getDownloadFilePath();
        
        $this->_doUnzip($downloadPath, $this->targetKeyName);
        
        // delete zip file
        unlink($downloadPath);
    }
    
    protected function _doUnzip($file)
    {
        chdir(TP_ADDON_MANAGER_TMP_PATH);
        chdir($this->targetKeyName);

        $zip = new ZipArchive;
        if ($zip->open($file) === TRUE) {
            if($zip->extractTo('./') === false){
                throw new Exception("unzip fail", 1);
            };
            $zip->close();
        } else {
            throw new Exception("unzip fail", 1);
        }
        return true;
        
    }

    
    public function copyFiles()
    {
        $ftp = new AddonManager_Library_Ftp($this->ftpUser, $this->ftpPass, XOOPS_ROOT_PATH);
        $ftp->connect();
        
        $uploadPath = XOOPS_ROOT_PATH .'/themes/'; 
        
        // 解凍してできたフォルダがどれかわからないとダメ-> tmp/id/id.zip -> unzip -> tmp/id/unzipedfolder/
        $unzipPath = TP_ADDON_MANAGER_TMP_PATH .'/'. $this->targetKeyName . '/' ;
        
        
        $ftp->copyNakami($unzipPath, $uploadPath);
        
        Ryus_File::removeDirectory(TP_ADDON_MANAGER_TMP_PATH.'/'.$this->targetKeyName);
    }
    
    public function getNextLink()
    {
        $ret = array();
        $ret['url'] = XOOPS_URL.'/modules/legacy/admin/index.php?action=ThemeList';
        $ret['text'] = t('ThemeList');
        return $ret;
    }
    
}
