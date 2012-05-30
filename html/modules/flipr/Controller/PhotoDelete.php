<?php
class Flipr_Controller_PhotoDelete extends Flipr_Abstract_Controller
{
	protected $photoId = 0;
	protected $photoHandler = null;
	protected $photoModel   = null;
	protected $database     = null;

	protected $errors = array();

	public function __construct()
	{
		parent::__construct();
		$this->_fetchPhotoId();
		$this->_getPhotoHandler();
		$this->_getPhotoModel();
		$this->_setPageTitle();
		$this->_getDatabase();
		$this->_checkPermission2();
	}

	public function main()
	{
		try
		{
			if ( $this->post('delete') )
			{
				$this->_delete();
			}
		}
		catch ( Exception $e )
		{
		}

		$this->_default();
	}

	protected function _default()
	{
		$this->_assignToken();
		$this->_assignOutput();
		$this->_assignPhoto();
		$this->_view();
	}

	protected function _delete()
	{
		$this->_validate();
		$this->_deleteBegin();
		$this->_deletePhoto();
		$this->_deletePhotoFile();
		$this->_deleteThumbnailFile();
		$this->_deleteCommit();
		$this->_redirect();
	}

	protected function _checkPermission()
	{
		// prevent
	}

	protected function _checkPermission2()
	{
		if ( $this->_isMyPhoto() )
		{
			return;
		}
	
		parent::_checkPermission();
	}
	
	protected function _isMyPhoto()
	{
		return ( $this->photoModel->getVar('user_id') == $this->root->cms->getUserId() );
	}

	protected function _fetchPhotoId()
	{
		$this->photoId = (int) $this->get('photo');

		if ( $this->photoId < 1 )
		{
			$this->root->redirect("Please select photo.", 'album_list');
		}
	}

	protected function _getPhotoHandler()
	{
		$this->photoHandler =& $this->root->getModelHandler('Photo');
	}

	protected function _getPhotoModel()
	{
		$this->photoModel = $this->photoHandler->load($this->photoId);

		if ( $this->photoModel === false )
		{
			$this->root->redirect("Photo not found.", 'album_list');
		}
	}

	protected function _setPageTitle()
	{
		$this->pageTitle = t("Delete {1}", $this->photoModel->getVar('name'));
	}

	protected function _assignToken()
	{
		$this->output['token'] = Pengin_Token::issue();
	}

	protected function _validate()
	{
		$this->_validateToken();

		if ( $this->_isError() )
		{
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

	protected function _deleteBegin()
	{
		$result = $this->database->query("BEGIN");

		if ( $result === false )
		{
			$this->_addError(t("DATABASE ERROR: Failed to delete album. ERR{1}", '100'), true);
		}
	}

	protected function _deletePhoto()
	{
		$isDeleted = $this->photoHandler->delete($this->photoId);

		if ( $isDeleted === false )
		{
			$this->_rollback('200');
		}
	}

	protected function _deletePhotoFile()
	{
		$photoPath = $this->photoModel->getPhotoPath();

		if ( file_exists($photoPath) === false )
		{
			return;
		}

		$isDeleted = unlink($photoPath);

		if ( $isDeleted === false )
		{
			$this->_rollback('300');
		}
	}

	protected function _deleteThumbnailFile()
	{
		$photoPath = $this->photoModel->getThumbPath();

		if ( file_exists($photoPath) === false )
		{
			return;
		}

		$isDeleted = unlink($photoPath);

		if ( $isDeleted === false )
		{
			$this->_rollback('301');
		}
	}

	protected function _deleteCommit()
	{
		$result = $this->database->query("COMMIT");

		if ( $result === false )
		{
			$this->_rollback('400');
		}
	}

	protected function _rollback($errorId)
	{
		$this->database->query("ROLLBACK");
		$this->_addError(t("DATABASE ERROR: Failed to delete album. ERR{1}", $errorId), true);
	}

	protected function _redirect()
	{
		$this->root->redirect("Deleted photo successfully.", 'photo_list', null, array('album' => $this->photoModel->getVar('album_id')));
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

	protected function _assignOutput()
	{
		$this->output['errors'] = $this->errors;
	}

	protected function _getDatabase()
	{
		$this->database =& $this->root->cms->database();
	}
	
	protected function _assignPhoto()
	{
		$this->output['photo'] = $this->photoModel->getVars();
	}
}
