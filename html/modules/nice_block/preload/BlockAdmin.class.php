<?php
if ( !defined('PENGIN_PATH') ) exit;

require_once PENGIN_PATH.'/Pengin.php';

class Nice_block_BlockAdmin extends XCube_ActionFilter
{
	protected $root = null;

	protected $dirname  = '';
	protected $xoopsTpl = null;

	protected $moveMode = false;

	public function preBlockFilter()
	{
		$this->root    =& Pengin::getInstance();
		$this->dirname = basename(dirname(dirname(__FILE__)));

		$this->mRoot->mDelegateManager->add('Legacy_RenderSystem.BeginRender', array(&$this, 'beginRender'));
		$this->mRoot->mDelegateManager->add('NiceAdmin.Preload.AdminTaskBar.AddTaskBar', array($this, 'addMenuBar'));

		if ( $this->root->get('move_block') )
		{
			$this->moveMode = true;
		}
	}

	public function beginRender(&$xoopsTpl)
	{
		$trace = debug_backtrace();

		if ( !isset($trace[3]['function']) or $trace[3]['function'] !== 'renderTheme' )
		{
			return;
		}

		if ( !$this->_isAdmin() )
		{
			return;
		}

		$this->xoopsTpl =& $xoopsTpl;

		if ( $this->moveMode === true )
		{
			$this->_displayAllSides();
			$this->_assignBlockInfo();
		}
	}

	protected function _displayAllSides()
	{
		$sides = array('xoops_ccblocks', 'xoops_clblocks', 'xoops_crblocks');

		foreach ( $sides as $side )
		{
			$this->_addDummyBlocks($side);
		}

		$this->xoopsTpl->assign('xoops_showrblock', 1);
		$this->xoopsTpl->assign('xoops_showcblock', 1);
		$this->xoopsTpl->assign('xoops_showlblock', 1);

	}

	protected function _assignBlockInfo()
	{
		$sides = array('xoops_ccblocks', 'xoops_clblocks', 'xoops_crblocks', 'xoops_lblocks', 'xoops_rblocks');

		foreach ( $sides as $side )
		{
			$blocks = $this->xoopsTpl->get_template_vars($side);

			if ( is_array($blocks) === false ) {
				continue;
			}

			foreach ( $blocks as &$block)
			{
				if ( isset($block['id']) === false )
				{
					continue;
				}

				$block['info'] = sprintf(' blockid="%u"', $block['id']);
			}

			$this->xoopsTpl->assign($side, $blocks);
		}
	}

	public function addMenuBar($adminTaskBar)
	{
		$translator = new Pengin_Translator(
			$this->root->cms->modulePath,
			$this->root->cms->cachePath,
			$this->root->cms->charset
		);
		$translator->useTranslation(
			$this->dirname,
			$this->root->cms->langcode,
			'translation'
		);

		if ( $this->moveMode === true )
		{
			$url   = '';
			$title = $translator->translate("Save Blocks");
			$js    = XOOPS_URL.'/modules/'.$this->dirname.'/public/javascript/drug_drop.js';
			$adminTaskBar->addJavaScript($js)
				->addLink('DrugDrop', $title, $url,0, false)
				->addLink('DrugDropCancel', $translator->translate("Cancel Move Blocks"), $this->_getCancelUrl(), 0, false);
		}
		else
		{
			$title = $translator->translate("Blocks Admin");
			$adminTaskBar->addLink('BlocksAdmin', $title , '' , 1);

			// submenu
			$url   = XOOPS_URL.'/modules/legacy/admin/index.php?action=CustomBlockEdit';
			$title = $translator->translate("Add Custom Block");
			$adminTaskBar->addSubLink('BlocksAdmin' , 'tpModalAddCustomBlock', $title, $url);
			
			$url   = XOOPS_URL.'/modules/legacy/admin/index.php?action=BlockInstallList';
			$title = $translator->translate("Install Block");
			$adminTaskBar->addSubLink('BlocksAdmin' , 'tpModalInstallBlock', $title, $url);

			$url   = $this->_getMoveModeUrl();
			$title = $translator->translate("Move Blocks");
			$adminTaskBar->addSubLink('BlocksAdmin' ,'tpNoModalDrugDrop', $title, $url);

		}

		$css = XOOPS_URL.'/modules/'.$this->dirname.'/public/css/drug_drop.css';
		$adminTaskBar->addStyleSheet($css);
	}

	protected function _addDummyBlocks($sideName)
	{
		$side = $this->xoopsTpl->get_template_vars($sideName);

		if ( !$side )
		{
			$this->xoopsTpl->assign($sideName, array(1));
		}
	}

	protected function _getMoveModeUrl()
	{
		$url    = Pengin_Url::getUrl();
		$parsed = Pengin_Url::parse($url);

		if ( !isset($parsed['query']) )
		{
			$parsed['query'] = array();
		}

		$parsed['query']['move_block'] = 1;

		$url = Pengin_Url::glue($parsed);

		return $url;
	}

	protected function _getCancelUrl()
	{
		$url    = Pengin_Url::getUrl();
		$parsed = Pengin_Url::parse($url);

		if ( isset($parsed['query']['move_block']) )
		{
			unset($parsed['query']['move_block']);
		}

		$url = Pengin_Url::glue($parsed);

		return $url;
	}

	protected function _isAdmin()
	{
		global $xoopsUser;

		if ( !is_object($xoopsUser) )
		{
			return false;
		}

		$moduleHandler =& xoops_gethandler('module');
		$moduleModel   = $moduleHandler->getByDirname($this->dirname);
		$moduleId      = $moduleModel->getVar('mid');

		return $xoopsUser->isAdmin($moduleId);
	}
}
