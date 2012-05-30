<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     dbkmarken_bmform
 * Version:  1.1
 * Date:     Aug 25, 2008
 * Author:   HIKAWA Kilica
 * Purpose:  add tag form to any template
 * Input:    url=target url you want to show tagcloud
 *           mybm=true:only bookmark of the user about this page. 
 *                false:all bookmarks about this page.
 *           bmDirname=dbkmarken dirname(required)
 * Examples: {dbkmarken_bmform url="http://dummy/" bmDirname="bookmark"}
 * -------------------------------------------------------------
 */
 
function smarty_function_dbkmarken_bmform($params, &$smarty)
{
	$root =& XCube_Root::getSingleton();

	//only registered user
	if(! $root->mContext->mXoopsUser){
		return false;
	}

	//get category lists from xcat service
	$service = $root->mServiceManager->getService("Dbkmarken_BmService");
	$client = $root->mServiceManager->createClient($service);

	$bmArr = array();

	//setup XCube_Token:Bm
	$bmTokenName = 'module.dbkmarken.BmEditForm.TOKEN';
	if(! $_SESSION['XCUBE_TOKEN'][$bmTokenName]){
		
		srand(microtime() * 100000);
		$salt = $root->getSiteConfig('Cube', 'Salt');
		$bmToken = md5($salt . uniqid(rand(), true));
		$_SESSION['XCUBE_TOKEN'][$bmTokenName] = $bmToken;
	}
	else{
		$bmToken = $_SESSION['XCUBE_TOKEN'][$bmTokenName];
	}
	//setup XCube_Token:Tag
	$tagTokenName = 'module.dbkmarken.TagEditForm.TOKEN';
	if(! $_SESSION['XCUBE_TOKEN'][$tagTokenName]){
		
		srand(microtime() * 100000);
		$salt = $root->getSiteConfig('Cube', 'Salt');
		$tagToken = md5($salt . uniqid(rand(), true));
		$_SESSION['XCUBE_TOKEN'][$tagTokenName] = $tagToken;
	}
	else{
		$tagToken = $_SESSION['XCUBE_TOKEN'][$tagTokenName];
	}

	if (is_object($client)) {
		$protocol = ($_SERVER['HTTPS'] || $_SERVER["SERVER_PROTOCOL"] = "https") ? 'https' : 'http';
		//$url = ($params['url']) ? $params['url'] : htmlspecialchars('http://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], ENT_QUOTES);
		$url = ($params['url']) ? $params['url'] : htmlspecialchars($protocol. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], ENT_QUOTES);
		$bmArr = $client->call('getBm', array('url'=>$url, 'mybm'=>true, 'dirname'=>$params['bmDirname']));
	}

	$bmId = ($bmArr[0]['bm_id']) ? $bmArr[0]['bm_id'] : 0;
	$tagArr = (is_array($bmArr[0]['tags'])) ? $bmArr[0]['tags'] : array();

	$tags = "";
	foreach(array_keys($tagArr) as $key){
		$tags .= '['. $tagArr[$key] .']';
	}

	$html = "";

	$html = '<form action="'. XOOPS_URL .'/modules/'. $params['bmDirname'] .'/index.php?action=BmEdit" method="post">
		<input type="hidden" name="'. $bmTokenName .'" value="'. $bmToken .'">
		<input type="hidden" name="'. $tagTokenName .'" value="'. $tagToken .'">
		<input type="hidden" name="dirname" value="'. $params['bmDirname'] .'">
		<input type="hidden" name="bm_id" value="'. $bmId .'">
		<input type="hidden" name="bm_title" value="'. $root->mContext->getAttribute('legacy_pagetitle') .'"}>
		<input type="hidden" name="uid" value="'. $root->mContext->mXoopsUser->get('uid') .'">
		<input type="hidden" name="memo" value="">
		<input type="hidden" name="url" value="'. $url .'">
		<input type="hidden" name="ret" value="1">
		<input type="text" name="tag_name" value="'. $tags .'" size=50 maxlength=255>
		<input type="submit" class="formButton" value="'. _SUBMIT .'"/>
		</form>';

	echo $html;
}
?>
