<?php
class Pengin_Preload_TemplateAutoUpdate extends XCube_ActionFilter
{
	protected $dirname     = '';
	protected $modulePath  = '';
	protected $templateDir = '';

	protected $temlates        = array();
	protected $updateTemplates = array();

	protected $db = null;

	protected $tplfileHandler = null;
	protected $tplfileObjs    = array();

	public function postFilter()
	{
		$this->_updateTemplate();
	}

	protected function _updateTemplate()
	{
		try
		{
			$this->_checkContext();
			$this->_initDirname();
			$this->_initModulePath();
			$this->_checkModulePath();
			$this->_initTempalteFileList();
			$this->_initDatabase();
			$this->_initTemplateListToUpdate();
			$this->_checkUpdateTemplates();
			$this->_initTplfileHandler();
			$this->_loadTplfileObjects();
			$this->_saveTplfileObjects();
		}
		catch ( Exception $e )
		{
			// nothing
		}
	}

	protected function _checkContext()
	{
		if ( isset($this->mRoot->mContext->mModule->mXoopsModule) == false )
		{
			throw new Exception();
		}

		if ( is_object($this->mRoot->mContext->mModule->mXoopsModule) == false )
		{
			throw new Exception();
		}
	}

	protected function _initDirname()
	{
		$this->dirname = $this->mRoot->mContext->mModule->mXoopsModule->get('dirname');
	}

	protected function _initModulePath()
	{
		$this->modulePath  = XOOPS_MODULE_PATH.'/'.$this->dirname;
		$this->templateDir = $this->modulePath.'/templates';
	}

	protected function _checkModulePath()
	{
		if ( file_exists($this->templateDir) === false )
		{
			throw new Exception();
		}
	}

	protected function _initTempalteFileList()
	{
		$files = $this->_getTemplateFiles();

		foreach ( $files as $file )
		{
			$name = basename($file);
			$name = $this->dirname.'.'.$name;

			$this->templates[] = array(
				'path'          => $file,
				'file'          => $name,
				'last_modified' => filemtime($file),
			);
		}
	}

	protected function _getTemplateFiles()
	{
		$files = glob($this->templateDir.'/*.tpl');

		if ( count($files) < 1 )
		{
			throw new Exception();
		}

		return $files;
	}

	protected function _initDatabase()
	{
		$this->db = $this->mRoot->mController->mDB;
	}

	protected function _initTemplateListToUpdate()
	{
		$lastModified = $this->_getLastModified();

		foreach ( $this->templates as $template )
		{
			if ( $template['last_modified'] > $lastModified )
			{
				$file = $template['file'];
				$updateTemplate = $template;
				$updateTemplate['content'] = file_get_contents($template['path']);
				$this->updateTemplates[$file] = $updateTemplate;
			}
		}
	}

	protected function _getLastModified()
	{
		$table = $this->db->prefix('tplfile');

		$sql = "
		SELECT `tpl_lastmodified` FROM `%s` 
			WHERE `tpl_module` = '%s'
			AND `tpl_tplset` = 'default'
			ORDER BY `tpl_lastmodified` DESC
			LIMIT 1";
		$sql = sprintf($sql, $table, $this->dirname);

		$result = $this->db->queryF($sql);

		if ( $result === false )
		{
			throw new Exception();
		}

		list($lastModified) = $this->db->fetchRow($result);
		
		return $lastModified;
	}

	protected function _checkUpdateTemplates()
	{
		if ( count($this->updateTemplates) < 1 )
		{
			throw new Exception();
		}
	}

	protected function _getInStatement()
	{
		$templateNames = array();

		foreach ( $this->updateTemplates as $updateTemplate )
		{
			$templateNames[] = $updateTemplate['file'];
		}

		$templateNames = "('".implode("','", $templateNames)."')";
		
		return $templateNames;
	}

	protected function _initTplfileHandler()
	{
		$this->tplfileHandler = xoops_gethandler('tplfile');
	}

	protected function _loadTplfileObjects()
	{
		$files = $this->_getInStatement();
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('tpl_module', $this->dirname));
		$criteria->add(new Criteria('tpl_tplset', 'default'));
		$criteria->add(new Criteria('tpl_file', $files, 'IN'));

		$this->tplfileObjs = $this->tplfileHandler->getObjects($criteria);

		if ( count($this->tplfileObjs) < 1 )
		{
			throw new Exception;
		}
	}

	protected function _saveTplfileObjects()
	{
		foreach ( $this->tplfileObjs as $tplfileObj )
		{
			$file   = $tplfileObj->getVar('tpl_file');
			$source = $this->updateTemplates[$file]['content'];
			$tplfileObj->setVar('tpl_source', $source, true);
			$tplfileObj->setVar('tpl_lastmodified', time());
			$isSaved = $this->tplfileHandler->forceUpdate($tplfileObj);
	
			if ( $isSaved === false )
			{
				trigger_error('Failed to update template: '.$file);
			}
		}
	}
}
