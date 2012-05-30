<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     dbkmarken_bm
 * Version:  1.0
 * Date:     Aug 22, 2008
 * Author:   HIKAWA Kilica
 * Purpose:  Display Tagcloud of requested page
 * Input:    url=target url you want to show tagcloud
 *           bmDirname=dbkmarken dirname(required)
 * Examples: {dbkmarken_bm bmDirname=bookmark}
 * -------------------------------------------------------------
 */
 
function smarty_function_dbkmarken_bm($params, &$smarty)
{
	//test code for service in smarty

	//get category lists from xcat service
	$root =& XCube_Root::getSingleton();
	$service = $root->mServiceManager->getService("Dbkmarken_BmService");
	$client = $root->mServiceManager->createClient($service);

	//get preflist
	$html = "";
	$bmArr = array();

	if (is_object($client)) {
		$url = ($params['url']) ? $params['url'] : htmlspecialchars('http://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], ENT_QUOTES);
		$bmArr = $client->call('getBm', array('url'=>$url, 'mybm'=>false, 'dirname'=>$params['bmDirname']));
	}
	$memberHandler =& xoops_gethandler('member');
	if($bmArr){
		$html .= "<ul>";
		foreach(array_keys($bmArr) as $key){
			$user =& $memberHandler->getUser($bmArr[$key]['uid']);
			$html .= '<li>'. $user->getShow('uname') .' : '. implode(",", $bmArr[$key]['tags']) .'</li>'; 
		}
		$html .= '</ul>';
	}
	echo $html;
}
?>
