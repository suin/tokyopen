<?php
abstract class AddonManager_Abstract_Installer
{
    protected $downloadUrlFormat ='';
    protected $ftpUser='';
    protected $ftpPass='';
    
    protected $targetKeyName;
    
    function __construct($ftpUser, $ftpPass, $downloadUrlFormat, $targetKeyName) {
        $this->ftpUser = $ftpUser;
        $this->ftpPass = $ftpPass;
        $this->downloadUrlFormat = $downloadUrlFormat;
        
        $this->targetKeyName = $targetKeyName;
    }
    
    public function downloadFile()
    {
        
        // TODO ファイルNotFound対策
        $url = sprintf($this->downloadUrlFormat, $this->targetKeyName);
        $downloadPath = $this->_getDownloadFilePath();
        $ch = curl_init($url);
        if($ch === false){
            throw new Exception(t('curl_init fail'), 1);
        }

        $fp = fopen($downloadPath, "w");

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $result = curl_exec($ch);
        if($result === false){
            throw new Exception("curl exec fail", 1);
            
        }
        fclose($fp);
    }
    
    public function unzipFile()
    {
        // local file name
        $downloadPath = $this->_getDownloadFilePath();
        
        $this->_doUnzip($downloadPath);
    }
    
    abstract public function copyFiles();
    
    abstract protected function _getDownloadFilePath();

    abstract protected function _doUnzip($file);

    /**
     * undocumented function
     *
     * @return array('url' => linkurl, 'text' => linktext)
     * @author ryuji
     */
    abstract public function getNextLink();
    
    
}
