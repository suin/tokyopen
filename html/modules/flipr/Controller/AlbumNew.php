<?php
class Flipr_Controller_AlbumNew extends Flipr_Abstract_Controller
{
	protected $input  = array();
	protected $errors = array();

	protected $albumHandler = null;
	protected $albumModel   = null;

	public function __construct()
	{
		parent::__construct();

		$this->output['errors'] =& $this->errors;
		$this->output['input']  =& $this->input;

		$this->_getAlbumModelHandler();
		$this->_getAlbumModel();
		$this->_initInput();
		$this->_setPageTitle();
	}

	public function main()
	{
		try
		{
			if ( $this->post('save') )
			{
				$this->_save();
			}
		}
		catch ( Exception $e )
		{
		}

		$this->_default();
	}

	protected function _default()
	{
		$this->output['token'] = Pengin_Token::issue();

		$this->_view();
	}

	protected function _save()
	{
		$this->_fetchInput();
		$this->_validate();
		$this->_saveAlbum();
		$this->_redirect();
	}

	protected function _getAlbumModelHandler()
	{
		$this->albumHandler =& $this->root->getModelHandler('Album');
	}

	protected function _getAlbumModel()
	{
		$this->albumModel = $this->albumHandler->create();
	}

	protected function _initInput()
	{
		$this->input = array(
			'name'  => '',
			'desc'  => '',
		);
	}

	protected function _setPageTitle()
	{
		$this->pageTitle = t("New Album");
	}

	protected function _fetchInput()
	{
		foreach ( $this->input as $name => $value )
		{
			$this->input[$name] = $this->post($name);
			$this->input[$name] = trim($this->input[$name]);
		}
	}

	protected function _validate()
	{
		$this->_validateToken();
		$this->_validateUploadDirectory();
		$this->_validateName();
		$this->_validateDesc();

		if ( $this->_isError() )
		{
			throw new Exception;
		}
	}

	protected function _saveAlbum()
	{
		$this->albumModel->setVar('name', $this->input['name']);
		$this->albumModel->setVar('desc', $this->input['desc']);
		$this->albumModel->setVar('user_id', $this->root->cms->getUserId());

		$isSaved = $this->albumHandler->save($this->albumModel);

		if ( $isSaved === false )
		{
			$this->_addError("Failed to add new album.");
			throw new Exception;
		}
	}

	protected function _validateToken()
	{
		$token = $this->post('token');

		if ( Pengin_Token::check($token) === false )
		{
			$this->_addError("ERROR: Please confirm the from and submit again.");
			throw new Exception;
		}
	}

	protected function _validateName()
	{
		if ( !isset($this->input['name'][0]) )
		{
			$this->_addError("Please enter album name.");
		}

		if ( isset($this->input['name'][255]) )
		{
			$this->_addError("Album name is too long.");
		}
	}

	protected function _validateDesc()
	{
	}

	protected function _redirect()
	{
		$this->root->redirect("Added new album successfully.", 'album_list', 'upload');
	}

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
