<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty number_format_filesize modifier plugin
 *
 * Type:     modifier<br>
 * Name:     number_format_filesize<br>
 * Purpose:  convert int to relative filesize string
 * @link http://smarty.php.net/manual/en/language.modifier.upper.php
 *          upper (Smarty online manual)
 * @author   itoh takashi
 * @param string
 * @return string
 */
function smarty_modifier_number_format_filesize($string)
{
	$size = intval($string);
	$unit = "Byte";
	
	$units = array(
		1             => "B",
		1000          => "kB",
		1000000       => "MB",
		1000000000    => "GB",
		1000000000000 => "TB",
		);
	
	foreach ($units as $key=>$value){
		if ($size/$key<1000){
			$s = sprintf("%f", $size/$key);
			return trim(substr($s, 0, 4),".") . $value;
			break;
		}
	}
	
	return $string;
}

?>
