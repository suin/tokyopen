<?php

class SiteNavi_Controller_AdminPageEdit extends SiteNavi_Abstract_ThreeStepAjaxFormController
{
	protected $useModels = array('Route');

	protected $route = null;
	protected $routeModel = null;
	protected function _getModelHandler()
	{
		return $this->routeHandler;
	}

	protected function _getForm()
	{
		return new SiteNavi_Form_AdminPageEdit();
	}

	
	protected function _useInputTemplate()
	{
		$this->template = 'pen:'.$this->dirname.'.admin_page_edit.input.tpl';
	}
	
	protected function _useConfirmTemplate()
	{
		$this->template = 'pen:'.$this->dirname.'.admin_page_edit.confirm.tpl';
	}

	/**
	 * 出力用の変数をバインドする.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _bindOutput()
	{
		parent::_bindOutput();
		$this->output['model'] = $this->model;

		$mediator = new SiteNavi_Library_AdhocMediator();

		$apiClass = $mediator->getApiClassByModuleId($this->model->get('module_id'));

		if ( $apiClass === false ) {
			$typeName = '';
		} else {
			$api = new $apiClass();
			$typeName = $api->getTitle();
		}


		$this->output['typeName'] = $typeName;

	
	}
}
