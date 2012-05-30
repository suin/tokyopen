<?php

function blocksadmin_list_blocks( $target_mid , $target_dirname )
{
	global $xoopsGTicket ;

	// main query
	$db =& Database::getInstance();
	$sql = "SELECT * FROM ".$db->prefix("newblocks")." WHERE mid='$target_mid' ORDER BY visible DESC,side,weight" ;
	$result = $db->query( $sql ) ;
	$block_arr = array() ;
	while( $myrow = $db->fetchArray( $result ) ) {
		$block_arr[] = new XoopsBlock( $myrow ) ;
	}
	if( empty( $block_arr ) ) return ;

	// cachetime options
	$cachetimes = array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH);

	// displaying TH
	echo "
	<h4 style='text-align:left;'>"._MD_A_MYBLOCKSADMIN_BLOCKADMIN."</h4>
	<form action='?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=$target_dirname' name='blockadmin' method='post'>
		<table width='95%' class='outer' cellpadding='4' cellspacing='1'>
		<tr valign='middle'>
			<th>"._MD_A_MYBLOCKSADMIN_TITLE."</th>
			<th align='center' nowrap='nowrap'>"._MD_A_MYBLOCKSADMIN_SIDE."</th>
			<th align='center'>"._MD_A_MYBLOCKSADMIN_WEIGHT."</th>
			<th align='center'>"._MD_A_MYBLOCKSADMIN_VISIBLEIN."</th>
			<th align='center'>"._MD_A_MYBLOCKSADMIN_BCACHETIME."</th>
			<th align='right'>"._MD_A_MYBLOCKSADMIN_ACTION."</th>
		</tr>\n" ;

	// blocks displaying loop
	$class = 'even' ;
	$block_configs = blocksadmin_get_block_configs() ;
	foreach( array_keys( $block_arr ) as $i ) {
		$sseln = $ssel0 = $ssel1 = $ssel2 = $ssel3 = $ssel4 = "";
		$scoln = $scol0 = $scol1 = $scol2 = $scol3 = $scol4 = "#FFFFFF";

		$weight = $block_arr[$i]->getVar("weight") ;
		$title = htmlspecialchars($block_arr[$i]->getVar("title",'n'),ENT_QUOTES) ;
		$name = $block_arr[$i]->getVar("name") ;
		$bcachetime = $block_arr[$i]->getVar("bcachetime") ;
		$bid = $block_arr[$i]->getVar("bid") ;

		// visible and side
		if ( $block_arr[$i]->getVar("visible") != 1 ) {
			$sseln = " checked='checked'";
			$scoln = "#FF0000";
		} else switch( $block_arr[$i]->getVar("side") ) {
			default :
			case XOOPS_SIDEBLOCK_LEFT :
				$ssel0 = " checked='checked'";
				$scol0 = "#00FF00";
				break ;
			case XOOPS_SIDEBLOCK_RIGHT :
				$ssel1 = " checked='checked'";
				$scol1 = "#00FF00";
				break ;
			case XOOPS_CENTERBLOCK_LEFT :
				$ssel2 = " checked='checked'";
				$scol2 = "#00FF00";
				break ;
			case XOOPS_CENTERBLOCK_RIGHT :
				$ssel4 = " checked='checked'";
				$scol4 = "#00FF00";
				break ;
			case XOOPS_CENTERBLOCK_CENTER :
				$ssel3 = " checked='checked'";
				$scol3 = "#00FF00";
				break ;
		}

		// bcachetime
		$cachetime_options = '' ;
		foreach( $cachetimes as $cachetime => $cachetime_name ) {
			if( $bcachetime == $cachetime ) {
				$cachetime_options .= "<option value='$cachetime' selected='selected'>$cachetime_name</option>\n" ;
			} else {
				$cachetime_options .= "<option value='$cachetime'>$cachetime_name</option>\n" ;
			}
		}

		// link blocks - modules
		$result = $db->query( "SELECT module_id FROM ".$db->prefix('block_module_link')." WHERE block_id='$bid'" ) ;
		$selected_mids = array();
		while ( list( $selected_mid ) = $db->fetchRow( $result ) ) {
			$selected_mids[] = intval( $selected_mid ) ;
		}
		$module_handler =& xoops_gethandler('module');
		$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
		$criteria->add(new Criteria('isactive', 1));
		$module_list =& $module_handler->getList($criteria);
		$module_list[-1] = _MD_A_MYBLOCKSADMIN_TOPPAGE;
		$module_list[0] = _MD_A_MYBLOCKSADMIN_ALLPAGES;
		ksort($module_list);
		$module_options = '' ;
		foreach( $module_list as $mid => $mname ) {
			if( in_array( $mid , $selected_mids ) ) {
				$module_options .= "<option value='$mid' selected='selected'>$mname</option>\n" ;
			} else {
				$module_options .= "<option value='$mid'>$mname</option>\n" ;
			}
		}

		// delete link if it is cloned block
		if( $block_arr[$i]->getVar("block_type") == 'D' || $block_arr[$i]->getVar("block_type") == 'C' ) {
			$delete_link = "<br /><a href='?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=$target_dirname&amp;op=delete&amp;bid=$bid'>"._DELETE."</a>" ;
		} else {
			$delete_link = '' ;
		}

		// clone link if it is marked as cloneable block
		// $modversion['blocks'][n]['can_clone']
		if( $block_arr[$i]->getVar("block_type") == 'D' || $block_arr[$i]->getVar("block_type") == 'C' ) {
			$can_clone = true ;
		} else {
			$can_clone = false ;
			foreach( $block_configs as $bconf ) {
				if( $block_arr[$i]->getVar("show_func") == $bconf['show_func'] && $block_arr[$i]->getVar("func_file") == $bconf['file'] && ( empty( $bconf['template'] ) || $block_arr[$i]->getVar("template") == $bconf['template'] ) ) {
					if( ! empty( $bconf['can_clone'] ) ) $can_clone = true ;
				}
			}
		}
		if( $can_clone ) {
			$clone_link = "<br /><a href='?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=$target_dirname&amp;op=clone&amp;bid=$bid'>"._CLONE."</a>" ;
		} else {
			$clone_link = '' ;
		}

		// displaying part
		echo "
		<tr valign='middle'>
			<td class='$class'>
				$name
				<br />
				<input type='text' name='titles[$bid]' value='$title' size='20' />
			</td>
			<td class='$class' align='center' nowrap='nowrap' width='125px'>
				<div style='float:left;background-color:$scol0;'>
					<input type='radio' name='sides[$bid]' value='".XOOPS_SIDEBLOCK_LEFT."' style='background-color:$scol0;' $ssel0 />
				</div>
				<div style='float:left;'>-</div>
				<div style='float:left;background-color:$scol2;'>
					<input type='radio' name='sides[$bid]' value='".XOOPS_CENTERBLOCK_LEFT."' style='background-color:$scol2;' $ssel2 />
				</div>
				<div style='float:left;background-color:$scol3;'>
					<input type='radio' name='sides[$bid]' value='".XOOPS_CENTERBLOCK_CENTER."' style='background-color:$scol3;' $ssel3 />
				</div>
				<div style='float:left;background-color:$scol4;'>
					<input type='radio' name='sides[$bid]' value='".XOOPS_CENTERBLOCK_RIGHT."' style='background-color:$scol4;' $ssel4 />
				</div>
				<div style='float:left;'>-</div>
				<div style='float:left;background-color:$scol1;'>
					<input type='radio' name='sides[$bid]' value='".XOOPS_SIDEBLOCK_RIGHT."' style='background-color:$scol1;' $ssel1 />
				</div>
				<br />
				<br />
				<div style='float:left;width:40px;'>&nbsp;</div>
				<div style='float:left;background-color:$scoln;'>
					<input type='radio' name='sides[$bid]' value='-1' style='background-color:$scoln;' $sseln />
				</div>
				<div style='float:left;'>"._NONE."</div>
			</td>
			<td class='$class' align='center'>
				<input type='text' name='weights[$bid]' value='$weight' size='3' maxlength='5' style='text-align:right;' />
			</td>
			<td class='$class' align='center'>
				<select name='bmodules[$bid][]' size='5' multiple='multiple'>
					$module_options
				</select>
			</td>
			<td class='$class' align='center'>
				<select name='bcachetimes[$bid]' size='1'>
					$cachetime_options
				</select>
			</td>
			<td class='$class' align='right'>
				<a href='?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=$target_dirname&amp;op=edit&amp;bid=$bid'>"._EDIT."</a>{$delete_link}{$clone_link}
			</td>
		</tr>\n" ;

		$class = ( $class == 'even' ) ? 'odd' : 'even' ;
	}

	echo "
		<tr>
			<td class='foot' align='center' colspan='6'>
				<input type='hidden' name='fct' value='blocksadmin' />
				<input type='hidden' name='op' value='order' />
				".$xoopsGTicket->getTicketHtml( __LINE__ , 1800 , 'myblocksadmin' )."
				<input type='submit' name='submit' value='"._SUBMIT."' />
			</td>
		</tr>
		</table>
	</form>\n" ;
}


function blocksadmin_get_block_configs()
{
	global $mydirpath ;

	$error_reporting_level = error_reporting( 0 ) ;
	if( preg_match( '/^[0-9a-zA-Z_-]+$/' , @$_GET['dirname'] ) ) {
		include XOOPS_ROOT_PATH.'/modules/'.$_GET['dirname'].'/xoops_version.php' ;
	} else {
		include $mydirpath.'/xoops_version.php' ;
	}
	error_reporting( $error_reporting_level ) ;
	if( empty( $modversion['blocks'] ) ) return array() ;
	else return $modversion['blocks'] ;
}


function blocksadmin_list_groups( $target_mid , $target_dirname , $target_mname )
{
	// query for getting blocks
	$db =& Database::getInstance();
	$sql = "SELECT * FROM ".$db->prefix("newblocks")." WHERE mid='$target_mid' ORDER BY visible DESC,side,weight" ;
	$result = $db->query( $sql ) ;
	$block_arr = array() ;
	while( $myrow = $db->fetchArray( $result ) ) {
		$block_arr[] = new XoopsBlock( $myrow ) ;
	}

	$item_list = array() ;
	foreach( array_keys( $block_arr ) as $i ) {
		$item_list[ $block_arr[$i]->getVar("bid") ] = $block_arr[$i]->getVar("title") ;
	}

	$form = new MyXoopsGroupPermForm( _MD_A_MYBLOCKSADMIN_PERMFORM , 1 , 'block_read' , '' ) ;
	// skip system (TODO)
	if( $target_mid > 1 ) {
		$form->addAppendix( 'module_admin' , $target_mid , $target_mname . ' ' . _MD_A_MYBLOCKSADMIN_PERM_MADMIN ) ;
		$form->addAppendix( 'module_read' , $target_mid , $target_mname .' ' . _MD_A_MYBLOCKSADMIN_PERM_MREAD ) ;
	}
	foreach( $item_list as $item_id => $item_name) {
			$form->addItem( $item_id , $item_name ) ;
	}
	echo $form->render() ;
}


function blocksadmin_update_block($bid, $bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bcachetime, $bmodules, $options=array())
{
	global $xoopsConfig;
	$myblock = new XoopsBlock($bid);
	if( $bside >= 0 ) $myblock->setVar('side', $bside);
	$myblock->setVar('weight', $bweight);
	$myblock->setVar('visible', $bvisible);
	$myblock->setVar('title', $btitle);
	if( isset( $bcontent ) ) $myblock->setVar('content', $bcontent);
	if( isset( $bctype ) ) $myblock->setVar('c_type', $bctype);
	$myblock->setVar('bcachetime', $bcachetime);
	if( isset($options) && (count($options) > 0) ) {
		$options = implode('|', $options);
		$myblock->setVar('options', $options);
	}
	if( $myblock->getVar('block_type') == 'C' ) {
		$name = blocksadmin_get_blockname_from_ctype( $myblock->getVar('c_type') ) ;
		$myblock->setVar('name', $name);
	}
	$msg = _MD_A_MYBLOCKSADMIN_DBUPDATED;
	if ($myblock->store() != false) {
		$db =& Database::getInstance();
		$sql = sprintf("DELETE FROM %s WHERE block_id = %u", $db->prefix('block_module_link'), $bid);
		$db->query($sql);
		foreach ($bmodules as $bmid) {
			$sql = sprintf("INSERT INTO %s (block_id, module_id) VALUES (%u, %d)", $db->prefix('block_module_link'), $bid, intval($bmid));
			$db->query($sql);
		}
		include_once XOOPS_ROOT_PATH.'/class/template.php';
		$xoopsTpl = new XoopsTpl();
		$xoopsTpl->xoops_setCaching(2);
		if ($myblock->getVar('template') != '') {
			if ($xoopsTpl->is_cached('db:'.$myblock->getVar('template'))) {
				if (!$xoopsTpl->clear_cache('db:'.$myblock->getVar('template'))) {
					$msg = 'Unable to clear cache for block ID'.$bid;
				}
			}
		} else {
			if ($xoopsTpl->is_cached('db:system_dummy.html', 'block'.$bid)) {
				if (!$xoopsTpl->clear_cache('db:system_dummy.html', 'block'.$bid)) {
					$msg = 'Unable to clear cache for block ID'.$bid;
				}
			}
		}
	} else {
		$msg = 'Failed update of block. ID:'.$bid;
	}
	return $msg ;
}


// update block instance for 2.2
function blocksadmin_update_blockinstance($id, $bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bcachetime, $bmodules, $options=array(), $bid=null)
{
	global $xoopsDB ;

	$instance_handler =& xoops_gethandler('blockinstance');
	$block_handler =& xoops_gethandler('block') ;
	if ($id > 0) {
		// update
		$instance =& $instance_handler->get($id);
		if( $bside >= 0 ) $instance->setVar('side', $bside);
		if( ! empty($options) ) $instance->setVar('options', $options);
	} else {
		// insert
		$instance =& $instance_handler->create();
		$instance->setVar( 'bid' , $bid ) ;
		$instance->setVar('side', $bside);
		$block = $block_handler->get( $bid ) ;
		$instance->setVar('options', $block->getVar("options") );
		if( empty( $btitle ) ) $btitle = $block->getVar("name") ;
	}
	$instance->setVar('weight', $bweight);
	$instance->setVar('visible', $bvisible);
	$instance->setVar('title', $btitle);
	// if( isset( $bcontent ) ) $instance->setVar('content', $bcontent);
	// if( isset( $bctype ) ) $instance->setVar('c_type', $bctype);
	$instance->setVar('bcachetime', $bcachetime);

	if ($instance_handler->insert($instance)) {
		$GLOBALS['xoopsDB']->query("DELETE FROM ".$GLOBALS['xoopsDB']->prefix('block_module_link')." WHERE block_id=".$instance->getVar('instanceid'));
		foreach ($bmodules as $mid) {
			$page = explode('-', $mid);
			$mid = $page[0];
			$pageid = $page[1];
			$GLOBALS['xoopsDB']->query("INSERT INTO ".$GLOBALS['xoopsDB']->prefix('block_module_link')." VALUES (".$instance->getVar('instanceid').", ".intval($mid).", ".intval($pageid).")");
		}
		return _MD_A_MYBLOCKSADMIN_DBUPDATED;
	}
	return 'Failed update of block instance. ID:'.$id;
}


function blocksadmin_do_order()
{
	$sides = is_array( @$_POST['sides'] ) ? $_POST['sides'] : array() ;
	foreach( array_keys( $sides ) as $key ) {
		$bid = intval( $key ) ;
		if( @$sides[$bid] < 0 ) {
			$visible = 0 ;
			$sides[$bid] = -1 ;
		} else {
			$visible = 1 ;
		}

		$bmodules = is_array( @$_POST['bmodules'][$bid] ) ? $_POST['bmodules'][$bid] : array(-1) ;
		blocksadmin_update_block( $bid , intval( $sides[$bid] ) , intval( @$_POST['weights'][$bid] ) , $visible , $_POST['titles'][$bid] , null , null , intval( @$_POST['bcachetimes'][$bid] ) , $bmodules , array() ) ;

	}

	return _MD_A_MYBLOCKSADMIN_DBUPDATED ;
}


function blocksadmin_do_delete( $bid )
{
	$bid = intval( $bid ) ;

	$myblock = new XoopsBlock($bid);
	if( ! is_object( $myblock ) ) die( 'Invalid bid' ) ;

	if ( $myblock->getVar('block_type') != 'D' && $myblock->getVar('block_type') != 'C' ) {
		die( 'Invalid block_type' ) ;
	}
	$myblock->delete() ;
	return _MD_A_MYBLOCKSADMIN_DBUPDATED ;
	/* removing template also (not necessary in this version)
	if( $myblock->getVar('template') != '' && ! defined('XOOPS_ORETEKI') ) {
		$tplfile_handler =& xoops_gethandler('tplfile') ;
		$btemplate =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'] , 'block' , $bid ) ;
		if( count( $btemplate ) > 0 ) {
			$tplman->delete( $btemplate[0] ) ;
		}
	} */
}


function blocksadmin_form_delete( $bid )
{
	global $target_dirname ;

	$bid = intval( $bid ) ;

	$myblock = new XoopsBlock( $bid ) ;
	if( ! is_object( $myblock ) ) die( 'Invalid bid' ) ;

	if( $myblock->getVar('block_type') == 'S' ) {
		die( 'System blocks can\'t be removed' ) ;
	} else if( $myblock->getVar('block_type') == 'M' ) {
		die( 'Module blocks can\'t be removed' ) ;
	} else {
		xoops_confirm( array( 'op' => 'delete_ok' ) + $GLOBALS['xoopsGTicket']->getTicketArray( __LINE__ , 1800 , 'myblocksadmin' ) , "?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=$target_dirname&amp;bid=$bid" , sprintf( _MD_A_MYBLOCKSADMIN_FMT_REMOVEBLOCK , $myblock->getVar('title') ) ) ;
	}
}


function blocksadmin_do_clone( $bid )
{
	$bid = intval( $bid ) ;

	$block = new XoopsBlock( $bid ) ;
	if( ! $block->getVar('bid') ) die( 'Invalid bid' ) ;

	// block type check
	$block_type = $block->getVar('block_type') ;
	if( ! in_array( $block_type , array( 'C' , 'M' , 'D' ) ) ) {
		die( 'Invalid block_type' ) ;
	}

	if( empty( $_POST['options'] ) ) $options = array() ;
	else if( is_array( $_POST['options'] ) ) $options = $_POST['options'] ;
	else $options = explode( '|' , $_POST['options'] ) ;

	// for backward compatibility
	// $cblock =& $block->clone(); or $cblock =& $block->xoopsClone();
	$cblock = new XoopsBlock() ;
	foreach( $block->vars as $k => $v ) {
		$cblock->assignVar( $k , $v['value'] ) ;
	}
	$cblock->setNew();

	$myts =& MyTextSanitizer::getInstance();
	$cblock->setVar('side', $_POST['bside']);
	$cblock->setVar('weight', $_POST['bweight']);
	$cblock->setVar('visible', $_POST['bvisible']);
	$cblock->setVar('title', $_POST['btitle']);
	$cblock->setVar('content', @$_POST['bcontent']);
	$cblock->setVar('c_type', @$_POST['bctype']);
	$cblock->setVar('bcachetime', $_POST['bcachetime']);
	if ( isset($options) && (count($options) > 0) ) {
		$options = implode('|', $options);
		$cblock->setVar('options', $options);
	}
	$cblock->setVar('bid', 0);
	$cblock->setVar('block_type', $block_type == 'C' ? 'C' : 'D' );
	$cblock->setVar('func_num', 255);
	$newid = $cblock->store() ;
	if( ! $newid ) {
		return $cblock->getHtmlErrors() ;
	}
	$db =& Database::getInstance();
	$bmodules = (isset($_POST['bmodules']) && is_array($_POST['bmodules'])) ? $_POST['bmodules'] : array(-1) ;
	foreach( $bmodules as $bmid ) {
		$sql = 'INSERT INTO '.$db->prefix('block_module_link').' (block_id, module_id) VALUES ('.$newid.', '.$bmid.')';
		$db->query($sql);
	}

	// permission copy
	$sql = "SELECT gperm_groupid FROM ".$db->prefix('group_permission')." WHERE gperm_name='block_read' AND gperm_modid='1' AND gperm_itemid='$bid'" ;
	$result = $db->query($sql);
	while( list( $gid ) = $db->fetchRow( $result ) ) {
		$sql = "INSERT INTO ".$db->prefix('group_permission')." (gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES ($gid, $newid, 1, 'block_read')";
		$db->query($sql);
	}

	return _MD_A_MYBLOCKSADMIN_DBUPDATED ;
}


function blocksadmin_do_edit( $bid )
{
	$bid = intval( $bid ) ;

	if( $bid <= 0 ) {
		$new_block = new XoopsBlock() ;
		$new_block->setNew() ;
		$new_block->setVar( 'name' , blocksadmin_get_blockname_from_ctype( 'C' ) ) ;
		$new_block->setVar( 'block_type' , 'C' ) ;
		$new_block->setVar( 'func_num' , 255 ) ;
		$bid = $new_block->store() ;
	}

	$bcachetime = intval( @$_POST['bcachetime'] ) ;
	$options = isset($_POST['options']) ? $_POST['options'] : array();
	$bcontent = isset($_POST['bcontent']) ? $_POST['bcontent'] : '';
	$bctype = isset($_POST['bctype']) ? $_POST['bctype'] : '';
	$bmodules = (isset($_POST['bmodules']) && is_array($_POST['bmodules'])) ? $_POST['bmodules'] : array(-1) ;
	return blocksadmin_update_block( $bid , intval(@$_POST['bside']) , intval(@$_POST['bweight']) , intval(@$_POST['bvisible']) , @$_POST['btitle'] , $bcontent , $bctype , $bcachetime , $bmodules , $options ) ;
}


function blocksadmin_form_edit( $bid , $mode = 'edit' )
{
	$bid = intval( $bid ) ;

	$myblock = new XoopsBlock( $bid ) ;
	if( ! $myblock->getVar('bid') ) {
		// new defaults
		$mode = 'new' ;
		$myblock->setVar( 'mid' , 0 ) ;
		$myblock->setVar( 'block_type' , 'C' ) ;
	}

	if( $myblock->getVar('mid') ) {
		// system or module block
		$module_handler =& xoops_gethandler( 'module' ) ;
		$module =& $module_handler->get( $myblock->getVar('mid') ) ;
		$action_base_url4disp = "?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=".$module->getVar('dirname')."&amp;bid=$bid" ;
	} else {
		// custom block
		$action_base_url4disp = "?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;dirname=__CustomBlocks__&amp;bid=$bid" ;
	}

	switch( $mode ) {
		case 'clone' :
			$form_title = _MD_A_MYBLOCKSADMIN_CLONEFORM ;
			$button_value = _MD_A_MYBLOCKSADMIN_BTN_CLONE ;
			$next_op = 'clone_ok' ;
			break ;
		case 'new' :
			$form_title = _MD_A_MYBLOCKSADMIN_NEWFORM ;
			$button_value = _MD_A_MYBLOCKSADMIN_BTN_NEW ;
			$next_op = 'new_ok' ;
			break ;
		case 'edit' :
		default :
			$form_title = _MD_A_MYBLOCKSADMIN_EDITFORM ;
			$button_value = _MD_A_MYBLOCKSADMIN_BTN_EDIT ;
			$next_op = 'edit_ok' ;
			break ;
	}

	$db =& Database::getInstance() ;
	$sql = 'SELECT module_id FROM '.$db->prefix('block_module_link').' WHERE block_id='.$bid ;
	$result = $db->query( $sql ) ;
	$modules = array() ;
	while( $row = $db->fetchArray( $result ) ) {
		$modules[] = intval( $row['module_id'] ) ;
	}
	$is_custom = in_array( $myblock->getVar('block_type') , array( 'C' , 'E' ) ) ? true : false ;

	$block = array(
		'bid' => $bid ,
		'form_action' => $action_base_url4disp ,
		'name' => $myblock->getVar('name') ,
		'side' => $myblock->getVar('side') ,
		'weight' => $myblock->getVar('weight') ,
		'visible' => $myblock->getVar('visible') ,
		'content' => $myblock->getVar('content', 'N') ,
		'title' => $myblock->getVar('title','E') ,
		'modules' => $modules ,
		'is_custom' => $is_custom ,
		'ctype' => $myblock->getVar('c_type') ,
		'cachetime' => $myblock->getVar('bcachetime') ,
		'edit_form' => $myblock->getOptions() ,
		'template' => $myblock->getVar('template') ,
		'options' => $myblock->getVar('options') ,
		'op' => $next_op ,
		'form_title' => $form_title ,
		'submit_button' => $button_value ,
	) ;

	echo '<a href="'.$action_base_url4disp.'">'. _MD_A_MYBLOCKSADMIN_BLOCKADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$form_title.'<br /><br />';
	include dirname(__FILE__).'/myblockform.php' ;
	$GLOBALS['xoopsGTicket']->addTicketXoopsFormElement( $form , __LINE__ , 1800 , 'myblocksadmin' ) ;
	$form->display();

}


function blocksadmin_form_preview( $bid )
{
	$myts =& MyTextSanitizer::getInstance() ;

	$bid = intval( $bid ) ;

	$myblock = new XoopsBlock( $bid ) ;
	if( $myblock->getVar('mid') ) die( 'Invalid block_type' ) ;
	$action_base_url4disp = '?mode=admin&amp;lib=altsys&amp;page=myblocksadmin&amp;bid='.$bid.'&amp;dirname=__CustomBlocks__' ;

	$bside = intval( @$_POST['bside'] ) ;
	$bweight = intval( @$_POST['bweight'] ) ;
	$bvisible = intval( @$_POST['bvisible'] ) ;
	$bmodules = is_array( @$_POST['bmodules'] ) ? $_POST['bmodules'] : array() ;
	$btitle = $myts->stripSlashesGPC( @$_POST['btitle'] ) ;
	$bcontent = $myts->stripSlashesGPC( @$_POST['bcontent'] ) ;
	$bctype = @$_POST['bctype'] ;
	$bcachetime = intval( @$_POST['bcachetime'] ) ;

	switch( @$_POST['op'] ) {
		case 'clone_ok' :
			$form_title = _MD_A_MYBLOCKSADMIN_CLONEFORM ;
			$button_value = _MD_A_MYBLOCKSADMIN_BTN_CLONE ;
			$next_op = 'clone_ok' ;
			break ;
		case 'new_ok' :
			$form_title = _MD_A_MYBLOCKSADMIN_NEWFORM ;
			$button_value = _MD_A_MYBLOCKSADMIN_BTN_NEW ;
			$next_op = 'new_ok' ;
			break ;
		case 'edit_ok' :
		default :
			$form_title = _MD_A_MYBLOCKSADMIN_EDITFORM ;
			$button_value = _MD_A_MYBLOCKSADMIN_BTN_EDIT ;
			$next_op = 'edit_ok' ;
			break ;
	}

	$block = array(
		'bid' => $bid ,
		'form_action' => $action_base_url4disp ,
		'name' => blocksadmin_get_blockname_from_ctype( $bctype ) ,
		'side' => $bside ,
		'weight' => $bweight ,
		'visible' => $bvisible ,
		'content' => $bcontent ,
		'title' => $btitle ,
		'modules' => $bmodules ,
		'is_custom' => true ,
		'ctype' => $bctype ,
		'cachetime' => $bcachetime ,
		'edit_form' => $myblock->getOptions() ,
		'template' => '' ,
		'options' => array() ,
		'op' => $next_op ,
		'form_title' => $form_title ,
		'submit_button' => $button_value ,
	) ;


	echo '<a href="'.$action_base_url4disp.'">'. _MD_A_MYBLOCKSADMIN_BLOCKADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$form_title.'<br /><br />';
	include dirname(__FILE__).'/myblockform.php' ;
	$GLOBALS['xoopsGTicket']->addTicketXoopsFormElement( $form , __LINE__ , 1800 , 'myblocksadmin' ) ;
	$form->display();

	// preview area

	$myblock->setVar('title', $btitle);
	$myblock->setVar('content', $bcontent);
	restore_error_handler() ;
	$original_level = error_reporting( E_ALL ) ;
	echo "
		<table width='100%' class='outer' cellspacing='1'>
			<tr>
				<th>".$myblock->getVar('title')."</th>
			</tr>
			<tr>
				<td class='odd'>".$myblock->getContent('S', $bctype)."</td>
			</tr>
		</table>\n" ;
	error_reporting( $original_level ) ;
}


function blocksadmin_get_blockname_from_ctype( $bctype )
{
	$ctypes = array(
		'H' => _MD_A_MYBLOCKSADMIN_CTYPE_HTML ,
		'S' => _MD_A_MYBLOCKSADMIN_CTYPE_SMILE ,
		'N' => _MD_A_MYBLOCKSADMIN_CTYPE_NOSMILE ,
		'P' => _MD_A_MYBLOCKSADMIN_CTYPE_PHP ,
	) ;

	return isset( $ctypes[$bctype] ) ? $ctypes[$bctype] : _MD_A_MYBLOCKSADMIN_CTYPE_SMILE ;
}

?>