<?php
/**
 * @brief この機能を使うには、mainfile.phpに下記の一行を追加してください
 * <code>
 * define("XOOPS_MAINFILE_INCLUDED",1);
 * define("USE_BBCODE_NOTES_BY_TOOLTIPS",1);  // ←コレを追加
 * </code>
 */

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

class Legacy_BBCodeNotesByTooltips extends XCube_ActionFilter 
{
	function preBlockFilter()
	{
		if (!defined('USE_BBCODE_NOTES_BY_TOOLTIPS')){
			return ;
		}
		$this->mRoot->mDelegateManager->add('Legacy_TextFilter.MakePreXCodeConvertTable',
											'Legacy_BBCodeNotesByTooltips::makePreXCodeConvertTable',
											XCUBE_DELEGATE_PRIORITY_1);
		$this->mRoot->mDelegateManager->add('Legacy_TextFilter.MakePostXCodeConvertTable',
											'Legacy_BBCodeNotesByTooltips::makePostXCodeConvertTable',
											XCUBE_DELEGATE_PRIORITY_10);
		
		$this->mRoot->mDelegateManager->add('Legacy_RenderSystem.SetupXoopsTpl',
											array($this, 'appendHeader'));

	}
	
	function appendHeader(&$xoopsTpl)
	{
		$this->includeJs('lib/prototype.js', $xoopsTpl);
		$this->includeJs('lib/effects.js', $xoopsTpl);
		$this->includeJs('lib/getElementsBySelector.js', $xoopsTpl);
		$this->includeJs('lib/tooltips.js', $xoopsTpl);
		$this->includeJs('Tooltips.activateOnLoad();', $xoopsTpl);
		$this->includeCSS('lib/tooltips.css',$xoopsTpl);
		$this->includeJs('lib/cookiemanager.js', $xoopsTpl);
		$this->includeJs('xoopsblock.js', $xoopsTpl);
	}
	
	
	function makePreXCodeConvertTable(&$patterns, &$replacements)
	{
		$patterns[] = "/\[note\s*=(.*)\](.*)\[\/note\]/esU";
		$replacements[] = "'[note='.base64_encode('$1').']'.base64_encode('$2').'[/note]'";
	}

	function makePostXCodeConvertTable(&$patterns, &$replacements)
	{
		$patterns[] = "/\[note\s*=(.*)\](.*)\[\/note\]/esU";
		$replacements[0][] = $replacements[1][] = "Legacy_BBCodeNotesByTooltips::noteText('$1','$2')";
	}
	
	
	function noteText($note, $target)
	{
		static $t_num;
		!isset($t_num) and $t_num = 0;
		$t_num++;
		
		$text = sprintf('<span class="tooltipTrigger" id="toolTipNote%d">%s</span><div class="tooltip" id="toolTipNote%dPopUp"><p>%s</p></div>',
						$t_num, htmlspecialchars(base64_decode($target)),
						$t_num, htmlspecialchars(base64_decode($note))
						);
		return $text;
	}
	
	function includeJs($js, &$xoopsTpl)
	{
		static $included_js;
		!isset($included_js) and $included_js = array();
		if (isset($included_js[$js])){
			 return;
		}
		$included_js[$js] = true;
		
		$xmh = $xoopsTpl->get_template_vars('xoops_module_header');
		if (preg_match('{\.js$}', $js)){
			$xmh = sprintf('%s<script src="%s/modules/xanhte/js/%s" type="text/javascript"></script>'."\n",
						   $xmh, XOOPS_URL, $js);
		} else {
			$xmh = sprintf('%s<script>%s</script>'."\n",
						   $xmh, $js);
		}
		
		$xoopsTpl->assign('xoops_module_header', $xmh);
		return;
	}
	
	
	function includeCSS($css, &$xoopsTpl)
	{
		static $included_css;
		!isset($included_css) and $included_css = array();
		if (isset($included_css[$css])){
			 return;
		}
		$included_css[$css] = true;
		
		$xmh = $xoopsTpl->get_template_vars('xoops_module_header');
		$xmh = sprintf('%s<link href="%s/modules/xanhte/js/lib/css/%s" rel="stylesheet" type="text/css" media="screen" />'."\n",
					   $xmh, XOOPS_URL, $css);
		
		$xoopsTpl->assign('xoops_module_header', $xmh);
		return;
	}
}
