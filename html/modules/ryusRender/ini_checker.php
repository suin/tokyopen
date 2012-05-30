<?php
/**
 * example siteConfig
 *
 * [RenderSystems]
 * Legacy_AdminRenderSystem=Legacy_RyusAdminRenderSystem
 *
 * [Legacy_RyusAdminRenderSystem]
 * root=XOOPS_ROOT_PATH
 * path=/modules/ryusRender/kernel
 * class=Legacy_RyusAdminRenderSystem
 */
if (!defined('XOOPS_ROOT_PATH')) exit();

class ryusRenderIniChecker
{
    public static function doCheck()
    {
        $root =& XCube_Root::getSingleton();
        $site_config_ryus = $root->getSiteConfig('Legacy_RyusAdminRenderSystem');
        $site_config_admin = $root->getSiteConfig('RenderSystems', 'Legacy_AdminRenderSystem', null);

        if (empty($site_config_ryus)) {
            ryusRenderIniChecker::ryusRenderExit();
        }
        if (
            is_array($site_config_ryus)
            and isset($site_config_ryus['root'])
            and isset($site_config_ryus['path'])
            and isset($site_config_ryus['class'])
            ) {
            $classfile = sprintf(
                                 '%s%s/%s.class.php',
                                 $site_config_ryus['root'],
                                 $site_config_ryus['path'],
                                 $site_config_ryus['class']
                                 );
            if (!file_exists($classfile)) {
                ryusRenderIniChecker::ryusRenderExit();
            }
        } else {
            ryusRenderIniChecker::ryusRenderExit();
        }

        if (empty($site_config_admin)) {
            ryusRenderIniChecker::ryusRenderExit();
        }

        if ($site_config_admin !== $site_config_ryus['class']) {
            ryusRenderIniChecker::ryusRenderExit();
        }

        return null;
    }

    private static function ryusRenderExit()
    {
        $message = 'error: ryusRender requird';

        $root =& XCube_Root::getSingleton();
        define('XOOPS_FOOTER_INCLUDED', 1);
        if (!is_object($root->mController)) exit();

        $xoopsLogger =& $root->mController->getLogger();
        $xoopsLogger->stopTime();

        // inquiry XCL taisaku
        $root->mSiteConfig['RenderSystems']['Inquiry_RenderSystem'] = 'Legacy_RenderSystem';
        $root->mSiteConfig['RenderSystems']['Legacy_RenderSystem'] = 'Legacy_RenderSystem';
        $root->mSiteConfig['RenderSystems']['Legacy_AdminRenderSystem'] = 'Legacy_RenderSystem';

        // ignore blocks
        // $root->mController->executeHeader();
        if (isset($GLOBALS['root']->mContext->mModule)) {
            // modules
            echo $message;
        } else {
            // legacy
            $GLOBALS['xoopsTpl']->assign('xoops_contents', $message);
        }
        $root->mController->executeView();
        exit();
    }
}
