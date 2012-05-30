<?php
/*
 * $Id: modifier.escape4qcart.php 260 2008-02-23 15:35:32Z tohokuaiki $
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     escape4qcart
 * Purpose:  Flash Escape
 * -------------------------------------------------------------
 */

function smarty_modifier_xanhte_escape4flash($str)
{
	$replace = array(
		',' => '',
		'"' => '',
		';' => '',
		'&' => '',
		"'" => "\'",
		);
	$before  = array();
	$after   = array();
	foreach  ($replace as $k=>$v){
		$before[] = $k;
		$after[]  = $v;
	}
	return str_replace($before, $after, html_entity_decode($str, ENT_QUOTES));
	
}
