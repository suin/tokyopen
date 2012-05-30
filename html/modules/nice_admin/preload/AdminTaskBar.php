<?php
require_once dirname(dirname(__FILE__)).'/Core/AdminTaskBar.php';

class NiceAdmin_Preload_AdminTaskBar extends XCube_ActionFilter
{
	protected $xoopsTpl = null;
	protected $output = array();

	public function preBlockFilter()
	{
		$pengin = Pengin::getInstance();
		$pengin->translator->useTranslation('nice_admin', $pengin->cms->langcode, 'translation');

		$this->mRoot->mDelegateManager->add('Legacy_RenderSystem.RenderTheme', array($this, 'addTaskBar'));
		$this->mRoot->mAdminTaskBar = NiceAdmin_Core_AdminTaskBar::getInstance();
		$this->mRoot->mAdminTaskBar
			->addStylesheet(XOOPS_MODULE_URL.'/nice_admin/public/css/admin_task_bar.css')
			->addLink('AdminMenuControlPanel', t("Control Panel"), XOOPS_URL.'/admin.php')
			->addLink('AdminMenuControlPanelLogout', t("Logout"), XOOPS_URL.'/user.php?op=logout', 0, false);
			
	}

	public function addTaskBar($xoopsTpl)
	{
		// <body>直下に下記を埋め込む
        //<{$xoops_admin_task_bar}>
        $xoopsTpl->register_prefilter(array(&$this, 'addSmartyVar'));

		if ( $this->_isAdmin() === false ) {
			return; // なにもしない
		}

		XCube_DelegateUtils::call('NiceAdmin.Preload.AdminTaskBar.AddTaskBar', $this->mRoot->mAdminTaskBar);

		$this->xoopsTpl = $xoopsTpl;
		$this->_addAdminMenu();
		$this->output['links'] = $this->mRoot->mAdminTaskBar->getLinks();
		$this->output['subLinks'] = $this->mRoot->mAdminTaskBar->getSubLinks();
		$this->_addTaskBarStylesheets();
		$this->_addTaskBarJavaScripts();
		$this->_render();
	}
	public function addSmartyVar($tplSource, &$smarty)
	{
	    if(stristr($tplSource, '<body')){
	        if(strpos($tplSource, '<{$xoops_admin_task_bar}>') === false){
//	            return  preg_replace('/(<body[^<]*>)/', '\1<{$xoops_admin_task_bar}>', $tplSource);
	            return  preg_replace('/(<body.*>)/', '\1<{$xoops_admin_task_bar}>', $tplSource);
	        }
	    }
	    return $tplSource;
	}

	protected function _isAdmin()
	{
		return $this->mRoot->mContext->mUser->isInRole('Site.Administrator');
	}

	protected function _addAdminMenu()
	{
	    // TOPでもJSはセットする（ブロック編集画面も表にだしたいので）
		$this->mRoot->mAdminTaskBar->addJavaScript(XOOPS_MODULE_URL.'/nice_admin/public/js/admin_task_bar_admin_menu.js');

		if ( is_object($this->mRoot->mContext->mXoopsModule) === false ) {
			return; // なにもしない
		}

		$xoopsModule = $this->mRoot->mContext->mXoopsModule;
		$dirname = $xoopsModule->get('dirname');

		if ( $this->mRoot->mContext->mUser->isInRole('Module.'.$dirname.'.Admin') === false ) {
			return false;
		}

		$module = Legacy_Utils::createModule($xoopsModule);

		if ( $module->hasAdminIndex() === false ) {
			return; 
		}

		$links = $module->getAdminMenu();

		foreach ( $links as $k => $link ) {
			if ( isset($link['show']) === true and $link['show'] === false ) {
				unset($links[$k]);
			}
		}

		if ( count($links) === 0 ) {
			return;
		}


		$this->mRoot->mAdminTaskBar->addLink(ucwords($dirname).'Admin' , $xoopsModule->getVar('name'), '', 1);
		foreach ( $links as $link ) {
			$name = 'AdminMenu';
			
			if ( isset($link['name']) === true ) {
				$name .= $link['name'];
			} else {
				$name .= ucfirst($dirname).$link['title'];
			}
			
			$this->mRoot->mAdminTaskBar->addSubLink(ucwords($dirname).'Admin' , 'tpModal'.$name, $link['title'], $link['link']);
		}
	}

	protected function _addTaskBarStyleSheets()
	{
		$stylesheets = $this->mRoot->mAdminTaskBar->getStylesheets();

		foreach ( $stylesheets as $stylesheet ) {
			$this->_addStylesheet($stylesheet);
		}
	}

	protected function _addTaskBarJavaScripts()
	{
		$javaScripts = $this->mRoot->mAdminTaskBar->getJavaScripts();

		foreach ( $javaScripts as $javaScript ) {
			$this->_addJavaScript($javaScript);
		}
	}

	protected function _addHeader($head)
	{
		$xoopsModuleHeader = $this->xoopsTpl->get_template_vars('xoops_module_header');
		$this->xoopsTpl->assign('xoops_module_header', $xoopsModuleHeader.$head);
	}

	protected function _addStylesheet($url)
	{
		$css = '<link rel="stylesheet" type="text/css" media="screen" href="'.$url.'" />';
		$this->_addHeader($css);
	}

	protected function _addJavaScript($url)
	{
		$js = '<script type="text/javascript" src="'.$url.'"></script>';
		$this->_addHeader($js);
	}

	protected function _render()
	{
		$xoopsTpl = new XoopsTpl();
		$xoopsTpl->assign($this->output);
		$adminTaskBar = $xoopsTpl->fetch('db:nice_admin.preload_task_bar.default.tpl');
		$this->xoopsTpl->assign('xoops_admin_task_bar', $adminTaskBar);
	}
}
