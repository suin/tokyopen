<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string.
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
 *          truncate (Smarty online manual)
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @return string
 */
function smarty_modifier_mb_cut($string, $length = 80, $etc = '...')
{
    if ($length == 0)
        return '';
	
    if (mb_strlen($string) > $length) {
        $length -= round(mb_strlen($etc)/2);
		return mb_substr($string, 0, $length).$etc;
	} else {
		return $string;
	}
}

/* vim: set expandtab: */

?>
