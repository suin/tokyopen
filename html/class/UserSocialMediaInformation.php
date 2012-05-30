<?php
/**
 * @file
 * @package tokyopen
 * @version $Id$
 */

class User_sosial_media_information
{
	public $tableName = 'user_social_media_information';
	public $db;
	
	public function __construct()
	{
		global $xoopsDB;
		$this->db = $xoopsDB;
	}
	
	public function getRecord($media_name, $id_name, $id)
	{
		$sql_default = "select * from ".$this->db->prefix($this->tableName)." where ";
		$where = 'usm_social_media_name = \'%s\' and %s = \'%s\'';
		$sql = $sql_default . sprintf($where, $media_name, $id_name, $id);
//echo "<div>".$sql."</div>";
		
		$results = $this->db->query($sql);
		$result = $this->db->fetchArray($results);

//echo "<div>".$result."</div>";

		return $result;
	}
	
	public	function checkSocialLogin($uid)
	{
/*
		if (is_object($xoopsUser)) {
			return;
		}
*/
		$root =& XCube_Root::getSingleton();
		$root->mLanguageManager->loadModuleMessageCatalog('user');
		$userHandler =& xoops_getmodulehandler('users', 'user');
		
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('uid', $uid));
		
		$userArr =& $userHandler->getObjects($criteria);
//echo "<br>checkSocialLogin111111111";
		if (count($userArr) != 1) {
			return;
		}
//echo "<br>checkSocialLogin22222222222";
		
		if ($userArr[0]->get('level') == 0) {
			// TODO We should use message "_MD_USER_LANG_NOACTTPADM"
			return;
		}
//echo "<br>checkSocialLogin33333333333";
		
		$handler =& xoops_gethandler('user');
		$user =& $handler->get($userArr[0]->get('uid'));
		
		if (is_callable(array($user, "getNumGroups"))) { // Compatible for replaced handler.
			if ($user->getNumGroups() == 0) {
//echo "<br>checkSocialLogin44444444444";
				return;
			}
		}
		else {
			$groups = $user->getGroups();
			if (count($groups) == 0) {
//echo "<br>checkSocialLogin555555555555555";
				return;
			}
		}
//echo "<br>checkSocialLogin6666666666";

		$xoopsUser = $user;
	
		//
		// Regist to session
		//
		$root->mSession->regenerate();
		$_SESSION = array();
		$_SESSION['xoopsUserId'] = $xoopsUser->get('uid');
		$_SESSION['xoopsUserGroups'] = $xoopsUser->getGroups();

	}
	
}
