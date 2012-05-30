<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     autolink<br>
 * Date:     Feb 26, 2003
 * Purpose:  convert \r\n, \r or \n to <<br>>
 * Input:<br>
 *         - contents = contents to replace
 *         - preceed_test = if true, includes preceeding break tags
 *           in replacement
 * Example:  {$text|autolink}
 * @link http://smarty.php.net/manual/en/language.modifier.autolink.php
 *          autolink (Smarty online manual)
 * @version  1.0
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function smarty_modifier_autolink($string)
{
/*	$string = preg_replace("/(^|[^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([^, \r\n\"\(\)'<>]+)/i",
						   "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>",
						   $string); */
						   
	$patterns[] = "/(^|[^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([^, \r\n\"\(\)'<>]+)/i";
	$replacements[] = "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>";
	$patterns[] = "/(^|[^]_a-z0-9-=\"'\/])www\.([a-z0-9\-]+)\.([^, \r\n\"\(\)'<>]+)/i";
	$replacements[] = "\\1<a href=\"http://www.\\2.\\3\" target=\"_blank\">www.\\2.\\3</a>";
	$patterns[] = "/(^|[^]_a-z0-9-=\"'\/:\.])([a-z0-9\-_\.]+?)@([^, \r\n\"\(\)'<>\[\]]+)/i";
	$replacements[] = "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>";
	
	$string = preg_replace($patterns, $replacements, $string);
	
    return $string;
}

/* vim: set expandtab: */

?>
