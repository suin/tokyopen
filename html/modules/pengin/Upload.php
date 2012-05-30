<?php
/* Example: When you use this class, you should extend this class.
	class Your_Upload extends Pengin_Upload
	{
		// set some properties here.
	}
	$uploader = new Your_Upload();
	$uploader->fetch('file');
	if ( $uploader->upload() ) {
		$uploader->rename($newName);
	}
*/

abstract class Pengin_Upload
{
	protected $fileName      = null;
	protected $fileType      = null;
	protected $fileSize      = null;
	protected $fileTempName  = null;
	protected $fileExtention = null;
	protected $fileError     = null;

	protected $savePath = null;
	protected $saveName = null;
	protected $errors = array();

	// following propeties should be implemented in sub-classes.
	protected $upDir             = null;
	protected $maxSize           = 100000;
	protected $prefix            = 'tmp_';
	protected $maxNameLength     = 30;
	protected $allowedExtentions = array('gif', 'jpeg', 'jpg', 'png', 'bmp');
	protected $maxImageWidth     = 1024;
	protected $maxImageHeight    = 1024;
	protected $mimeTypeMap       = array(
		'gif'  => 'image/gif',
		'jpeg' => 'image/jpeg',
		'jpg'  => 'image/jpeg',
		'png'  => 'image/png',
		'bmp'  => 'image/bmp',
	);

	public function __construct()
	{
	}

	public function fetch($name, $offset = null)
	{
		if ( $this->exists($name, $offset) === false )
		{
			return false;
		}

		if ( $offset === null )
		{
			$this->_fetchOne($name);
		}
		else
		{
			$this->_fetchMulti($name, $offset);
		}

		if ( $this->fileError === UPLOAD_ERR_NO_FILE )
		{
			return false;
		}

		return true;
	}

	public function exists($name, $offset = null)
	{
		if ( $offset === null )
		{
			return ( isset($_FILES[$name]) and is_string($_FILES[$name]['name']) );
		}
		else
		{
			return isset($_FILES[$name]['name'][$offset]);
		}
	}

	public function upload()
	{
		$this->_setSaveName();
		$this->_setSavePath();
		$this->_validateDirectory();
		$this->_validateFile();

		if ( $this->isError() === true )
		{
			return false;
		}

		if ( $this->_saveFile() === false )
		{
			return false;
		}

		if ( $this->_changePermission() === false )
		{
			return false;
		}

		return true;
	}

	public function rename($newName)
	{
		$newPath = $this->upDir.'/'.$newName;

		if ( file_exists($newPath) )
		{
			$this->_addError("File already exists.");
			return false;
		}

		if ( file_exists($this->savePath) === false )
		{
			$this->_addError("Renaming file not found.");
			return false;
		}

		if ( rename($this->savePath, $newPath) === false )
		{
			$this->_addError("File cannot rename.");
			return false;
		}

		$this->savePath = $newPath;

		return true;
	}

	public function get($name)
	{
		return $this->$name;
	}

	public function getFileName()
	{
		return $this->fileName.'.'.$this->fileExtention;
	}

	public function getSaveName()
	{
		return $this->saveName.'.'.$this->fileExtention;
	}

	public function getImageWidth()
	{
		return reset(getimagesize($this->savePath));
	}

	public function getImageHeight()
	{
		return next(getimagesize($this->savePath));
	}

	public function getMaxSize()
	{
		return $this->_fileSize($this->maxSize);
	}

	public function isError()
	{
		return ( count($this->errors) > 0 );
	}

	public function __get($name)
	{
		return $this->get($name);
	}

	protected function _fetchOne($name)
	{
		$fileParts = pathinfo($_FILES[$name]['name']);
		$this->fileName      = $fileParts['filename'];
		$this->fileExtention = strtolower(pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION));
		$this->fileType      = $_FILES[$name]['type'];
		$this->fileSize      = $_FILES[$name]['size'];
		$this->fileTempName  = $_FILES[$name]['tmp_name'];
		$this->fileError     = $_FILES[$name]['error'];
	}

	protected function _fetchMulti($name, $offset)
	{
		$fileParts = pathinfo($_FILES[$name]['name'][$offset]);
		$this->fileName      = $fileParts['filename'];
		$this->fileExtention = strtolower(pathinfo($_FILES[$name]['name'][$offset], PATHINFO_EXTENSION));
		$this->fileType      = $_FILES[$name]['type'][$offset];
		$this->fileSize      = $_FILES[$name]['size'][$offset];
		$this->fileTempName  = $_FILES[$name]['tmp_name'][$offset];
		$this->fileError     = $_FILES[$name]['error'][$offset];
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
	}

	protected function _validateDirectory()
	{
		$this->_validateDirectoryNameBlank();
		$this->_validateDirectoryExists();
		$this->_validateDirectoryWritable();
	}

	protected function _saveFile()
	{
		if ( move_uploaded_file($this->fileTempName, $this->savePath) === false )
		{
			$this->_addError("Cannot move file.");
			return false;
		}

		return true;
	}

	protected function _changePermission($perm = 0644)
	{
		if ( chmod($this->savePath, $perm) === false )
		{
			$this->_addError("Cannot change the file permisson.");
			return false;
		}

		return true;
	}

	protected function _validateImageBySize()
	{
		if ( getimagesize($this->fileTempName) === false )
		{
			$this->_addError("File is not image.");
		}
	}

	protected function _validateImageWidth()
	{
		$size = getimagesize($this->fileTempName);

		if ( $size[0] > $this->maxImageWidth )
		{
			$this->_addError(t("Image width must be smaller than {1}px.", $this->maxImageWidth));
		}
	}

	protected function _validateImageHeight()
	{
		$size = getimagesize($this->fileTempName);

		if ( $size[1] > $this->maxImageHeight )
		{
			$this->_addError(t("Image height must be smaller than {1}px.", $this->maxImageHeight));
		}
	}

	protected function _validateFileSize()
	{
		if ( $this->fileSize < 0 )
		{
			$this->_addError("File size is invalid.");
		}
	}

	protected function _validateFileNamed()
	{
		if ( $this->fileName == '' )
		{
			$this->_addError("File name is blank.");
		}
	}

	protected function _validateFileUploaded()
	{
		if ( is_uploaded_file($this->fileTempName) === false )
		{
			$this->_addError("File uploading failed.");
		}
	}

	protected function _validateFileError()
	{
		switch ( $this->fileError )
		{
			case UPLOAD_ERR_INI_SIZE:   $this->_addError("The uploaded file exceeds the upload_max_filesize directive in php.ini."); break;
			case UPLOAD_ERR_FORM_SIZE:  $this->_addError("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form."); break;
			case UPLOAD_ERR_PARTIAL:    $this->_addError("The uploaded file was only partially uploaded."); break;
			case UPLOAD_ERR_NO_TMP_DIR: $this->_addError("Missing a temporary folder."); break;
			case UPLOAD_ERR_CANT_WRITE: $this->_addError("Failed to write file to disk."); break;
			case UPLOAD_ERR_EXTENSION:  $this->_addError("A PHP extension stopped the file upload."); break;
		}
	}

	protected function _validateFileHasExt()
	{
		if ( $this->fileExtention == '' )
		{
			$this->_addError("File extention is invalid.");
		}
	}

	protected function _validateFileExtAllowed()
	{
		if ( in_array($this->fileExtention, $this->allowedExtentions) === false )
		{
			$this->_addError(t("File format '{1}' is inacceptable.", $this->fileExtention));
		}
	}

	protected function _validateFileExtKnown()
	{
		if ( isset($this->mimeTypeMap[$this->fileExtention]) === false )
		{
			$this->_addError(t("Unknown file format '{1}'.", $this->fileExtention));
		}
	}

	protected function _validateFileMimetype()
	{
		if ( $this->mimeTypeMap[$this->fileExtention] !== $this->fileType )
		{
			$this->_addError(t("File format '{1}' doesn't match file extention.", $this->fileExtention));
		}
	}

	protected function _validateFileMaxSize()
	{
		if ( $this->fileSize > $this->maxSize )
		{
			$this->_addError(t("File size must be smaller than {1}. Current size is {2}. ", $this->_fileSize($this->maxSize), $this->_fileSize($this->fileSize)));
		}
	}

	protected function _validateFileNameMaxLength()
	{
		if ( strlen($this->fileName) > $this->maxNameLength )
		{
			$this->_addError(t("File name must be shorter than {1} characters.", $this->maxNameLength));
		}
	}

	protected function _validateFileAlreadyExists()
	{
		if ( file_exists($this->savePath) === true )
		{
			$this->_addError("File already exists.");
		}
	}

	protected function _validateDirectoryNameBlank()
	{
		if ( $this->upDir == '' )
		{
			$this->_addError("Directory is invaild.");
		}
	}

	protected function _validateDirectoryExists()
	{
		if ( is_dir($this->upDir) === false )
		{
			$this->_addError("Not directory.");
		}
	}

	protected function _validateDirectoryWritable()
	{
		if ( is_writeable($this->upDir) === false )
		{
			$this->_addError("Directory is not writable.");
		}
	}

	protected function _setSavePath()
	{
		$this->savePath = $this->upDir.'/'.$this->prefix.$this->saveName.'.'.$this->fileExtention;
	}

	protected function _setSaveName()
	{
		$this->_setRandomName();
		$this->_substrName();
	}

	protected function _setRandomName()
	{
		$this->saveName = md5(mt_rand());
	}

	protected function _substrName()
	{
		$this->saveName = substr($this->saveName, 0, $this->maxNameLength);
	}

	protected function _fileSize($size)
	{
		$filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . t($filesizename[$i]) : t("0 Bytes");
	}

	protected function _addError($message)
	{
		$this->errors[] = t($message);
	}
}
