<?php
class AddonManager_Controller_AdminDefault extends AddonManager_Abstract_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	

	public function main()
	{
	    if(method_exists($this, $this->Action)){
	        call_user_func(array($this, $this->Action));
	    }else{
    	    // なければ_default実行
	        $this->_default();
	    }
	}

	protected function _default()
	{
	    $addonStoreUrl =  $this->_getConfig('addon_store_url');
	    if ($addonStoreUrl) {
    	    $this->output['addon_store_url'] = $addonStoreUrl;
        }else{
	        $this->template = 'pen:addon_manager.admin_error.tpl';
    	    $this->output['error_message'] = t('addon store url not defined.');
	    }
		$this->_view();
	}
	
}
