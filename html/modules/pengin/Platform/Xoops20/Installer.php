<?php
class Pengin_Platform_Xoops20_Installer
{
	protected $root     = null;
	protected $ret      = array();
	protected $messages = array();
	protected $module   = null;
	protected $dirname  = null;
	protected $path     = null;
	protected $currentVersion = null;

	public function __construct()
	{
	}

	public function prepareAPI($dirname)
	{
		$class = get_class($this);

		eval('
		function xoops_module_install_'.$dirname.'($module)
		{
			$installer = new '.$class.';
			return $installer->install($module);
		}
		function xoops_module_update_'.$dirname.'($module, $currentVersion)
		{
			$installer = new '.$class.';
			return $installer->update($module, $currentVersion);
		}
		function xoops_module_uninstall_'.$dirname.'($module)
		{
			$installer = new '.$class.';
			return $installer->uninstall($module);
		}
		');
	}

	public function install($module)
	{
		return $this->_main($module, '_install');
	}

	public function update($module, $currentVersion)
	{
		$this->currentVersion = $currentVersion / 100;
		return $this->_main($module, '_update');
	}

	public function uninstall($module)
	{
		return $this->_main($module, '_uninstall');
	}

	protected function _main($module, $method)
	{
		try
		{
			$this->_init($module);
			$this->$method();
		}
		catch ( Exception $e )
		{
			$this->_message();
			return false;
		}

		$this->_message();
		return true;
	}


	protected function _init($module)
	{
		$this->root    =& Pengin::getInstance();
		$this->module  = $module;
		$this->dirname = $module->getVar('dirname');
		$this->path    = $this->root->cms->modulePath.DS.$this->dirname;
	}

	protected function _install()
	{
		$this->_template();
		$this->_addTable();
	}

	protected function _update()
	{
		$this->_template();
		$this->_deleteLanguageCache();
	}

	protected function _uninstall()
	{
		$this->_dropTable();
		$this->_deleteLanguageCache();
	}

	protected function _template()
	{
		require_once $this->root->cms->rootPath.DS.'class'.DS.'xoopsblock.php';
		require_once $this->root->cms->rootPath.DS.'class'.DS.'template.php';

		$tplfileHandler =& xoops_gethandler('tplfile');

		$moduleId       = $this->module->getVar('mid');
		$templateDir    = $this->path.DS.'templates';
		$templatePrefix = $this->dirname.'.';

		$files = glob($templateDir.DS.'*.tpl');

		foreach ( $files as $file )
		{
			if ( !is_file($file) )
			{
				continue;
			}

			$modified     = intval(@filemtime($file));
			$contents     = file_get_contents($file);
			$templateName = $templatePrefix.basename($file);

			$tplfile =& $tplfileHandler->create();
			$tplfile->setVar('tpl_source', $contents, true);
			$tplfile->setVar('tpl_refid', $moduleId);
			$tplfile->setVar('tpl_tplset', 'default');
			$tplfile->setVar('tpl_file', $templateName);
			$tplfile->setVar('tpl_desc', '', true);
			$tplfile->setVar('tpl_module', $this->dirname);
			$tplfile->setVar('tpl_lastmodified', $modified);
			$tplfile->setVar('tpl_lastimported', 0);
			$tplfile->setVar('tpl_type', 'module');

			if ( $tplfileHandler->insert($tplfile) )
			{
				$tplid = $tplfile->getVar('tpl_id');
				$this->_addMessage('Template <b>'.htmlspecialchars($templateName).'</b> added to the database. (ID: <b>'.$tplid.'</b>)');
			}
			else
			{
				$this->_addError('ERROR: Could not insert template <b>'.htmlspecialchars($templateName).'</b> to the database.');
				throw new Exception;
			}
		}
	}

	protected function _addTable()
	{
		$db =& Database::getInstance();

		$sqlFilePath = $this->path.'/sql/mysql.sql';
		$prefix      = $db->prefix().'_'.$this->dirname;

		if ( !file_exists($sqlFilePath) )
		{
			return;
		}

		$this->_addMessage('SQL file found at <b>'.htmlspecialchars($sqlFilePath).'</b>.<br /> Creating tables...');

		if ( file_exists($this->root->cms->rootPath.'/class/database/oldsqlutility.php') )
		{
			require_once $this->root->cms->rootPath.'/class/database/oldsqlutility.php';
			$sqlutil = new OldSqlUtility;
		}
		else
		{
			require_once $this->root->cms->rootPath.'/class/database/sqlutility.php';
			$sqlutil = new SqlUtility;
		}

		$tables        = array();
		$createdTables = array();

		$sqlQuery = trim(file_get_contents($sqlFilePath));
		$sqlutil->splitMySqlFile($tables, $sqlQuery);

		if ( !is_array($tables) or count($tables) === 0 )
		{
			return;
		}

		foreach ( $tables as $table )
		{
			$prefixedQuery = $sqlutil->prefixQuery($table, $prefix);

			if ( !$prefixedQuery )
			{
				$this->_addError('Invalid SQL <b>'.htmlspecialchars($table).'</b>');
				throw new Exception;
			}

			if ( !$db->query($prefixedQuery[0]) )
			{
				$this->_addError('<b>'.htmlspecialchars($db->error()).'</b>');
				throw new Exception;
			}

			if( !in_array($prefixedQuery[4], $createdTables) )
			{
				$this->_addMessage('Table <b>'.htmlspecialchars($prefix.'_'.$prefixedQuery[4]).'</b> created.');
				$createdTables[] = $prefixedQuery[4];
			}
			else
			{
				$this->_addMessage('Data inserted to table <b>'.htmlspecialchars($prefix.'_'.$prefixedQuery[4]).'</b>.');
			}
		}
	}

	protected function _deleteLanguageCache()
	{
		$pattan = $this->root->cms->cachePath.DS.$this->dirname.'_*';
		$files  = glob($pattan);

		foreach ( $files as $file )
		{
			if ( !file_exists($file) )
			{
				continue;
			}

			if ( @unlink($file) )
			{
				$this->_addMessage('Language cache <b>'.htmlspecialchars($file).'</b> deleted.');
			}
			else
			{
				$this->_addError('ERROR: Could not delete language cache <b>'.htmlspecialchars($file).'<b>.');
				throw new Exception;
			}
		}
	}

	protected function _dropTable()
	{
		$db =& Database::getInstance() ;

		$sqlFilePath = $this->path.'/sql/mysql.sql' ;
		$prefix      = $db->prefix().'_'.$this->dirname;

		if ( !file_exists($sqlFilePath) )
		{
			return;
		}

		$this->_addMessage('SQL file found at <b>'.htmlspecialchars($sqlFilePath).'</b>.<br  /> Deleting tables...');

		$sqlLines = file($sqlFilePath);

		foreach ( $sqlLines as $sqlLine )
		{
			if ( !preg_match('/^CREATE TABLE \`?([a-zA-Z0-9_-]+)\`? /i', $sqlLine, $regs) )
			{
				continue;
			}

			$sql = 'DROP TABLE '.$prefix.'_'.$regs[1];

			if ( $db->query($sql) )
			{
				$this->_addMessage('Table <b>'.htmlspecialchars($prefix.'_'.$regs[1]).'</b> dropped.');
			}
			else
			{
				$this->_addError('ERROR: Could not drop table <b>'.htmlspecialchars($prefix.'_'.$regs[1]).'<b>.');
			}
		}
	}

	protected function _addMessage($msg)
	{
		$this->messages[] = array('type' => 'message', 'content' => $msg);
	}

	protected function _addError($msg)
	{
		$this->messages[] = array('type' => 'error', 'content' => $msg);
	}

	protected function _message()
	{
		// TODO >> test on XOOPS 2.0
		global $msgs, $ret;
		$ret =& $ret;

		if ( !is_array($ret) )
		{
			$ret = array();
		}

		foreach ( $this->messages as $message )
		{
			if ( $message['type'] == 'error' )
			{
				$ret[] = '<span style="color:#ff0000;">'.$message['connect'].'</span><br />';
			}
			else
			{
				$ret[] = $message['content'].'<br />';
			}
		}
	}
}

?>
