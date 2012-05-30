<?php
class Flipr_Library_PhotoUpload extends Pengin_Upload
{
	public function __construct()
	{
		$root =& Pengin::getInstance();

		$configHandler =& $root->getModelHandler('Config');
		$configs = $configHandler->getConfigs();

		$this->upDir             = $root->cms->uploadPath.DS.$root->context->dirname; // TODO >> config
		$this->maxSize           = $configs['max_file_size'];
		$this->prefix            = 'tmp_';
		$this->maxNameLength     = 150;
		$this->allowedExtentions = array('gif', 'jpeg', 'jpg', 'png');
		$this->maxImageWidth     = $configs['max_width'];
		$this->maxImageHeight    = $configs['max_height'];
		$this->mimeTypeMap       = array(
			'gif'  => 'image/gif',
			'jpeg' => 'image/jpeg',
			'jpg'  => 'image/jpeg',
			'png'  => 'image/png',
		);
	}

	protected function _validateFile()
	{
		$this->_validateFileError();
		$this->_validateFileExtKnown();
		$this->_validateFileMimetype();
		$this->_validateFileHasExt();
		$this->_validateFileExtAllowed();
		$this->_validateFileSize();
		$this->_validateFileNamed();
		$this->_validateFileUploaded();
		$this->_validateFileMaxSize();
		$this->_validateFileNameMaxLength();
		$this->_validateFileAlreadyExists();
		$this->_validateImageBySize();
		$this->_validateImageWidth();
		$this->_validateImageHeight();
	}
}