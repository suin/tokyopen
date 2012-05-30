<?php
class Flipr_Controller_PhotoEdit extends Flipr_Controller_PhotoNew
{
	protected $photoId = 0;

	public function __construct()
	{
		parent::__construct();
		$this->_checkPermission2();
	}

	protected function _default()
	{
		parent::_default();
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

	protected function _setPageTitle()
	{
		$this->pageTitle = t("Edit {1}", $this->photoModel->getVar('name'));
	}

	protected function _initInput()
	{
		$this->input = array(
			'name' => $this->photoModel->getVar('name'),
			'desc' => $this->photoModel->getVar('desc'),
		);
	}

	protected function _fetchPhotoId()
	{
		$this->photoId = (int) $this->get('photo');

		if ( $this->photoId < 1 )
		{
			$this->root->redirect("Please select photo.", 'album_list');
		}
	}

	protected function _getPhotoModel()
	{
		$this->_fetchPhotoId();
		$this->photoModel = $this->photoHandler->load($this->photoId);

		if ( $this->photoModel === false )
		{
			$this->root->redirect("Photo not found.", 'album_list');
		}
	}

	protected function _moveFile()
	{
		if ( $this->_isNewPhotoUploaded() === true )
		{
			parent::_moveFile();
			$this->_deleteOldPhoto();
		}
	}

	protected function _deleteOldPhoto()
	{
		$upDir         = $this->root->cms->uploadPath.DS.$this->root->context->dirname; // TODO >> config
		$fileName      = $this->photoModel->getVar('file_name');
		$fileExtention = $this->photoModel->getVar('file_ext');
		$photoPath     = $upDir.DS.$fileName.'.'.$fileExtention;

		unlink($photoPath);
	}

	protected function _validateName()
	{
		if ( $this->_isNewPhotoUploaded() === false )
		{
			if ( isset($this->input['name'][0]) === false )
			{
				// 通常タイトルが空のときはファイル名がタイトルになるが、
				// 新しくファイルをアップロードしない場合は必須にする。
				$this->_addError("Please enter Photo Name.", true);
			}
		}

		parent::_validateName();
	}

	protected function _setFileInfo()
	{
		if ( $this->_isNewPhotoUploaded() === true )
		{
			parent::_setFileInfo();
		}
	}

	protected function _deletePhoto()
	{
		if ( $this->_isNewPhotoUploaded() === true )
		{
			parent::_deletePhoto();
		}
	}

	protected function _makeThumbnail()
	{
		if ( $this->_isNewPhotoUploaded() === true )
		{
			parent::_makeThumbnail();
		}
	}

	protected function _validateFile()
	{
		if ( $this->_isNewPhotoUploaded() === true )
		{
			parent::_validateFile();
		}
	}

	protected function _redirect()
	{
		$this->root->redirect("Updated photo successfully.", 'photo', null, array('photo' => $this->photoId));
	}

	protected function _isNewPhotoUploaded()
	{
		return $this->photoUploader->fetch('file');
	}
}
