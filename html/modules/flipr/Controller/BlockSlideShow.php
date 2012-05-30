<?php
class Flipr_Controller_BlockSlideShow extends Pengin_Controller_AbstractBlock
{
	protected $albumId = 0;
	protected $albumHandler = null;
	protected $albumModel   = null;
	protected $photoHandler = null;
	protected $photoModels  = array();

	protected $errors = array();

	protected static $isAddedHeaderScript = false;

	public function __construct()
	{
		parent::__construct();
	}

	public function main()
	{
		parent::main();
	}

	protected function _show()
	{
		$this->_getAlbumHandler();
		$this->_getPhotoHandler();
		$this->_fetchAlbumId();
		$this->_getAlbumModel();
		$this->_assignAlbum();
		$this->_getPhotoModels();
		$this->_assignPhotos();
		$this->_view();
		$this->_addHeaderScripts();
	}

	protected function _edit()
	{
		try
		{
			$this->_getAlbumHandler();
			$this->_assignErrors();
			$this->_assignAlbums();
			$this->_fetchAlbumId();
			$this->_getAlbumModel();
		}
		catch ( Exception $e )
		{
			$this->errors[] = t($e->getMessage());
		}

		$this->_view();
	}

	protected function _assignErrors()
	{
		$this->output['errors'] =& $this->errors;
	}

	protected function _getAlbumHandler()
	{
		$this->albumHandler =& $this->root->getModelHandler('Album');
	}
	
	protected function _getPhotoHandler()
	{
		$this->photoHandler =& $this->root->getModelHandler('Photo');
	}

	protected function _fetchAlbumId()
	{
		$this->albumId = $this->options[1];
	}

	protected function _getAlbumModel()
	{
		$this->albumModel = $this->albumHandler->load($this->albumId);

		if ( $this->albumModel === false )
		{
			throw new Exception("Album not found.");
		}
	}

	protected function _assignAlbum()
	{
		$this->output['album'] = $this->albumModel->getVars();
	}

	protected function _assignAlbums()
	{
		$albumModels = $this->albumHandler->find();

		$this->output['albums'] = array();

		foreach ( $albumModels as $albumModel )
		{
			$this->output['albums'][] = $albumModel->getVars();
		}
	}

	protected function _getPhotoModels()
	{
		$this->photoModels = $this->photoHandler->findByAlbumId($this->albumId);
	}
	
	protected function _assignPhotos()
	{
		$this->output['photos'] = array();

		foreach ( $this->photoModels as $photoModel )
		{
			$this->output['photos'][] = $photoModel->getVars();
		}
	}
	
	protected function _addHeaderScripts()
	{
		if ( self::$isAddedHeaderScript === true )
		{
			return;
		}

		$this->root->cms->addJavaScript($this->url.'/public/javascript/jquery.cycle.min.js');
		$this->root->cms->addJavaScript($this->url.'/public/javascript/'.$this->controller.'.js');
		$this->root->cms->addStyleSheet($this->url.'/public/css/'.$this->controller.'.css');

		self::$isAddedHeaderScript = true;
	}
}
