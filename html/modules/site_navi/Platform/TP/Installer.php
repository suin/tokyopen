<?php

class SiteNavi_Platform_TP_Installer extends Pengin_Platform_TP_Installer
{
	protected function _install()
	{
		parent::_install();
		$this->_updateTopPage();
	}

	protected function _update()
	{
		parent::_update();
		$this->_applyUpdateSQL();
	}

	protected function _applyUpdateSQL()
	{
		if ( $this->currentVersion < 1.01 ) {
			$update = new SiteNavi_Platform_UpdateTo101($this->dirname);
			$update->update();
		}
	}

	protected function _updateTopPage()
	{
		$db = $this->root->cms->database();
		$table = $db->prefix($this->dirname.'_route');
		$url   = $db->quoteString(TP_URL);
		$moduleId = $this->_getModuleId();
		$query = "UPDATE %s SET url = %s, module_id = %u WHERE id = 1";
		$query = sprintf($query, $table, $url, $moduleId);
		$isSuccess = $db->query($query);
		
		if ( $isSuccess == false ) {
			$this->_addError('ERROR: Could update top page data.');
			throw new RuntimeException();
		} else {
			$this->_addMessage('Top page data was successfully updated.');
		}
	}

	protected function _getModuleId()
	{
		$db = $this->root->cms->database();

		$table = $db->prefix('modules');
		$dirname = $db->quoteString($this->dirname);
		$query = "SELECT mid FROM %s WHERE dirname = %s LIMIT 1";
		$query = sprintf($query, $table, $dirname);
		
		$result = $db->query($query);

		if ( $result === false ) {
			$this->_addError('ERROR: Failed to fetch module ID.');
			throw new RuntimeException();
		}

		$row = $db->fetchArray($result);
		
		if ( array_key_exists('mid', $row) === false ) {
			$this->_addError('ERROR: Failed to fetch module ID.');
			throw new RuntimeException();
		}
		
		return $row['mid'];
	}
}
