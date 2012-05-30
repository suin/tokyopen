<?php
/**
 * @version $Id: modifier.xoops_pagenavi_markup.php 590 2008-06-27 05:57:52Z hodaka $
 * @author hodaka <hodaka@kuri3.net>
 * Smarty plugin
 * -----------------------------------------------------------------
 * Type:     modifier
 * Name:     xoops_pagenavi_markup
 * Purpose:  Convert a xoops navigation result to a semantic markup.
 * Like this: <{$page_navi|xoops_pagenavi:"0":"Previous"":"Next":"mypagenavi"}>
 * -----------------------------------------------------------------
 */

function smarty_modifier_xoops_pagenavi_markup($string, $centering='1', $prev='Prev', $next='Next', $class='xoopspagenavi')
{
    $patterns = array("/(<u>)?\&laquo\;(<\/u>)?/", "/(<u>)?\&raquo\;(<\/u>)?/");
    $replacements = array('Prev', 'Next');
    $string = preg_replace($patterns, $replacements, $string);

    $patterns[] = '/\.\.\./';
    $patterns[] = '/<a href=(["\']?)([^"\'<>]*)\\1([^<>]*)>(Prev|Next|[\d]+)<\/a>/';
    $patterns[] = '/(<b>|<strong>)\((\d+)\)(<\/b>|<\/strong>)/';
    $replacements[] = '<li class="pageSkip"><span>...</span></li>';
    $replacements[] = '<li class="page\\4"><a href="\\2" title="go to page \\4">\\4</a></li>';
    $replacements[] = '<li class="page\\2 pageCurrent"><span>\\2</span></li>';
    $string = preg_replace($patterns, $replacements, $string);
    
    $patterns = array("/Prev/", "/Next/");
    $replacements = array($prev, $next);
    $string = '<ul class="'.$class.'">'.preg_replace($patterns, $replacements, $string).'</ul>';

    if($centering == '1')
        $string = '<div class="pagenaviOuter clearFix">'.$string.'</div>';

    return $string;
}

?>