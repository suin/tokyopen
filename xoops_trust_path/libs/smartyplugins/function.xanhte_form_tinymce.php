<?php

/// use XOOPS_URL
function smarty_function_xanhte_form_tinymce($params, &$smarty)
{
	/// languagae
	$root =& XCube_Root::getSingleton();
	$lang = $root->mLanguageManager->getLanguage();

	extract($params);	// $name = form_input_name 
	
	if (is_null($name)) return ;
	
	if (!isset($attr)){
		$attr = 'style="width:100%" rows="15"';
	}
	
	$ctl =& Ethna_Controller::getInstance();
	$ae =& $ctl->getActionError();
	
	// 現在アクティブなアクションフォーム以外のフォーム定義を
	// 利用する場合にはSmarty変数を仲介させる(いまいちか？)
	$app = $smarty->get_template_vars('app');
	if (isset($app['__def__']) && $app['__def__'] != null) {
		if (isset($app['__def__'][$name])) {
			$def = $app['__def__'][$name];
		}
	} else {
		$af =& $ctl->getActionForm();
		$def = $af->getDef($name);
	}
	
	$form_value = $af->get($name);	
	$xoops_url = XOOPS_URL;
	
	if (!defined('TINYMCE_PRINTED')){
	define('TINYMCE_PRINTED', 1);
print<<<EOF
	<script language="javascript" type="text/javascript">
	// FileBrowser Callback
    function fileBrowserCallBack(field_name, url, type, win) {
	    var connector = "{$xoops_url}/modules/xanhte/common/tinymce/file_manager/file_manager.php";
            my_field = field_name;
			my_win = win;
			switch (type) {
				case "image":
					connector += "?type=img";
					break;
				case "media":
					connector += "?type=media";
					break;
				case "flash": //for older versions of tinymce
					connector += "?type=media";
					break;
				case "file":
					connector += "?type=files";
					break;
			}
			window.open(connector, "file_manager", "modal,width=570,height=400,scrollbars=1");
    }
	
	tinyMCE.init({
	  language : "$lang",
	  auto_resize : true,
	  theme : "advanced",
	  mode : "exact",
	  elements : "{$name}",
	  content_css : "css/advanced.css",
	  extended_valid_elements : "a[href|target|name]",
	  force_br_newlines : true, // no <p> but <br>
	  forced_root_block : '', // no <p> but <br>
	  force_p_newlines : false,	// no <p> but <br>
	  file_browser_callback : "fileBrowserCallBack",
	  plugins : "table",
	  theme_advanced_buttons3_add_before : "tablecontrols,separator",
		//invalid_elements : "a",
	  theme_advanced_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1", // Theme specific setting CSS classes
		//execcommand_callback : "myCustomExecCommandHandler",
	  debug : false
	});
	</script>
EOF;
	}
	printf('<textarea name="%s" %s>%s</textarea>', $name, $attr, $form_value);
	return null ;
}
