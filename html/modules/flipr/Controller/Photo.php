<?php
class Flipr_Controller_Photo extends Flipr_Abstract_Controller
{
	protected $photoId = 0;
	protected $photoHandler  = null;
	protected $photoModel    = null;
	protected $albumHandler  = null;
	protected $albumModel    = null;
	protected $photoTotal    = 0;

	public function __construct()
	{
		parent::__construct();
		$this->_fetchPhotoId();
		$this->_getPhotoHandler();
		$this->_getPhotoModel();
		$this->_getAlbumHandler();
		$this->_getAlbumModel();
		$this->_setPageTitle();
	}

	public function main()
	{
		$this->_default();
	}

	protected function _default()
	{
		$this->_assignAlbum();
		$this->_assignPhoto();
		$this->_assignOutput();
		$this->_view();
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

	protected function _getAlbumHandler()
	{
		$this->albumHandler =& $this->root->getModelHandler('Album');
	}

	protected function _getAlbumModel()
	{
		$albumId = $this->photoModel->getVar('album_id');
		$this->albumModel = $this->albumHandler->load($albumId);

		if ( $this->albumModel === false )
		{
			$this->root->redirect("Album not found.", 'album_list');
		}
	}

	protected function _setPageTitle()
	{
		$this->pageTitle = $this->photoModel->getVar('name');
	}

	protected function _assignPhoto()
	{
		$this->output['photo'] = $this->photoModel->getVars();
	}

	protected function _assignAlbum()
	{
		$this->output['album'] = $this->albumModel->getVars();
	}

	protected function _assignOutput()
	{
	}

	protected function _head()
	{
		parent::_head();
		$this->root->cms->addStyleSheet($this->url.'/public/javascript/fancybox/jquery.fancybox-1.3.4.css');
		$this->root->cms->addJavaScript($this->url.'/public/javascript/fancybox/jquery.fancybox-1.3.4.pack.js');
		$this->root->cms->addJavaScript($this->url.'/public/javascript/fancybox.js');
	}
}
