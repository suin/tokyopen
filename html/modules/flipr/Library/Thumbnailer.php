<?php
class Flipr_Library_Thumbnailer
{
	protected $originalPath  = '';
	protected $originalImage = null;

	protected $originalWidth  = 0;
	protected $originalHeight = 0;

	protected $thumbnailPath  = '';
	protected $thumbnailImage = null;

	protected $thumbnailWidth  = 0;
	protected $thumbnailHeight = 0;

	protected $extention = '';

	public function __construct($originalPath, $thumbnailPath, $thumbnailWidth, $thumbnailHeight)
	{
		$this->originalPath    = $originalPath;
		$this->thumbnailPath   = $thumbnailPath;
		$this->thumbnailWidth  = $thumbnailWidth;
		$this->thumbnailHeight = $thumbnailHeight;
	}

	public function resize($method = 'max')
	{
		try
		{
			$this->_setExtention();
			$this->_setOriginalSize();

			if ( $method === 'min' )
			{
				$this->_setThumbnailSizeMin();
			}
			else
			{
				$this->_setThumbnailSizeMax();
			}

			$this->_createOriginalImage();
			$this->_createThumbnailImage();
			$this->_resample();
			$this->_createFile();
		}
		catch ( Exception $e )
		{
			return false;
		}

		return true;
	}

	protected function _setExtention()
	{
		$this->extention = pathinfo($this->originalPath, PATHINFO_EXTENSION);
	}

	protected function _createOriginalImage()
	{
		if ( $this->extention === 'png' )
		{
			$this->originalImage = imagecreatefrompng($this->originalPath);
		}
		elseif ( $this->extention === 'jpeg' or $this->extention === 'jpg' )
		{
			$this->originalImage = imagecreatefromjpeg($this->originalPath);
		}
		elseif ( $this->extention === 'gif' )
		{
			$this->originalImage = imagecreatefromgif($this->originalPath);
		}
		else
		{
			throw new Exception;// TODO >> error
		}
	}

	protected function _createThumbnailImage()
	{
		$this->thumbnailImage = imagecreatetruecolor($this->thumbnailWidth, $this->thumbnailHeight);
	}

	protected function _setOriginalSize()
	{
		$size = getimagesize($this->originalPath);

		if ( $size === false )
		{
			throw new Exception;// TODO >> error
		}

		list($this->originalWidth, $this->originalHeight) = $size;
	}

	protected function _setThumbnailSizeMin()
	{
		$originalRaito = $this->originalWidth / $this->originalHeight;
		
		if ( $this->thumbnailWidth / $this->thumbnailHeight > $originalRaito)
		{
			$this->thumbnailHeight = intval($this->thumbnailWidth / $originalRaito);
		}
		else
		{
			$this->thumbnailWidth = intval($this->thumbnailHeight * $originalRaito);
		}
	}

	protected function _setThumbnailSizeMax()
	{
		$originalRaito = $this->originalWidth / $this->originalHeight;
		
		if ( $this->thumbnailWidth / $this->thumbnailHeight > $originalRaito)
		{
			$this->thumbnailWidth = intval($this->thumbnailHeight * $originalRaito);
		}
		else
		{
			$this->thumbnailHeight = intval($this->thumbnailWidth / $originalRaito);
		}
	}

	protected function _resample()
	{
		imagecopyresampled(
			$this->thumbnailImage,
			$this->originalImage, 
			0, 
			0, 
			0, 
			0, 
			$this->thumbnailWidth, 
			$this->thumbnailHeight, 
			$this->originalWidth, 
			$this->originalHeight
		);
	}

	protected function _createFile()
	{
		if ( $this->extention === 'png' )
		{
			imagepng($this->thumbnailImage, $this->thumbnailPath);
		}
		elseif ( $this->extention === 'jpeg' or $this->extention === 'jpg' )
		{
			imagejpeg($this->thumbnailImage, $this->thumbnailPath, 100);
		}
		elseif ( $this->extention === 'gif' )
		{
			imagegif($this->thumbnailImage, $this->thumbnailPath);
		}
		else
		{
			throw new Exception;// TODO >> error
		}
	}
}