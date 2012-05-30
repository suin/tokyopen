<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     dbkmarken_rater
 * Version:  1.0
 * Date:     Aug 28, 2008
 * Author:   HIKAWA Kilica
 * Purpose:  Add Ajax Rating Star to any template
 * Input:    url=target url you want to show tagcloud
 *           star=number of stars for max value
 *           bmDirname=dbkmarken dirname(required)
 *           loadJQuery=load jQuery Libraries
 * Examples: {dbkmarken_bmform url="http://dummy/" bmDirname="bookmark"}
 * -------------------------------------------------------------
 */
 
function smarty_function_dbkmarken_rater($params, &$smarty)
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
		$url = ($params['url']) ? $params['url'] : htmlspecialchars('http://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], ENT_QUOTES);
		$bmArr = $client->call('getBm', array('url'=>$url, 'mybm'=>true, 'dirname'=>$params['bmDirname']));
	}

	$bmId = ($bmArr[0]['bm_id']) ? $bmArr[0]['bm_id'] : 0;
	$tagArr = (is_array($bmArr[0]['tags'])) ? $bmArr[0]['tags'] : array();

	$tags = "";
	foreach(array_keys($tagArr) as $key){
		$tags .= '['. $tagArr[$key] .']';
	}

	$html = "";
	$star = ($params['star']) ? $params['star'] : 1;

	//load jQuery
	if($params['loadJQuery']==1){
		$html = '<script type="text/javascript" src="http://www.google.com/jsapi"></script>';
	}
	$html .= '<link href="'. XOOPS_URL .'/common/jquery/rater/rater.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
	google.load("language", "1");
	google.load("jquery", "1");
	</script>

	<script type="text/javascript">
		google.setOnLoadCallback(function() {
			$(function (){
			  function starRater(res){
				$("#'. $params['bmDirname'] .'_rater").empty().rater(
					"'. XOOPS_URL .'/modules/'. $params['bmDirname'] .'/index.php?action=BmRater&'. $bmTokenName .'='. $bmToken .'&'. $tagTokenName .'='. $tagToken .'" , {
						maxvalue : '. $star .', 
						style    : "basic", 
						curvalue : res||0,
						callback : starRater
				});
			  }
			  starRater(0);
			});
		});
	</script>
	<div id="'. $params['bmDirname'] .'_rater"></div>';

	echo $html;
}
?>
