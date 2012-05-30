<?php
abstract class Flipr_Abstract_FormController extends Flipr_Abstract_Controller
{
	protected $input  = array();
	protected $errors = array();

	public function __construct()
	{
		parent::__construct();

		$this->output['input']  =& $this->input;
		$this->output['errors'] =& $this->errors;
	}

	public function main()
	{
		try
		{
			if ( $this->post('save') )
			{
				$this->_save();
			}
			if ( $this->post('confirm') )
			{
				$this->_confirm();
			}
		}
		catch ( Exception $e )
		{
		}

		$this->_default();
	}

	abstract protected function _save();
	abstract protected function _confirm();
	abstract protected function _default();
	abstract protected function _validate();
	abstract protected function _initInput();
	abstract protected function _fetchInput();

	protected function _addError($message, $isThrow = false)
	{
		$this->errors[] = t($message);

		if ( $isThrow === true )
		{
			throw new Exception;
		}
	}

	protected function _isError()
	{
		return ( count($this->errors) > 0 );
	}
}
