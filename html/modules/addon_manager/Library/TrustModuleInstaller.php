<?php

/**
* 
*/
class AddonManager_Library_TrustModuleInstaller extends AddonManager_Abstract_Installer
{
    protected function _getDownloadFilePath()
    {
        $downloadPath = TP_ADDON_MANAGER_TMP_PATH .'/'. $this->targetKeyName . '.tgz';
        return $downloadPath;
    }
    
    protected function _doUnzip($file)
    {
        chdir(TP_ADDON_MANAGER_TMP_PATH);

        $zip = new ZipArchive;
        if ($zip->open($file) === TRUE) {
            $zip->extractTo('./');
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

        // copy html
        $uploadPath = XOOPS_ROOT_PATH . '/' ; 
        $unzipPath = TP_ADDON_MANAGER_TMP_PATH .'/'. $this->targetKeyName .'/html';
        $ftp->copyNakami($unzipPath, $uploadPath);
        
        // copy xoops_trust_path
        $uploadPath = XOOPS_TRUST_PATH . '/' ; 
        $unzipPath = TP_ADDON_MANAGER_TMP_PATH .'/'. $this->targetKeyName .'/xoops_trust_path/';
        $ftp->copyNakami($unzipPath, $uploadPath);
        
        
    }
    public function getNextLink()
    {
        $ret = array();
        $ret['url'] = '../../legacy/admin/index.php?action=ModuleInstall&dirname='.$this->targetKeyName;
        $ret['text'] = t('Module Install');
        return $ret;
    }
    
}

