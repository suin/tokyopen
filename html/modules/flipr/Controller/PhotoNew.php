<?php
class Flipr_Controller_PhotoNew extends Flipr_Abstract_FormController
{
	protected $albumId = 0;
	protected $albumHandler  = null;
	protected $albumModel    = null;
	protected $photoHandler  = null;
	protected $photoUploader = null;
	protected $photoModel    = null;

	public function __construct()
	{
		parent::__construct();
		$this->_fetchAlbumId();
		$this->_getAlbumModelHandler();
		$this->_getAlbumModel();
		$this->_getPhotoHandler();
		$this->_getPhotoModel();
		$this->_getPhotoUploader();
		$this->_setPageTitle();
		$this->_initInput();
	}

	protected function _default()
	{
		$this->_assignAlbum();
		$this->_assignPhoto();
		$this->_assignOutput();
		$this->_assignToken();
		$this->_view();
	}

	protected function _save()
	{
		$this->_fetchInput();
		$this->_validate();
		$this->_moveFile();
		$this->_savePhoto();
		$this->_makeThumbnail();
		$this->_redirect();
	}

	protected function _confirm()
	{
	}

	protected function _moveFile()
	{
		$newName = $this->photoUploader->getSaveName();

		if ( $this->photoUploader->rename($newName) === false )
		{
			$this->_deletePhoto();
			$this->errors += $this->photoUploader->get('errors');
			throw new Exception;
		}
	}

	protected function _savePhoto()
	{
		$this->photoModel->setVar('name', $this->_getPhotoName());
		$this->photoModel->setVar('desc', $this->input['desc']);
		$this->photoModel->setVar('user_id', $this->root->cms->getUserId());
		$this->photoModel->setVar('album_id', $this->albumId);
		$this->_setFileInfo();

		$isSaved = $this->photoHandler->save($this->photoModel);

		if ( $isSaved === false )
		{
			$this->_deletePhoto();
			$this->_addError("Failed to upload photo.", true);
		}
	}

	protected function _setFileInfo()
	{
		$this->photoModel->setVar('file_name', $this->photoUploader->get('saveName'));
		$this->photoModel->setVar('file_ext', $this->photoUploader->get('fileExtention'));
		$this->photoModel->setVar('file_title', $this->photoUploader->getFileName());
		$this->photoModel->setVar('file_mime', $this->photoUploader->get('fileType'));
		$this->photoModel->setVar('file_size', $this->photoUploader->get('fileSize'));
		$this->photoModel->setVar('file_width', $this->photoUploader->getImageWidth());
		$this->photoModel->setVar('file_height', $this->photoUploader->getImageHeight());
	}

	protected function _getPhotoName()
	{
		if ( isset($this->input['name'][0]) )
		{
			return $this->input['name'];
		}

		return $this->photoUploader->getFileName();
	}

	protected function _deletePhoto()
	{
		unlink($this->photoUploader->get('savePath'));
	}

	protected function _makeThumbnail()
	{
		$originalPath  = $this->photoModel->getPhotoPath();
		$parsed = explode('.', $originalPath);
		$parsed[count($parsed) - 2] .= '_small';
		$thumbnailPath = implode('.', $parsed);

		$thumbnailer = new Flipr_Library_Thumbnailer($originalPath, $thumbnailPath, $this->configs['thumb_width'], $this->configs['thumb_height']);
		$thumbnailer->resize('min'); // TODO >> check result
	}

	protected function _redirect()
	{
		$this->root->redirect("Uploaded photo successfully.", 'photo_list', null, array('album' => $this->photoModel->getVar('album_id')));
	}

	protected function _initInput()
	{
		$this->input = array(
			'name' => '',
			'desc' => '',
		);
	}

	protected function _fetchInput()
	{
		foreach ( $this->input as $name => $value )
		{
			$this->input[$name] = $this->post($name);
		}
	}

	protected function _validate()
	{
		$this->_validateToken();
		$this->_validateUploadDirectory();
		$this->_validateName();
		$this->_validateDesc();
		$this->_validateFile();

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
		}
	}

	protected function _validateName()
	{
		if ( isset($this->input['name'][255]) === true )
		{
			$this->_addError("Photo Name is too long.");
		}
	}

	protected function _validateDesc()
	{
	}

	protected function _validateFile()
	{
		if ( $this->photoUploader->fetch('file') === false )
		{
			$this->_addError("Please select file.", true);
		}

		if ( $this->photoUploader->upload() === false )
		{
			$this->errors += $this->photoUploader->get('errors');

			throw new Exception;
		}
	}

	protected function _fetchAlbumId()
	{
		$this->albumId = (int) $this->get('album');

		if ( $this->albumId < 1 )
		{
			$this->root->redirect("Please select album.", 'album_list', 'upload');
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
			$this->root->redirect("Album not found.", 'album_list', 'upload');
		}
	}

	protected function _getPhotoHandler()
	{
		$this->photoHandler =& $this->root->getModelHandler('Photo');
	}

	protected function _getPhotoModel()
	{
		$this->photoModel = $this->photoHandler->create();
	}

	protected function _getPhotoUploader()
	{
		$this->photoUploader = new Flipr_Library_PhotoUpload;
	}

	protected function _setPageTitle()
	{
		$this->pageTitle = t("Upload Photos to {1}", $this->albumModel->getVar('name'));
	}

	protected function _assignAlbum()
	{
		$this->output['album'] = $this->albumModel->getVars();
	}

	protected function _assignPhoto()
	{
		$this->output['photo'] = $this->photoModel->getVars();
	}

	protected function _assignOutput()
	{
		$this->output['max_size'] = $this->photoUploader->getMaxSize();
	}

	protected function _assignToken()
	{
		$this->output['token'] = Pengin_Token::issue();
	}
}
