<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     dbkmarken_tagcloud
 * Version:  1.0
 * Date:     Aug 22, 2008
 * Author:   HIKAWA Kilica
 * Purpose:  Display Tagcloud of requested page
 * Input:    max=tagcloud max font size(%)
 *           min=tagcloud min font size(%)
 *           bmDirname=dbkmarken dirname(required)
 *           url=target url you want to show tagcloud
 * Examples: {dbkmarken_tagcloud bmDirname=bookmark max=150 min=80}
 * -------------------------------------------------------------
 */

function smarty_function_dbkmarken_tagcloud($params, &$smarty)
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
		//get prefecture list from xcat service
		$url = ($params['url']) ? $params['url'] : htmlspecialchars('http://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], ENT_QUOTES);
		$bmArr = $client->call('getBm', array('url'=>$url, 'dirname'=>$params['bmDirname']));
	}
	if(! is_array($bmArr)){
		echo $html;
		return;
	}

	foreach(array_keys($bmArr) as $bKey){
		foreach(array_keys($bmArr[$bKey]['tags']) as $tKey){
			$tagArr[] = $bmArr[$bKey]['tags'][$tKey];
		}
	}
	
	$tagCount = array_count_values($tagArr);

	// change these font sizes if you will
	$max_size = ($params['max']) ? $params['max'] : 120; // max font size in %
	$min_size = ($params['min']) ? $params['min'] : 60; // min font size in %

	// get the largest and smallest array values
	if($tagCount){
		$max_qty = max(array_values($tagCount));
		$min_qty = min(array_values($tagCount));
	}

	// find the range of values
	$spread = $max_qty - $min_qty;
	if (0 == $spread) { // we don't want to divide by zero
	    $spread = 1;
	}

	// determine the font-size increment
	// this is the increase per tag quantity (times used)
	$step = ($max_size - $min_size)/($spread);

	// loop through our tag array
	foreach ($tagCount as $key => $value) {

	    // calculate CSS font-size
	    // find the $value in excess of $min_qty
	    // multiply by the font-size increment ($size)
	    // and add the $min_size set above
	    $size = $min_size + (($value - $min_qty) * $step);
	    // uncomment if you want sizes in whole %:
	    // $size = ceil($size);

	    // you'll need to put the link destination in place of the #
	    // (assuming your tag links to some sort of details page)
	    $html = $html .'<a href="'. XOOPS_URL .'/modules/'. $params['bmDirname'] .'/index.php?action=TagList&tag_name='. urlencode($key) .'" style="font-size: '.$size.'%">'.$key.'</a> ';
	    // notice the space at the end of the link
	}
	echo $html;
}
?>
