<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {xanhte_form_fileuploader} function plugin
 */
// {{{ smarty_function_xanhte_form_fileuploader
/**
 *	smarty 
 *
 *
 *	sample:
 *	<code>
 *	<{xanhte_form_fileuploader uploader="xupload" dirname=`$xoops_dirname`}>
 *	</code>
 *
 */
function smarty_function_xanhte_fileuploader_fetch($params, &$smarty)
{
	extract($params);
	
	$item_id = isset($item_id) ? intval($item_id) : 0;
	if (!isset($dirname)) $dirname = $smarty->get_template_vars('xoops_dirname');
	if (!isset($uploader))$uploader = "extrauploader";
	if (!isset($class)) $class = "xanhteFileInfo";
	
	if (!$item_id){
		return '';
	}
	
	$mod_h =& xoops_gethandler('module');
	$mod =& $mod_h->getByDirname($dirname);
	if (!is_object($mod)){
		echo  $dirname.' is not valid module.';
		return ;
	}
	$mid = $mod->mid();

	require_once XOOPS_TRUST_PATH."/xanhte/www/main.php";
	$uploaded_file = Xanhte_Controller::main_PassThru('Xanhte_Controller', 'passthru_swfuploader_getbyitemid',
													  array('dirname' => $uploader, 
															'mid' => $mid,
															'itemid' => $item_id,
															));
	
	if (!is_a($uploaded_file, 'Xanhte_Uploader')){
		return '';
	}
	
	$f_name = htmlspecialchars($uploaded_file->get('f_name'), ENT_QUOTES);
	$dl_key = $uploaded_file->get('f_dl_key') ? '<input type="text" size="6" name="dl_key" />&nbsp;Download Key<br />' : '';
	
	printf('<div class="%s"><form action="%s/modules/%s/getfile.php/%s" target="_blank">%s<input type="hidden" name="f_filename" value="%s" /><button type="submit"><img src="%s/modules/%s/images/download.gif">&nbsp;%s(%s)</button></form></div>', 
		   $class, XOOPS_URL, $uploader, urlencode(mb_convert_encoding($f_name, 'UTF-8', _CHARSET)),
		   $dl_key, 
		   $uploaded_file->get('f_filename'), 
		   XOOPS_URL, $uploader,
		   $f_name, __number_format_filesize($uploaded_file->get('f_size'))
		   );
}


function __number_format_filesize($string)
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
/* vim: set expandtab: */
?>
