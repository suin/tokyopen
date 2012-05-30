<?php
/**
 * @package ryusRender
 * $Id: Legacy_RyusAdminRenderSystem.class.php 97 2009-07-10 06:52:27Z naoto $
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

require_once XOOPS_ROOT_PATH . '/modules/legacyRender/kernel/Legacy_AdminRenderSystem.class.php';

class Legacy_RyusAdminRenderSystem extends Legacy_AdminRenderSystem
{
	const AJAXMODE_NO     = 0; // Ajaxモード以外
	const AJAXMODE_PLAIN  = 1; // スタイルシートがつかない素のHTMLだけ
	const AJAXMODE_IFRAME = 2; // スタイルシートつき

	protected $ajaxmode = self::AJAXMODE_NO;
	
	public function prepare($controller)
	{
		parent::prepare($controller);

		// GET, POST, Cookieが勝つ
		if ( isset($_REQUEST['ajaxmode']) === true ) {
			switch ( $_REQUEST['ajaxmode'] ) {
				case self::AJAXMODE_PLAIN :
					$this->ajaxmode = self::AJAXMODE_PLAIN;
					break;
				case self::AJAXMODE_IFRAME: 
					$this->ajaxmode = self::AJAXMODE_IFRAME;
					break;
				default:
					break;
			} 
		}
	}
    
    /**
     * オリジナルとの違い
     * - テーマ下admin_theme.htmlのチェック削除
     * @param string $target 
     * @return void
     * @author ryuji
     */
    function renderTheme(&$target)
	{
		//
		// Assign from attributes of the render-target.
		//
		foreach($target->getAttributes() as $key=>$value) {
			$this->mSmarty->assign($key,$value);
		}
		
		$this->mSmarty->assign('stdout_buffer', $this->_mStdoutBuffer);

		//
		// Get a virtual current module object from the controller and assign it.
		//
		$moduleObject =& $this->mController->getVirtualCurrentModule();
		$this->mSmarty->assign("currentModule", $moduleObject);

		//
		// Other attributes
		//
		$this->mSmarty->assign('legacy_sitename', $this->mController->mRoot->mContext->getAttribute('legacy_sitename'));
		$this->mSmarty->assign('legacy_pagetitle', $this->mController->mRoot->mContext->getAttribute('legacy_pagetitle'));
		$this->mSmarty->assign('legacy_slogan', $this->mController->mRoot->mContext->getAttribute('legacy_slogan'));
		
		//
		// Theme rendering
		//
		switch ( $this->ajaxmode ) {
			case self::AJAXMODE_PLAIN:
				$themeFileName = 'file:admin_ajax.html';
				break;
			case self::AJAXMODE_IFRAME:
				$themeFileName = 'file:admin_iframe.html';
				break;
			case self::AJAXMODE_NO:
			default:
				$blocks = array();
				foreach($this->mController->mRoot->mContext->mAttributes['legacy_BlockContents'][0] as $key => $result) {
					// $this->mSmarty->append('xoops_lblocks', $result);
					$blocks[$result['name']] = $result;
				}
				$this->mSmarty->assign('xoops_lblocks', $blocks);
				
				$themeFileName = "file:admin_theme.html";
				break;
		}

		$this->mSmarty->template_dir=LEGACY_ADMIN_RENDER_FALLBACK_PATH;


		$this->mSmarty->setModulePrefix('');
		$result=$this->mSmarty->fetch($themeFileName);

		$target->setResult($result);
	}
	
    function renderMain(&$target)
    {
        //
        // Assign from attributes of the render-target.
        //
        foreach ($target->getAttributes() as $key => $value) {
            $this->mSmarty->assign($key, $value);
        }
        if (defined('RYUS_ADMIN_THEME_URL')) {
            $this->mSmarty->assign('ryus_admin_theme_url', RYUS_ADMIN_THEME_URL);
        }

        $result = null;
        if ($target->getTemplateName()) {
            if ($target->getAttribute('legacy_module') != null) {
                $this->mSmarty->setModulePrefix($target->getAttribute('legacy_module'));
            }
            $template_dir = $this->getTemplateDirname($target);
            if (empty($template_dir)) {
                $result = $this->mSmarty->fetch('db:' . $target->getTemplateName());
            } else {
                $this->mSmarty->template_dir = $template_dir;
                $result = $this->mSmarty->fetch($template_dir . '/' . $target->getTemplateName());
            }

            $buffer = $target->getAttribute('stdout_buffer');
            $this->_mStdoutBuffer .= $buffer;
        } else {
            $result = $target->getAttribute('stdout_buffer');
        }
        if (empty($result)) {
            $result = $target->getAttribute('stdout_buffer');
        }
        $target->setResult($result);

        //
        // Clear assign.
        //
        foreach ($target->getAttributes() as $key => $value) {
            $this->mSmarty->clear_assign($key);
        }
    }

    public function getTemplateDirname(&$target)
    {
        $filename = $target->getTemplateName();
        if ($target->getAttribute('legacy_module') != null) {
            $dirname = $target->getAttribute('legacy_module');
        } else {
            $dirname = $target->mModuleName;
        }

        $candidates = array();
        if (defined('RYUS_ADMIN_THEME_PATH')) {
            if (!empty($dirname)) {
                $candidates[11] = RYUS_ADMIN_THEME_PATH . '/' . $dirname;
            }
            $candidates[12] = RYUS_ADMIN_THEME_PATH;
        }
        if (!empty($dirname)) {
            $candidates[31] = XOOPS_THEME_PATH . '/admin/modules/' . $dirname;
            $candidates[51] = XOOPS_MODULE_PATH . '/' . $dirname . '/admin/templates';
        }
        $candidates[32] = XOOPS_THEME_PATH . '/admin';
        $candidates[99] = LEGACY_ADMIN_RENDER_FALLBACK_PATH;

        ksort($candidates);
        $template_dirname = null;
        foreach ($candidates as $sort => $dir) {
            if (file_exists($dir . '/' . $filename) and empty($template_dirname)) {
                $template_dirname = $dir;
                continue;
            }
        }
        return $template_dirname;
    }
}
