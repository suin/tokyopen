<?php
// ------------------------------------------------------------------------- //
//                  myblocksadmin_for_2.2.php (altsys)                       //
//                - XOOPS block admin for each modules -                     //
//                       GIJOE <http://www.peak.ne.jp/>                      //
// ------------------------------------------------------------------------- //


// get blocks owned by the module (Imported from xoopsblock.php then modified)
$db =& Database::getInstance();
$sql = "SELECT bid,name,show_func,func_file,template FROM ".$db->prefix("newblocks")." WHERE mid='$target_mid'";
$result = $db->query($sql);
$block_arr = array();
while( list( $bid , $bname , $show_func , $func_file , $template ) = $db->fetchRow( $result ) ) {
	$block_arr[$bid] = array(
		'name' => $bname ,
		'show_func' => $show_func ,
		'func_file' => $func_file ,
		'template' => $template
	) ;
}


// for 2.2
function list_blockinstances()
{
	global $target_dirname , $block_arr , $xoopsGTicket ;

	$myts =& MyTextSanitizer::getInstance() ;

	// cachetime options
	$cachetimes = array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH);

	// displaying TH
	echo "
	<form action='admin.php' name='blockadmin' method='post'>
		<table width='95%' class='outer' cellpadding='4' cellspacing='1'>
		<tr valign='middle'>
			<th>"._AM_TITLE."</th>
			<th align='center' nowrap='nowrap'>"._AM_SIDE."</th>
			<th align='center'>"._AM_WEIGHT."</th>
			<th align='center'>"._AM_VISIBLEIN."</th>
			<th align='center'>"._AM_BCACHETIME."</th>
			<th align='right'>"._AM_ACTION."</th>
		</tr>\n" ;

	// get block instances
	$crit = new Criteria("bid", "(".implode(",",array_keys($block_arr)).")", "IN");
	$criteria = new CriteriaCompo($crit);
	$criteria->setSort('visible DESC, side ASC, weight');
	$instance_handler =& xoops_gethandler('blockinstance');
	$instances =& $instance_handler->getObjects($criteria, true, true);

	//Get modules and pages for visible in
	$module_list[_AM_SYSTEMLEVEL]["0-2"] = _AM_ADMINBLOCK;
	$module_list[_AM_SYSTEMLEVEL]["0-1"] = _AM_TOPPAGE;
	$module_list[_AM_SYSTEMLEVEL]["0-0"] = _AM_ALLPAGES;
	$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
	$criteria->add(new Criteria('isactive', 1));
	$module_handler =& xoops_gethandler('module');
	$module_main =& $module_handler->getObjects($criteria, true, true);
	if (count($module_main) > 0) {
		foreach (array_keys($module_main) as $mid) {
			$module_list[$module_main[$mid]->getVar('name')][$mid."-0"] = _AM_ALLMODULEPAGES;
			$pages = $module_main[$mid]->getInfo("pages");
			if ($pages == false) {
				$pages = $module_main[$mid]->getInfo("sub");
			}
			if (is_array($pages) && $pages != array()) {
				foreach ($pages as $id => $pageinfo) {
					$module_list[$module_main[$mid]->getVar('name')][$mid."-".$id] = $pageinfo['name'];
				}
			}
		}
	}

	// blocks displaying loop
	$class = 'even' ;
	$block_configs = get_block_configs() ;
	foreach( array_keys( $instances ) as $i ) {
		$sseln = $ssel0 = $ssel1 = $ssel2 = $ssel3 = $ssel4 = "";
		$scoln = $scol0 = $scol1 = $scol2 = $scol3 = $scol4 = "#FFFFFF";

		$weight = $instances[$i]->getVar("weight") ;
		$title = $instances[$i]->getVar("title") ;
		$bcachetime = $instances[$i]->getVar("bcachetime") ;
		$bid = $instances[$i]->getVar("bid") ;
		$name = $myts->makeTboxData4Edit( $block_arr[$bid]['name'] ) ;

		$visiblein = $instances[$i]->getVisibleIn();

		// visible and side
		if ( $instances[$i]->getVar("visible") != 1 ) {
			$sseln = " checked='checked'";
			$scoln = "#FF0000";
		} else switch( $instances[$i]->getVar("side") ) {
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

		$module_options = '' ;
		foreach( $module_list as $mname => $module ) {
			$module_options .= "<optgroup label='$mname'>\n" ;
			foreach( $module as $mkey => $mval ) {
				if( in_array( $mkey , $visiblein ) ) {
					$module_options .= "<option value='$mkey' selected='selected'>$mval</option>\n" ;
				} else {
					$module_options .= "<option label='$mval' value='$mkey'>$mval</option>\n" ;
				}
			}
			$module_options .= "</optgroup>\n" ;
		}

		// delete link if it is cloned block
		$delete_link = "<br /><a href='".XOOPS_URL."/modules/system/admin.php?fct=blocksadmin&amp;op=delete&amp;id=$i&amp;selmod=$mid&amp;dirname=$target_dirname'>"._DELETE."</a>" ;

		// displaying part
		echo "
		<tr valign='middle'>
			<td class='$class'>
				$name
				<br />
				<input type='text' name='title[$i]' value='$title' size='20' />
			</td>
			<td class='$class' align='center' nowrap='nowrap' width='125px'>
				<div style='float:left;background-color:$scol0;'>
					<input type='radio' name='side[$i]' value='".XOOPS_SIDEBLOCK_LEFT."' style='background-color:$scol0;' $ssel0 />
				</div>
				<div style='float:left;'>-</div>
				<div style='float:left;background-color:$scol2;'>
					<input type='radio' name='side[$i]' value='".XOOPS_CENTERBLOCK_LEFT."' style='background-color:$scol2;' $ssel2 />
				</div>
				<div style='float:left;background-color:$scol3;'>
					<input type='radio' name='side[$i]' value='".XOOPS_CENTERBLOCK_CENTER."' style='background-color:$scol3;' $ssel3 />
				</div>
				<div style='float:left;background-color:$scol4;'>
					<input type='radio' name='side[$i]' value='".XOOPS_CENTERBLOCK_RIGHT."' style='background-color:$scol4;' $ssel4 />
				</div>
				<div style='float:left;'>-</div>
				<div style='float:left;background-color:$scol1;'>
					<input type='radio' name='side[$i]' value='".XOOPS_SIDEBLOCK_RIGHT."' style='background-color:$scol1;' $ssel1 />
				</div>
				<br />
				<br />
				<div style='float:left;width:40px;'>&nbsp;</div>
				<div style='float:left;background-color:$scoln;'>
					<input type='radio' name='side[$i]' value='-1' style='background-color:$scoln;' $sseln />
				</div>
				<div style='float:left;'>"._NONE."</div>
			</td>
			<td class='$class' align='center'>
				<input type='text' name=weight[$i] value='$weight' size='3' maxlength='5' style='text-align:right;' />
			</td>
			<td class='$class' align='center'>
				<select name='bmodule[$i][]' size='5' multiple='multiple'>
					$module_options
				</select>
			</td>
			<td class='$class' align='center'>
				<select name='bcachetime[$i]' size='1'>
					$cachetime_options
				</select>
			</td>
			<td class='$class' align='right'>
				<a href='".XOOPS_URL."/modules/system/admin.php?fct=blocksadmin&amp;op=edit&amp;id=$i&amp;dirname=$target_dirname'>"._EDIT."</a>{$delete_link}
				<input type='hidden' name='id[$i]' value='$i' />
			</td>
		</tr>\n" ;

		$class = ( $class == 'even' ) ? 'odd' : 'even' ;
	}

	// list block classes for add (not instances)
	foreach( $block_arr as $bid => $block ) {

		$description4show = '' ;
		foreach( $block_configs as $bconf ) {
			if( $block['show_func'] == $bconf['show_func'] && $block['func_file'] == $bconf['file'] && ( empty( $bconf['template'] ) || $block['template'] == $bconf['template'] ) ) {
				if( ! empty( $bconf['description'] ) ) $description4show = $myts->makeTboxData4Show( $bconf['description'] ) ;
			}
		}

		echo "
		<tr>
			<td class='$class' align='left'>
				".$myts->makeTboxData4Edit($block['name'])."
			</td>
			<td class='$class' align='left' colspan='4'>
				$description4show
			</td>
			<td class='$class' align='center'>
				<input type='submit' name='addblock[$bid]' value='"._ADD."' />
			</td>
		</tr>
		\n" ;
		$class = ( $class == 'even' ) ? 'odd' : 'even' ;
	}

	echo "
		<tr>
			<td class='foot' align='center' colspan='6'>
				<input type='hidden' name='fct' value='blocksadmin' />
				<input type='hidden' name='op' value='order2' />
				".$xoopsGTicket->getTicketHtml( __LINE__ , 1800 , 'myblocksadmin' )."
				<input type='submit' name='submit' value='"._SUBMIT."' />
			</td>
		</tr>
		</table>
	</form>\n" ;
}


// for 2.2
function list_groups2()
{
	global $target_mid , $target_mname , $xoopsDB ;

	$result = $xoopsDB->query( "SELECT i.instanceid,i.title FROM ".$xoopsDB->prefix("block_instance")." i LEFT JOIN ".$xoopsDB->prefix("newblocks")." b ON i.bid=b.bid WHERE b.mid='$target_mid'" ) ;

	$item_list = array() ;
	while( list( $iid , $title ) = $xoopsDB->fetchRow( $result ) ) {
		$item_list[ $iid ] = $title ;
	}

	$form = new MyXoopsGroupPermForm( _MD_AM_ADGS , 1 , 'block_read' , '' ) ;
	if( $target_mid > 1 ) {
		$form->addAppendix( 'module_admin' , $target_mid , $target_mname . ' ' . _AM_ACTIVERIGHTS ) ;
		$form->addAppendix( 'module_read' , $target_mid , $target_mname .' ' . _AM_ACCESSRIGHTS ) ;
	}
	foreach( $item_list as $item_id => $item_name) {
			$form->addItem( $item_id , $item_name ) ;
	}
	echo $form->render() ;
}



if( ! empty( $_POST['submit'] ) ) {
	if ( ! $xoopsGTicket->check( true , 'myblocksadmin' ) ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	include dirname(__FILE__).'/include/mygroupperm.php' ;
	redirect_header( XOOPS_URL."/modules/".$xoopsModule->dirname()."/admin/myblocksadmin.php?dirname=$target_dirname" , 1 , _MD_AM_DBUPDATED );
}

xoops_cp_header() ;
if( file_exists( $mytrustdirpath.'/admin/mymenu.php' ) ) include( $mytrustdirpath.'/admin/mymenu.php' ) ;

echo "<h3 style='text-align:left;'>$target_mname</h3>\n" ;

if( ! empty( $block_arr ) ) {
	echo "<h4 style='text-align:left;'>"._AM_BADMIN."</h4>\n" ;
	list_blockinstances() ;
}

list_groups2() ;
xoops_cp_footer() ;


?>
