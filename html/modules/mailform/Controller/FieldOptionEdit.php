<?php

class Mailform_Controller_FieldOptionEdit extends Mailform_Abstract_Controller
{
	protected $result = array(
		'error' => false,
		'html'  => '',
		'pageTitle' => '',
	);

	protected $params = array();
	protected $plugin = null;
	protected $errors = array();

	public function __construct()
	{
		parent::__construct();
		$this->_checkPermsission();
		$this->_setUpPlugin();
		$this->_fetchParams();
		$this->result['pageTitle'] = t("Field Option");
	}

	public function main()
	{
		if ( $this->post('validate') ) {
			$this->_validateAction();
		}

		$this->_defaultAction();
	}

	protected function _checkPermsission()
	{
		if ( $this->root->cms->isAdmin() === false ) {
			die('error');
		}
	}

	protected function _setUpPlugin()
	{
		$fieldName = $this->get('name');

		$pluginManager = new Mailform_Plugin_Manager();
		$plugin = $pluginManager->getPlugin($fieldName);

		if ( $plugin === false ) {
			$this->_error();
		}

		$this->plugin = $plugin;
	}

	protected function _fetchParams()
	{
		$params = $this->post('params');

		if ( is_array($params) === false ) {
			$this->_error();
		}

		$options = $this->plugin->getDefaultPluginOptions();

		foreach ( $options as $name => $value ) {
			if ( array_key_exists($name, $params) === false ) {
				$params[$name] = '';
			}
		}

		$this->params = $params;
	}

	protected function _defaultAction()
	{
		$params = $this->params;
		$params = Pengin_TextFilter::escapeHtmlArray($params);
		ob_start();
		$this->plugin->editPluginOptions($params);
		$this->output['editHTML'] = ob_get_clean();
		$this->_viewAjax();
	}

	protected function _validateAction()
	{
		$this->errors = $this->plugin->validatePluginOptions($this->params);

		$this->result['validationError'] = ( count($this->errors) > 0 );
		$this->output['errors'] = $this->errors;
	}

	protected function _error()
	{
		$this->result['error'] = true;
		$this->_viewAjax();
	}

	protected function _viewAjax()
	{
		if ( $this->result['html'] == '' ) {
			ob_start();
			$this->_view();
			$this->result['html'] = ob_get_clean();
		}

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($this->result);
		die;
	}
}
