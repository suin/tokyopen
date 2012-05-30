<?php
require_once XOOPS_ROOT_PATH . '/modules/legacyRender/kernel/Legacy_RenderSystem.class.php';
require_once TP_RYUS_PATH . '/Request.php';
class RyusRender_RenderSystem extends Legacy_RenderSystem
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function prepare($controller)
    {
        parent::prepare($controller);
        $request =  new Ryus_Request();
        $self_url = $request->getUrl();
        $this->mXoopsTpl->assign('tp_self_url', $self_url);
    }
}