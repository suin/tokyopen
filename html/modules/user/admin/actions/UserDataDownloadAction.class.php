<?php
/**
 * @package user
 * @version $Id: UserDataDownloadAction.class.php,v 1.1 2007/05/15 02:34:42 minahito Exp $
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

require_once XOOPS_MODULE_PATH . "/user/class/AbstractListAction.class.php";

class User_UserDataDownloadAction extends User_Action
{
	function &_getHandler()
	{
		$handler =& xoops_getmodulehandler('users');
		return $handler;
	}

	function _getBaseUrl()
	{
		return "./index.php?action=UserDataDownload";
	}

	function executeViewIndex(&$controller, &$xoopsUser, &$render)
	{
		$render->setTemplateName("user_data_download.html");
		$member_handler =& xoops_gethandler('member');
		$user_count = $member_handler->getUserCount();
		$render->setAttribute('user_count', $user_count);
	}

	function getDefaultView(&$controller, &$xoopsUser)
	{
		return USER_FRAME_VIEW_INDEX;
	}

	/// CSVファイルを出力する
	function execute(&$controller, &$xoopsUser)
	{
		$filename = sprintf('User data List %s.csv', date('Ymd'));
		$text = '';
		$field_line = '';

		$user_handler =& $this->_getHandler();
		$result = $user_handler->db->query('SELECT * FROM '.$user_handler->db->prefix('users').' ORDER BY uid');
		if (!$result){
			return USER_FRAME_VIEW_INDEX;
		}
		$user = $user_handler->db->fetchArray($result);
		if (!$user){
			return USER_FRAME_VIEW_INDEX;
		}
		foreach ($user as $key=>$var){
			$_f = '_MD_USER_LANG_'.strtoupper($key);
			$field_line .= (defined($_f) ? constant($_f) : $key).",";
		}
		$field_line .= "\n";

		header('Pragma: 1');
		header('Cache-control: private, max-age=60, pre-check=30');
		if( preg_match('/firefox/i' , $_SERVER['HTTP_USER_AGENT']) ){
			header("Content-Type: application/x-csv");
		}else{
			header("Content-Type: application/vnd.ms-excel");
		}
		header("Content-Disposition: attachment ; filename=\"{$filename}\"") ;

		if (strncasecmp($GLOBALS['xoopsConfig']['language'], 'ja', 2)===0){
			mb_convert_variables('sjis-win', _CHARSET, $field_line);
		}
		echo $field_line;
		while($user) {
			$user_data = '';
			foreach ($user as $key=>$value){
				switch ($key){
					case 'user_regdate':
					case 'last_login':
						$value = $value ? formatTimestamp($value, 'Y/n/j H:i') : '';
						break;
					default:
				}
				if (preg_match('/[,"\r\n]/', $value)) {
					$value = preg_replace('/"/', "\"\"", $value);
					$value = "\"$value\"";
				}
				$user_data .= $value . ',';
			}
			$text = trim($user_data, ',')."\n";

			/// japanese
			if (strncasecmp($GLOBALS['xoopsConfig']['language'], 'ja', 2)===0){
				mb_convert_variables('SJIS', _CHARSET, $text);
			}
			echo $text;

			$user = $user_handler->db->fetchArray($result);
		}
		exit();
	}
}

?>