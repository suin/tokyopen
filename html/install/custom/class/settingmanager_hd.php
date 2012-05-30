<?php

require_once dirname(dirname(dirname(__FILE__))).'/class/settingmanager.php';

class setting_manager_hd extends setting_manager {

	var $trust_path;

	function setting_manager_hd($post=false){
		parent::setting_manager($post);
		
		if( $post ) {
			if( isset( $_POST['trust_path'] ) ) {
				$this->trust_path = $this->sanitizer->stripSlashesGPC( $_POST['trust_path'] ) ;
			}
		} else {
			// find xoops_trust_path
			$path = $this->root_path ;
			while( strlen( $path ) > 4 ) {
				if( is_dir( $path . '/xoops_trust_path' ) ) {
					$this->trust_path = $path . '/xoops_trust_path' ;
					break ;
				}
				$path = dirname( $path ) ;
			}
		}
	}


	function readConstant(){
		if( defined('XOOPS_TRUST_PATH') ) {
			$this->trust_path = XOOPS_TRUST_PATH ;
		}
		parent::readConstant() ;
	}

	function editform(){
		$trust_tr = '
			   <tr valign="top" align="left">
					<td class="head">
						<b>XOOPS_TRUST_PATH</b><br />
						<span style="font-size:85%;">'._INSTALL_CL0_1.'</span>
					</td>
					<td class="even">
						<input type="text" name="trust_path" id="trust_path" size="40" maxlength="100" value="'.htmlspecialchars($this->trust_path,ENT_QUOTES).'" />
					</td>
				</tr>' ;

		$ret = parent::editform() ;
		list( $ret_pre , $ret_post ) = explode( '</table>' , $ret ) ;
		return $ret_pre . $trust_tr . $ret_post ."</table>";
	}

	function confirmForm(){
		$trust_tr = '
					<tr>
						<td class="bg3"><b>XOOPS_TRUST_PATH</b></td>
						<td class="bg1">'.htmlspecialchars($this->trust_path,ENT_QUOTES).'</td>
					</tr>' ;
		$trust_hidden = '
            <input type="hidden" name="trust_path" value="'.htmlspecialchars($this->trust_path,ENT_QUOTES).'" />' ;

		$ret = parent::confirmForm() ;
		list( $ret_pre , $ret_post ) = explode( '</table>' , $ret ) ;
		return $ret_pre . $trust_tr . $ret_post . $trust_hidden . '</table>';
	}

}


?>