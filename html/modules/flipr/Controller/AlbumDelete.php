<?php
class Flipr_Controller_AlbumDelete extends Flipr_Abstract_Controller
{
	protected $albumId = 0;
	protected $albumHandler  = null;
	protected $albumModel    = null;
	protected $photoHandler  = null;
	protected $database      = null;

	protected $errors = array();

	public function __construct()
	{
		parent::__construct();
		$this->_fetchAlbumId();
		$this->_getAlbumModelHandler();
		$this->_getAlbumModel();
		$this->_getPhotoHandler();
		$this->_setPageTitle();
		$this->_getDatabase();
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
		$this->_view();
	}

	protected function _delete()
	{
		$this->_validate();
		$this->_deleteBegin();
		$this->_deleteAlbum();
		$this->_deletePhotos();
		$this->_deleteCommit();
		$this->_redirect();
	}

	protected function _fetchAlbumId()
	{
		$this->albumId = (int) $this->get('album');

		if ( $this->albumId < 1 )
		{
			$this->root->redirect("Please select album.", 'album_list');
		}
	}

	protected function _getAlbumModelHandler()
	{
		$this->albumHandler =& $this->root->getModelHandler('Album');
	}

	protected function _getAlbumModel()
	{
		$this->albumModel = $this->albumHandler->load($this->albumId);

		if ( $this->albumModel === false )
		{
			$this->root->redirect("Album not found.", 'album_list');
		}
	}

	protected function _getPhotoHandler()
	{
		$this->photoHandler =& $this->root->getModelHandler('Photo');
	}

	protected function _setPageTitle()
	{
		$this->pageTitle = t("Delete {1}", $this->albumModel->getVar('name'));
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

	protected function _deleteAlbum()
	{
		$isDeleted = $this->albumHandler->delete($this->albumId);

		if ( $isDeleted === false )
		{
			$this->_rollback('200');
		}
	}

	protected function _deletePhotos()
	{
		$photoModels = $this->photoHandler->findByAlbumId($this->albumId);

		foreach ( $photoModels as $photoModel )
		{
			$this->_deletePhoto($photoModel);
			$this->_deletePhotoFile($photoModel);
			$this->_deleteThumbnailFile($photoModel);
		}
	}

	protected function _deletePhoto($photoModel)
	{
		$isDeleted = $this->photoHandler->delete($photoModel->getVar('id'));

		if ( $isDeleted === false )
		{
			$this->_rollback('300');
		}
	}

	protected function _deletePhotoFile($photoModel)
	{
		$photoPath = $photoModel->getPhotoPath();

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

	protected function _deleteThumbnailFile($photoModel)
	{
		$photoPath = $photoModel->getThumbPath();

		if ( file_exists($photoPath) === false )
		{
			return;
		}

		$isDeleted = unlink($photoPath);

		if ( $isDeleted === false )
		{
			$this->_rollback('302');
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
		$this->root->redirect("Deleted album successfully.", 'album_list');
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
}
