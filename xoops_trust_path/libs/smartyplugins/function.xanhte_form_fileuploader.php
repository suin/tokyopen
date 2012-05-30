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
function smarty_function_xanhte_form_fileuploader($params, &$smarty)
{
	global $xoopsConfig;

	extract($params);
	
	$rand = mt_rand(); // flash cache 
	$current_filename = "";
	$current_fileid = "";
	
	if (!isset($item_id)) $item_id = 0;
	if (!isset($uploader))$uploader = "extrauploader";
	if (!isset($dirname)) $dirname = $smarty->get_template_vars('xoops_dirname');
	if (!isset($bgcolor)) $bgcolor = "#FFFFFF";
	if (!isset($width)) $width = "460";
	if (!isset($height)) $height = "120";
	
	require_once sprintf('%s/modules/%s/language/%s/modinfo.php', 
						 XOOPS_ROOT_PATH, $uploader, $xoopsConfig['language']);
						 
	$mod_h =& xoops_gethandler('module');
	$mod =& $mod_h->getByDirname($dirname);
	$mid = $mod->mid();
	
	$uploaded_file = array();
	if (isset($_POST["xanhte_uploader_key"])){
		$xanhte_uploader_key = $_POST["xanhte_uploader_key"];
		if (preg_match("/^\w{32}$/", $xanhte_uploader_key)){
			printf('<input type="hidden" id="xanhte_uploader_key" name="xanhte_uploader_key" value="%s" />',
				   $xanhte_uploader_key);
			require_once XOOPS_TRUST_PATH."/xanhte/www/main.php";
			$uploaded_file =& Xanhte_Controller::main_PassThru('Xanhte_Controller', 'passthru_swfuploader_getbyfilename',
															   array('dirname' => $uploader, 
																	 'filename' => $xanhte_uploader_key));
		}
	}
	else if (strcasecmp($_SERVER['REQUEST_METHOD'], 'get')===0 && $item_id){
		// get uploaded file
		$uploaded_file =& Xanhte_Controller::main_PassThru('Xanhte_Controller', 'passthru_swfuploader_getbyitemid',
														   array(
															   'dirname' => $uploader, 
															   'mid' => $mid,
															   'itemid' => $item_id,
															   ));
		
	}
	
	if (is_a($uploaded_file, 'Xanhte_Uploader')){
		$current_filename = $uploaded_file->get('f_name');
		$current_fileid   = $uploaded_file->get('f_filename');
	}
	
	printf('<script src="%s/common/lib/swfobject.js" type="text/javascript"></script>'."\n", XOOPS_URL);
	
	$div_id = $uploader.'_swfform' ;
	
	
	$user_id = $GLOBALS['xoopsUser']->uid();
	
	printf('<div id="%s">%s</div>
	        <script type="text/javascript">
			function postUploadComplete(msg, iserror){
			    if (iserror==1){ return ;}
				var input = document.getElementById("xanhte_uploader_key");
				if (!input){
				    input = document.createElement("INPUT");
					input.type  = "hidden";
					input.name  = input.id  = "xanhte_uploader_key";
				    var div = document.getElementById("%s");
					div.insertBefore(input, div.firstChild);
				}
				input.value = msg;
			}			
			var so=new SWFObject("%s/modules/xanhte/swf/xanhte_uploader.swf?%s", "%s", "%d", "%d", "8", "%s");
			so.addVariable("post_url", "%s");
			so.addVariable("mid", "%d");
			so.addVariable("uid", "%d");
			so.addVariable("item_id", "%d");
			so.addVariable("current_filename", "%s");
			so.addVariable("current_fileid", "%s");
			so.write("%s");
			</script>',
		   $div_id, _MI_UPLOADER_FLASH8_REQUIRED, $div_id, XOOPS_URL, $rand, $div_id,
		   $width, $height, $bgcolor,
		   sprintf('%s/modules/%s/upload.php', XOOPS_URL, $uploader),
		   $mid,
		   $user_id,
		   $item_id,
		   urlencode($current_filename),
		   urlencode($current_fileid),
		   $div_id);
}
/* vim: set expandtab: */
?>
