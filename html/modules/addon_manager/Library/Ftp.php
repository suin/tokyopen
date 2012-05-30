<?php

class AddonManager_Library_Ftp
{
    protected $con;
    protected $user;
    protected $pass;
    protected $xoops_root_path;
    
    function __construct($user, $pass, $xoops_root_path)
    {
        $this->user = $user;
        $this->pass = $pass;
        $this->xoops_root_path = $xoops_root_path;
    }
    
    public function connect()
    {
        $this->con = ftp_connect('127.0.0.1', 21, 10);//TODO  port 21, timeout 10sec
        if($this->con === false){
            // 接続エラー
            throw new Exception(t("ftp connect fail"), 1);
        }
        $result = ftp_login($this->con, $this->user, $this->pass);
        if($result === false){
            throw new Exception(t("ftp login fail"), 1);
        }
        
    }
    
    public function copy($sourcePath, $targetPath)
    {
        $this->ftpPut($sourcePath, $targetPath, $this->con);
    }
    
    public function copyNakami($sourcePath, $targetPath)
    {
        $this->ftpPutNakami($sourcePath, $targetPath, $this->con);
    }
    
    
    /**
     * ftp rootの絶対パスを返す ex /home/ryuji/public_htmlにxoopsがあり、ftp rootが /home/ryuji/ だったら 戻り値は /home/ryuji
     *　さらに $xoops_root_pathで指定されたディレクトリへ移動する
     * @param string $con 
     * @param string $xoops_root_path 
     * @return void
     * @author ryuji
     * DIRECTORY_SEPARETERを使わないで'/'にしている。WinFileZillaでセパレータに\を使うとftp_chdirできないため
     * 
     */
	protected function seekFTPRoot($con)
	{
	    $xoops_root_path = $this->xoops_root_path;
		static $ftp_root ;
		
		if (!is_null($ftp_root)){
			return $ftp_root ;
		}
		
		$path = explode('/', $xoops_root_path);
		
		$current_path = '';
		for ($i=count($path)-1; $i>=0 ;$i--){
			$current_path = '/'.$path[$i].$current_path;
			if (@ftp_chdir($con, $current_path)){
				$ftp_root = substr($xoops_root_path, 0, strrpos($xoops_root_path, $current_path));
				return $ftp_root;
			}
		}

        throw new Exception(t("seekFTP fail"), 1);
		
		return false;
	}


    protected function _ftpPutSub($local_path, $remote_path, $remote_pos, $con)
    {
		$ftp_root = $this->seekFTPRoot($con);
		$file_list = $this->getFileList($local_path);
		$dir = $file_list['dir'];
		krsort($dir);


		foreach ($dir as $directory){
			$remote_directory = $remote_path.substr($directory, $remote_pos);
			if (!is_dir($remote_directory)){ 
			    $this->ftp_mkdir($con, $remote_directory);
			}
		}

		/// put files
		ftp_chdir($con, '/');
		foreach ($file_list['file'] as $l_file){
			$r_file = $remote_path.substr($l_file, $remote_pos ); // +1 is remove first flash
			$ftp_remote_file = substr($r_file, strlen($ftp_root));
			ftp_put($con, $ftp_remote_file, $l_file, FTP_BINARY);
		}
    }
    
	protected function ftpPut($local_path, $remote_path, $con)
	{
	    $targetDirName = basename($local_path);
		if (!is_dir($remote_path.$targetDirName)){
			$this->ftp_mkdir($con, $remote_path.$targetDirName);
		}
		$remote_pos = strlen(dirname($local_path)) + 1;
		
		$this->_ftpPutSub($local_path, $remote_path, $remote_pos, $con);
    }
    

	protected function ftpPutNakami($local_path, $remote_path, $con)
	{
		$remote_pos = strlen($local_path) + 1;
		$this->_ftpPutSub($local_path, $remote_path, $remote_pos, $con);
		
    }
    
	private function  getFileList($dir, $list=array('dir'=> array(), 'file' => array()))
	{
		if (is_dir($dir) == false) {
			return;
		}

		$dh = opendir($dir);
		if ($dh) {
			while (($file = readdir($dh)) !== false) {
				if ($file == '.' || $file == '..'){
					continue;
				}
				else if (is_dir("$dir/$file")) {
					$list = $this->getFileList("$dir/$file", $list);
					$list['dir'][] = "$dir/$file";
				}
				else {
					$list['file'][] = "$dir/$file";
				}
			}
		}
		closedir($dh);
		return $list;
	}
	
	
	/**
	 * undocumented function
	 *
	 * @param ftp resource $con 
	 * @param string $dir ローカルの絶対パス
	 * @return void
	 * @author ryuji
	 */
	protected function ftp_mkdir($con, $dir)
	{
	   $ftpRoot = $this->seekFTPRoot($con);
	   $localDir = substr($dir, strlen($ftpRoot));
	   return $this->ftpMkdirByFtpPath($con, $localDir);
	}
	
	/**
	 * ftp root からのパスで$dirは指定する
	 *
	 * @param string $con 
	 * @param string $dir 
	 * @return void
	 * @author ryuji
	 */
	protected function ftpMkdirByFtpPath($con, $dir)
	{
		$parent = dirname($dir);
        if ($dir === $parent) {
            return true;
        }

		$ftp_root = $this->seekFTPRoot($con);
		if (is_dir($ftp_root.$parent) === false) {
			if ($this->ftpMkdirByFtpPath($con, $parent) === false) {
                return false;
            }
        }
        return ftp_mkdir($con, $dir);
	}
    
}