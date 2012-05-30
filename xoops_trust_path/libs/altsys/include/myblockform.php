<?php

$usespaw = empty( $_GET['usespaw'] ) ? 0 : 1 ;

require_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

// form
$form = new XoopsThemeForm( $block['form_title'] , 'blockform' , $block['form_action'] ) ;

// name (label)
if( ! empty( $block['name'] ) ) {
	$form->addElement( new XoopsFormLabel( _MD_A_MYBLOCKSADMIN_NAME , htmlspecialchars( $block['name'] , ENT_QUOTES ) ) ) ;
}

// side (select)
$side_select = new XoopsFormSelect( _MD_A_MYBLOCKSADMIN_SIDE , 'bside' , intval( $block['side'] ) ) ;
$side_select->addOptionArray( array( 0 => _MD_A_MYBLOCKSADMIN_SBLEFT , 1 => _MD_A_MYBLOCKSADMIN_SBRIGHT , 3 => _MD_A_MYBLOCKSADMIN_CBLEFT , 4 => _MD_A_MYBLOCKSADMIN_CBRIGHT , 5 => _MD_A_MYBLOCKSADMIN_CBCENTER , ) ) ;
$form->addElement( $side_select ) ;

// weight (textbox)
$form->addElement( new XoopsFormText( _MD_A_MYBLOCKSADMIN_WEIGHT , 'bweight' , 2 , 5 , intval( $block['weight'] ) ) ) ;

// visible (radio)
$form->addElement( new XoopsFormRadioYN( _MD_A_MYBLOCKSADMIN_VISIBLE , 'bvisible' , intval( $block['visible'] ) ) ) ;

// module (multi-select)
if( $block['modules'] !== -1 ) {
	$module_handler =& xoops_gethandler( 'module' ) ;
	$criteria = new CriteriaCompo( new Criteria( 'hasmain' , 1 ) ) ;
	$criteria->add( new Criteria( 'isactive' , 1 ) ) ;
	$module_list = $module_handler->getList( $criteria ) ;
	$module_list= array( -1 => _MD_A_MYBLOCKSADMIN_TOPPAGE , 0 => _MD_A_MYBLOCKSADMIN_ALLPAGES ) + $module_list ;
	$mod_select = new XoopsFormSelect( _MD_A_MYBLOCKSADMIN_VISIBLEIN , 'bmodules' , $block['modules'] , min( 15 , sizeof( $module_list ) ) , true ) ;
	$mod_select->addOptionArray( $module_list ) ;
	$form->addElement( $mod_select ) ;
}

// title (textbox)
$form->addElement( new XoopsFormText( _MD_A_MYBLOCKSADMIN_TITLE , 'btitle' , 50 , 255 , htmlspecialchars( $block['title'] , ENT_QUOTES ) ) , false ) ;


// Only custom blocks in XOOPS 2.0.x
if ( $block['is_custom'] ) {
	// content (textarea)
	$notice_for_tags = '<span style="font-size:x-small;font-weight:bold;">'._MD_A_MYBLOCKSADMIN_CAPT_USABLETAGS.'</span><br /><span style="font-size:x-small;font-weight:normal;">'.htmlspecialchars(sprintf( _MD_A_MYBLOCKSADMIN_FMT_TAGRULE , '{X_SITEURL}', XOOPS_URL.'/'),ENT_QUOTES).'</span>' ;
	$current_op = @$_GET['op'] == 'clone' ? 'clone' : 'edit' ;
	//$uri_to_myself = XOOPS_URL . "/modules/blocksadmin/admin/admin.php?fct=blocksadmin&amp;op=$current_op&amp;bid={$block['bid']}" ;
	$uri_to_myself = "?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=__CustomBlocks__&amp;op=$current_op&amp;bid={$block['bid']}" ;
	// $can_use_spaw = check_browser_can_use_spaw() ;
	$can_use_spaw = true ;
	if( $usespaw && $can_use_spaw ) {
		// SPAW Config
		global $spaw_dir;
		global $spaw_root;
		global $spaw_base_url;
		global $spaw_default_toolbars;
		global $spaw_default_theme;
		global $spaw_default_lang;
		global $spaw_default_css_stylesheet;
		global $spaw_inline_js;
		global $spaw_dropdown_data ;
		global $spaw_wysiwyg_instCount;
		global $spaw_active_toolbar;
		global $spaw_internal_link_script;
		global $spaw_img_popup_url;
		global $spaw_valid_imgs;
		global $spaw_upload_allowed;
		global $spaw_img_delete_allowed;
		global $spaw_a_targets;
		global $spaw_internal_link_script;
		global $spaw_disable_style_controls;
		global $spaw_imglibs;
		global $xoopsDB;
		include XOOPS_ROOT_PATH.'/common/spaw/spaw_control.class.php' ;
		ob_start() ;
		$sw = new SPAW_Wysiwyg( 'bcontent' , $block['content'] ) ;
		$sw->show() ;
		$textarea = new XoopsFormLabel( _MD_A_MYBLOCKSADMIN_CONTENT , ob_get_contents() ) ;
		$textarea->setDescription( $notice_for_tags . "<br /><br /><a href='$uri_to_myself&amp;usespaw=0'>NORMAL</a>" ) ;
		ob_end_clean() ;
	} else {
		$myts =& MyTextSanitizer::getInstance();
		$textarea = new XoopsFormDhtmlTextArea( _MD_A_MYBLOCKSADMIN_CONTENT , 'bcontent' , htmlspecialchars( $block['content'] , ENT_QUOTES ) , 15, 70);
		if( $can_use_spaw ) {
			$textarea->setDescription( $notice_for_tags . "<br /><br /><a href='$uri_to_myself&amp;usespaw=1'>SPAW</a>" ) ;
		} else {
			$textarea->setDescription( $notice_for_tags ) ;
		}
	}
	$form->addElement( $textarea ) ;

	// custom type (select)
	$ctype_select = new XoopsFormSelect( _MD_A_MYBLOCKSADMIN_CTYPE , 'bctype' , $block['ctype'] ) ;
	$ctype_select->addOptionArray( array( 'H' => _MD_A_MYBLOCKSADMIN_CTYPE_HTML , 'T' => _MD_A_MYBLOCKSADMIN_CTYPE_NOSMILE , 'S' => _MD_A_MYBLOCKSADMIN_CTYPE_SMILE , 'P' => _MD_A_MYBLOCKSADMIN_CTYPE_PHP ) ) ;
	$form->addElement($ctype_select);

// module's block
} else {

	// link for editing template
	if ($block['template'] != '' && ! defined('XOOPS_ORETEKI') ) {
		$tplfile_handler =& xoops_gethandler('tplfile');
		$btemplate = $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], 'block', null , null, $block['template']);
		if (count($btemplate) > 0) {
			$form->addElement( new XoopsFormLabel( _MD_A_MYBLOCKSADMIN_CONTENT , '<a href="?mode=admin&amp;lib=altsys&amp;page=mytplsform&amp;tpl_file='.$btemplate[0]->getVar('tpl_file').'&amp;tpl_tplset='.htmlspecialchars($GLOBALS['xoopsConfig']['template_set'],ENT_QUOTES).'">'._MD_A_MYBLOCKSADMIN_EDITTPL.'</a>'));
		} else {
			$btemplate2 =& $tplfile_handler->find('default', 'block', null , null , $block['template']);
			if (count($btemplate2) > 0) {
				$form->addElement(new XoopsFormLabel(_MD_A_MYBLOCKSADMIN_CONTENT, '<a href="?mode=admin&amp;lib=altsys&amp;page=mytplsform&amp;tpl_file='.$btemplate2[0]->getVar('tpl_file').'&amp;tpl_tplset=default">'._MD_A_MYBLOCKSADMIN_EDITTPL.'</a>'));
			}
		}
	}
	if ($block['edit_form'] != false) {
		$form->addElement(new XoopsFormLabel( _MD_A_MYBLOCKSADMIN_OPTIONS , $block['edit_form'] ) ) ;
	}
}

// cachetime (select)
$cache_select = new XoopsFormSelect( _MD_A_MYBLOCKSADMIN_BCACHETIME , 'bcachetime' , intval( $block['cachetime'] ) ) ;
$cache_select->addOptionArray(array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH));
$form->addElement($cache_select);

// next op (hidden)
$form->addElement( new XoopsFormHidden( 'op', preg_replace( '/[^a-zA-Z0-9_-]/' , '' , $block['op'] ) ) ) ;

// buttons
$button_tray = new XoopsFormElementTray( '' , '&nbsp;' ) ;
if( $block['is_custom'] ) {
	$button_tray->addElement( new XoopsFormButton( '' , 'preview' , _PREVIEW , 'submit' ) ) ;
}
$button_tray->addElement( new XoopsFormButton( '' , 'submitblock' , $block['submit_button'] , "submit" ) ) ;
$form->addElement( $button_tray ) ;


// checks browser compatibility with the control
function altsys_check_browser_can_use_spaw() {
	$browser = $_SERVER['HTTP_USER_AGENT'] ;
	// check if msie
	if( eregi( "MSIE[^;]*" , $browser , $msie ) ) {
		// get version 
		if( eregi( "[0-9]+\.[0-9]+" , $msie[0] , $version ) ) {
			// check version
			if( (float)$version[0] >= 5.5 ) {
				// finally check if it's not opera impersonating ie
				if( ! eregi( "opera" , $browser ) ) {
					return true ;
				}
			}
		}
	}
	return false ;
}

?>