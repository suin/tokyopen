<?php
/**
 *
 * @package Legacy
 * @version $Id: install_dbconfirm.inc.php,v 1.1 2007/05/15 02:34:39 minahito Exp $
 * @copyright Copyright 2005, 2006 XOOPSCube.org <http://xoopscube.org/> 
 * @license http://www.gnu.org/licenses/gpl.txt GNU GENERAL PUBLIC LICENSE Version 2
 *
 */
    include_once dirname(__FILE__).'/class/settingmanager_hd.php';
    $sm = new setting_manager_hd(true);

    $content = $sm->checkData();
    if (!empty($content)) {
        $wizard->setTitle(_INSTALL_L93);
        $wizard->setContent($content . $sm->editform());
        $wizard->setNext(array('dbconfirm_hd',_INSTALL_L91));
    } else {
        $wizard->setContent($sm->confirmForm());
    }
    $wizard->render();
?>
