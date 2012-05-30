<?php

require_once _TP_DEFINITION_CUSTOM_FILE;
require_once _TP_DEFINITION_INC_FILE;


  /* get installable themes */
  include_once TP_SMARTY_CLASS_PATH;
  include_once dirname(__FILE__).'/class/hdCustomSmarty.class.php';
  include_once dirname(dirname(dirname(__FILE__))).'/core/XCube_Theme.class.php';
  
  $default_checked = array('tp_solid', 'tp_over_the_rainbow', 'cube_default');
  
// copy from	modules/legacyRender/kernel/DelegateFunctions.class.php::function getInstalledThemes()
  if ($d_handler = opendir(XOOPS_ROOT_PATH.'/themes')) {
	  while (($dirname = readdir($d_handler)) !== false) {
		  if ($dirname == "." || $dirname == "..") {
					continue;
		  }
		  
		  $themeDir = XOOPS_ROOT_PATH . "/themes/" . $dirname;
		  if (is_dir($themeDir)) {
			  $theme = new XCube_Theme();
			  $theme->mDirname = $dirname;
			  
			  if ($theme->loadManifesto($themeDir . "/manifesto.ini.php")) {
				  if ($theme->mRenderSystemName == 'Legacy_RenderSystem') {
//					  $results[] =& $theme;
					  $_res = get_object_vars($theme);
					  $_res['checked'] = in_array($theme->mDirname, $default_checked);
					  $results[] = $_res;
				  }
			  }
			  else {
				  if (file_exists($themeDir . "/theme.html")) {
					  $theme->mName = $dirname;
					  $theme->mRenderSystemName = 'Legacy_RenderSystem';
					  $theme->mFormat = "XOOPS2 Legacy Style";
//					  $results[] =& $theme;
					  $_res = get_object_vars($theme);
					  $_res['checked'] = in_array($theme->mDirname, $default_checked);
					  $results[] = $_res;
				  }
			  }
			  
			  unset($theme);
		  }
	  }
	  closedir($d_handler);
  }
  
  
  $tpl_file = XOOPS_ROOT_PATH . '/install/custom/templates/install2_selectthemes.html';
  $hd_smarty = new HdCustomSmarty();
  $hd_smarty->assign('themes', $results);
  $hd_smarty->assign('tp_default', 'tp_solid');
  $wizard->assign('theme_selector', $hd_smarty->fetch($tpl_file));
  
  $wizard->setTemplatePath(dirname(__FILE__));
  $wizard->render('templates/install_siteInit_hd.html');

