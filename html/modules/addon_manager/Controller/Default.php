<?php
class AddonManager_Controller_Default extends AddonManager_Abstract_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

    protected function _getActionMethodName()
    {
        $actionName = $this->get('action', 'default');
        $methodName = '_' . $actionName;
        return $methodName;
    }

	public function main()
	{
	    // action名を取得
	    $actionName = $this->_getActionMethodName();
	    if(method_exists($this, $actionName)){
    	    // 相当するメソッドがあれば実行
	        call_user_func(array($this, $actionName));
	    }else{
    	    // なければ_default実行
	        $this->_default();
	    }
	}

	protected function _default()
	{
		$this->_view();
	}
}
